<?php
/**
 * Created by PhpStorm.
 * User: jdschulze
 * Date: 14.10.16
 * Time: 09:52
 */

namespace Phlexible\Bundle\SitemapBundle\Tests\Sitemap;

use Phlexible\Bundle\CountryContextBundle\Mapping\CountryCollection;
use Phlexible\Bundle\SitemapBundle\Event\UrlEvent;
use Phlexible\Bundle\SitemapBundle\Event\UrlsetEvent;
use Phlexible\Bundle\SitemapBundle\Event\XmlSitemapEvent;
use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapGenerator;
use Phlexible\Bundle\SitemapBundle\SitemapEvents;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeManagerInterface;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeNode;
use Phlexible\Bundle\TreeBundle\ContentTree\DelegatingContentTree;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Routing\Router;

class SitemapGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testEventDispatcherShouldDispatchUrlGenerationEvent()
    {
        $siterootId = '1bcaab4d-098e-4737-ac93-53cae9d83887';

        $eventDispatcher = $this->prophesize(EventDispatcher::class);
        $eventDispatcher->dispatch(SitemapEvents::URLSET_GENERATION, Argument::type(UrlsetEvent::class))->shouldBeCalled();
        $eventDispatcher->dispatch(SitemapEvents::URL_GENERATION, Argument::type(UrlEvent::class))->shouldBeCalled();
        $eventDispatcher->dispatch(SitemapEvents::XML_GENERATION, Argument::type(XmlSitemapEvent::class))->shouldBeCalled();

        $tree = $this->prophesize(DelegatingContentTree::class);
        $root = new ContentTreeNode();
        $root->setId(1);
        $root->setTree($tree->reveal());
        $tree->getRoot()->willReturn($root);
        $tree->hasChildren($root)->willReturn(false);
        $tree->get(1)->willReturn($root);
        $tree->setLanguage('de')->shouldBeCalled();
        $tree->isPublished($root)->willReturn(true);

        $contentTreeManager = $this->prophesize(ContentTreeManagerInterface::class);
        $contentTreeManager->find($siterootId)->willReturn($tree->reveal());

        $countryCollection = $this->prophesize(CountryCollection::class);
        $countryCollection->filterLanguage('de')->willReturn(array('de'));

        $router = $this->prophesize(Router::class);
        $router->generate($root, ['_country' => 'de', '_locale' => 'de'], 0)->shouldBeCalled()->willReturn('http://www.test.de');

        $sitemap = new SitemapGenerator(
            $contentTreeManager->reveal(),
            $countryCollection->reveal(),
            $router->reveal(),
            $eventDispatcher->reveal(),
            'de'
        );

        $result = $sitemap->generateSitemap($siterootId);

        $expected = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>http://www.test.de</loc>
    </url>
</urlset>
EOF;

        $this->assertSame($expected, $result);
    }
}
