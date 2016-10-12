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
use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapCache;

/**
 * Class SitemapController
 * @package Phlexible\Bundle\SitemapBundle
 * @Route(service="phlexible_sitemap.sitemap_controller")
 */
class SitemapController
{
    /**
     * @var SiterootRequestMatcher
     */
    private $siterootRequestMatcher;

    /**
     * @var SitemapCache
     */
    private $sitemapCache;

    public function __construct(SiterootRequestMatcher $siterootRequestMatcher, SitemapCache $sitemapCache)
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
        $requestMatcher = $this->siterootRequestMatcher;
        $siteRoot = $requestMatcher->matchRequest($request);

        $generator = $this->sitemapCache;

        $sitemap = $generator->getSitemap($siteRoot);

        return new Response($sitemap);
    }
}
