<?php
/**
 * @package Conlabz_Regions
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Regions_Block_Regions extends Mage_Core_Block_Template
{
    protected $_template = 'conlabz/regions/regions.phtml';

    protected $_mapping = [
        'eu' => [
            'name' => 'Europe',
            'languages' => [
                'eu_de' => 'Deutsch',
                'eu_en' => 'English',
                'eu_fr' => 'Français',
                'eu_es' => 'Español',
                'eu_it' => 'Italiano'
            ]
        ],
        'ch' => [
            'name' => 'Switzerland',
            'languages' => [
                'ch_de' => 'Deutsch',
                'ch_fr' => 'Français',
                'ch_it' => 'Italiano'
            ]
        ],
        'uk' => [
            'name' => 'Great Britain',
            'languages' => [
                'uk_en' => 'English'
            ]
        ],
        'nam' => [
            'name' => 'North America',
            'languages' => [
                'nam_en' => 'English',
                'nam_fr' => 'Français',
                'nam_es' => 'Español'
            ]
        ],
        'sam' => [
            'name' => 'South America',
            'languages' => [
                'sam_en' => 'English',
                'sam_es' => 'Español'
            ]
        ],
        'aus' => [
            'name' => 'Australia',
            'languages' => [
                'aus_en' => 'English'
            ]
        ]
    ];

    /**
     * @return array
     */
    public function getMapping()
    {
        return $this->_mapping;
    }

    /**
     * @param $storeCode
     * @return string
     */
    public function getRegionUrl($storeCode)
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . $storeCode;
    }

    public function getRegions()
    {
        return Mage::getSingleton('regions/regions')
            ->getRegions();
    }
    
    public function getCountryNames()
    {
        $countries = Mage::getSingleton('directory/country')
            ->getCollection()
            ->toOptionArray(false);
        $map = array();
        foreach ($countries as $country) {
            $map[$country['value']] = $country['label'];
        }
        return $map;
    }
    
    public function getCountryNamesJson()
    {
        return Mage::helper('core')->jsonEncode($this->getCountryNames());
    }
    
    public function getCacheLifetime()
    {
        return 604800;
    }
}
