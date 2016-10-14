<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\SitemapBundle\Event;

use Phlexible\Bundle\SitemapBundle\Exception\InvalidArgumentException;
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use Symfony\Component\EventDispatcher\Event;
use Thepixeldeveloper\Sitemap\Urlset;

/**
 * XML sitemap event
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 */
class XmlSitemapEvent extends Event
{
    /**
     * @var Urlset
     */
    private $xmlSitemap;

    /**
     * @var string
     */
    private $siteRoot;

    /**
     * @param string $xmlSitemap
     * @param Siteroot $siteRoot
     */
    public function __construct($xmlSitemap, Siteroot $siteRoot)
    {
        if (!is_string($xmlSitemap)) {
            throw new InvalidArgumentException("XML sitemap must be a string!");
        }
        $this->xmlSitemap = $xmlSitemap;

        $this->siteRoot = $siteRoot;
    }

    /**
     * @return string
     */
    public function getXmlSitemap()
    {
        return $this->xmlSitemap;
    }

    /**
     * @return string
     */
    public function getSiteRoot()
    {
        return $this->siteRoot;
    }
}
