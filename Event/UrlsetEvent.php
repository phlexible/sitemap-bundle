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
 * Element catch event
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 */
class UrlsetEvent extends Event
{
    /**
     * @var Urlset
     */
    private $urlset;

    /**
     * @var int
     */
    private $siteRoot;

    /**
     * @param Urlset $urlset
     * @param Siteroot $siteroot
     */
    public function __construct(Urlset $urlset, Siteroot $siteroot)
    {
        $this->urlset = $urlset;
        $this->siteRoot = $siteroot;
    }

    /**
     * @return Urlset
     */
    public function getUrlset()
    {
        return $this->urlset;
    }

    public function getSiteroot()
    {
        return $this->siteRoot;
    }
}
