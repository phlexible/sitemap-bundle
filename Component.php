<?php
/**
 * MAKEweb
 *
 * PHP Version 5
 *
 * @category    Makeweb
 * @package     Makeweb_GoogleSitemaps
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 * @author      Franz Hanenberg <franzhanenberg@googlemail.com>
 * @author      Marko Schmitz <schmitz.marko@googlemail.com>
 * @version     SVN: $Id$
 */

/**
 * Google sitemap component
 *
 * @category    Makeweb
 * @package     Makeweb_GoogleSitemaps
 * @author      Franz Hanenberg <franzhanenberg@googlemail.com>
 * @author      Marko Schmitz <schmitz.marko@googlemail.com>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class Makeweb_GoogleSitemaps_Component extends MWF_Component_Abstract
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this
            ->setVersion('0.6.7')
            ->setId('googlesitemaps')
            ->setFile(__FILE__)
            ->setPackage('makeweb');
    }

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
