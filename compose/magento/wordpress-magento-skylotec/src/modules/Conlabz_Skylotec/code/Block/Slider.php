<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Slider extends Fishpig_Wordpress_Block_Post_List_Wrapper_Abstract
{
    protected $_template = 'slider/homepage.phtml';
    
    /**
     *
     * @return Fishpig_Wordpress_Model_Post
     */
    public function getSlides()
    {
        $collection = parent::getPostCollection();
        $collection->addPostTypeFilter('slider');
        $collection->addMetaFieldToSelect('image');
        $collection->addMetaFieldToSelect('url');
        return $collection;
    }
}
