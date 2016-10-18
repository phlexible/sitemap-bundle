<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\SitemapBundle\Exception;

use Symfony\Component\Filesystem\Exception\IOException as FilesystemIOException;

/**
 * Class IOException
 *
 * @author Jens Schulze <jdschulze@brainbits.net>
 */
class IOException extends FilesystemIOException implements ExceptionInterface
{
}