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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Sitemap extension.
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 */
class PhlexibleSitemapExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $configuration = $this->getConfiguration($config, $container);
        $config = $this->processConfiguration($configuration, $config);

        $container->setParameter('phlexible_sitemap.cache_dir', $config['cache_dir']);

        $container->setAlias('phlexible_sitemap.node_urlset_generator', $config['node_urlset_generator']);
        $container->setAlias('phlexible_sitemap.node_url_generator', $config['node_url_generator']);
    }
}
