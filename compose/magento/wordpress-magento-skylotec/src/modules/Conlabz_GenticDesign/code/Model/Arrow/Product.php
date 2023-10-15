<?php

/**
 * @package Conlabz_GenticDesign
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_GenticDesign_Model_Arrow_Product implements Conlabz_GenticDesign_Model_Arrow_Interface
{
    const POSITION_ATTRIBUTE = 'reihenfolge';
    
    /**
     *
     * @var Mage_Catalog_Model_Product
     */
    protected $_product;
    
    /**
     *
     * @var Mage_Catalog_Model_Product
     */
    protected $_nextProduct;
    
    /**
     *
     * @var Mage_Catalog_Model_Product
     */
    protected $_prevProduct;
    
    /**
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = Mage::registry('current_product');
        }
        return $this->_product;
    }

    /**
     *
     * @param Mage_Catalog_Model_Product $product
     * @return \Conlabz_GenticDesign_Model_Arrow_Product
     */
    public function setProduct(Mage_Catalog_Model_Product $product)
    {
        $this->_product = $product;
        return $this;
    }
    
    public function getCurrentPosition()
    {
        return $this->getProduct()->getData(self::POSITION_ATTRIBUTE);
    }
    
    protected function _getCollection()
    {
        /* @var $collection Mage_Catalog_Model_Resource_Product_Collection */
        $collection = Mage::getModel('catalog/product')->getCollection();
        if ($category = Mage::registry('current_category')) {
            $collection->addCategoryFilter($category);
        }
        $collection->addAttributeToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
        $collection->addAttributeToSelect('name');
        $collection->addUrlRewrite()
            ->setPage(1, 1);
        
        return $collection;
    }
    
    protected function _getNextProduct()
    {
        if (null === $this->_nextProduct) {
            $collection = $this->_getCollection();
            $collection->addAttributeToFilter(self::POSITION_ATTRIBUTE, array(
                'gt' => $this->getCurrentPosition()
            ))
            ->addAttributeToSort(self::POSITION_ATTRIBUTE, 'ASC');
            $item = $collection->getFirstItem();
            $this->_nextProduct = $item->getId()
                ? $item
                : false;
        }
        return $this->_nextProduct;
    }
    
    public function getNextUrl()
    {
        if ($item = $this->_getNextProduct()) {
            return $item->getProductUrl();
        }
    }
    
    protected function _getPrevProduct()
    {
        if (null === $this->_prevProduct) {
            $collection = $this->_getCollection();
            $collection->addAttributeToFilter(self::POSITION_ATTRIBUTE, array(
                'lt' => $this->getCurrentPosition()
            ))
            ->addAttributeToSort(self::POSITION_ATTRIBUTE, 'DESC');
            $item = $collection->getFirstItem();
            $this->_prevProduct = $item->getId()
                ? $item
                : false;
        }
        return $this->_prevProduct;
    }
    
    public function getPrevUrl()
    {
        if ($item = $this->_getPrevProduct()) {
            return $item->getProductUrl();
        }
    }

    public function getNextLabel()
    {
        if ($item = $this->_getNextProduct()) {
            return $item->getName();
        }
    }

    public function getPrevLabel()
    {
        if ($item = $this->_getPrevProduct()) {
            return $item->getName();
        }
    }
}
