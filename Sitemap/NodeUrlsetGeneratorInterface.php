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
use Thepixeldeveloper\Sitemap\Urlset;

/**
 * Generates an url set for a tree node.
 *
 * @author Stephan Wentz <swentz@brainbits.net>
 */
interface NodeUrlsetGeneratorInterface
{
    /**
     * @param ContentTreeNode $treeNode
     * @param string $language
     *
     * @return Urlset|null
     */
    public function generateUrlset(ContentTreeNode $treeNode, $language);
}
