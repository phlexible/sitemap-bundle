<?php
/**
 * MAKEweb
 *
 * PHP Version 5
 *
 * @category    Makeweb
 * @package     Makeweb_GoogleSitemaps
 * @copyright   2011 brainbits GmbH (http://www.brainbits.net)
 * @version     SVN: $Id$
 */

/**
 * Google sitemap before event
 *
 * @category    Makeweb
 * @package     Makeweb_GoogleSitemaps
 * @author      Matthias Harmuth <mharmuth@brainbits.net>
 * @copyright   2013 brainbits GmbH (http://www.brainbits.net)
 */
class Makeweb_GoogleSitemaps_Event_BeforeCreateUrlset extends Brainbits_Event_Notification_Abstract
{
    /**
     * @var string
     */
    protected $_notificationName = Makeweb_GoogleSitemaps_Event::BEFORE_CREATE_URLSET;

    /**
     * @var Makeweb_GoogleSitemaps_Xml
     */
    protected $_xml;

    /**
     * @var Makeweb_Siteroots_Siteroot
     */
    protected $_siteroot;

    /**
     * @var string
     */
    protected $_language;

    /**
     * Constructor
     *
     * @param Makeweb_GoogleSitemaps_Xml $xml
     * @param Makeweb_Siteroots_Siteroot $siteroot
     * @param string $language
     */
    public function __construct(Makeweb_GoogleSitemaps_Xml $xml,
                                Makeweb_Siteroots_Siteroot $siteroot,
                                $language)
    {
        $this->_xml      = $xml;
        $this->_siteroot = $siteroot;
        $this->_language = $language;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->_language;
    }

    /**
     * @return \Makeweb_Siteroots_Siteroot
     */
    public function getSiteroot()
    {
        return $this->_siteroot;
    }

    /**
     * @return \Makeweb_GoogleSitemaps_Xml
     */
    public function getXml()
    {
        return $this->_xml;
    }
}