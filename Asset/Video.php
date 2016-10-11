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
 * Google sitemap video asset
 *
 * @category    Makeweb
 * @package     Makeweb_GoogleSitemaps
 * @author      Franz Hanenberg <franzhanenberg@googlemail.com>
 * @author      Marko Schmitz <schmitz.marko@googlemail.com>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class Makeweb_GoogleSitemaps_Asset_Video extends Makeweb_GoogleSitemaps_Asset
{
    /**
     * @var string
     */
    protected $_contentLoc = null;

    /**
     * Constructor
     *
     * @param string $loc
     */
    public function __construct($contentLoc)
    {
        $this->_contentLoc = $contentLoc;
    }
    /**
     * Return asset type
     *
     * @return string
     */
    public function getType()
    {
        return 'video';
    }

    /**
     * Set asset url location
     *
     * @return string
     */
    public function setContentLoc($contentLoc)
    {
        $this->_contentLoc = $contentLoc;
    }

    /**
     * Return asset url location
     *
     * @return string
     */
    public function getContentLoc()
    {
        return $this->_contentLoc;
    }

    /**
     * Return values
     * @return array
     */
    public function getValues()
    {
        return array(
            'content_loc' => $this->getContentLoc(),
            'title' 	  => $this->getTitle()
        );
    }
}
