<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\SitemapBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Build command
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 */
class BuildCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('sitemap:build')
            ->setDescription('Build and store a new sitemap XML file for the given site root.')
            ->addArgument(
                'siteRootId',
                InputArgument::OPTIONAL,
                'Site root identifier. If no identifier is given, regenerate all sitemaps.'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sitemapCache = $this->getContainer()->get('phlexible_sitemap.sitemap_cache');
        $siteRootManager = $this->getContainer()->get('phlexible_siteroot.siteroot_manager');

        if ($siteRootId = $input->getArgument('siteRootId')) {
            if ($siteRoot = $siteRootManager->find($siteRootId)) {
                $siteRoots = [$siteRoot];
            } else {
                $output->writeln("<error>Invalid Site root identifier!</error>");

                return 1;
            }
        } else {
            $siteRoots = $siteRootManager->findAll();
        }

        foreach ($siteRoots as $thisSiteRoot) {
            $sitemapCache->getSitemap($thisSiteRoot, true);
            $thisSiteRootId = $thisSiteRoot->getId();
            $output->writeln("Generated new cache file for $thisSiteRootId");
        }

        $output->writeln("Done.");

        return $output;
    }
}