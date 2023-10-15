<?php

/**
 * @package Conlabz_Recaptcha
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Recaptcha_Block_Jsapi
    extends Mage_Core_Block_Template
{
    /**
     *
     * @var string
     */
    protected $_template = 'conlabz/recaptcha/jsapi.phtml';
    
    /**
     * 
     * @return string
     */
    public function getJsApiUrl()
    {
        $helper = $this->_getHelper();
        return $helper->getJsApiUrl() . '?' . http_build_query($helper->getJsApiParams());
    }
    
    /**
     * 
     * @return Conlabz_Recaptcha_Helper_Data
     */
    protected function _getHelper()
    {
        return $this->helper('recaptcha');
    }
}
