<?php
/**
 * Created by PhpStorm.
 * User: jdschulze
 * Date: 12.10.16
 * Time: 12:12
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
     * @var int
     */
    private $siteRootId;

    /**
     * @param Urlset $urlset
     * @param int $siteRootId
     */
    public function __construct(Urlset $urlset, $siteRootId)
    {
        $this->urlset = $urlset;

        if (!is_int($siteRootId)) {
            throw new InvalidArgumentException("Invalid Site Root Id $siteRootId");
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
}
