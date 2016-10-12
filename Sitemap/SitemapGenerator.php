<?php
/**
 * Created by PhpStorm.
 * User: jdschulze
 * Date: 11.10.16
 * Time: 16:29
 */

namespace Phlexible\Bundle\SitemapBundle\Sitemap;

use Phlexible\Bundle\CountryContextBundle\Mapping\CountryCollection;
use Phlexible\Bundle\SitemapBundle\Exception\InvalidArgumentException;
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeManagerInterface;
use Phlexible\Bundle\TreeBundle\Model\TreeNodeInterface;
use Phlexible\Bundle\TreeBundle\Tree\TreeIterator;
use Phlexible\Bundle\TreeBundle\Tree\TreeManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Thepixeldeveloper\Sitemap\Output;
use Thepixeldeveloper\Sitemap\Url;
use Thepixeldeveloper\Sitemap\Urlset;

class SitemapGenerator
{
    /**
     * @var TreeManager
     */
    private $treeManager;

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
     * @var string
     */
    private $languagesAvailable;

    /**
     * Generator constructor.
     * @param TreeManager $treeManager
     * @param ContentTreeManagerInterface $contentTreeManager
     * @param CountryCollection $countryCollection
     * @param RouterInterface $router
     * @param string $languagesAvailable
     */
    public function __construct(
        TreeManager $treeManager,
        ContentTreeManagerInterface $contentTreeManager,
        CountryCollection $countryCollection,
        RouterInterface $router,
        $languagesAvailable
    ) {
        $this->treeManager = $treeManager;
        $this->contentTreeManager = $contentTreeManager;
        $this->countryCollection = $countryCollection;
        $this->router = $router;
        $this->languagesAvailable = $languagesAvailable;
    }

    public function generateSitemap(Siteroot $siteRoot)
    {
        $siteRootId = $siteRoot->getId();
        $tree = $this->treeManager->getBySiteRootId($siteRootId);
        if (!$tree) {
            throw new InvalidArgumentException("Tree for site root id $siteRootId not found");
        }

        $languages = explode(',', $this->languagesAvailable);

        $contentTree = $this->contentTreeManager->find($siteRootId);

        $iterator = new TreeIterator($tree);

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
                    $loc = $this->generateUrl($treeNode, (string) $country, $language);
                    // DEBUG:
//                    echo "Language: $language; Country: $country; URL: $loc.<br>";
                    $urlElement = (new Url($loc));
                    $urlSet->addUrl($urlElement);
                }
            }
        }
        // DEBUG:
        echo (new Output())->getOutput($urlSet);

        return 0;
    }

    /**
     * @param TreeNodeInterface $treeNode
     * @param                   $country
     * @param                   $language
     *
     * @return string
     */
    private function generateUrl(TreeNodeInterface $treeNode, $country, $language)
    {
        $path = $this->router->generate(
            $treeNode,
            ['_country' => (string) $country, '_locale' => $language],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $path = $this->cleanUrl($path);

        return $path;
    }

    /**
     * @param string $url
     *
     * @return string
     */
    private function cleanUrl($url)
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
}