<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\SitemapBundle\Tests\Sitemap;

use Phlexible\Bundle\CountryContextBundle\Mapping\CountryCollection;
use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapCache;
use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapGenerator;
use Phlexible\Bundle\SitemapBundle\SitemapEvents;
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeManagerInterface;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeNode;
use Phlexible\Bundle\TreeBundle\ContentTree\DelegatingContentTree;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\Router;

/**
 * Class SitemapCacheTest
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 */
class SitemapCacheTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSitemap()
    {
        $siterootId = '1bcaab4d-098e-4737-ac93-53cae9d83887';
        $url = 'http://www.test.de';
        $xmlSitemap = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>http://www.test.de</loc>
    </url>
</urlset>
EOF;
        $cacheDir = 'fakedir';

//        $tree = $this->prophesize(DelegatingContentTree::class);
//        $root = new ContentTreeNode();
//        $root->setId(1);
//        $root->setTree($tree->reveal());
//        $tree->getRoot()->willReturn($root);
//        $tree->hasChildren($root)->willReturn(false);
//        $tree->get(1)->willReturn($root);
//        $tree->setLanguage('de')->shouldBeCalled();
//        $tree->isPublished($root)->willReturn(true);

        $siteRoot = new Siteroot($siterootId);

        $sitemapGenerator = $this->prophesize(SitemapGenerator::class);
        $sitemapGenerator->generateSitemap($siteRoot)->willReturn($xmlSitemap);

        $fileSystem = $this->prophesize(Filesystem::class);
        $fileSystem->exists(Argument::any())->willReturn(true);
        $fileSystem->dumpFile(Argument::any(), Argument::any())->willReturn(true);

//        $contentTreeManager = $this->prophesize(ContentTreeManagerInterface::class);
//        $contentTreeManager->find($siterootId)->willReturn($tree->reveal());

//        $countryCollection = $this->prophesize(CountryCollection::class);
//        $countryCollection->filterLanguage('de')->willReturn(array('de'));

//        $router = $this->prophesize(Router::class);
//        $router->generate($root, ['_country' => 'de', '_locale' => 'de'], 0)->shouldBeCalled()->willReturn($url);

        $sitemapCache = new SitemapCache($sitemapGenerator->reveal(), $fileSystem->reveal(), $cacheDir);
        $result = $sitemapCache->getSitemap($siteRoot, true);

        $this->assertSame($xmlSitemap, $result);
    }
}
