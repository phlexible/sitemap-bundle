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
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeNode;
use Thepixeldeveloper\Sitemap\Subelements\Link;
use Thepixeldeveloper\Sitemap\Url;
use Thepixeldeveloper\Sitemap\Urlset;

/**
 * Generates an url set for all country-versions of a single tree node.
 *
 * @author Stephan Wentz <swentz@brainbits.net>
 */
class HreflangNodeUrlsetGenerator implements NodeUrlsetGeneratorInterface
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
     * @param CountryCollection $countryCollection
     * @param NodeUrlGeneratorInterface $nodeUrlGenerator
     */
    public function __construct(CountryCollection $countryCollection, NodeUrlGeneratorInterface $nodeUrlGenerator)
    {
        $this->countryCollection = $countryCollection;
        $this->nodeUrlGenerator  = $nodeUrlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function generateUrlset(ContentTreeNode $treeNode, $language)
    {
        $urlSet = new Urlset();

        $url = $this->generateUrlFromNode($treeNode, $language);

        $countries = $this->countryCollection->filterLanguage($language);

        foreach ($countries as $country) {
            $countryUrl = $this->generateUrlFromNode($treeNode, $language, (string) $country);

            if ($countryUrl) {
                $hrefLang = new Link("{$language}-{$country}", $countryUrl->getLoc());
                $url->addSubElement($hrefLang);
            }
        }

        $urlSet->addUrl($url);

        return $urlSet;
    }

    /**
     * @param ContentTreeNode $treeNode
     * @param string $language
     * @param string $country
     *
     * @return Url
     */
    private function generateUrlFromNode(ContentTreeNode $treeNode, $language, $country = null)
    {
        $parameters = ['_locale' => $language];

        if (!empty($country)) {
            $parameters['_country'] = (string) $country;
        }

        $url = $this->nodeUrlGenerator->generateUrl($treeNode, $parameters);

        return $url;
    }
}
