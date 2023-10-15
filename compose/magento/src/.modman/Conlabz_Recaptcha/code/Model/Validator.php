<?php

/**
 * @package Conlabz_Recaptcha
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Recaptcha_Model_Validator
{    
    const API_URL = 'https://www.google.com/recaptcha/api/siteverify';
    
    /**
     * @return boolean
     */
    public function isValid(Mage_Core_Controller_Request_Http $request)
    {
        $apiResponse = file_get_contents($this->_getApiUrl($request));
        if (!$apiResponse) {
            return false;            
        }
        $jsonResponse = Mage::helper('core')->jsonDecode($apiResponse);
        return (isset($jsonResponse['success']) && $jsonResponse['success']);
    }
    
    /**
     * 
     * @param Mage_Core_Controller_Request_Http $request
     * @return array
     */
    protected function _getApiParams(Mage_Core_Controller_Request_Http $request)
    {
        return array(
            'secret'    => $this->_getHelper()->getSecretKey(),
            'response'  => $request->getPost(Conlabz_Recaptcha_Helper_Data::POST_PARAM),
            'remoteip'  => $this->_getRemoteAddr($request)
        );
    }
    
    /**
     * retrieve remote address
     * 
     * @param type $request
     * @return type
     */
    protected function _getRemoteAddr(Mage_Core_Controller_Request_Http $request)
    {
        if ($ip = $request->getServer('X-Forwarded-For')) {
            return $ip;
        }
        return $request->getServer('REMOTE_ADDR');
    }
    
    /**
     * 
     * @return Conlabz_Recaptcha_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('recaptcha');
    }
    
    protected function _getApiUrl(Mage_Core_Controller_Request_Http $request)
    {
        return self::API_URL 
            . '?' . http_build_query($this->_getApiParams($request));
            
    }
}
