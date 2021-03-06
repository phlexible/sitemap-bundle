<?php

/*
 * This file is part of the phlexible sitemap package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\SitemapBundle\Test\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use Phlexible\Bundle\SitemapBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Configuration test.
 *
 * @author Stephan Wentz <swentz@brainbits.net>
 *
 * @covers \Phlexible\Bundle\SitemapBundle\DependencyInjection\Configuration
 */
class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }

    public function testDefaultValues()
    {
        $this->assertProcessedConfigurationEquals(
            array(
                array(),
            ),
            array(
                'cache_dir' => '%kernel.cache_dir%/sitemap',
                'node_urlset_generator' => 'phlexible_sitemap.language_node_urlset_generator',
                'node_url_generator' => 'phlexible_sitemap.simple_node_url_generator',
            )
        );
    }

    public function testConfiguredValues()
    {
        $this->assertProcessedConfigurationEquals(
            array(
                array(
                    'cache_dir' => 'cache',
                    'node_urlset_generator' => 'urlset',
                    'node_url_generator' => 'url',
                ),
            ),
            array(
                'cache_dir' => 'cache',
                'node_urlset_generator' => 'urlset',
                'node_url_generator' => 'url',
            )
        );
    }
}
