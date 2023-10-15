<?php

/**
 * @package Conlabz_Recaptcha
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Recaptcha_Block_Form
    extends Mage_Core_Block_Template
{
    /**
     *
     * @var string
     */
    protected $_template = 'conlabz/recaptcha/form.phtml';
    
    /**
     * retrieve helper
     * 
     * @return Conlabz_Recaptcha_Helper_Data
     */
    protected function _getHelper()
    {
        return $this->helper('recaptcha');
    }
    
    /**
     * 
     * retrieve site key
     * 
     * @return string
     */
    public function getSiteKey()
    {
        return $this->_getHelper()->getSiteKey();
    }
    
    /**
     * 
     * @return string
     */
    public function getCurrentUrl()
    {
        return Mage::helper('core/url')->getCurrentUrl();;
    }
}
