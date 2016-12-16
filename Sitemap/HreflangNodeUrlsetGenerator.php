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
use Thepixeldeveloper\Sitemap\Subelements\Link;
use Thepixeldeveloper\Sitemap\Url;
use Thepixeldeveloper\Sitemap\Urlset;

/**
 * Generates an url set for all country-versions of a single tree node.
 *
 * @author Stephan Wentz <swentz@brainbits.net>
 */
class HreflangNodeUrlsetGenerator extends CountryNodeUrlsetGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generateUrlset(ContentTreeNode $treeNode, $language)
    {
        $urlSet = new Urlset();

        // get first country to build main url
        $countries = $this->countryCollection->filterLanguage($language);
        $allCountries = $countries->all();
        $mainCountry = array_shift($allCountries);

        $url = $this->generateUrlFromNode($treeNode, $mainCountry, $language);

        foreach ($allCountries as $country) {
            $countryUrl = $this->generateUrlFromNode($treeNode, $language, (string) $country);

            if ($countryUrl) {
                $countryCode = mb_strtoupper($country);
                $hrefLang = new Link("{$language}-{$countryCode}", $countryUrl->getLoc());
                $url->addSubElement($hrefLang);
            }
        }

        $urlSet->addUrl($url);

        return $urlSet;
    }
}
