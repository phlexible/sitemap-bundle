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
     * @var array
     */
    private $parameters;

    /**
     * @param Url               $url
     * @param TreeNodeInterface $treeNode
     * @param array             $parameters
     */
    public function __construct(Url $url, TreeNodeInterface $treeNode, array $parameters)
    {
        $this->url = $url;
        $this->treeNode = $treeNode;
        $this->parameters = $parameters;
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
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
