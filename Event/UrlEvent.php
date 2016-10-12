<?php
/**
 * Created by PhpStorm.
 * User: jdschulze
 * Date: 12.10.16
 * Time: 12:36
 */
namespace Phlexible\Bundle\SitemapBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Thepixeldeveloper\Sitemap\Url;

/**
 * Element catch event
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 */
class UrlEvent extends Event
{
    /**
     * @var Url
     */
    private $url;

    /**
     * @param Url $url
     */
    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    /**
     * @return Url
     */
    public function getUrl()
    {
        return $this->url;
    }
}
