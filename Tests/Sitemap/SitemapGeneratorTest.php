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

use Phlexible\Bundle\SitemapBundle\Event\UrlsetEvent;
use Phlexible\Bundle\SitemapBundle\Sitemap\NodeUrlsetGeneratorInterface;
use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapGenerator;
use Phlexible\Bundle\SitemapBundle\SitemapEvents;
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeManagerInterface;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeNode;
use Phlexible\Bundle\TreeBundle\ContentTree\DelegatingContentTree;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Thepixeldeveloper\Sitemap\Url;
use Thepixeldeveloper\Sitemap\Urlset;

/**
 * Sitemap generator test.
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 *
 * @covers \Phlexible\Bundle\SitemapBundle\Sitemap\SitemapGenerator
 */
class SitemapGeneratorTest extends TestCase
{
    public function testGenerateSitemap()
    {
        $siterootId = '1bcaab4d-098e-4737-ac93-53cae9d83887';
        $url = 'http://www.test.de';
        $language = 'de';

        $siteRoot = new Siteroot($siterootId);

        $tree = $this->prophesize(DelegatingContentTree::class);
        $root = new ContentTreeNode();
        $root->setId(1);
        $root->setTree($tree->reveal());
        $tree->getRoot()->willReturn($root);
        $tree->hasChildren($root)->willReturn(false);
        $tree->get(1)->willReturn($root);
        $tree->setLanguage('de')->shouldBeCalled();
        $tree->isPublished($root)->willReturn(true);

        $eventDispatcher = $this->prophesize(EventDispatcher::class);
        $eventDispatcher->dispatch(
            SitemapEvents::URLSET_GENERATION,
            Argument::that(
                function (UrlsetEvent $event) use ($siteRoot) {
                    $this->assertSame($siteRoot, $event->getSiteroot());

                    return true;
                }
            )
        )->shouldBeCalled();

        $contentTreeManager = $this->prophesize(ContentTreeManagerInterface::class);
        $contentTreeManager->find($siterootId)->willReturn($tree->reveal());

        $urlset = new Urlset();
        $urlset->addUrl(new Url($url));

        $nodeUrlsetGenerator = $this->prophesize(NodeUrlsetGeneratorInterface::class);
        $nodeUrlsetGenerator->generateUrlset($root, 'de')->willReturn($urlset);

        $sitemap = new SitemapGenerator(
            $contentTreeManager->reveal(),
            $nodeUrlsetGenerator->reveal(),
            $eventDispatcher->reveal(),
            $language
        );

        $result = $sitemap->generateSitemap($siteRoot, $language);

        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns:xsi="https://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 https://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>http://www.test.de</loc>
    </url>
</urlset>
EOF;

        $this->assertSame($expected, $result);
    }

    /**
     * @expectedException \Phlexible\Bundle\SitemapBundle\Exception\InvalidArgumentException
     */
    public function testGenerateSitemapWithInvalidTreeThrowsException()
    {
        $siterootId = '1bcaab4d-098e-4737-ac93-53cae9d83887';
        $language = 'de';

        $siteRoot = new Siteroot($siterootId);

        $contentTreeManager = $this->prophesize(ContentTreeManagerInterface::class);
        $contentTreeManager->find($siterootId)->willReturn(null);

        $nodeUrlsetGenerator = $this->prophesize(NodeUrlsetGeneratorInterface::class);
        $nodeUrlsetGenerator->generateUrlset()->shouldNotBeCalled();

        $eventDispatcher = $this->prophesize(EventDispatcher::class);

        $sitemap = new SitemapGenerator(
            $contentTreeManager->reveal(),
            $nodeUrlsetGenerator->reveal(),
            $eventDispatcher->reveal(),
            $language
        );

        $sitemap->generateSitemap($siteRoot, $language);
    }

    public function testGenerateSitemapWithUnpublishedNode()
    {
        $siterootId = '1bcaab4d-098e-4737-ac93-53cae9d83887';
        $language = 'de';

        $siteRoot = new Siteroot($siterootId);

        $tree = $this->prophesize(DelegatingContentTree::class);
        $root = new ContentTreeNode();
        $root->setId(1);
        $root->setTree($tree->reveal());
        $tree->getRoot()->willReturn($root);
        $tree->hasChildren($root)->willReturn(false);
        $tree->get(1)->willReturn($root);
        $tree->setLanguage('de')->shouldBeCalled();
        $tree->isPublished($root)->willReturn(false);

        $eventDispatcher = $this->prophesize(EventDispatcher::class);
        $eventDispatcher->dispatch(
            SitemapEvents::URLSET_GENERATION,
            Argument::that(
                function (UrlsetEvent $event) use ($siteRoot) {
                    $this->assertSame($siteRoot, $event->getSiteroot());

                    return true;
                }
            )
        )->shouldBeCalled();

        $contentTreeManager = $this->prophesize(ContentTreeManagerInterface::class);
        $contentTreeManager->find($siterootId)->willReturn($tree->reveal());

        $nodeUrlsetGenerator = $this->prophesize(NodeUrlsetGeneratorInterface::class);
        $nodeUrlsetGenerator->generateUrlset()->shouldNotBeCalled();

        $sitemap = new SitemapGenerator(
            $contentTreeManager->reveal(),
            $nodeUrlsetGenerator->reveal(),
            $eventDispatcher->reveal(),
            $language
        );

        $result = $sitemap->generateSitemap($siteRoot, $language);

        $expected = <<<'EOF'
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns:xsi="https://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 https://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>
EOF;

        $this->assertSame($expected, $result);
    }
}
