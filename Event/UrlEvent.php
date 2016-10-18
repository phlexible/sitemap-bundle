<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\SitemapBundle\Event;

use Phlexible\Bundle\SitemapBundle\Exception\InvalidArgumentException;
use Phlexible\Bundle\TreeBundle\Model\TreeNodeInterface;
use Symfony\Component\EventDispatcher\Event;
use Thepixeldeveloper\Sitemap\Url;

/**
 * Url event
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
     * @var TreeNodeInterface
     */
    private $treeNode;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $language;

    /**
     * @param Url $url
     * @param TreeNodeInterface $treeNode
     * @param string $country
     * @param string $language
     */
    public function __construct(Url $url, TreeNodeInterface $treeNode, $country, $language)
    {
        $this->url = $url;
        $this->treeNode = $treeNode;
        if (is_string($country)) {
            $this->country = $country;
        } else {
            throw new InvalidArgumentException('Country must be a string!');
        }
        if (is_string($language)) {
            $this->language = $language;
        } else {
            throw new InvalidArgumentException('Language must be a string!');
        }
    }

    /**
     * @return Url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return TreeNodeInterface
     */
    public function getTreeNode()
    {
        return $this->treeNode;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }
}
