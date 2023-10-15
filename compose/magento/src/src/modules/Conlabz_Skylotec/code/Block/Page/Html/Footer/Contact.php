<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Page_Html_Footer_Contact extends Mage_Core_Block_Template
{
    protected $_template = 'page/html/footer/contact.phtml';
    
    public function getAddress($type, $escape = true)
    {
        $value = Mage::getStoreConfig('general/imprint/' . $type);
        if ($escape) {
            return $this->escapeHtml($value);
        }
        return $value;
    }

    public function getCountry()
    {
        $countryCode = Mage::getStoreConfig('general/store_information/merchant_country');
        return Mage::app()->getLocale()->getCountryTranslation($countryCode);
    }

    public function getStoreContact()
    {
        return Mage::getStoreConfig('general/store_information/address');
    }
}
