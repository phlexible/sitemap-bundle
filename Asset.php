<?php
/**
 * MAKEweb
 *
 * PHP Version 5
 *
 * @category    Makeweb
 * @package     Makeweb_GoogleSitemaps
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 * @version     SVN: $Id$
 */

/**
 * Google sitemap asset
 *
 * @category    Makeweb
 * @package     Makeweb_GoogleSitemaps
 * @author      Franz Hanenberg <franzhanenberg@googlemail.com>
 * @author      Marko Schmitz <schmitz.marko@googlemail.com>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
abstract class Makeweb_GoogleSitemaps_Asset
{
    /**
     * @var string
     */
    protected $_title = null;

    /**
     * Return asset type
     *
     * @return string
     */
    abstract public function getType();

    /**
     * Return asset title
     *
     * @return _title
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Set asset title
     * Provides a fluent interface
     *
     * @param string $title
     * @return MWF_Core_Asset
     */
    public function setTitle($title)
    {
        $this->_title = $title;

        return $this;
    }
}
