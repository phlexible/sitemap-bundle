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
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeInterface;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeManagerInterface;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeNode;
use Phlexible\Bundle\TreeBundle\ContentTree\DelegatingContentTree;
use Phlexible\Bundle\TreeBundle\Tree\TreeManager;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class SitemapGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContentTreeManagerInterface
     */
    private $contentTreeManager;

    /**
     * @var CountryCollection
     */
    private $countryCollection;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $siteRootId = '1bcaab4d-098e-4737-ac93-53cae9d83887';

    public function testEventDispatcherShouldDispatchUrlGenerationEvent()
    {
        $contentTreeManager = $this->prophesize(ContentTreeManagerInterface::class);
        $countryCollection = $this->prophesize(CountryCollection::class);
        $router = $this->prophesize(Router::class);
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

        $contentTreeManager->find($this->siteRootId)->willReturn($tree->reveal());

        $countryCollection->filterLanguage('de')->willReturn(array('de'));

        $sitemap = new SitemapGenerator(
            $contentTreeManager->reveal(),
            $countryCollection->reveal(),
            $router->reveal(),
            $eventDispatcher->reveal(),
            'de'
        );

        $sitemap->generateSitemap($this->siteRootId);
    }
}
