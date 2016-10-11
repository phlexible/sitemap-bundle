<?php
/**
 * MAKEweb
 *
 * PHP Version 5
 *
 * @category    Makeweb
 * @package     Makeweb_GoogleSitemaps
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 * @version     SVN: $Id$
 */

/**
 * Instances Overview Controller
 *
 * @category    Makeweb
 * @package     Makeweb_GoogleSitemaps
 * @author      Franz Hanenberg <franzhanenberg@googlemail.com>
 * @author      Marko Schmitz <schmitz.marko@googlemail.com>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class Googlesitemaps_DataController extends MWF_Controller_Action
{
    /**
     * Array of requestParams
     * @var array
     */
    protected $_requestParams = array();

    /**
     * Array of url parts
     * @var array
     */
    protected $_urlParts = array();

    /**
     * @var Makeweb_Siteroots_Siteroot
     */
    protected $_siteroot = null;

    /**
     * @var Makeweb_Siteroots_Siteroot_Url
     */
    protected $_siterootUrl = null;

    public function init()
    {
        parent::init();

        $uri         = $_SERVER['REQUEST_URI'];
        $explodedUri = explode('?', $uri);

        $host = !empty($_SERVER['HTTP_X_FORWARDED_HOST'])
            ? $_SERVER['HTTP_X_FORWARDED_HOST']
            : $_SERVER['HTTP_HOST'];

        $explodedHost = explode(':', $host);
        $host         = $explodedHost[0];
        $port         = !empty($explodedHost[1]) ? $explodedHost[1] : 80;

        $this->_urlParts = array(
            'scheme'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http',
            'host'     => $host,
            'port'     => $port,
            'user'     => '',
            'pass'     => '',
            'path'     => $explodedUri[0],
            'query'    => !empty($explodedUri[1]) ? $explodedUri[1] : '',
            'fragment' => '',
        );

        $siterootUrlManager = Makeweb_Siteroots_Siteroot_Url_Manager::getInstance();
        $this->_siterootUrl = $siterootUrlManager->findMatchingUrl($host, '');
        $this->_siteroot    = $this->_siterootUrl->getSiteroot();

        $request = new Makeweb_Frontend_Request($this->_response, null, false, $this->getRequest()->getBaseUrl());
        $this->_requestParams = $request->getHandler()->process($this->_urlParts, $this->_siterootUrl);
    }

    public function indexAction()
    {
        if (false !== $this->getRequest()->getParam('language', false))
        {
            return $this->languageAction();
        }

        $container    = $this->getContainer();
        $dispatcher   = $container->dispatcher;
        $sitemapIndex = new Makeweb_GoogleSitemaps_Index();

        // post before GatherSitemaps event
        $beforeEvent = new Makeweb_GoogleSitemaps_Event_GatherSitemapsPre($this->getRequest(), $sitemapIndex);
        if (!$dispatcher->postNotification($beforeEvent))
        {
            return false;
        }

        $treeManager = $container->elementsTreeManager;

        $tree = $treeManager->getBySiteRootId(
            $this->_requestParams[Makeweb_Frontend_Request_Handler_Interface::KEY_SITEROOT_URL]['siteroot_id']
        );
        $languages = $tree->getOnlineLanguages(
            $this->_requestParams[Makeweb_Frontend_Request_Handler_Interface::KEY_SITEROOT_URL]['target']
        );

        foreach ($languages as $language)
        {
            $sitemapIndex->addSitemap(
                $this->_urlParts['scheme'] . '://'
                    . $this->_urlParts['host']
                    . $this->_urlParts['path']
                    . '?language=' . $language
            );
        }

        // post GatherSitemaps event
        $event = new Makeweb_GoogleSitemaps_Event_GatherSitemapsPost($this->getRequest(), $sitemapIndex);
        $event->setBeforeNotification($beforeEvent);
        $dispatcher->postNotification($event);

        $this->getResponse()->setHeader('Content-type', 'text/xml');
        $this->getResponse()->setBody($sitemapIndex->render());
    }

    /**
     * Add urlset for given language
     */
    public function languageAction()
    {
        $container             = $this->getContainer();
        $treeManager           = $container->elementsTreeManager;
        $elementVersionManager = $container->elementsVersionManager;
        $dispatcher            = $container->dispatcher;

        $siterootId = $this->_siteroot->getId();
        $language   = $this->getRequest()->getParam('language');

        $tree = $treeManager->getBySiteRootId($siterootId);
        $xml  = new Makeweb_GoogleSitemaps_Xml();

        // post before create urlset event
        $beforeEvent = new Makeweb_GoogleSitemaps_Event_BeforeCreateUrlset($xml, $this->_siteroot, $language);
        if (!$dispatcher->postNotification($beforeEvent))
        {
            return false;
        }

        $skipElementtypeIds = $this->getContainer()->getParam(':googlesitemaps.skip_elementtype_ids');

        $rii = new RecursiveIteratorIterator($tree->getIterator(), RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($rii as $node)
        {
            /* @var $node Makeweb_Elements_Tree_Node */

            $eid = $node->getEid();
            $version = $node->getOnlineVersion($language);
            if (!$version)
            {
                continue;
            }

            try
            {
                $elementVersion = $elementVersionManager->get($eid, $version);
            }
            catch (Exception $e)
            {
                $elementVersion = null;
            }

            if (!$elementVersion)
            {
                continue;
            }

            if (in_array((int) $elementVersion->getElementTypeID(), $skipElementtypeIds))
            {
                continue;
            }

            if ($node->isRestricted($language, $elementVersion->getVersion()))
            {
                continue;
            }

            $elementTypeVersion = $elementVersion->getElementTypeVersionObj();
            $elementType = $elementTypeVersion->getElementType();

            if ($elementType->getType() !== Makeweb_Elementtypes_Elementtype_Version::TYPE_FULL)
            {
                continue;
            }

            // check for nofollow meta key
            $metaIdentifier = new Makeweb_Elements_Element_Version_MetaSet_Identifier($elementVersion, $language);
            $metaSetId  = $elementTypeVersion->getMetaSetId();
            if ($metaSetId)
            {
                $metaSet = Media_MetaSets_Item_Peer::get($metaSetId, $metaIdentifier);
                if ($metaSet->hasKey('nofollow') && ($metaSet->nofollow === 'true'))
                {
                    continue;
                }
            }

            $loc     = Makeweb_Navigations_Link::createFromTid($node->getId(), $language, false);
            $lastMod = date('c', strtotime($elementVersion->getCreateTime()));

            $url = new Makeweb_GoogleSitemaps_Url($loc, $node);

            $exlodedUrl = explode('/', $loc);

            $level = count($exlodedUrl) - 3;

            switch ($level) {
                case 1:
                    $url->setPriority('1.0');
                    $url->setChangeFreq(Makeweb_GoogleSitemaps_Xml::FREQ_HOURLY);
                    $url->setLastMod($lastMod);
                    break;

                case 2:
                    $url->setPriority('0.9');
                    $url->setChangeFreq(Makeweb_GoogleSitemaps_Xml::FREQ_DAILY);
                    $url->setLastMod($lastMod);
                    break;

                default:
                    $url->setPriority('0.8');
                    $url->setChangeFreq(Makeweb_GoogleSitemaps_Xml::FREQ_DAILY);
                    $url->setLastMod($lastMod);
                    break;
            }

            $xml->addUrl($url);
        }

        // post create event
        $event = new Makeweb_GoogleSitemaps_Event_CreateUrlset($xml, $this->_siteroot, $language);
        $event->setBeforeNotification($beforeEvent);
        $dispatcher->postNotification($event);

        $this->getResponse()->setHeader('Content-type', 'text/xml');
        $this->getResponse()->setBody($xml->generate());
    }
}
