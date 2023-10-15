<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Page_Html_Subnavigation extends Mage_Core_Block_Template
{
    /**
     *
     * @var string
     */
    protected $_template = 'page/html/subnavigation.phtml';
    
    /**
     *
     * @var array
     */
    protected $_items;
    
    /**
     *
     * @return Varien_Data_Collection
     */
    public function getItems()
    {
        if (null === $this->_items) {
            $collection = new Varien_Data_Collection();
            Mage::dispatchEvent('skylotec_block_subnavigation_getitems', array(
                'collection' => $collection
            ));
            $this->_items = $collection;
        }
        return $this->_items;
    }
}
