<?php

/*
 * This file is part of the phlexible sitemap package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\SitemapBundle\Sitemap;

use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeNode;
use Thepixeldeveloper\Sitemap\Url;

/**
 * Generates an url for a tree node.
 *
 * @author Stephan Wentz <swentz@brainbits.net>
 */
interface NodeUrlGeneratorInterface
{
    /**
     * @param ContentTreeNode $treeNode
     * @param array           $parameters
     *
     * @return Url|null
     */
    public function generateUrl(ContentTreeNode $treeNode, array $parameters);
}
