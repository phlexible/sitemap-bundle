<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\SitemapBundle\Sitemap;

use Phlexible\Bundle\SitemapBundle\Exception\InvalidArgumentException;
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use Symfony\Component\Filesystem\Filesystem;

class SitemapCache
{
    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var SitemapGenerator
     */
    private $sitemapGenerator;

    /**
     * SitemapCache constructor.
     * @param SitemapGenerator $sitemapGenerator
     * @param string $cacheDir
     */
    public function __construct(SitemapGenerator $sitemapGenerator, $cacheDir)
    {
        $this->cacheDir = $cacheDir;
        $this->sitemapGenerator = $sitemapGenerator;
    }

    /**
     * @param Siteroot $siteRoot
     * @param bool $rebuildCache
     * @return string
     */
    public function getSitemap(Siteroot $siteRoot, $rebuildCache = false)
    {
        if (!is_bool($rebuildCache)) {
            throw new InvalidArgumentException("Argument 'refreshCache' must be boolean!");
        }

        $siteRootId = $siteRoot->getId();

        return $this->getSitemapById($siteRootId, $rebuildCache);
    }

    public function getSitemapById($siteRootId, $rebuildCache = false)
    {
        if (!is_string($siteRootId)) {
            throw new InvalidArgumentException("Argument 'siteRootId' must be a string!");
        }
        if (!is_bool($rebuildCache)) {
            throw new InvalidArgumentException("Argument 'refreshCache' must be boolean!");
        }

        $filename = $this->cacheDir . '/' . $siteRootId;
        $fileSystem = new Filesystem();

        // Force cache file creation if it does not exist yet
        if (!$fileSystem->exists($filename)) {
            $rebuildCache = true;
        }

        if ($rebuildCache) {
            $urlSet = $this->sitemapGenerator->generateSitemap($siteRootId);
            try {
                $fileSystem->dumpFile($filename, $urlSet);
            } catch (\Symfony\Component\Filesystem\Exception\IOException $e) {
                throw new \Phlexible\Bundle\SitemapBundle\Exception\IOException("Could not write file $filename", $e);
            }
        } else {
            $urlSet = file_get_contents($filename);
            if (false === $urlSet) {
                throw new \Phlexible\Bundle\SitemapBundle\Exception\IOException("Could not read file $filename");
            }
        }

        return $urlSet;
    }
}