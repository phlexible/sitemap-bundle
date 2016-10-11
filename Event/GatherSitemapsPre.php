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
 * Google sitemap event
 *
 * @category    Makeweb
 * @package     Makeweb_GoogleSitemaps
 * @author      Marco Fischer <mfischer@brainbits.net>
 * @copyright   2011 brainbits GmbH (http://www.brainbits.net)
 */
class Makeweb_GoogleSitemaps_Event_GatherSitemapsPre extends Brainbits_Event_Notification_Abstract
{
    /**
     * @var string
     */
    protected $_notificationName = Makeweb_GoogleSitemaps_Event::GATHER_SITEMAPS_PRE;

    /**
     * @var Zend_Controller_Request_Abstract
     */
    protected $_request = null;

    /**
     * @var Makeweb_GoogleSitemaps_Index
     */
    protected $_index = null;

    /**
     * Constructor
     *
     * @param Zend_Controller_Request_Abstract $request
     * @param Makeweb_GoogleSitemaps_Index $index
     */
    public function __construct(Zend_Controller_Request_Abstract $request, Makeweb_GoogleSitemaps_Index $index)
    {
        $this->_request = $request;
        $this->_index   = $index;
    }

    /**
     * Return request
     *
     * @return Zend_Controller_Request_Abstract
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * Return index
     *
     * @return Makeweb_GoogleSitemaps_Index
     */
    public function getIndex()
    {
        return $this->_index;
    }
}