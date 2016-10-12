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
class UrlsetEvent extends Event
{
    /**
     * @var Urlset
     */
    private $urlset;

    /**
     * @var string
     */
    private $siteRootId;

    /**
     * @param Urlset $urlset
     * @param string $siteRootId
     */
    public function __construct(Urlset $urlset, $siteRootId)
    {
        $this->urlset = $urlset;

        if (!is_string($siteRootId)) {
            throw new InvalidArgumentException("Site root id must be a string!");
        }
        $this->siteRootId = $siteRootId;
    }

    /**
     * @return Urlset
     */
    public function getUrlset()
    {
        return $this->urlset;
    }

    /**
     * @return string
     */
    public function getSiteRootId()
    {
        return $this->siteRootId;
    }
}
