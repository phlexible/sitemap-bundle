<?php

/*
 * This file is part of the phlexible sitemap package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\SitemapBundle\Sitemap;

use Phlexible\Bundle\CountryContextBundle\Mapping\Country;
use Phlexible\Bundle\CountryContextBundle\Mapping\CountryCollection;
use Phlexible\Bundle\CountryContextBundle\Mapping\Language;
use Phlexible\Bundle\SitemapBundle\Event\UrlsetEvent;
use Phlexible\Bundle\SitemapBundle\Exception\InvalidArgumentException;
use Phlexible\Bundle\SitemapBundle\SitemapEvents;
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeManagerInterface;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeNode;
use Phlexible\Bundle\TreeBundle\Tree\TreeIterator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thepixeldeveloper\Sitemap\Output;
use Thepixeldeveloper\Sitemap\Urlset;

/**
 * Generates a sitemap with hreflang annotation
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 */
class HreflangSitemapGenerator implements SitemapGeneratorInterface
{
    /**
     * @var ContentTreeManagerInterface
     */
    private $contentTreeManager;

    /**
     * @var NodeUrlsetGeneratorInterface
     */
    private $nodeUrlSetGenerator;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var array
     */
    private $availableLanguages = [];

    /**
     * @param ContentTreeManagerInterface $contentTreeManager
     * @param NodeUrlsetGeneratorInterface $nodeUrlsetGenerator
     * @param EventDispatcherInterface $eventDispatcher
     * @param CountryCollection $countryCollection
     */
    public function __construct(
        ContentTreeManagerInterface $contentTreeManager,
        NodeUrlsetGeneratorInterface $nodeUrlsetGenerator,
        EventDispatcherInterface $eventDispatcher,
        CountryCollection $countryCollection
    ) {
        $this->contentTreeManager  = $contentTreeManager;
        $this->nodeUrlSetGenerator = $nodeUrlsetGenerator;
        $this->eventDispatcher     = $eventDispatcher;

        /** @var Country $country */
        foreach ($countryCollection as $country) {
            $languages = $country->getLanguages();
            /** @var Language $language */
            foreach ($languages as $language) {
                if ($language->isExposed()) {
                    $langcode = $language->getIdentifier();
                    if (!in_array($langcode, $this->availableLanguages)) {
                        $this->availableLanguages[] = $langcode;
                    }
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function generateSitemap(Siteroot $siteroot, $force = false)
    {
        $contentTree = $this->contentTreeManager->find($siteroot->getId());
        if (!$contentTree) {
            throw new InvalidArgumentException("Tree for site root {$siteroot->getId()} not found");
        }

        $urlSet = new Urlset();

        $iterator = new TreeIterator($contentTree);

        $rii = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($rii as $childNode) {
            /** @var ContentTreeNode $treeNode */
            $treeNode = $contentTree->get($childNode->getId());

            foreach ($this->availableLanguages as $language) {
                /** @noinspection PhpUndefinedMethodInspection */
                $contentTree->setLanguage($language);

                /** @noinspection PhpParamsInspection */
                if (!$contentTree->isPublished($treeNode)) {
                    continue;
                }

                $nodeUrlSet = $this->nodeUrlSetGenerator->generateUrlset($treeNode, $language);

                $urlSet = $this->mergeUrlSet($urlSet, $nodeUrlSet);
            }
        }

        $event = new UrlsetEvent($urlSet, $siteroot);
        $this->eventDispatcher->dispatch(SitemapEvents::URLSET_GENERATION, $event);

        return $this->generateSitemapFromUrlSet($urlSet);
    }

    /**
     * @param Urlset[] $sourceUrlSets
     *
     * @return Urlset
     */
    private function mergeUrlSet(Urlset ...$sourceUrlSets)
    {
        $urlSet = new Urlset();

        foreach ($sourceUrlSets as $sourceUrlSet) {
            foreach ($sourceUrlSet->getUrls() as $url) {
                $urlSet->addUrl($url);
            }
        }

        return $urlSet;
    }

    /**
     * @param Urlset $urlSet
     *
     * @return string
     */
    private function generateSitemapFromUrlSet($urlSet)
    {
        return (new Output())->getOutput($urlSet);
    }
}
