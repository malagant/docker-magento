<?php
/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Catalog_Product_View_Images extends Mage_Catalog_Block_Product_View_Media
{

        
    const RESIZE_DIMENSION = 309;
    const ZOOM_DIMENSION = 1600;
    const VIDEO_URL           = 'https://www.youtube.com/embed/%s?autoplay=1';
    const VIDEO_NOCOOKIE_URL  = 'https://www.youtube-nocookie.com/embed/%s?autoplay=1';
    const VIDEO_THUMBNAIL_URL = 'https://img.youtube.com/vi/%s/0.jpg';

    const XML_PATH_VIDEO_PRIVACY = 'catalog/frontend/video_privacy';

    /**
     *
     * @var array
     */
    protected $_mediaImages = array();
    
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
    protected $_gdataCache = array();
    
    protected function _construct()
    {
        parent::_construct();
        $this->_gdata = new Zend_Gdata_YouTube();
    }
    
    /**
     *
     *
     * @var Varien_Object[]
     */
    protected $_videos;
    
    public function getImages($label = null)
    {
        if (null === $label) {
            $label = $this->getLabel();
        }
        if (empty($this->_mediaImages[$label])) {
            $this->_mediaImages[$label] = array();
            foreach ($this->getGalleryImages() as $image) {
                if ($image->getLabel() == $label) {
                    $this->_mediaImages[$label][] = $image;
                }
            }
        }
        return $this->_mediaImages[$label];
    }
    
    /**
     * retrieve videos of product
     *
     * @return array
     */
    public function getVideos()
    {
        if (null === $this->_videos) {
            if ($videoIds = $this->getProduct()->getVideos()) {
                $videoIds = explode("\n", $videoIds);
                foreach ($videoIds as $videoId) {
                    if (trim($videoId) === '') {
                        continue;
                    }
                    $video = new Varien_Object(array(
                        'is_video' => true,
                        'video_id' => $videoId
                    ));
                    $this->_videos[] = $video;
                }
            } else {
                $this->_videos = array();
            }
        }
        return $this->_videos;
    }
    
    /**
     * retrieve video thumbnail url
     *
     * @param Varien_Object $video
     * @return string
     */
    public function getVideoThumbnail(Varien_Object $video)
    {
        //$thumbnails = $this->getVideoDetail('videoThumbnails', $video->getVideoId());
        return sprintf(
            static::VIDEO_THUMBNAIL_URL,
            $video->getVideoId()
        );
    }
    
    /**
     * retrieve video url
     *
     * @param Varien_Object $video
     * @return string
     */
    public function getVideoUrl(Varien_Object $video)
    {
        $format = Mage::getStoreConfig(self::XML_PATH_VIDEO_PRIVACY) ? static::VIDEO_NOCOOKIE_URL : static::VIDEO_URL;
        return sprintf($format, $video->getVideoId());
    }
    
    /**
     *
     * @param string $key
     * @param string $videoId
     * @return string
     */
    public function getVideoDetail($key, $videoId, $default = '')
    {
        $method = 'get' . ucfirst($key);
        $details = $this->getVideoDetails($videoId);
        if (method_exists($details, $method) && ($result = $details->{$method}())) {
            return $result;
        }
        return $default;
    }
    
    /**
     *
     * @param Varien_Object $video
     * @param string $default
     * @return string
     */
    public function getVideoTitle(Varien_Object $video, $default = '')
    {
        return $this->getVideoDetail('title', $video->getVideoId(), $default);
    }
    
    /**
     *
     * @param Varien_Object $video
     * @param string $default
     * @return string
     */
    public function getVideoDescription(Varien_Object $video, $default = '')
    {
        return $this->getVideoDetail('videoDescription', $video->getVideoId(), $default);
    }
    
    public function getVideoDetails($videoId)
    {
        if (!isset($this->_gdataCache[$videoId])) {
            /* @var $videoEntry Zend_Gdata_YouTube_VideoEntry */
            $videoEntry = $this->_gdata->getVideoEntry($videoId);
            $this->_gdataCache[$videoId] = $videoEntry;
        }
        return $this->_gdataCache[$videoId];
    }
    
    /**
     * retrieve all images including videos as array
     *
     * @return Varien_Object[]
     */
    public function getAllImages()
    {
        $labels = $this->getLabels();
        $images = array();
        foreach ($labels as $label) {
            foreach ($this->getImages($label) as $image) {
                $images[] = $image;
            }
        }
        $allImages = array_merge(
            $images,
            $this->getVideos()
        );
        return $allImages;
    }
    
    public function getLabels()
    {
        if (null === $this->getData('labels')) {
            $this->setData('labels', array('d','a'));
        }
        return $this->getData('labels');
    }
    
    public function getLabel()
    {
        if (null === $this->getData('label')) {
            $this->setData('label', 's');
        }
        return $this->getData('label');
    }
    
    public function getResizeUrl($_image, $keepFrame = false)
    {
        $helper = Mage::helper('catalog/image');
        $image = $helper->init($this->getProduct(), 'image', $_image->getFile())
                ->backgroundColor(24, 24, 24)
                ->resize(null, self::RESIZE_DIMENSION)
                ->keepFrame($keepFrame);
        
        return $image;
    }

    public function getZoomImageUrl($_image, $keepFrame = true)
    {
        $helper = Mage::helper('catalog/image');
        $image = $helper->init($this->getProduct(), 'image', $_image->getFile())
                ->backgroundColor(24, 27, 31)
                ->keepAspectRatio(true)
                ->resize(self::ZOOM_DIMENSION)
                ->keepFrame($keepFrame);
        return $image;
    }
}
