<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\SitemapBundle;

/**
 * Element finder events
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
    const AFTER_URL_GENERATION = 'phlexible_sitemap.after_url_generation';

    /**
     * Fired before URLset generation
     */
    const BEFORE_URLSET_GENERATION = 'phlexible_sitemap.before_urlset_generation';

    /**
     * Fired after URLset generation
     */
    const AFTER_URLSET_GENERATION = 'phlexible_sitemap.after_urlset_generation';
}
