<?php

/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\SitemapBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PhlexibleSitemapBundle extends Bundle
{
    public function initContainer(MWF_Container_ContainerBuilder $container, array $configs)
    {
        $processor = new Symfony\Component\Config\Definition\Processor();
        $configuration = new Makeweb_GoogleSitemaps_Container_Configuration();
        $processedConfiguration = $processor->processConfiguration(
            $configuration,
            $configs
        );

        $container->setParams(
            array(
                ':googlesitemaps.skip_elementtype_ids' => $processedConfiguration['skip_elementtype_ids'],
            )
        );
    }

    /**
     * Callback for routes
     *
     * @return array
     */
    public function getFrontendRoutes()
    {
        $data = new Zend_Controller_Router_Route(
            '/sitemap.xml',
            array('module' => 'googlesitemaps', 'controller' => 'data', 'action' => 'index')
        );

        return array(
            'googlesitemaps_data' => $data,
        );
    }
}
