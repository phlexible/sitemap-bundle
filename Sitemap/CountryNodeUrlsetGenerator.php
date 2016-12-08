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

use Phlexible\Bundle\CountryContextBundle\Mapping\CountryCollection;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeInterface;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeNode;
use Thepixeldeveloper\Sitemap\Url;
use Thepixeldeveloper\Sitemap\Urlset;

/**
 * Generates an url set for all country-versions of a single tree node.
 *
 * @author Stephan Wentz <swentz@brainbits.net>
 */
class CountryNodeUrlsetGenerator implements NodeUrlsetGeneratorInterface
{
    /**
     * @var CountryCollection
     */
    private $countryCollection;

    /**
     * @var NodeUrlGeneratorInterface
     */
    private $nodeUrlGenerator;

    /**
     * @param CountryCollection         $countryCollection
     * @param NodeUrlGeneratorInterface $nodeUrlGenerator
     */
    public function __construct(CountryCollection $countryCollection, NodeUrlGeneratorInterface $nodeUrlGenerator)
    {
        $this->countryCollection = $countryCollection;
        $this->nodeUrlGenerator = $nodeUrlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function generateUrlset(ContentTreeNode $treeNode, $language)
    {
        $urlSet = new Urlset();

        $countries = $this->countryCollection->filterLanguage($language);
        foreach ($countries as $country) {
            $url = $this->generateUrlFromNode($treeNode, (string) $country, $language);
            if ($url) {
                $urlSet->addUrl($url);
            }
        }

        return $urlSet;
    }

    /**
     * @param ContentTreeNode $treeNode
     * @param string          $country
     * @param string          $language
     *
     * @return Url
     */
    private function generateUrlFromNode(ContentTreeNode $treeNode, $country, $language)
    {
        $url = $this->nodeUrlGenerator->generateUrl(
            $treeNode,
            ['_country' => (string) $country, '_locale' => $language]
        );

        return $url;
    }

}
