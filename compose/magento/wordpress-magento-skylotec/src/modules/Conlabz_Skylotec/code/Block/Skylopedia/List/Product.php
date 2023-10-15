<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Skylopedia_List_Product extends Conlabz_Skylotec_Block_Skylopedia_List
{
    public function getPostCollection()
    {
        $collection = parent::getPostCollection();
        /* @var $helper Conlabz_Wordpress_Helper_Related */
        $helper = $this->helper('conwp/related');
        $collection->addPostTypeFilter('post');
        $collection->addMetaFieldToSelect('youtube_id');
        $helper->addProductFilter($collection, Mage::registry('current_product'));
        return $collection;
    }
}
