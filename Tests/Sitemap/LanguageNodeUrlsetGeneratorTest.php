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

use Phlexible\Bundle\SitemapBundle\Sitemap\LanguageNodeUrlsetGenerator;
use Phlexible\Bundle\SitemapBundle\Sitemap\NodeUrlGeneratorInterface;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeNode;
use Thepixeldeveloper\Sitemap\Url;
use Thepixeldeveloper\Sitemap\Urlset;

/**
 * Language node urlset generator test
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 *
 * @covers \Phlexible\Bundle\SitemapBundle\Sitemap\LanguageNodeUrlsetGenerator
 */
class LanguageNodeUrlsetGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateUrl()
    {
        $url = 'http://www.test.de';

        $treeNode = new ContentTreeNode();
        $treeNode->setId(1);

        $urlGenerator = $this->prophesize(NodeUrlGeneratorInterface::class);
        $urlGenerator->generateUrl($treeNode, array('_locale' => 'de'))->willReturn(new Url($url));

        $generator = new LanguageNodeUrlsetGenerator($urlGenerator->reveal());

        $result = $generator->generateUrlset($treeNode, 'de');

        $expected = new Urlset();
        $expected->addUrl(new Url($url));

        $this->assertEquals($expected, $result);
    }

    public function testGenerateUrlCanReturnEmptyUrlset()
    {
        $url = 'http://www.test.de';

        $treeNode = new ContentTreeNode();
        $treeNode->setId(1);

        $urlGenerator = $this->prophesize(NodeUrlGeneratorInterface::class);
        $urlGenerator->generateUrl($treeNode, array('_locale' => 'de'))->willReturn(null);

        $generator = new LanguageNodeUrlsetGenerator(
            $urlGenerator->reveal()
        );

        $result = $generator->generateUrlset($treeNode, 'de');

        $this->assertInstanceOf(Urlset::class, $result);
        $this->assertCount(0, $result->getUrls());
    }
}
