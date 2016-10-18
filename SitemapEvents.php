<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
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
}
