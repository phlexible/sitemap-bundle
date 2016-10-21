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

use Phlexible\Bundle\SitemapBundle\Command\BuildCommand;
use Phlexible\Bundle\SitemapBundle\Sitemap\SitemapGeneratorInterface;
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use Phlexible\Bundle\SiterootBundle\Model\SiterootManagerInterface;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Build command test
 *
 * @author Stephan Wentz <sw@brainbits.net>
 *
 * @covers \Phlexible\Bundle\SitemapBundle\Command\BuildCommand
 */
class BuildCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildForAllSiteroots()
    {
        $generator = $this->prophesize(SitemapGeneratorInterface::class);

        $siteroot = new Siteroot('foo');

        $siterootManager = $this->prophesize(SiterootManagerInterface::class);
        $siterootManager->findAll()->willReturn(array($siteroot));

        $command = new BuildCommand($generator->reveal(), $siterootManager->reveal());

        $commandTester = new CommandTester($command);
        $commandTester->execute(array());

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Generated new cache file for foo', $output);
    }

    public function testBuildForSpecificSiteroot()
    {
        $generator = $this->prophesize(SitemapGeneratorInterface::class);

        $siteroot = new Siteroot('foo');

        $siterootManager = $this->prophesize(SiterootManagerInterface::class);
        $siterootManager->find('foo')->willReturn($siteroot);

        $command = new BuildCommand($generator->reveal(), $siterootManager->reveal());

        $commandTester = new CommandTester($command);
        $commandTester->execute(array('siterootId' => 'foo'));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Generated new cache file for foo', $output);
    }

    public function testBuildForInvalidSiteroot()
    {
        $generator = $this->prophesize(SitemapGeneratorInterface::class);

        $siterootManager = $this->prophesize(SiterootManagerInterface::class);
        $siterootManager->find('bar')->willReturn(null);

        $command = new BuildCommand($generator->reveal(), $siterootManager->reveal());

        $commandTester = new CommandTester($command);
        $commandTester->execute(array('siterootId' => 'bar'));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $status = $commandTester->getStatusCode();
        $this->assertSame(1, $status);
        $this->assertContains('Invalid siteroot identifier bar', $output);
    }
}
