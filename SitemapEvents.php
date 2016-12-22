<?php

/*
 * This file is part of the phlexible sitemap package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\SitemapBundle;

/**
 * Sitemap events
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 */
class SitemapEvents
{
    /**
     * Fired before URL generation
     */
    const BEFORE_URL_GENERATION = 'phlexible_sitemap.before_url_generation';

    /**
     * Fired after URL generation
     */
    const URL_GENERATION = 'phlexible_sitemap.url_generation';

    /**
     * Fired after URLset generation
     */
    const URLSET_GENERATION = 'phlexible_sitemap.urlset_generation';

    /**
     * Fired after sitemap index gemeration
     */
    const SITEMAPINDEX_GENERATION = 'phlexible_sitemap.sitemapindex_generation';
}
