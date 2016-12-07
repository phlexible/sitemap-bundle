<?php

/*
 * This file is part of the phlexible sitemap package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\SitemapBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Sitemap configuration.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('phlexible_sitemap');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('cache_dir')->defaultValue('%kernel.cache_dir%/sitemap')->end()
                ->scalarNode('sitemap_generator')->defaultValue('phlexible_sitemap.caching_generator')->end()
                ->scalarNode('node_urlset_generator')->defaultValue('phlexible_sitemap.language_node_urlset_generator')->end()
                ->scalarNode('node_url_generator')->defaultValue('phlexible_sitemap.simple_node_url_generator')->end()
            ->end();

        return $treeBuilder;
    }
}
