<?php

/*
 * This file is part of the phlexible sitemap package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\SitemapBundle\Tests\Controller;

use Phlexible\Bundle\SitemapBundle\Controller\SitemapController;
use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapGeneratorInterface;
use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapIndexGeneratorInterface;
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use Phlexible\Bundle\SiterootBundle\Siteroot\SiterootRequestMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sitemap controller test
 *
 * @author Stephan Wentz <sw@brainbits.net>
 *
 * @covers \Phlexible\Bundle\SitemapBundle\Controller\SitemapController
 */
class SitemapControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateSitemap()
    {
        $request = new Request();
        $request->query->set('language', 'de');

        $siteroot = new Siteroot('foo');

        $generator = $this->prophesize(SitemapGeneratorInterface::class);

        $generator->generateSitemap(
            $siteroot,
            $request->query->get('language')
        )->willReturn('bar');

        $siterootRequestMatcher = $this->prophesize(SiterootRequestMatcher::class);
        $siterootRequestMatcher->matchRequest($request)->willReturn($siteroot);

        $controller = new SitemapController(
            $generator->reveal(),
            $siterootRequestMatcher->reveal()
        );

        $result = $controller->indexAction($request);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame(200, $result->getStatusCode());
        $this->assertSame('bar', $result->getContent());
        $this->assertSame('text/xml; charset=UTF-8', $result->headers->get('Content-type'));
    }

    public function testGenerateSitemapIndex()
    {
        $request = new Request();

        $siteroot = new Siteroot('foo');

        $generator = $this->prophesize(SitemapIndexGeneratorInterface::class);
        $generator->generateSitemapIndex($siteroot)->willReturn('bar');

        $siterootRequestMatcher = $this->prophesize(SiterootRequestMatcher::class);
        $siterootRequestMatcher->matchRequest($request)->willReturn($siteroot);

        $controller = new SitemapController($generator->reveal(), $siterootRequestMatcher->reveal());

        $result = $controller->indexAction($request);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame(200, $result->getStatusCode());
        $this->assertSame('bar', $result->getContent());
        $this->assertSame('text/xml; charset=UTF-8', $result->headers->get('Content-type'));
    }
}
