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

use Phlexible\Bundle\SitemapBundle\Event\UrlEvent;
use Phlexible\Bundle\SitemapBundle\Sitemap\SimpleNodeUrlGenerator;
use Phlexible\Bundle\SitemapBundle\SitemapEvents;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeNode;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Routing\Router;
use Thepixeldeveloper\Sitemap\Url;

/**
 * Simple node url generator test.
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 *
 * @covers \Phlexible\Bundle\SitemapBundle\Sitemap\SimpleNodeUrlGenerator
 */
class SimpleNodeUrlGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateUrl()
    {
        $url = 'http://www.test.de';

        $treeNode = new ContentTreeNode();
        $treeNode->setId(1);

        $eventDispatcher = $this->prophesize(EventDispatcher::class);
        $eventDispatcher->dispatch(
            SitemapEvents::BEFORE_URL_GENERATION,
            Argument::that(
                function (UrlEvent $event) use ($url) {
                    $this->assertSame($url, $event->getUrl()->getLoc());

                    return true;
                }
            )
        )->shouldBeCalled()->willReturnArgument(1);
        $eventDispatcher->dispatch(
            SitemapEvents::URL_GENERATION,
            Argument::that(
                function (UrlEvent $event) use ($url) {
                    $this->assertSame($url, $event->getUrl()->getLoc());

                    return true;
                }
            )
        )->shouldBeCalled();

        $router = $this->prophesize(Router::class);
        $router->generate($treeNode, [], 0)->shouldBeCalled()->willReturn($url);

        $generator = new SimpleNodeUrlGenerator(
            $router->reveal(),
            $eventDispatcher->reveal()
        );

        $result = $generator->generateUrl($treeNode, array());

        $expected = new Url($url);

        $this->assertEquals($expected, $result);
    }

    public function testGenerateUrlCanBeCancelled()
    {
        $url = 'http://www.test.de';

        $treeNode = new ContentTreeNode();
        $treeNode->setId(1);

        $eventDispatcher = $this->prophesize(EventDispatcher::class);
        $eventDispatcher->dispatch(
            SitemapEvents::BEFORE_URL_GENERATION,
            Argument::that(
                function (UrlEvent $event) use ($url) {
                    $this->assertSame($url, $event->getUrl()->getLoc());

                    return true;
                }
            )
        )->shouldBeCalled()->will(function($args) {
            $event = $args[1];
            $event->stopPropagation();

            return $event;
        });
        $eventDispatcher->dispatch(
            SitemapEvents::URL_GENERATION,
            Argument::that(
                function (UrlEvent $event) use ($url) {
                    $this->assertSame($url, $event->getUrl()->getLoc());

                    return true;
                }
            )
        )->shouldNotBeCalled();

        $router = $this->prophesize(Router::class);
        $router->generate($treeNode, [], 0)->shouldBeCalled()->willReturn($url);

        $generator = new SimpleNodeUrlGenerator(
            $router->reveal(),
            $eventDispatcher->reveal()
        );

        $result = $generator->generateUrl($treeNode, array());

        $this->assertNull($result);
    }
}
