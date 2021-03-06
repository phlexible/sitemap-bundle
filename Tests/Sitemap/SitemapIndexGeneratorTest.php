<?php

/*
 * This file is part of the phlexible sitemap package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\SitemapBundle\Tests\Sitemap;

use Phlexible\Bundle\SitemapBundle\Event\SitemapIndexEvent;
use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapIndexGenerator;
use Phlexible\Bundle\SitemapBundle\SitemapEvents;
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Routing\Router;

/**
 * Sitemap index generator test.
 *
 * @author Matthias Harmuth <mharmuth@brainbits.net>
 *
 * @covers \Phlexible\Bundle\SitemapBundle\Sitemap\SitemapGenerator
 */
class SitemapIndexGeneratorTest extends TestCase
{
    public function testGenerateSitemapIndex()
    {
        $siterootId = '1bcaab4d-098e-4737-ac93-53cae9d83887';
        $url1 = 'http://www.test.de?sitemap-de.xml';
        $url2 = 'http://www.test.de?sitemap-en.xml';

        $siteRoot = new Siteroot($siterootId);

        $eventDispatcher = $this->prophesize(EventDispatcher::class);
        $eventDispatcher->dispatch(
            SitemapEvents::SITEMAPINDEX_GENERATION,
            Argument::that(
                function (SitemapIndexEvent $event) use ($siteRoot) {
                    $this->assertSame($siteRoot, $event->getSiteroot());

                    return true;
                }
            )
        )->shouldBeCalled();

        $router = $this->prophesize(Router::class);
        $router->generate('sitemap_sitemap', ['_locale' => 'de'], 0)->shouldBeCalled()->willReturn($url1);
        $router->generate('sitemap_sitemap', ['_locale' => 'en'], 0)->shouldBeCalled()->willReturn($url2);

        $sitemapIndex = new SitemapIndexGenerator(
            $eventDispatcher->reveal(),
            $router->reveal(),
            'de,en'
        );

        $result = $sitemapIndex->generateSitemapIndex($siteRoot);

        $expected = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns:xsi="https://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 https://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>$url1</loc>
    </sitemap>
    <sitemap>
        <loc>$url2</loc>
    </sitemap>
</sitemapindex>
EOF;

        $this->assertSame($expected, $result);
    }
}
