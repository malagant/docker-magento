<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Model_Observer_Tabs_Product
{
    /**
     * Event: skylotec_block_tabs_gettabs_product
     *
     * @param Varien_Event_Observer $observer
     */
    public function addProductTabs(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        /* @var $tabCollection Varien_Data_Collection */
        $tabCollection = $event->getCollection();
        
        $tabCollection->addItem(new Varien_Object(array(
            'href' => '#',
            'label' => 'Properties',
            'is_active' => true
        )));
    }
}
