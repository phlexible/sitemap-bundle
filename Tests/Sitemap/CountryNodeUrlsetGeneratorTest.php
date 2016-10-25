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

use Phlexible\Bundle\CountryContextBundle\Mapping\CountryCollection;
use Phlexible\Bundle\SitemapBundle\Sitemap\CountryNodeUrlsetGenerator;
use Phlexible\Bundle\SitemapBundle\Sitemap\NodeUrlGeneratorInterface;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeNode;
use Thepixeldeveloper\Sitemap\Url;
use Thepixeldeveloper\Sitemap\Urlset;

/**
 * Country node urlset generator test
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 *
 * @covers \Phlexible\Bundle\SitemapBundle\Sitemap\CountryNodeUrlsetGenerator
 */
class CountryNodeUrlsetGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateUrl()
    {
        $url = 'http://www.test.de';

        $treeNode = new ContentTreeNode();
        $treeNode->setId(1);

        $urlGenerator = $this->prophesize(NodeUrlGeneratorInterface::class);
        $urlGenerator->generateUrl($treeNode, array('_country' => 'de', '_locale' => 'de'))->willReturn(new Url($url));

        $countryCollection = $this->prophesize(CountryCollection::class);
        $countryCollection->filterLanguage('de')->willReturn(array('de'));

        $generator = new CountryNodeUrlsetGenerator($countryCollection->reveal(), $urlGenerator->reveal());

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
        $urlGenerator->generateUrl($treeNode, array('_country' => 'de', '_locale' => 'de'))->willReturn(null);

        $countryCollection = $this->prophesize(CountryCollection::class);
        $countryCollection->filterLanguage('de')->willReturn(array('de'));

        $generator = new CountryNodeUrlsetGenerator($countryCollection->reveal(), $urlGenerator->reveal());

        $result = $generator->generateUrlset($treeNode, 'de');

        $this->assertInstanceOf(Urlset::class, $result);
        $this->assertCount(0, $result->getUrls());
    }
}
