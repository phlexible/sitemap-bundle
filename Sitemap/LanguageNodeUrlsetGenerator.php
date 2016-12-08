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

use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeInterface;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeNode;
use Thepixeldeveloper\Sitemap\Url;
use Thepixeldeveloper\Sitemap\Urlset;

/**
 * Generates an url set for one language of a tree node.
 *
 * @author Stephan Wentz <swentz@brainbits.net>
 */
class LanguageNodeUrlsetGenerator implements NodeUrlsetGeneratorInterface
{
    /**
     * @var NodeUrlGeneratorInterface
     */
    private $nodeUrlGenerator;

    /**
     * @param NodeUrlGeneratorInterface $nodeUrlGenerator
     */
    public function __construct(NodeUrlGeneratorInterface $nodeUrlGenerator)
    {
        $this->nodeUrlGenerator = $nodeUrlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function generateUrlset(ContentTreeNode $treeNode, $language)
    {
        $urlSet = new Urlset();

        $url = $this->generateUrlFromNode($treeNode, (string) $language);
        if ($url) {
            $urlSet->addUrl($url);
        }

        return $urlSet;
    }

    /**
     * @param ContentTreeNode $treeNode
     * @param string          $language
     *
     * @return Url
     */
    private function generateUrlFromNode(ContentTreeNode $treeNode, $language)
    {
        $url = $this->nodeUrlGenerator->generateUrl(
            $treeNode,
            ['_locale' => $language]
        );

        return $url;
    }
}
