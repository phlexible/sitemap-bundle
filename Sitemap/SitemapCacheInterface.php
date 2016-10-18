<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\SitemapBundle\Sitemap;

use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;

/**
 * Retrieve a sitemap file from the sitemap generator, or the file cache
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 */
interface SitemapCacheInterface
{
    /**
     * @param Siteroot $siteRoot
     * @param bool $rebuildCache
     * @return string
     */
    public function getSitemap(Siteroot $siteRoot, $rebuildCache = false);
}