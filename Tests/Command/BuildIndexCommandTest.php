<?php

/*
 * This file is part of the phlexible sitemap package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\SitemapBundle\Tests\Command;

use Phlexible\Bundle\SitemapBundle\Command\BuildIndexCommand;
use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapIndexGeneratorInterface;
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use Phlexible\Bundle\SiterootBundle\Model\SiterootManagerInterface;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Build command test.
 *
 * @author Matthias Harmuth <mharmuth@brainbits.net>
 *
 * @covers \Phlexible\Bundle\SitemapBundle\Command\BuildCommand
 */
class BuildIndexCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildForAllSiteroots()
    {
        $generator = $this->prophesize(SitemapIndexGeneratorInterface::class);

        $siteroot = new Siteroot('foo');

        $siterootManager = $this->prophesize(SiterootManagerInterface::class);
        $siterootManager->findAll()->willReturn(array($siteroot));

        $command = new BuildIndexCommand($generator->reveal(), $siterootManager->reveal());

        $commandTester = new CommandTester($command);
        $commandTester->execute(array());

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Generated new sitemap index cache file for foo', $output);
    }

    public function testBuildForSpecificSiteroot()
    {
        $generator = $this->prophesize(SitemapIndexGeneratorInterface::class);

        $siteroot = new Siteroot('foo');

        $siterootManager = $this->prophesize(SiterootManagerInterface::class);
        $siterootManager->find('foo')->willReturn($siteroot);

        $command = new BuildIndexCommand($generator->reveal(), $siterootManager->reveal());

        $commandTester = new CommandTester($command);
        $commandTester->execute(array('siterootId' => 'foo'));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Generated new sitemap index cache file for foo', $output);
    }

    public function testBuildForInvalidSiteroot()
    {
        $generator = $this->prophesize(SitemapIndexGeneratorInterface::class);

        $siterootManager = $this->prophesize(SiterootManagerInterface::class);
        $siterootManager->find('bar')->willReturn(null);

        $command = new BuildIndexCommand($generator->reveal(), $siterootManager->reveal());

        $commandTester = new CommandTester($command);
        $commandTester->execute(array('siterootId' => 'bar'));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $status = $commandTester->getStatusCode();
        $this->assertSame(1, $status);
        $this->assertContains('Invalid siteroot identifier bar', $output);
    }
}
