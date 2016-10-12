<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\SitemapBundle\Event;

use Phlexible\Bundle\SitemapBundle\Exception\InvalidArgumentException;
use Symfony\Component\EventDispatcher\Event;
use Thepixeldeveloper\Sitemap\Urlset;

/**
 * Element catch event
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
    private $siteRootId;

    /**
     * @param string $xmlSitemap
     * @param string $siteRootId
     */
    public function __construct($xmlSitemap, $siteRootId)
    {
        if (!is_string($xmlSitemap)) {
            throw new InvalidArgumentException("XML sitemap must be a string!");
        }
        $this->xmlSitemap = $xmlSitemap;

        if (!is_string($siteRootId)) {
            throw new InvalidArgumentException("Site root id must be a string!");
        }
        $this->siteRootId = $siteRootId;
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
    public function getSiteRootId()
    {
        return $this->siteRootId;
    }
}
