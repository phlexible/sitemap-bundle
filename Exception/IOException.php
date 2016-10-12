<?php
/**
 * Created by PhpStorm.
 * User: jdschulze
 * Date: 11.10.16
 * Time: 16:42
 */

namespace Phlexible\Bundle\SitemapBundle\Exception;

class IOException extends \Symfony\Component\Filesystem\Exception\IOException implements ExceptionInterface
{
}