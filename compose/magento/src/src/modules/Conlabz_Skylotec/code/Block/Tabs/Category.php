<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Tabs_Category extends Conlabz_Skylotec_Block_Tabs
{
    /**
     *
     * @return array
     */
    public function getTabs()
    {
        $tabs = array(
            new Varien_Object(array(
                'label' => 'Products',
                'href' => '#products',
                'is_active' => true
            ))
        );
        if (count($this->_tabs)) {
            $tabs = array_merge($tabs, $this->_tabs);
        }
        $this->sort($tabs);
        return $tabs;
    }
    
    /**
     *
     * @return Conlabz_Skylotec_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('skylotec');
    }
}
