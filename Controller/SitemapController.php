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
use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapIndexGeneratorInterface;
use Phlexible\Bundle\SiterootBundle\Siteroot\SiterootRequestMatcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

/**
 * Sitemap controller
 *
 * @Route(service="phlexible_sitemap.sitemap_controller")
 */
class SitemapController
{
    /**
     * @var SitemapIndexGeneratorInterface
     */
    private $sitemapGenerator;

    /**
     * @var SitemapIndexGeneratorInterface
     */
    private $sitemapIndexGenerator;

    /**
     * @var SiterootRequestMatcher
     */
    private $siterootRequestMatcher;

    /**
     * @param SitemapGeneratorInterface      $sitemapGenerator
     * @param SitemapIndexGeneratorInterface $sitemapIndexGenerator
     * @param SiterootRequestMatcher         $siterootRequestMatcher
     */
    public function __construct(
        SitemapGeneratorInterface $sitemapGenerator,
        SitemapIndexGeneratorInterface $sitemapIndexGenerator,
        SiterootRequestMatcher $siterootRequestMatcher
    ) {
        $this->sitemapGenerator = $sitemapGenerator;
        $this->sitemapIndexGenerator = $sitemapIndexGenerator;
        $this->siterootRequestMatcher = $siterootRequestMatcher;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/sitemap.xml", name="sitemap_index")
     */
    public function indexAction(Request $request)
    {
        $siteroot = $this->siterootRequestMatcher->matchRequest($request);
        $sitemap = $this->sitemapIndexGenerator->generateSitemapIndex($siteroot);

        return new Response($sitemap, 200, array('Content-type' => 'text/xml; charset=UTF-8'));
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/sitemap-{_locale}.xml", name="sitemap_sitemap")
     */
    public function sitemapAction(Request $request)
    {
        $siteroot = $this->siterootRequestMatcher->matchRequest($request);
        $language = $request->getLocale();

        if (!$language) {
            throw new MissingMandatoryParametersException('Missing parameter "language"');
        }

        $sitemap = $this->sitemapGenerator->generateSitemap($siteroot, $language);

        return new Response($sitemap, 200, array('Content-type' => 'text/xml; charset=UTF-8'));
    }
}
