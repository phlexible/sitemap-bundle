<?php

/*
 * This file is part of the phlexible sitemap package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\SitemapBundle\Command;

use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapGeneratorInterface;
use Phlexible\Bundle\SiterootBundle\Model\SiterootManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Build cached XML sitemap files for a given site root ID, or for all site roots.
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 */
class BuildCommand extends Command
{
    /**
     * @var SitemapGeneratorInterface
     */
    private $sitemapGenerator;

    /**
     * @var SiterootManagerInterface
     */
    private $siterootManager;

    /**
     * BuildCommand constructor.
     *
     * @param SitemapGeneratorInterface $sitemapGenerator
     * @param SiterootManagerInterface  $siterootManager
     */
    public function __construct(SitemapGeneratorInterface $sitemapGenerator, SiterootManagerInterface $siterootManager)
    {
        $this->sitemapGenerator = $sitemapGenerator;
        $this->siterootManager = $siterootManager;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('sitemap:build')
            ->setDescription('Build and store a new sitemap XML file for the given site root.')
            ->addArgument(
                'language',
                InputArgument::REQUIRED,
                'The language to generate sitemap for.'
            )
            ->addArgument(
                'siterootId',
                InputArgument::OPTIONAL,
                'Site root identifier. If no identifier is given, regenerate all sitemaps.'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);

        if ($siterootId = $input->getArgument('siterootId')) {
            $siteroot = $this->siterootManager->find($siterootId);
            if (!$siteroot) {
                $style->error("Invalid siteroot identifier $siterootId.");

                return 1;
            }
            $siteroots = [$siteroot];
        } else {
            $siteroots = $this->siterootManager->findAll();
        }

        $language = $input->getArgument('language');

        foreach ($siteroots as $siteroot) {
            $this->sitemapGenerator->generateSitemap($siteroot, $language, true);
            $style->success("Generated new sitemap cache file for '{$siteroot->getId()}' and language '$language'");
        }

        return 0;
    }
}
