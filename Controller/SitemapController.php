<?php

/*
 * This file is part of the phlexible sitemap package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\SitemapBundle\Controller;

use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapGeneratorInterface;
use Phlexible\Bundle\SiterootBundle\Siteroot\SiterootRequestMatcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sitemap controller
 *
 * @Route(service="phlexible_sitemap.sitemap_controller")
 */
class SitemapController
{
    /**
     * @var SitemapGeneratorInterface
     */
    private $sitemapGenerator;

    /**
     * @var SiterootRequestMatcher
     */
    private $siterootRequestMatcher;

    /**
     * @param SitemapGeneratorInterface $sitemapGenerator
     * @param SiterootRequestMatcher    $siterootRequestMatcher
     */
    public function __construct(SitemapGeneratorInterface $sitemapGenerator, SiterootRequestMatcher $siterootRequestMatcher)
    {
        $this->sitemapGenerator = $sitemapGenerator;
        $this->siterootRequestMatcher = $siterootRequestMatcher;
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @Route("/sitemap.xml", name="sitemap_2index")
     */
    public function indexAction(Request $request)
    {
        $siteroot = $this->siterootRequestMatcher->matchRequest($request);
        $sitemap = $this->sitemapGenerator->generateSitemap($siteroot);

        return new Response($sitemap, 200, array('Content-type' => 'text/xml; charset=UTF-8'));
    }
}
