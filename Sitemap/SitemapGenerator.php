<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\SitemapBundle\Sitemap;

use Phlexible\Bundle\CountryContextBundle\Mapping\CountryCollection;
use Phlexible\Bundle\SitemapBundle\Event\UrlEvent;
use Phlexible\Bundle\SitemapBundle\Event\UrlsetEvent;
use Phlexible\Bundle\SitemapBundle\Event\XmlSitemapEvent;
use Phlexible\Bundle\SitemapBundle\Exception\InvalidArgumentException;
use Phlexible\Bundle\SitemapBundle\SitemapEvents;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeManagerInterface;
use Phlexible\Bundle\TreeBundle\Model\TreeNodeInterface;
use Phlexible\Bundle\TreeBundle\Tree\TreeIterator;
use Phlexible\Bundle\TreeBundle\Tree\TreeManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Thepixeldeveloper\Sitemap\Output;
use Thepixeldeveloper\Sitemap\Url;
use Thepixeldeveloper\Sitemap\Urlset;

class SitemapGenerator
{
    /**
     * @var ContentTreeManagerInterface
     */
    private $contentTreeManager;

    /**
     * @var CountryCollection
     */
    private $countryCollection;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var string
     */
    private $languagesAvailable;

    /**
     * Generator constructor.
     *
     * @param ContentTreeManagerInterface $contentTreeManager
     * @param CountryCollection           $countryCollection
     * @param RouterInterface             $router
     * @param EventDispatcherInterface    $eventDispatcher
     * @param string                      $languagesAvailable
     */
    public function __construct(
        ContentTreeManagerInterface $contentTreeManager,
        CountryCollection $countryCollection,
        RouterInterface $router,
        EventDispatcherInterface $eventDispatcher,
        $languagesAvailable
    )
    {
        $this->contentTreeManager = $contentTreeManager;
        $this->countryCollection = $countryCollection;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->languagesAvailable = $languagesAvailable;
    }

    /**
     * @param string $siteRootId
     *
     * @return string
     */
    public function generateSitemap($siteRootId)
    {
        if (!is_string($siteRootId)) {
            throw new InvalidArgumentException("Site root id must be a string! $siteRootId");
        }

        $languages = explode(',', $this->languagesAvailable);

        $contentTree = $this->contentTreeManager->find($siteRootId);
        if (!$contentTree) {
            throw new InvalidArgumentException("Tree for site root id $siteRootId not found");
        }

        $iterator = new TreeIterator($contentTree);

        // Create a urlset sitemap
        $urlSet = new Urlset();

        $rii = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($rii as $childNode) {
            /** @var TreeNodeInterface $childNode */
            $treeNode = $contentTree->get($childNode->getId());

            foreach ($languages as $language) {
                $contentTree->setLanguage($language);

                if (!$contentTree->isPublished($childNode)) {
                    continue;
                }

                $countries = $this->countryCollection->filterLanguage($language);
                foreach ($countries as $country) {
                    $urlString = $this->generateUrlStringFromNode($treeNode, (string) $country, $language);
                    $urlSet->addUrl($this->generateUrlElement($urlString));
                }
            }
        }

        $event = new UrlsetEvent($urlSet, $siteRootId);
        $this->eventDispatcher->dispatch(SitemapEvents::URLSET_GENERATION, $event);

        $sitemap = $this->generateSitemapFromUrlSet($urlSet, $siteRootId);

        return $sitemap;
    }

    /**
     * @param TreeNodeInterface $treeNode
     * @param                   $country
     * @param                   $language
     *
     * @return string
     */
    private function generateUrlStringFromNode(TreeNodeInterface $treeNode, $country, $language)
    {
        $path = $this->router->generate(
            $treeNode,
            ['_country' => (string) $country, '_locale' => $language],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $path = $this->cleanUrlString($path);

        return $path;
    }

    /**
     * @param string $url
     *
     * @return string
     */
    private function cleanUrlString($url)
    {
        $search = ['/app.php', '/app_dev.php'];
        $cleanUrl = str_replace($search, '', $url);

        return $cleanUrl;
    }

    private function generateLangCode($language, $country)
    {
        $language = strtolower($language);
        if ('' === $language) {
            throw new InvalidArgumentException('Language string must not be empty!');
        }

        $country = strtolower($country);
        if ('' === $country) {
            $country = $language;
        }

        if ($language === $country) {
            $langCode = $language;
        } else {
            $langCode = "$language-$country";
        }

        return $langCode;
    }

    private function generateUrlElement($urlString)
    {
        $urlElement = (new Url($urlString));

        $event = new UrlEvent($urlElement);
        $this->eventDispatcher->dispatch(SitemapEvents::URL_GENERATION, $event);

        return $urlElement;
    }

    private function generateSitemapFromUrlSet($urlSet, $siteRootId)
    {
        $sitemap = (new Output())->getOutput($urlSet);

        $event = new XmlSitemapEvent($sitemap, $siteRootId);
        $this->eventDispatcher->dispatch(SitemapEvents::XML_GENERATION, $event);

        return $sitemap;
    }
}
