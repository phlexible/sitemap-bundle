<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\SitemapBundle\Event;

use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use Symfony\Component\EventDispatcher\Event;
use Thepixeldeveloper\Sitemap\Urlset;

/**
 * Urlset event
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
     * @var string
     */
    private $siteRoot;

    /**
     * @param Urlset $urlset
     * @param Siteroot $siteRoot
     */
    public function __construct(Urlset $urlset, Siteroot $siteRoot)
    {
        $this->urlset = $urlset;

        $this->siteRoot = $siteRoot;
    }

    /**
     * @return Urlset
     */
    public function getUrlset()
    {
        return $this->urlset;
    }

    /**
     * @return Siteroot
     */
    public function getSiteRoot()
    {
        return $this->siteRoot;
    }
}
