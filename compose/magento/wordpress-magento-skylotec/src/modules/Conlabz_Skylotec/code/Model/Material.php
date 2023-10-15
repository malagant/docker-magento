<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Model_Material
{
    protected $_materialCodes;
    
    protected $_materialAttribute;
    
    protected $_materialNames;
    
    protected $_materialsMap;
    
    protected $_coatingCodes;
    
    protected $_coatingAttribute;
    
    protected $_coatingNames;
    
    protected $_coatingMap;
    
    public function getMaterialNameByCode($code)
    {
        $code = trim($code);
        $materialsMap = $this->getMaterialsMap();
        return isset($materialsMap[$code])
            ? $materialsMap[$code]
            : $code;
    }
    
    protected function getMaterialsMap()
    {
        if (null === $this->_materialsMap) {
            $materialNames = $this->getMaterialNames();
            foreach ($this->getMaterialCodes() as $i => $materialCode) {
                foreach ($materialNames as $materialName) {
                    if ($materialName['value'] === $materialCode['value']) {
                        $this->_materialsMap[trim($materialCode['label'])] = trim($materialName['label']);
                    }
                }
            }
        }
        return $this->_materialsMap;
    }
    
    protected function getMaterialAttribute()
    {
        if (null === $this->_materialAttribute) {
            $this->_materialAttribute = Mage::getSingleton('eav/config')
                ->getAttribute('catalog_product', 'material');
        }
        return $this->_materialAttribute;
    }
    
    public function getMaterialNames()
    {
        if (null === $this->_materialNames) {
            $this->_materialNames = $this->getMaterialAttribute()
                ->getSource()
                ->getAllOptions(false);
        }
        return $this->_materialNames;
    }
    
    public function getMaterialCodes()
    {
        if (null === $this->_materialCodes) {
            $this->_materialCodes = $this->getMaterialAttribute()
                ->setStoreId(0)
                ->getSource()
                ->getAllOptions(false);
        }
        return $this->_materialCodes;
    }
    
    public function getCoatingNameByCode($code)
    {
        $code = trim($code);
        $coatingMap = $this->getCoatingMap();
        return isset($coatingMap[$code])
            ? $coatingMap[$code]
            : $code;
    }
    
    protected function getCoatingMap()
    {
        if (null === $this->_coatingMap) {
            $coatingNames = $this->getCoatingNames();
            foreach ($this->getCoatingCodes() as $i => $coatingCode) {
                foreach ($coatingNames as $coatingName) {
                    if ($coatingName['value'] === $coatingCode['value']) {
                        $this->_coatingMap[trim($coatingCode['label'])] = trim($coatingName['label']);
                    }
                }
            }
        }
        return $this->_coatingMap;
    }
    
    protected function getCoatingAttribute()
    {
        if (null === $this->_coatingAttribute) {
            $this->_coatingAttribute = Mage::getSingleton('eav/config')
                ->getAttribute('catalog_product', 'coating');
        }
        return $this->_coatingAttribute;
    }
    
    public function getCoatingNames()
    {
        if (null === $this->_coatingNames) {
            $this->_coatingNames = $this->getCoatingAttribute()
                ->getSource()
                ->getAllOptions(false);
        }
        return $this->_coatingNames;
    }
    
    public function getCoatingCodes()
    {
        if (null === $this->_coatingCodes) {
            $this->_coatingCodes = $this->getCoatingAttribute()
                ->setStoreId(0)
                ->getSource()
                ->getAllOptions(false);
        }
        return $this->_coatingCodes;
    }
}
