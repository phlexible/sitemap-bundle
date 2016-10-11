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
class Makeweb_GoogleSitemaps_Event_GatherSitemapsPost extends Makeweb_GoogleSitemaps_Event_GatherSitemapsPre
{
    /**
     * @var string
     */
    protected $_notificationName = Makeweb_GoogleSitemaps_Event::GATHER_SITEMAPS_POST;
}