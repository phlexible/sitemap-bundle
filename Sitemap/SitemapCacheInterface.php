<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\SitemapBundle\Sitemap;

use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;

interface SitemapCacheInterface
{
    public function getSitemap(Siteroot $siteRoot, $rebuildCache = false);
}