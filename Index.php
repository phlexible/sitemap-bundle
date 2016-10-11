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
 * Google sitemap index
 *
 * @category    Makeweb
 * @package     Makeweb_GoogleSitemaps
 * @author      Marco Fischer <mfischer@brainbits.net>
 * @copyright   2011 brainbits GmbH (http://www.brainbits.net)
 */
class Makeweb_GoogleSitemaps_Index
{
    /**
     * array of sitemaps
     * @var array
     */
    protected $_sitemaps = array();

    /**
     * set all sitemaps
     * @param array $sitemaps
     */
    public function setSitemaps($sitemaps)
    {
        $this->_sitemaps = $sitemaps;
    }

    /**
     * get all sitemaps
     * @return array
     */
    public function getSitemaps()
    {
        return $this->_sitemaps;
    }

    /**
     * add sitemap
     * @param string $sitemap
     */
    public function addSitemap($sitemap)
    {
        $this->_sitemaps[] = $sitemap;
    }

    /**
     * render sitemaps to sitemap-index
     * @return string
     */
    public function render()
    {
        $dom = new DOMDocument('1.0','UTF-8');
        $dom->formatOutput = true;

        $sitemapindex = $dom->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'sitemapindex');
        $dom->appendChild($sitemapindex);

        foreach ($this->_sitemaps as $sitemapItem)
        {
            $sitemap = $sitemapindex->appendChild($dom->createElement('sitemap'));

            $loc = $sitemap->appendChild($dom->createElement('loc'));
            $loc->appendChild($dom->createTextNode($sitemapItem));

            $lastmod = $sitemap->appendChild($dom->createElement('lastmod'));
            $lastmod->appendChild($dom->createTextNode(date('Y-m-d')));
        }

        return $dom->saveXML();
    }
}