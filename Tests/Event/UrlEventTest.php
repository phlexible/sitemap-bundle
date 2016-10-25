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

use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeNode;
use Thepixeldeveloper\Sitemap\Url;

/**
 * Url event test
 *
 * @author Stephan Wentz <sw@brainbits.net>
 *
 * @covers \Phlexible\Bundle\SitemapBundle\Event\UrlEvent
 */
class UrlEventTest extends \PHPUnit_Framework_TestCase
{
    public function testUrlEvent()
    {
        $url = new Url('http://www.test.de');
        $node = new ContentTreeNode();
        $parameters = array('_locale' => 'de');

        $event = new UrlEvent($url, $node, $parameters);

        $this->assertSame($url, $event->getUrl());
        $this->assertSame($node, $event->getTreeNode());
        $this->assertSame($parameters, $event->getParameters());
    }
}
