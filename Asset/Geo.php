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
 * Google sitemap news asset
 *
 * @category    Makeweb
 * @package     Makeweb_GoogleSitemaps
 * @author      Franz Hanenberg <franzhanenberg@googlemail.com>
 * @author      Marko Schmitz <schmitz.marko@googlemail.com>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class Makeweb_GoogleSitemaps_Asset_News extends Makeweb_GoogleSitemaps_Asset
{
    /**
     * @var string
     */
    protected $_loc = null;

    /**
     * Constructor
     *
     * @param string $loc
     */
    public function __construct($loc)
    {
        $this->_loc = $loc;
    }

    /**
     * Return asset type
     *
     * @return string
     */
    public function getType()
    {
        return 'news';
    }

    /**
     * Set asset url location
     *
     * @return string
     */
    public function setLoc($loc)
    {
        $this->_loc = $loc;
    }

    /**
     * Return asset url location
     *
     * @return string
     */
    public function getLoc()
    {
        return $this->_loc;
    }

    /**
     * Return values
     * @return array
     */
    public function getValues()
    {
        return array(
            'loc'   => $this->getLoc(),
            'title' => $this->getTitle()
        );
    }
}
