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
class Makeweb_GoogleSitemaps_Url
{

    /**
     * @var string
     */
    protected $_loc = null;

    /**
     * @var Makeweb_Elements_Tree_Node
     */
    protected $_node = null;

    /**
     * @var string
     */
    protected $_priority = '0.5';

    /**
     * @var string
     */
    protected $_lastMod = null;

    /**
     * @var string
     */
    protected $_changeFreq = null;

    /**
     * @var array
     */
    protected $_assets = array();

    /**
     * rel-alternate-hreflang annotations in a Sitemap
     * see: https://support.google.com/webmasters/answer/2620865
     *
     * @var array
     */
    protected $_alternates = array();

    /**
     * @param string $loc
     * @param Makeweb_Elements_Tree_Node $node
     */
    public function __construct($loc, Makeweb_Elements_Tree_Node $node)
    {
        $this->_loc  = $loc;
        $this->_node = $node;
    }

    /**
     * add location
     *
     * @return string
     */
    public function getLoc()
    {
        return $this->_loc;
    }

    /**
     * @return Makeweb_Elements_Tree_Node
     */
    public function getNode()
    {
        return $this->_node;
    }

    /**
     * add priority
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->_priority;
    }

    /**
     * set Priority
     *
     * @param integer $priority
     * @return integer
     */
    public function setPriority($priority)
    {
        $this->_priority = (string)$priority;

        return $this;
    }

    /**
     * get Last modification
     *
     * @return string
     */
    public function getLastMod()
    {
        return $this->_lastMod;
    }

    /**
     * get Last modification
     *
     * @param string $lastMod
     * @return string
     */
    public function setLastMod($lastMod)
    {
        $this->_lastMod = $lastMod;

        return $this;
    }

    /**
     * Change Frequency
     *
     * @return string
     */
    public function getChangeFreq()
    {
        return $this->_changeFreq;
    }

    /**
     * set Change Frequency
     *
     * @param string $changeFreq
     * @return string
     */
    public function setChangeFreq($changeFreq)
    {
        $this->_changeFreq = $changeFreq;

        return $this;
    }

    /**
     * get Asset
     *
     * @return array
     */
    public function getAssets()
    {
        return $this->_assets;
    }
    /**
     *
     * @param Makeweb_GoogleSitemaps_Asset $asset
     * @return array
     */
    public function addAsset(Makeweb_GoogleSitemaps_Asset $asset)
    {
        $this->_assets[] = $asset;
    }

    /**
     * @param array $alternates
     */
    public function setAlternates($alternates)
    {
        $this->_alternates = $alternates;
    }

    /**
     * @return Makeweb_GoogleSitemaps_Alternate[]
     */
    public function getAlternates()
    {
        return $this->_alternates;
    }

    /**
     * @param Makeweb_GoogleSitemaps_Alternate $alternate
     */
    public function addAlternate(Makeweb_GoogleSitemaps_Alternate $alternate)
    {
        $this->_alternates[] = $alternate;
    }
}
