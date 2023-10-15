<?php

/**
 * @package Conlabz_Recaptcha
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Recaptcha_Model_Observer
{
    /**
     *
     * @var string
     */
    protected $_recaptchaBlock;

    /**
     * @return Conlabz_Recaptcha_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('recaptcha');
    }
    
    /**
     * Event: core_block_abstract_to_html_before
     * 
     * @param Varien_Event_Observer $observer
     * @return \Conlabz_Recaptcha_Model_Observer
     */
    public function injectRecaptcha(Varien_Event_Observer $observer)
    {
        if (!$this->_getHelper()->isActive()) {
            return;
        }
        $event = $observer->getEvent();
        /* @var $block Mage_Core_Block_Abstract */  
        $block = $event->getBlock();
        if ($block->getEnableRecaptcha()) {
            $block->append(
                $this->_getRecaptchaBlock(), 
                Conlabz_Recaptcha_Helper_Data::BLOCK_ALIAS
            );
        }
        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     * @return void
     * @throws Varien_Exception
     */
    public function verifyUserResponse(Varien_Event_Observer $observer)
    {
        if (!$this->_getHelper()->isActive()) {
            return;
        }
        $event = $observer->getEvent();
        $controller = $event->getControllerAction();
        $request = $controller->getRequest();
        $responseToken = $request->getPost(Conlabz_Recaptcha_Helper_Data::POST_PARAM); 
        /* @var $response Mage_Core_Controller_Response_Http */
        $response = $controller->getResponse();
        if (!$responseToken) {
            Mage::getSingleton('customer/session')->setFormData($request->getPost());
            Mage::getSingleton('core/session')->addError(__('Please solve the captcha.'));
            $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
            $response->setRedirect($request->getPost('back_url'));
            return;
        }
        
        $validator = Mage::getSingleton('recaptcha/validator');
        if (!$validator->isValid($request)) {
            Mage::getSingleton('core/session')->addSuccess(__('Success!'));
            $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
            $response->setRedirect($request->getPost('back_url'));
            return;
        }
    }
    
    /**
     * 
     * @return Conlabz_Recaptcha_Block_Form
     */
    protected function _getRecaptchaBlock()
    {
        if (null === $this->_recaptchaBlock) {
            $this->_recaptchaBlock = Mage::app()->getLayout()->createBlock(
                'recaptcha/form',
                Conlabz_Recaptcha_Helper_Data::BLOCK_NAME
            );
        }
        return $this->_recaptchaBlock;
    }
}
