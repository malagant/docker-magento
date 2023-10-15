<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Skylopedia_List_Category extends Conlabz_Skylotec_Block_Skylopedia_List implements Conlabz_Skylotec_Block_Tabs_Interface
{
    protected $_collection;

    public function getPostCollection()
    {
        $collection = parent::getPostCollection();
        /* @var $helper Conlabz_Wordpress_Helper_Related */
        $helper = $this->helper('conwp/related');
        $collection->addMetaFieldToSelect('youtube_id');
        $helper->addCategoryFilter($collection, Mage::registry('current_category'));
        return $collection;
    }

    /**
     * @remove true
     *
     * @return boolean
     */
    public function canDisplay()
    {
        return true || $this->getPostCollection()->getSize() > 0;
    }
}
