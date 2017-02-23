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
use PHPUnit\Framework\TestCase;
use Thepixeldeveloper\Sitemap\SitemapIndex;

/**
 * SitemapIndex event test.
 *
 * @author Matthias Harmuth <mharmuth@brainbits.net>
 *
 * @covers \Phlexible\Bundle\SitemapBundle\Event\SitemapIndexEvent
 */
class SitemapIndexEventTest extends TestCase
{
    public function testSitemapIndexEvent()
    {
        $sitemapIndex = new SitemapIndex();
        $siteroot = new Siteroot('foo');

        $event = new SitemapIndexEvent($sitemapIndex, $siteroot);

        $this->assertSame($sitemapIndex, $event->getSitemapIndex());
        $this->assertSame($siteroot, $event->getSiteroot());
    }
}
