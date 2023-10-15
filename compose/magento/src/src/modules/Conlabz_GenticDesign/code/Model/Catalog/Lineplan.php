<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_GenticDesign_Model_Catalog_Lineplan
{
    const ATTR_LINEPLAN_WINTER = 'lineplan_winter';
    const ATTR_LINEPLAN_SUMMER = 'lineplan_sommer';
    
    /**
     *
     * @var string
     */
    protected $_startWinterDate;
    
    /**
     *
     * @var string
     */
    protected $_startSummerDate;
    
    /**
     *
     * @var int
     */
    protected $_storeId;
    
    /**
     *
     * @return int
     */
    public function getStoreId()
    {
        if (!$this->_storeId) {
            return Mage::app()->getStore()->getId();
        }
        return $this->_storeId;
    }

    /**
     *
     * @param id $storeId
     * @return \Conlabz_GenticDesign_Model_Catalog_Lineplan
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }
        
    /**
     *
     * @return string
     */
    protected function _getStartWinterDate()
    {
        $this->_startWinterDate = $this->_getHelper()
            ->getLineplanWinterStartDate($this->getStoreId());
        return $this->_startWinterDate;
    }
    
    /**
     *
     * @return string
     */
    protected function _getStartSummerDate()
    {
        $this->_startSummerDate = $this->_getHelper()
            ->getLineplanSummerStartDate($this->getStoreId());
        return $this->_startSummerDate;
    }
    
    /**
     *
     * @param string $date
     * @return bool
     */
    public function isWinter($date = null)
    {
        if (null === $date) {
            $date = date('m-d');
        }
        $isWinter = !$this->isSummer($date);
        return $isWinter;
    }
    
    /**
     *
     * @param string $date
     * @return bool
     */
    public function isSummer($date = null)
    {
        if (null === $date) {
            $date = date('m-d');
        }
        $isSummer = (
                $this->_getStartSummerDate() <= $date
             && $this->_getStartWinterDate() > $date
        );
        return $isSummer;
    }
    
    /**
     * check whether the product can be shown
     *
     * @param Mage_Catalog_Model_Product $product
     * @return boolean
     */
    public function canShowProduct(Mage_Catalog_Model_Product $product)
    {
        if ($this->isWinter() && !$product->getData(self::ATTR_LINEPLAN_WINTER)) {
            return false;
        }
        if ($this->isSummer() && !$product->getData(self::ATTR_LINEPLAN_SUMMER)) {
            return false;
        }
        return true;
    }
    
    /**
     *
     * @param Mage_Catalog_Model_Resource_Product_Collection $collection
     */
    public function filterProductCollection(Mage_Catalog_Model_Resource_Product_Collection $collection)
    {
        if ($this->isWinter()) {
            $collection->addAttributeToFilter(self::ATTR_LINEPLAN_WINTER, 1);
        }
        if ($this->isSummer()) {
            $collection->addAttributeToFilter(self::ATTR_LINEPLAN_SUMMER, 1);
        }
    }

    /**
     *
     * @return Conlabz_GenticDesign_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('genticdesign');
    }
}
