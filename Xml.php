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
 * Google sitemap xml generator
 *
 * @category    Makeweb
 * @package     Makeweb_GoogleSitemaps
 * @author      Franz Hanenberg <franzhanenberg@googlemail.com>
 * @author      Marko Schmitz <schmitz.marko@googlemail.com>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class Makeweb_GoogleSitemaps_Xml
{
    const FREQ_ALWAYS  = 'always';
    const FREQ_HOURLY  = 'hourly';
    const FREQ_DAILY   = 'daily';
    const FREQ_WEEKLY  = 'weekly';
    const FREQ_MONTHLY = 'monthly';
    const FREQ_YEARLY  = 'yearly';
    const FREQ_NEVER   = 'never';

    /**
     * Version of the Google Sitemap this class is for
     * @var string
     */
    protected $_sitemapVersion = '0.9';

    /**
     * URL to the namespace for Google Sitemaps
     * @var string
     */
    protected $_namespace = 'http://www.sitemaps.org/schemas/sitemap/0.9';

    /**
     * URL to the xhtml namespace
     * @var string
     */
    protected $_xhtmlNamespace = 'http://www.w3.org/1999/xhtml';

    /**
     * Additional namespaces
     * @var array
     */
    protected $_namespaces = array(
        'image' => 'http://www.sitemaps.org/schemas/sitemap-image/1.1',
        'video' => 'http://www.sitemaps.org/schemas/sitemap-video/1.1',
    );

    /**
     * Character encoding to use in output XML
     * @var string
     */
    protected $_xmlEncoding = 'UTF-8';

    /**
     * The different values allowed for change frequency, if specified
     * @var array
     */
    protected $_changeFreqs = array(
        self::FREQ_ALWAYS,
        self::FREQ_HOURLY,
        self::FREQ_DAILY,
        self::FREQ_WEEKLY,
        self::FREQ_MONTHLY,
        self::FREQ_YEARLY,
        self::FREQ_NEVER
    );

    /**
     * Maximum length a URL can be
     * @var integer
     */
    protected $_maxUrlLen = 2048;

    /**
     * The range of values priority can be
     * @var float
     */
    protected $_priorityMin = 0.0;
    protected $_priorityMax = 1.0;
    protected $_priorityStep = 0.1;
    protected $_priorityFormat = '%01.1f';

    /**
     * Format strings for representing last modified timestamps, using
     * gmdate() to format the strings. PHP's timezone flag outputs
     * in the format '+0000' rather than '+00:00'
     * @var string
     */
    protected $_lastModDate = 'Y-m-d';
//    protected $_lastModDateTime = 'Y-m-d\TH:i:s';
    protected $_lastModDateTime = 'c';

    /**
     * Maximum number of URLs that can be specified
     * @var integer
     */
    protected $_maxURLs = 50000;

    /**
     * @var Makeweb_GoogleSitemaps_Url[]
     */
    protected $_urls = array();

    /**
     * Add url object
     * Provides a fluent interface
     *
     * @param Makeweb_GoogleSitemaps_Url $url
     * @return Makeweb_GoogleSitemaps_Xml
     */
    public function addUrl(Makeweb_GoogleSitemaps_Url $url)
    {
        $this->_urls[] = $url;

        return $this;
    }

    /**
     * @return Makeweb_GoogleSitemaps_Url[]
     */
    public function getUrls()
    {
        return $this->_urls;
    }

    /**
     * Generate XML string
     *
     * @return string
     */
    public function generate()
    {
        $dom = new DOMDocument('1.0','UTF-8');
        $dom->formatOutput = true;

        $urlSet = $dom->createElementNS($this->_namespace, 'urlset');
        $dom->appendChild($urlSet);
        $urlSet->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xhtml', $this->_xhtmlNamespace);

        foreach ($this->_urls as $urlItem)
        {
            $url = $urlSet->appendChild($dom->createElement('url'));

            $loc = $url->appendChild($dom->createElement('loc'));
            $loc->appendChild($dom->createTextNode($urlItem->getLoc()));

            $priority = $url->appendChild($dom->createElement('priority'));
            $priority->appendChild($dom->createTextNode($urlItem->getPriority()));

            if ($urlItem->getLastMod())
            {
                $lastmod = $url->appendChild($dom->createElement('lastmod'));
                $lastmod->appendChild($dom->createTextNode($urlItem->getLastMod()));
            }

            if ($urlItem->getChangeFreq())
            {
                $changefreq = $url->appendChild($dom->createElement('changefreq'));
                $changefreq->appendChild($dom->createTextNode($urlItem->getChangeFreq()));
            }

            // add assets
            foreach ($urlItem->getAssets() as $assetItem)
            {
                $type = $assetItem->getType();

                $dom->createAttributeNS($this->_namespaces[$type], $type . ':' . $type);

                $asset = $dom->createElementNS($this->_namespaces[$type], $type);
                $url->appendChild($asset);

                $values = $assetItem->getValues();

                foreach ($values as $key => $value)
                {
                    if ($value)
                    {
                        $assetLoc = $asset->appendChild($dom->createElement($key));
                        $assetLoc->appendChild($dom->createTextNode($value));
                    }
                }
            }

            // add alternates
            foreach ($urlItem->getAlternates() as $alternate)
            {
                $link = $dom->createElement('xhtml:link');
                $link->setAttribute('rel', 'alternate');
                $link->setAttribute('hreflang', $alternate->getLang());
                $link->setAttribute('href', $alternate->getLink());

                $url->appendChild($link);
            }
        }

        return $dom->saveXML();
    }
}
