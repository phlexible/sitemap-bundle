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

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Phlexible\Bundle\SitemapBundle\Sitemap\CachingSitemapGenerator;
use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapGenerator;
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;

/**
 * Caching sitemap generator test
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 *
 * @covers \Phlexible\Bundle\SitemapBundle\Sitemap\CachingSitemapGenerator
 */
class CachingSitemapGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $cacheRoot;

    public function setUp()
    {
        $this->cacheRoot = vfsStream::setup();
    }

    public function testGenerateSitemapGeneratesNewSitemap()
    {
        $siterootId = '1bcaab4d-098e-4737-ac93-53cae9d83887';

        $siteRoot = new Siteroot($siterootId);
        $language = 'de';

        $sitemapGenerator = $this->prophesize(SitemapGenerator::class);
        $sitemapGenerator->generateSitemap($siteRoot, $language)->willReturn('generated');

        $sitemapCache = new CachingSitemapGenerator($sitemapGenerator->reveal(), $this->cacheRoot->url());
        $sitemapCache->generateSitemap($siteRoot, true);

        $this->assertFileExists($this->cacheRoot->getChild("$siterootId.xml")->url());
        $this->assertEquals('generated', $this->cacheRoot->getChild("$siterootId.xml")->getContent());
    }

    public function testForceGenerateSitemapDoesnNotReturnCachedSitemap()
    {
        $siterootId = '1bcaab4d-098e-4737-ac93-53cae9d83887';
        $language = 'de';

        vfsStream::newFile("$siterootId-$language.xml")
            ->withContent('cached')
            ->at($this->cacheRoot);

        $siteRoot = new Siteroot($siterootId);

        $sitemapGenerator = $this->prophesize(SitemapGenerator::class);
        $sitemapGenerator->generateSitemap($siteRoot, $language)->willReturn('generated');

        $sitemapCache = new CachingSitemapGenerator($sitemapGenerator->reveal(), $this->cacheRoot->url());
        $sitemapCache->generateSitemap($siteRoot, true);

        $this->assertFileExists($this->cacheRoot->getChild("$siterootId.xml")->url());
        $this->assertEquals('generated', $this->cacheRoot->getChild("$siterootId.xml")->getContent());
    }

    public function testGenerateSitemapReturnCachedSitemap()
    {
        $siterootId = '1bcaab4d-098e-4737-ac93-53cae9d83887';
        $language = 'de';

        vfsStream::newFile("$siterootId-$language.xml")
            ->withContent('cached')
            ->at($this->cacheRoot);

        $siteRoot = new Siteroot($siterootId);

        $sitemapGenerator = $this->prophesize(SitemapGenerator::class);
        $sitemapGenerator->generateSitemap($siteRoot, $language)->shouldNotBeCalled();

        $sitemapCache = new CachingSitemapGenerator($sitemapGenerator->reveal(), $this->cacheRoot->url());
        $result = $sitemapCache->generateSitemap($siteRoot, false);

        $this->assertSame('cached', $result);
    }

    /**
     * @expectedException \Phlexible\Bundle\SitemapBundle\Exception\WriteFileException
     */
    public function testWriteThrowsExceptionOnInsufficiantPermissions()
    {
        $siterootId = '1bcaab4d-098e-4737-ac93-53cae9d83887';
        $language = 'de';

        $dir = vfsStream::newDirectory('invalid', 000)
            ->at($this->cacheRoot);

        $siteRoot = new Siteroot($siterootId);

        $sitemapGenerator = $this->prophesize(SitemapGenerator::class);
        $sitemapGenerator->generateSitemap($siteRoot, $language)->willReturn('generated');

        $sitemapCache = new CachingSitemapGenerator($sitemapGenerator->reveal(), $dir->url());
        $sitemapCache->generateSitemap($siteRoot, true);
    }

    /**
     * @expectedException \Phlexible\Bundle\SitemapBundle\Exception\ReadFileException
     */
    public function testReadThrowsExceptionOnInsufficiantPermissions()
    {
        $siterootId = '1bcaab4d-098e-4737-ac93-53cae9d83887';
        $language = 'de';

        $dir = vfsStream::newDirectory('invalid', 000)
            ->at($this->cacheRoot);

        vfsStream::newFile("$siterootId.xml", 000)
            ->withContent('cached')
            ->at($dir);

        $siteRoot = new Siteroot($siterootId);

        $sitemapGenerator = $this->prophesize(SitemapGenerator::class);
        $sitemapGenerator->generateSitemap($siteRoot, $language)->willReturn('generated');

        $sitemapCache = new CachingSitemapGenerator($sitemapGenerator->reveal(), $dir->url());
        $sitemapCache->generateSitemap($siteRoot, false);
    }

}
