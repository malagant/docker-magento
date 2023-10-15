<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Regions_Model_Regions extends Mage_Core_Model_Abstract
{

    const WP_REGIONS_FIELD = 'regions';
    
    protected $_regions;
    
    protected $_regionsArray = array(
        array(
            'region' => 'Europe',
            'code' => 'eu',
            'countries' => array(
                'GB' => 'uk_en',
                'DE' => 'eu_de',
                'NL' => 'eu_en',
                'BE' => 'eu_fr',
                'LU' => 'eu_fr',
                'FR' => 'eu_fr',
                'ES' => 'eu_es',
                'PT' => 'eu_es',
                'IT' => 'eu_it',
                'AT' => 'eu_de',
                'PL' => 'eu_en',
                'CZ' => 'eu_en',
                'SK' => 'eu_en',
                'HU' => 'eu_en',
                'SI' => 'eu_en',
                'HR' => 'eu_en',
                'BA' => 'eu_en',
                'RS' => 'eu_en',
                'RO' => 'eu_en',
                'BG' => 'eu_en',
                'GR' => 'eu_en',
                'TR' => 'eu_en',
                'EE' => 'eu_en',
                'LV' => 'eu_en',
                'LT' => 'eu_en',
                'FI' => 'eu_en',
                'SE' => 'eu_en',
                'DK' => 'eu_en',
                'IE' => 'eu_en',
                'IS' => 'eu_en',
                'NO' => 'eu_en',
            )
        ),
        array(
            'region' => 'North America',
            'code' => 'nam',
            'countries' => array(
                'US' => 'nam_en_us',
                'CA' => 'nam_en_ca'
            )
        ),
        array(
            'region' => 'South America',
            'code' => 'sam',
            'countries' => array(
                'Select Region' => 'sam_es'
            )
        ),
        array(
            'region' => 'Australia',
            'code' => 'aus',
            'countries' => array(
                'AU' => 'aus_en'
            )
        )
    );

    protected $_regionsMap = array(
        ''
    );
    
    public function getRegions($currentUrl = true)
    {
        if (null === $this->_regions) {
            foreach ($this->_regionsArray as $region) {
                $countries = array();
                foreach ($region['countries'] as $countryCode => $storeCode) {
                    $country = new Varien_Object(array(
                        'code' => $countryCode,
                        'name' => $this->getCountryNameByCode($countryCode),
                        'url'  => $this->_getUrl($storeCode, $countryCode)
                    ));
                    $countries[] = $country;
                }
                $oRegion = new Varien_Object(array(
                    'countries' => $countries,
                    'region' => $region['region'],
                    'code' => $region['code']
                ));
                $this->_regions[] = $oRegion;
            }
        }
    }
    
    protected function _getUrl($storeCode, $countryCode)
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)
            . $storeCode
            . '/regions/country/switch/country/' . $countryCode;
    }
    
    public function getDefaultCountry()
    {
        return new Varien_Object(array(
            'code' => 'DE',
            'name' => $this->getCountryNameByCode('DE'),
            'url' => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'eu_en'
        ));
    }
    
    public function getCountryNameByCode($code)
    {
        if ($code === 'Select Region') {
            return $code;
        }
        $countryModel = Mage::getModel('directory/country')->loadByCode($code);
        return $countryModel->getName();
    }
}
