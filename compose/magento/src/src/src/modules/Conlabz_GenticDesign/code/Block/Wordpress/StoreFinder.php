<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_GenticDesign_Block_Wordpress_StoreFinder extends Mage_Core_Block_Template
{
    protected $_template = 'wordpress/storefinder/table.phtml';
    
    protected $_metaFieldsToSelect = array(
        'land',
        'strasse',
        'plz',
        'ortsname',
        'telefon',
        'handy',
        'fax',
        'e-mail',
        'internet',
        'oeffnungszeiten',
        'streetview_link'
    );

    /**
     *
     * @var array
     */
    protected $_countries = array(
        'DE' => 'Germany',
        'AT' => 'Austria',
        'FR' => 'France',
    );

    /**
     *
     * @param string $country country code
     * @return Fishpig_Wordpress_Model_Resource_Post_Collection
     */
    public function getStoresCollection($country = 'DE')
    {
        $stores = Mage::getResourceModel('wordpress/post_collection')
            ->addIsViewableFilter()
            ->addMetaFieldToFilter('land', $country)
            ->addMetaFieldToSort('plz', 'ASC')
            ->addPostTypeFilter('haendler');
        
        foreach ($this->_metaFieldsToSelect as $metaFieldToSelect) {
            $stores->addMetaFieldToSelect($metaFieldToSelect);
        }
            
        return $stores;
    }
    
    /**
     *
     * @return array
     */
    public function getStoresGroupedByZip($country = 'DE')
    {
        $storesGroupedByZip = array();
        foreach ($this->getStoresCollection($country) as $store) {
            $zip = $store->getMetaValue('plz');
            if (!strlen($zip)) {
                continue;
            }
            $zipArea = $zip[0];
            if (!isset($storesGroupedByZip[$zipArea])) {
                $storesGroupedByZip[$zipArea] = array();
            }
            $storesGroupedByZip[$zipArea][] = $store;
        }
        return $storesGroupedByZip;
    }

    /**
     *
     * @return string
     */
    public function getPageContent()
    {
        $page = Mage::getModel('wordpress/post')->load('haendler', 'post_name');
        if ($page->getId()) {
            return $page->getPostContent();
        }
        return '';
    }

    /**
     *
     * @return array
     */
    public function getCountries()
    {
        return $this->_countries;
    }
}
