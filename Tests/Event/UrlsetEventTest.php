<?php

/*
 * This file is part of the phlexible sitemap package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\SitemapBundle\Event;

use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use Thepixeldeveloper\Sitemap\Urlset;

/**
 * Urlset event test
 *
 * @author Stephan Wentz <sw@brainbits.net>
 *
 * @covers \Phlexible\Bundle\SitemapBundle\Event\UrlsetEvent
 */
class UrlsetEventTest extends \PHPUnit_Framework_TestCase
{
    public function testUrlsetEvent()
    {
        $urlset = new Urlset();
        $siteroot = new Siteroot('foo');

        $event = new UrlsetEvent($urlset, $siteroot);

        $this->assertSame($urlset, $event->getUrlset());
        $this->assertSame($siteroot, $event->getSiteroot());
    }
}
