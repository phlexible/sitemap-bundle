<?php

/*
 * This file is part of the phlexible sitemap package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
    private $siteroot;

    /**
     * @param Urlset   $urlset
     * @param Siteroot $siteroot
     */
    public function __construct(Urlset $urlset, Siteroot $siteroot)
    {
        $this->urlset = $urlset;
        $this->siteroot = $siteroot;
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
    public function getSiteroot()
    {
        return $this->siteroot;
    }
}
