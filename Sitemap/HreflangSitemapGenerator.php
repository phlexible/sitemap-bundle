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

use Phlexible\Bundle\SitemapBundle\Event\UrlsetEvent;
use Phlexible\Bundle\SitemapBundle\Exception\InvalidArgumentException;
use Phlexible\Bundle\SitemapBundle\SitemapEvents;
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeManagerInterface;
use Phlexible\Bundle\TreeBundle\Model\TreeNodeInterface;
use Phlexible\Bundle\TreeBundle\Tree\TreeIterator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thepixeldeveloper\Sitemap\Output;
use Thepixeldeveloper\Sitemap\Urlset;

/**
 * Generates a sitemap with hreflang annotation
 *
 * @author Matthias Harmuth <mharmuth@brainbits.net>
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
    private $availableLanguages;

    /**
     * @param ContentTreeManagerInterface  $contentTreeManager
     * @param NodeUrlsetGeneratorInterface $nodeUrlsetGenerator
     * @param EventDispatcherInterface     $eventDispatcher
     * @param string                       $availableLanguages
     */
    public function __construct(
        ContentTreeManagerInterface $contentTreeManager,
        NodeUrlsetGeneratorInterface $nodeUrlsetGenerator,
        EventDispatcherInterface $eventDispatcher,
        $availableLanguages
    )
    {
        $this->contentTreeManager = $contentTreeManager;
        $this->nodeUrlSetGenerator = $nodeUrlsetGenerator;
        $this->eventDispatcher = $eventDispatcher;
        $this->availableLanguages = explode(',', $availableLanguages);
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
            /** @var TreeNodeInterface $childNode */
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
