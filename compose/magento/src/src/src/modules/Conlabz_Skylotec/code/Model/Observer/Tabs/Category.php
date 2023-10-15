<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Model_Observer_Tabs_Category
{
    public function addCategoryTabs(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        /* @var $tabCollection Varien_Data_Collection */
        $tabCollection = $event->getCollection();
        
        foreach ($this->_getTabs() as $tab) {
            $tabCollection->addItem($tab);
        }
    }
}
