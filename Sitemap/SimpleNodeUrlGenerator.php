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

use Phlexible\Bundle\SitemapBundle\Event\UrlEvent;
use Phlexible\Bundle\SitemapBundle\SitemapEvents;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeNode;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Thepixeldeveloper\Sitemap\Url;

/**
 * Generates an url for a tree node.
 *
 * @author Stephan Wentz <swentz@brainbits.net>
 */
class SimpleNodeUrlGenerator implements NodeUrlGeneratorInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var array
     */
    private $indexScriptNames;

    /**
     * @param RouterInterface          $router
     * @param EventDispatcherInterface $eventDispatcher
     * @param array                    $indexScriptNames
     */
    public function __construct(
        RouterInterface $router,
        EventDispatcherInterface $eventDispatcher,
        array $indexScriptNames = ['/app.php', '/app_dev.php']
    ) {
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->indexScriptNames = $indexScriptNames;
    }

    /**
     * {@inheritdoc}
     */
    public function generateUrl(ContentTreeNode $treeNode, array $parameters)
    {
        $urlString = $this->router->generate(
            $treeNode,
            $parameters,
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $urlString = $this->cleanUrlString($urlString);

        $url = new Url($urlString);

        $event = new UrlEvent($url, $treeNode, $parameters);
        if ($this->eventDispatcher->dispatch(SitemapEvents::BEFORE_URL_GENERATION, $event)->isPropagationStopped()) {
            return null;
        }

        $event = new UrlEvent($url, $treeNode, $parameters);
        $this->eventDispatcher->dispatch(SitemapEvents::URL_GENERATION, $event);

        return $url;
    }

    /**
     * Remove index scripts from URL paths.
     *
     * @param string $url
     *
     * @return string
     */
    private function cleanUrlString($url)
    {
        $cleanUrl = str_replace($this->indexScriptNames, '', $url);

        return $cleanUrl;
    }
}
