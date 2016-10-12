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
            ->addArgument('siteRootId', InputArgument::REQUIRED, 'Site root identifier');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sitemapCache = $this->getContainer()->get('phlexible_sitemap.sitemap_cache');

        if ($siteRootId = $input->getArgument('siteRootId')) {
            $sitemapCache->getSitemapById($siteRootId, true);
            $output->writeln("Generated new cache file for $siteRootId");
        }

        return $output;
    }
}