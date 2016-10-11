<?php

/**
 * Phlexible
 *
 * PHP Version 5
 *
 * @category    Makeweb
 * @package     Makeweb_GoogleSitemaps
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */

/**
 * Container Configuration
 *
 * @category    Makeweb
 * @package     Makeweb_GoogleSitemaps
 * @author      Phillip Look <pl@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class Makeweb_GoogleSitemaps_Container_Configuration implements Symfony\Component\Config\Definition\ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new Symfony\Component\Config\Definition\Builder\TreeBuilder();
        $rootNode = $treeBuilder->root('googlesitemaps');

        $rootNode
            ->children()
                ->arrayNode('skip_elementtype_ids')
                    ->prototype('integer')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
