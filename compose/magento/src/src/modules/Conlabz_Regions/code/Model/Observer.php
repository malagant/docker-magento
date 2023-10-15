<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Regions_Model_Observer
{
    /**
     * Event: core_collection_abstract_load_before
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterPostCollectionRegions(Varien_Event_Observer $observer)
    {
        if (!$this->_isEnabled()) {
            return;
        }
        $collection = $observer->getEvent()->getCollection();
        if (!Mage::helper('conwp')->canFilterCollection($collection)) {
            return;
        }
        $currentRegion = $this->_getCurrentRegion();
        $collection->addMetaFieldToSelect(Conlabz_Regions_Model_Regions::WP_REGIONS_FIELD);
        $collection->addMetaFieldToFilter(Conlabz_Regions_Model_Regions::WP_REGIONS_FIELD, array(
            array('like' => '%"' . $currentRegion . '"%'),
            array('null' => true)
        ));
    }
    
    /**
     * Event: wordpress_post_view_init_after
     * Event: wordpress_page_view_init_after
     *
     * @param Varien_Event_Observer $observer
     */
    public function checkObjectAccessForRegion(Varien_Event_Observer $observer)
    {
        if (!$this->_isEnabled()) {
            return;
        }
        $currentRegion = $this->_getCurrentRegion();
        $event = $observer->getEvent();
        /* @var $object Fishpig_Wordpress_Model_Abstract */
        $object = $event->getObject();
        /* @var $controller Fishpig_Wordpress_Controller_Abstract */
        $controller = $event->getAction();
        
        $regions = $object->getMetaValue(Conlabz_Regions_Model_Regions::WP_REGIONS_FIELD);
        if ($regions && !in_array($currentRegion, $regions)) {
            $controller->norouteAction();
        }
    }
    
    /**
     *
     * @return boolean
     */
    protected function _isEnabled()
    {
        return Mage::helper('regions')->isRegionEnabled();
    }
    
    /**
     * retrieve current region code
     *
     * @return string
     */
    protected function _getCurrentRegion()
    {
        return Mage::app()->getWebsite()->getCode();
    }
}
