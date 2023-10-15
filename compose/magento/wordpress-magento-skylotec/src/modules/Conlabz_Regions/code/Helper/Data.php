<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Regions_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_ENABLE_REGIONS = 'wordpress/module/enable_regions';
    
    protected $language;
     
    public function getLanguage()
    {
        if (null === $this->language) {
            $this->language =  Mage::getSingleton('regions/language');
        }
        return $this->language;
    }
    
    /**
     * get real remote address
     * @return string
     */
    public function getRealRemoteAddr()
    {
        if (null !== ($ip = $this->_getRequest()->getServer('HTTP_X_FORWARDED_FOR'))) {
            return $ip;
        }
        return Mage::helper('core/http')->getRemoteAddr();
    }

    /**
     * get browser accept language
     * @return mixed
     */
    public function getAcceptLanguage()
    {
        return Mage::helper('core/http')->getHttpAcceptLanguage();
    }

    /**
     * get current country code
     */
    public function getCurrentCountryCode()
    {
        return $this->getLanguage()->getCountryCode();
    }
    
    /**
     * get shipping country
     */
    public function getShippingCountry()
    {
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        if (null === ($country = $quote->getShippingAddress()->getCountry())) {
            $country = $this->getLanguage()->getCountryCode();
        }
        return $country;
    }
    
    /**
     * check if regions are enabled
     *
     * @return boolean
     */
    public function isRegionEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_ENABLE_REGIONS);
    }
}
