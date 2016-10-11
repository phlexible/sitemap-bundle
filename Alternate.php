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
 * Google sitemap alternate
 *
 * @category    Makeweb
 * @package     Makeweb_GoogleSitemaps
 * @author      Matthias Harmuth <mharmuth@brainbits.net>
 * @copyright   2013 brainbits GmbH (http://www.brainbits.net)
 */
class Makeweb_GoogleSitemaps_Alternate
{
    /**
     * @var string
     */
    protected $_lang;

    /**
     * @var string
     */
    protected $_link;

    /**
     * @param string $lang
     * @param string $link
     */
    public function __construct($lang, $link)
    {
        $this->_lang = $lang;
        $this->_link = $link;
    }

    /**
     * @param string $lang
     */
    public function setLang($lang)
    {
        $this->_lang = $lang;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->_lang;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->_link = $link;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->_link;
    }
}
