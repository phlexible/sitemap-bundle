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

use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;

/**
 * Generates a sitemap for a given site root.
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 */
interface SitemapGeneratorInterface
{
    /**
     * @param Siteroot $siteroot
     * @param string   $language
     * @param bool     $force
     *
     * @return string
     */
    public function generateSitemap(Siteroot $siteroot, $language, $force = false);
}
