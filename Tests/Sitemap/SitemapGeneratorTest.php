<?php
/**
 * Created by PhpStorm.
 * User: jdschulze
 * Date: 14.10.16
 * Time: 09:52
 */

namespace Phlexible\Bundle\SitemapBundle\Tests\Sitemap;

use Phlexible\Bundle\CountryContextBundle\Mapping\CountryCollection;
use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapGenerator;
use Phlexible\Bundle\SitemapBundle\SitemapEvents;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeManagerInterface;
use Phlexible\Bundle\TreeBundle\Tree\TreeManager;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Routing\Router;


class SitemapGeneratorTest extends \PHPUnit_Framework_TestCase
{
    private $treeManager;
    private $contentTreeManager;
    private $countryCollection;
    private $router;
    private $siteRootId = '1bcaab4d-098e-4737-ac93-53cae9d83887';

    public function setUp()
    {
        $this->treeManager = $this->prophesize(TreeManager::class);
        $this->contentTreeManager = $this->prophesize(ContentTreeManagerInterface::class);
        $this->countryCollection = $this->prophesize(CountryCollection::class);
        $this->router = $this->prophesize(Router::class);
    }

    public function testEventDispatcherShouldDispatchUrlGenerationEvent()
    {
        $eventDispatcher = $this->prophesize(\Symfony\Component\EventDispatcher\EventDispatcher::class);
        $eventDispatcher->dispatch(SitemapEvents::URL_GENERATION, Argument::type(Event::class))->shouldBeCalled();

        $sitemap = new SitemapGenerator(
            $this->treeManager->reveal(),
            $this->contentTreeManager->reveal(),
            $this->countryCollection->reveal(),
            $this->router->reveal(),
            $eventDispatcher->reveal(),
            ['de']
        );

        $sitemap->generateSitemap($this->siteRootId);
    }

    public function testEventDispatcherShouldDispatchUrlSetGenerationEvent()
    {
        $eventDispatcher = $this->prophesize(\Symfony\Component\EventDispatcher\EventDispatcher::class);
        $eventDispatcher->dispatch(SitemapEvents::URLSET_GENERATION, Argument::type(Event::class))->shouldBeCalled();

        $sitemap = new SitemapGenerator(
            $this->treeManager->reveal(),
            $this->contentTreeManager->reveal(),
            $this->countryCollection->reveal(),
            $this->router->reveal(),
            $eventDispatcher->reveal(),
            ['de']
        );

        $sitemap->generateSitemap($this->siteRootId);
    }

    public function testEventDispatcherShouldDispatchXmlGenerationEvent()
    {
        $eventDispatcher = $this->prophesize(\Symfony\Component\EventDispatcher\EventDispatcher::class);
        $eventDispatcher->dispatch(SitemapEvents::XML_GENERATION, Argument::type(Event::class))->shouldBeCalled();

        $sitemap = new SitemapGenerator(
            $this->treeManager->reveal(),
            $this->contentTreeManager->reveal(),
            $this->countryCollection->reveal(),
            $this->router->reveal(),
            $eventDispatcher->reveal(),
            ['de']
        );

        $sitemap->generateSitemap($this->siteRootId);
    }
}
