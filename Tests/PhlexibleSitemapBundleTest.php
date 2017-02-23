<?php

/*
 * This file is part of the phlexible sitemap package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\SitemapBundle\Tests;

use Phlexible\Bundle\SitemapBundle\PhlexibleSitemapBundle;
use PHPUnit\Framework\TestCase;

/**
 * Phlexible sitemap bundle test.
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 *
 * @covers \Phlexible\Bundle\SitemapBundle\PhlexibleSitemapBundle
 */
class PhlexibleSitemapBundleTest extends TestCase
{
    public function testBundle()
    {
        $bundle = new PhlexibleSitemapBundle();

        $this->assertSame('PhlexibleSitemapBundle', $bundle->getName());
    }
}
