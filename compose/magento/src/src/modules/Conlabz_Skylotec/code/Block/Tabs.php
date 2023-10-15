<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
abstract class Conlabz_Skylotec_Block_Tabs extends Mage_Core_Block_Template
{
    protected $_template = 'skylotec/tabs.phtml';

    protected $_tabs = array();
    
    /**
     * @return Varien_Object[]
     */
    abstract public function getTabs();

    public function addTab(array $tabData, $blockName)
    {
        $block = $this->getLayout()->getBlock($blockName);
        if ($block instanceof Conlabz_Skylotec_Block_Tabs_Interface &&
            $block->canDisplay()
        ) {
            $this->_tabs[] = new Varien_Object($tabData);
        }
    }

    public function sort(array &$tabs)
    {
        usort($tabs, function ($a, $b) {
            $orderA = $a->getSortOrder() ? $a->getSortOrder() : 0;
            $orderB = $b->getSortOrder() ? $b->getSortOrder() : 0;
            if ($orderA == $orderB) {
                return 0;
            }
            return $orderA > $orderB ? 1 : -1;
        });
    }
}
