<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Gdata extends Mage_Core_Block_Template
{
    const VIDEO_URL = '//www.youtube.com/embed/%s?autoplay=1';
    const VIDEO_THUMBNAIL_URL = '//img.youtube.com/vi/%s/0.jpg';
    
    /**
     *
     * @var Zend_Gdata_YouTube
     */
    protected $_gdata;
    
    /**
     * cached gdata (youtube videos)
     *
     * @var array
     */
    protected static $_gdataCache = array();
    
    protected function _construct()
    {
        parent::_construct();
        $this->_gdata = new Zend_Gdata_YouTube();
    }
    
    /**
     *
     * @param string $videoId
     * @return Zend_Gdata_YouTube_VideoEntry
     */
    public function getVideoDetails($videoId)
    {
        if (!isset($this->_gdataCache[$videoId])) {
            /* @var $videoEntry Zend_Gdata_YouTube_VideoEntry */
            $videoEntry = $this->_gdata->getVideoEntry($videoId);
            self::$_gdataCache[$videoId] = $videoEntry;
        }
        return self::$_gdataCache[$videoId];
    }
    
    /**
     * retrieve video url
     *
     * @param string $videoId
     * @return type
     */
    public function getVideoUrl($videoId)
    {
        return sprintf(
            static::VIDEO_URL,
            $videoId
        );
    }
    
    /**
     * retrieve video thumbnail url
     *
     * @param $videoId
     * @return string
     */
    public function getVideoThumbnail($videoId)
    {
        return sprintf(
            static::VIDEO_THUMBNAIL_URL,
            $videoId
        );
    }
}
