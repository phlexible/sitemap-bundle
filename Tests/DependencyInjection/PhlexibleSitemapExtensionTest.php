<?php

/*
 * This file is part of the phlexible sitemap package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\SitemapBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Phlexible\Bundle\SitemapBundle\DependencyInjection\PhlexibleSitemapExtension;

/**
 * Phlexible sitemap extension test
 *
 * @author Stephan Wentz <swentz@brainbits.net>
 *
 * @covers \Phlexible\Bundle\SitemapBundle\DependencyInjection\PhlexibleSitemapExtension
 */
class PhlexibleSitemapExtensionTest extends AbstractExtensionTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return array(
            new PhlexibleSitemapExtension()
        );
    }

    public function testContainerWithDefaultConfiguration()
    {
        $this->load();

        $this->assertContainerBuilderHasAlias('phlexible_sitemap.node_urlset_generator', 'phlexible_sitemap.language_node_urlset_generator');
        $this->assertContainerBuilderHasAlias('phlexible_sitemap.node_url_generator', 'phlexible_sitemap.simple_node_url_generator');
    }

    public function testContainerWithCustomerConfiguration()
    {
        $this->load(array(
            'node_urlset_generator' => 'urlset',
            'node_url_generator' => 'url',
        ));

        $this->assertContainerBuilderHasAlias('phlexible_sitemap.node_urlset_generator', 'urlset');
        $this->assertContainerBuilderHasAlias('phlexible_sitemap.node_url_generator', 'url');
    }
}
