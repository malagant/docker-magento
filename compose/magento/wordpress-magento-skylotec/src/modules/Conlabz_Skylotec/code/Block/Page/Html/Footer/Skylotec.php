<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Page_Html_Footer_Skylotec extends Mage_Core_Block_Template
{
    protected $_template = 'page/html/footer/skylotec.phtml';

    /**
     *
     * @var Mage_Catalog_Model_Resource_Category_Collection
     */
    protected $_categories;

    /**
     *
     * @return Mage_Catalog_Model_Resource_Category_Collection
     */
    public function getCategories()
    {
        if (null === $this->_categories) {
            /* @var $collection Mage_Catalog_Model_Resource_Category_Collection */
            $collection = Mage::getModel('catalog/category')->getCollection();
            $collection->addAttributeToSelect(array(
                'name',
                'url_key',
                'thumbnail'
            ));
            $collection->addIsActiveFilter();
            $collection->addAttributeToFilter('level', 2);
            $collection->setOrder('position', 'ASC');
            $this->_categories = $collection;
        }
        return $this->_categories;
    }
}
