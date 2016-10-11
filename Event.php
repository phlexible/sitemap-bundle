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
 * Google sitemap events
 *
 * @category    Makeweb
 * @package     Makeweb_GoogleSitemaps
 * @author      Marco Fischer <mfischer@brainbits.net>
 * @copyright   2011 brainbits GmbH (http://www.brainbits.net)
 */
interface Makeweb_GoogleSitemaps_Event
{
    /**
     * Fired before sitemaps are filled with basic languages
     *
     * Set variables:
     * - index
     */
    const GATHER_SITEMAPS_PRE = 'googlesitemaps.gather_sitemaps_pre';

    /**
     * Fired after sitemaps are filled with basic languages
     *
     * Set variables:
     * - index
     */
    const GATHER_SITEMAPS_POST = 'googlesitemaps.gather_sitemaps_post';

    /**
     * Fired before urlset gets created
     */
    const BEFORE_CREATE_URLSET = 'googlesitemaps.before_create_urlset';

    /**
     * Fired after urlset was created
     */
    const CREATE_URLSET = 'googlesitemaps.create_urlset';
}