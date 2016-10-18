<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\SitemapBundle\Controller;

use Phlexible\Bundle\SiterootBundle\Siteroot\SiterootRequestMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapCacheInterface;

/**
 * SitemapController
 *
 * @Route(service="phlexible_sitemap.sitemap_controller")
 */
class SitemapController
{
    /**
     * @var SiterootRequestMatcher
     */
    private $siterootRequestMatcher;

    /**
     * @var SitemapCacheInterface
     */
    private $sitemapCache;

    /**
     * @param SiterootRequestMatcher $siterootRequestMatcher
     * @param SitemapCacheInterface $sitemapCache
     */
    public function __construct(SiterootRequestMatcher $siterootRequestMatcher, SitemapCacheInterface $sitemapCache)
    {
        $this->siterootRequestMatcher = $siterootRequestMatcher;
        $this->sitemapCache = $sitemapCache;
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @Route("/sitemap.xml", name="sitemap_index")
     */
    public function indexAction(Request $request)
    {
        $siteRoot = $this->siterootRequestMatcher->matchRequest($request);

        $sitemapCache = $this->sitemapCache;

        $sitemap = $sitemapCache->getSitemap($siteRoot);

        return new Response($sitemap);
    }
}
