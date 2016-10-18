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
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeManagerInterface;
use Phlexible\Bundle\TreeBundle\Model\TreeNodeInterface;
use Phlexible\Bundle\TreeBundle\Tree\TreeIterator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Thepixeldeveloper\Sitemap\Output;
use Thepixeldeveloper\Sitemap\Url;
use Thepixeldeveloper\Sitemap\Urlset;

/**
 * Class SitemapGenerator
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 */
class SitemapGenerator implements SitemapGeneratorInterface
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
     * @var array
     */
    private $indexScriptNames = [];

    /**
     * Generator constructor.
     *
     * @param ContentTreeManagerInterface $contentTreeManager
     * @param CountryCollection $countryCollection
     * @param RouterInterface $router
     * @param EventDispatcherInterface $eventDispatcher
     * @param string $languagesAvailable
     * @param array $indexScriptNames
     */
    public function __construct(
        ContentTreeManagerInterface $contentTreeManager,
        CountryCollection $countryCollection,
        RouterInterface $router,
        EventDispatcherInterface $eventDispatcher,
        $languagesAvailable,
        array $indexScriptNames = ['/app.php', '/app_dev.php']
    ) {
        $this->contentTreeManager = $contentTreeManager;
        $this->countryCollection = $countryCollection;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->languagesAvailable = $languagesAvailable;
        $this->indexScriptNames = $indexScriptNames;
    }

    /**
     * @inheritdoc
     */
    public function generateSitemap(Siteroot $siteRoot)
    {
        $siteRootId = $siteRoot->getId();

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
                /** @noinspection PhpUndefinedMethodInspection */
                $contentTree->setLanguage($language);

                /** @noinspection PhpParamsInspection */
                if (!$contentTree->isPublished($childNode)) {
                    continue;
                }

                $countries = $this->countryCollection->filterLanguage($language);
                foreach ($countries as $country) {
                    $url = $this->generateUrlFromNode($treeNode, (string) $country, $language);
                    if ($url) {
                        $urlSet->addUrl($url);
                    }
                }
            }
        }

        $event = new UrlsetEvent($urlSet, $siteRoot);
        $this->eventDispatcher->dispatch(SitemapEvents::URLSET_GENERATION, $event);

        $sitemap = $this->generateSitemapFromUrlSet($urlSet);

        return $sitemap;
    }

    /**
     * @param TreeNodeInterface $treeNode
     * @param string $country
     * @param string $language
     *
     * @return Url
     */
    private function generateUrlFromNode(TreeNodeInterface $treeNode, $country, $language)
    {
        $urlString = $this->router->generate(
            $treeNode,
            ['_country' => (string) $country, '_locale' => $language],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $urlString = $this->cleanUrlString($urlString);

        $urlElement = (new Url($urlString));

        $event = new UrlEvent($urlElement, $treeNode, $country, $language);

        if ($this->eventDispatcher->dispatch(SitemapEvents::BEFORE_URL_GENERATION, $event)->isPropagationStopped()) {
            return null;
        }

        $event = new UrlEvent($urlElement, $treeNode, $country, $language);
        $this->eventDispatcher->dispatch(SitemapEvents::URL_GENERATION, $event);

        return $urlElement;
    }

    /**
     * Remove index scripts from URL paths
     * @param string $url
     *
     * @return string
     */
    private function cleanUrlString($url)
    {
        $cleanUrl = str_replace($this->indexScriptNames, '', $url);

        return $cleanUrl;
    }

    /**
     * @param Urlset $urlSet
     * @return string
     */
    private function generateSitemapFromUrlSet($urlSet)
    {
        $sitemap = (new Output())->getOutput($urlSet);

        return $sitemap;
    }
}
