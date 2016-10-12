<?php

namespace Phlexible\Bundle\SitemapBundle\Controller;

use Phlexible\Bundle\SiterootBundle\Siteroot\SiterootRequestMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapGenerator;

class SitemapController
{
    /**
     * @var SiterootRequestMatcher
     */
    protected $siterootRequestMatcher;

    /**
     * @var SitemapGenerator
     */
    protected $sitemapGenerator;

    public function __construct(SiterootRequestMatcher $siterootRequestMatcher, SitemapGenerator $sitemapGenerator)
    {
        $this->siterootRequestMatcher = $siterootRequestMatcher;
        $this->sitemapGenerator = $sitemapGenerator;
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

        $generator = $this->sitemapGenerator;

        $generator->generateSitemap($siteRoot);

        // TODO: …
    }
}
