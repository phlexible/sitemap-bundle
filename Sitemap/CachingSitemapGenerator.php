<?php

/*
 * This file is part of the phlexible sitemap package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\SitemapBundle\Sitemap;

use Phlexible\Bundle\SitemapBundle\Exception\ReadFileException;
use Phlexible\Bundle\SitemapBundle\Exception\WriteFileException;
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Generates a cached sitemap, using a nested sitemap generator.
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 */
class CachingSitemapGenerator implements SitemapGeneratorInterface
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
     *
     * @param SitemapGeneratorInterface $sitemapGenerator
     * @param string                    $cacheDir
     */
    public function __construct(SitemapGeneratorInterface $sitemapGenerator, $cacheDir)
    {
        $this->sitemapGenerator = $sitemapGenerator;
        $this->cacheDir = rtrim($cacheDir, '/') . '/';
    }

    /**
     * {@inheritdoc}
     */
    public function generateSitemap(Siteroot $siteroot, $language, $force = false)
    {
        $siteRootId = $siteroot->getId();

        $filename = $this->cacheDir . $siteRootId . '-' . $language . '.xml';
        $fileSystem = new Filesystem();

        if ($force || !$fileSystem->exists($filename)) {
            $urlSet = $this->sitemapGenerator->generateSitemap($siteroot, $language);
            try {
                $fileSystem->dumpFile($filename, $urlSet);
            } catch (IOException $e) {
                throw new WriteFileException("Could not write file $filename", 0, $e);
            }
        } else {
            if (!is_readable($filename) || false === $urlSet = file_get_contents($filename)) {
                throw new ReadFileException("Could not read file $filename");
            }
        }

        return $urlSet;
    }

    /**
     * {@inheritdoc}
     */
    public function generateSitemapIndex(Siteroot $siteroot, $force = false)
    {
        $siteRootId = $siteroot->getId();

        $filename = $this->cacheDir . $siteRootId . '.xml';
        $fileSystem = new Filesystem();

        if ($force || !$fileSystem->exists($filename)) {
            $urlSet = $this->sitemapGenerator->generateSitemapIndex($siteroot);
            try {
                $fileSystem->dumpFile($filename, $urlSet);
            } catch (IOException $e) {
                throw new WriteFileException("Could not write file $filename", 0, $e);
            }
        } else {
            if (!is_readable($filename) || false === $urlSet = file_get_contents($filename)) {
                throw new ReadFileException("Could not read file $filename");
            }
        }

        return $urlSet;
    }
}
