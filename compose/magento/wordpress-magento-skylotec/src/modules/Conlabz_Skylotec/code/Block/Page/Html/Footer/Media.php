<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Page_Html_Footer_Media extends Conlabz_Skylotec_Block_Gdata
{
    protected $_template = 'page/html/footer/media.phtml';
    
    protected $_latestVideo;
    
    /**
     *
     * @return null|Fishpig_Wordpress_Model_Post
     */
    public function getLatestVideo()
    {
        if (null === $this->_latestVideo) {
            /* @var $collection Fishpig_Wordpress_Model_Resource_Post_Collection_Abstract */
            $collection = Mage::getModel('wordpress/post')->getCollection();
            $collection->addIsPublishedFilter();
            $collection->addIsViewableFilter();
            $collection->addPostTypeFilter('skylopedia');
            $collection->addMetaFieldToSelect('youtube_id');
            $collection->addMetaFieldToFilter('youtube_id', array(
                'nlike' => ''
            ));
            $collection->setOrder('post_date', 'DESC');
            $collection->getSelect()->limit(1);
            $this->_latestVideo = $collection->getFirstItem();
        }
        return $this->_latestVideo;
    }
    
    /**
     *
     * @return boolean
     */
    public function hasLatestVideo()
    {
        return (bool) $this->getLatestVideo()->getId();
    }
}
