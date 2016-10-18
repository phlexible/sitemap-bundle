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
 * Interface SitemapGeneratorInterface
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 */
interface SitemapGeneratorInterface
{
    /**
     * @param Siteroot $siteRoot
     *
     * @return string
     */
    public function generateSitemap(Siteroot $siteRoot);
}