<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\SitemapBundle\Tests\Sitemap;

use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapCache;
use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapGenerator;
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use org\bovigo\vfs\vfsStream;

/**
 * SitemapCacheTest
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 */
class SitemapCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var vfsStream
     */
    private $cacheRoot;

    /**
     * @var string
     */
    private $xmlSitemap = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>http://www.test.de</loc>
    </url>
</urlset>
EOF;

    public function setUp()
    {
        $this->cacheRoot = vfsStream::setup();
    }

    public function testGetSitemapForceRebuild()
    {
        $siterootId = '1bcaab4d-098e-4737-ac93-53cae9d83887';

        $siteRoot = new Siteroot($siterootId);

        $sitemapGenerator = $this->prophesize(SitemapGenerator::class);
        $sitemapGenerator->generateSitemap($siteRoot)->willReturn($this->xmlSitemap);

        $sitemapCache = new SitemapCache($sitemapGenerator->reveal(), $this->cacheRoot->url());
        $result = $sitemapCache->getSitemap($siteRoot, true);

        $this->assertSame($this->xmlSitemap, $result);
    }

    public function testGetSitemapCached()
    {
        $siterootId = '1bcaab4d-098e-4737-ac93-53cae9d83887';

        $siteRoot = new Siteroot($siterootId);

        $sitemapGenerator = $this->prophesize(SitemapGenerator::class);
        $sitemapGenerator->generateSitemap($siteRoot)->willReturn($this->xmlSitemap);

        $sitemapCache = new SitemapCache($sitemapGenerator->reveal(), $this->cacheRoot->url());
        $result = $sitemapCache->getSitemap($siteRoot, false);

        $this->assertSame($this->xmlSitemap, $result);
    }
}
