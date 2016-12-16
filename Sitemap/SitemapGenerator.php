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

use Phlexible\Bundle\SitemapBundle\Event\SitemapIndexEvent;
use Phlexible\Bundle\SitemapBundle\Event\UrlsetEvent;
use Phlexible\Bundle\SitemapBundle\Exception\InvalidArgumentException;
use Phlexible\Bundle\SitemapBundle\SitemapEvents;
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeManagerInterface;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeNode;
use Phlexible\Bundle\TreeBundle\Tree\TreeIterator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Thepixeldeveloper\Sitemap\Output;
use Thepixeldeveloper\Sitemap\Sitemap;
use Thepixeldeveloper\Sitemap\SitemapIndex;
use Thepixeldeveloper\Sitemap\Urlset;

/**
 * Generates a sitemap for a given site root
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 */
class SitemapGenerator implements SitemapGeneratorInterface
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
     * @var RouterInterface
     */
    private $router;

    /**
     * @var array
     */
    private $availableLanguages;

    /**
     * @param ContentTreeManagerInterface $contentTreeManager
     * @param NodeUrlsetGeneratorInterface $nodeUrlsetGenerator
     * @param EventDispatcherInterface $eventDispatcher
     * @param RouterInterface $router
     * @param string $availableLanguages
     */
    public function __construct(
        ContentTreeManagerInterface $contentTreeManager,
        NodeUrlsetGeneratorInterface $nodeUrlsetGenerator,
        EventDispatcherInterface $eventDispatcher,
        RouterInterface $router,
        $availableLanguages
    ) {
        $this->contentTreeManager  = $contentTreeManager;
        $this->nodeUrlSetGenerator = $nodeUrlsetGenerator;
        $this->eventDispatcher     = $eventDispatcher;
        $this->router              = $router;
        $this->availableLanguages  = explode(',', $availableLanguages);
    }

    /**
     * {@inheritdoc}
     */
    public function generateSitemap(Siteroot $siteroot, $language, $force = false)
    {
        $contentTree = $this->contentTreeManager->find($siteroot->getId());
        if (!$contentTree) {
            throw new InvalidArgumentException("Tree for site root {$siteroot->getId()} not found");
        }

        if (!in_array($language, $this->availableLanguages)) {
            throw new InvalidArgumentException("Language $language not allowed for generating sitemap");
        }

        $urlSet = new Urlset();

        $iterator = new TreeIterator($contentTree);

        $rii = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($rii as $childNode) {
            /** @var ContentTreeNode $treeNode */
            $treeNode = $contentTree->get($childNode->getId());

            /** @noinspection PhpUndefinedMethodInspection */
            $contentTree->setLanguage($language);

            /** @noinspection PhpParamsInspection */
            if (!$contentTree->isPublished($treeNode)) {
                continue;
            }

            $nodeUrlSet = $this->nodeUrlSetGenerator->generateUrlset($treeNode, $language);

            $urlSet = $this->mergeUrlSet($urlSet, $nodeUrlSet);
        }

        $event = new UrlsetEvent($urlSet, $siteroot);
        $this->eventDispatcher->dispatch(SitemapEvents::URLSET_GENERATION, $event);

        return $this->generateSitemapFromUrlSet($urlSet);
    }

    /**
     * {@inheritdoc}
     */
    public function generateSitemapIndex(Siteroot $siteroot, $force = false)
    {
        $sitemapIndex = new SitemapIndex();

        foreach ($this->availableLanguages as $language) {
            $loc = $this->router->generate(
                'sitemap_2index',
                ['language' => $language],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $url = new Sitemap($loc);
            $sitemapIndex->addSitemap($url);
        }

        $event = new SitemapIndexEvent($sitemapIndex, $siteroot);
        $this->eventDispatcher->dispatch(SitemapEvents::SITEMAPINDEX_GENERATION, $event);

        return $this->generateSitemapFromSitemapIndex($sitemapIndex);
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
     * @return string
     */
    private function generateSitemapFromUrlSet($urlSet)
    {
        return (new Output())->getOutput($urlSet);
    }

    /**
     * @param SitemapIndex $sitemapIndex
     * @return string
     */
    private function generateSitemapFromSitemapIndex($sitemapIndex)
    {
        return (new Output())->getOutput($sitemapIndex);
    }
}
