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
 * Generates a cached sitemap index, using a nested sitemap index generator.
 *
 * @author Matthias Harmuth <mharmuth@brainbits.net>
 */
class CachingSitemapIndexGenerator implements SitemapIndexGeneratorInterface
{
    /**
     * @var SitemapIndexGeneratorInterface
     */
    private $sitemapIndexGenerator;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * SitemapCache constructor.
     *
     * @param SitemapIndexGeneratorInterface $sitemapIndexGenerator
     * @param string                         $cacheDir
     */
    public function __construct(SitemapIndexGeneratorInterface $sitemapIndexGenerator, $cacheDir)
    {
        $this->sitemapIndexGenerator = $sitemapIndexGenerator;
        $this->cacheDir = rtrim($cacheDir, '/') . '/';
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
            $urlSet = $this->sitemapIndexGenerator->generateSitemapIndex($siteroot);
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
