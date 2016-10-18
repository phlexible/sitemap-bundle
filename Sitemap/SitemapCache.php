<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\SitemapBundle\Sitemap;

use Phlexible\Bundle\SitemapBundle\Exception\InvalidArgumentException;
use Phlexible\Bundle\SitemapBundle\Exception\IOException;
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use Symfony\Component\Filesystem\Filesystem;

/**
 * {@inheritdoc}
 */
class SitemapCache implements SitemapCacheInterface
{
    /**
     * @var SitemapGeneratorInterface
     */
    private $sitemapGenerator;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * SitemapCache constructor.
     * @param SitemapGeneratorInterface $sitemapGenerator
     * @param string $cacheDir
     */
    public function __construct(SitemapGeneratorInterface $sitemapGenerator, $cacheDir)
    {
        $this->sitemapGenerator = $sitemapGenerator;
        $this->cacheDir = $cacheDir;
    }

    /**
     * {@inheritdoc}
     */
    public function getSitemap(Siteroot $siteRoot, $rebuildCache = false)
    {
        if (!is_bool($rebuildCache)) {
            throw new InvalidArgumentException("Argument 'refreshCache' must be boolean!");
        }

        $siteRootId = $siteRoot->getId();

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
            $urlSet = $this->sitemapGenerator->generateSitemap($siteRoot);
            try {
                $fileSystem->dumpFile($filename, $urlSet);
            } catch (\Symfony\Component\Filesystem\Exception\IOException $e) {
                throw new IOException("Could not write file $filename", $e);
            }
        } else {
            $urlSet = file_get_contents($filename);
            if (false === $urlSet) {
                throw new IOException("Could not read file $filename");
            }
        }

        return $urlSet;
    }
}