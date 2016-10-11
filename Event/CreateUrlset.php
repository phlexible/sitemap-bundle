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
 * @author      Matthias Harmuth <mharmuth@brainbits.net>
 * @copyright   2013 brainbits GmbH (http://www.brainbits.net)
 */
class Makeweb_GoogleSitemaps_Event_CreateUrlset extends Makeweb_GoogleSitemaps_Event_BeforeCreateUrlset
{
    /**
     * @var string
     */
    protected $_notificationName = Makeweb_GoogleSitemaps_Event::CREATE_URLSET;
}