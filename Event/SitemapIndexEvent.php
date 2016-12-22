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
use Thepixeldeveloper\Sitemap\SitemapIndex;

/**
 * SitemapIndex event
 *
 * @author Matthias Harmuth <mharmuth@brainbits.net>
 */
class SitemapIndexEvent extends Event
{
    /**
     * @var SitemapIndex
     */
    private $sitemapIndex;

    /**
     * @var string
     */
    private $siteroot;

    /**
     * @param SitemapIndex $sitemapIndex
     * @param Siteroot     $siteroot
     */
    public function __construct(SitemapIndex $sitemapIndex, Siteroot $siteroot)
    {
        $this->sitemapIndex = $sitemapIndex;
        $this->siteroot = $siteroot;
    }

    /**
     * @return SitemapIndex
     */
    public function getSitemapIndex()
    {
        return $this->sitemapIndex;
    }

    /**
     * @return Siteroot
     */
    public function getSiteroot()
    {
        return $this->siteroot;
    }
}
