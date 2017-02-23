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
 * Generates a sitemap index for a given site root.
 *
 * @author Matthias Harmuth <mharmuth@brainbits.net>
 */
interface SitemapIndexGeneratorInterface
{
    /**
     * @param Siteroot $siteroot
     * @param bool     $force
     *
     * @return string
     */
    public function generateSitemapIndex(Siteroot $siteroot, $force = false);
}
