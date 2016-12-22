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
use Phlexible\Bundle\SitemapBundle\SitemapEvents;
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Thepixeldeveloper\Sitemap\Output;
use Thepixeldeveloper\Sitemap\Sitemap;
use Thepixeldeveloper\Sitemap\SitemapIndex;

/**
 * Generates a sitemap index for a given site root
 *
 * @author Matthias Harmuth <mharmuth@brainbits.net>
 */
class SitemapIndexGenerator implements SitemapIndexGeneratorInterface
{
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
     * @param EventDispatcherInterface $eventDispatcher
     * @param RouterInterface          $router
     * @param string                   $availableLanguages
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        RouterInterface $router,
        $availableLanguages
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->router = $router;
        $this->availableLanguages = explode(',', $availableLanguages);
    }

    /**
     * {@inheritdoc}
     */
    public function generateSitemapIndex(Siteroot $siteroot, $force = false)
    {
        $sitemapIndex = new SitemapIndex();

        foreach ($this->availableLanguages as $language) {
            $loc = $this->router->generate(
                'sitemap_sitemap',
                ['_locale' => $language],
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
     * @param SitemapIndex $sitemapIndex
     * @return string
     */
    private function generateSitemapFromSitemapIndex($sitemapIndex)
    {
        return (new Output())->getOutput($sitemapIndex);
    }
}
