<?php

/**
 * @package Conlabz_Recaptcha
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Recaptcha_Helper_Data
    extends Mage_Core_Helper_Abstract
{
    const XML_PATH_ACTIVE = 'recaptcha/general/active';
    const XML_PATH_CLIENT_KEY = 'recaptcha/api/site_key';
    const XML_PATH_SECRET_KEY = 'recaptcha/api/secret_key';
    
    const JS_API_URL = 'https://www.google.com/recaptcha/api.js';
    const BLOCK_ALIAS = 'recaptcha';
    const BLOCK_NAME  = 'recaptcha.form';
    const POST_PARAM = 'g-recaptcha-response';
    
    /**
     * 
     * @return boolean
     */
    public function isActive()
    {
        return (bool) Mage::getStoreConfig(self::XML_PATH_ACTIVE);
    }
    
    /**
     * retrieve secret key
     * 
     * @return string
     */
    public function getSecretKey()
    {
        return trim(Mage::getStoreConfig(self::XML_PATH_SECRET_KEY));
    }
    
    /**
     * retrieve site key
     * 
     * @return string
     */
    public function getSiteKey()
    {
        return trim(Mage::getStoreConfig(self::XML_PATH_CLIENT_KEY));
    }
    
    /**
     * 
     * @return string retrieve javascript api url
     * 
     * @return string
     */
    public function getJsApiUrl()
    {
        return self::JS_API_URL;
    }
    
    /**
     * 
     * @return array
     */
    public function getJsApiParams()
    {
        return array(
            'hl' => $this->getLanguageCode()
        );
    }
    
    /**
     * 
     * @param mixed|null|string $locale
     * @return string
     */
    public function getLanguageCode($locale = null)
    {
        if (null === $locale) {
            $locale = Mage::app()->getLocale()->getLocaleCode();
        }
        return substr($locale, 0, 2);
    }
}
