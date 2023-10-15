<?php
require_once Mage::getModuleDir('controllers', 'Mage_Checkout').DS.'OnepageController.php';
class Conlabz_Eshop_OnepageController extends Mage_Checkout_OnepageController
{
    
    public function indexAction()
    {
        
        if (!Mage::helper('checkout')->canOnepageCheckout()) {
            Mage::getSingleton('checkout/session')->addError($this->__('The onepage checkout is disabled.'));
            $this->_redirect('checkout/cart');
            return;
        }
        
        $quote = $this->getOnepage()->getQuote();
        if (!$quote->hasItems() || $quote->getHasError()) {
            $this->_redirect('checkout/cart');
            return;
        }
        
        if (!$quote->validateMinimumAmount()) {
            $error = Mage::getStoreConfig('sales/minimum_order/error_message') ?
                Mage::getStoreConfig('sales/minimum_order/error_message') :
                Mage::helper('checkout')->__('Subtotal must exceed minimum order amount');

            Mage::getSingleton('checkout/session')->addError($error);
            $this->_redirect('checkout/cart');
            return;
        }
        
        Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_secure'=>true)));

        $apiCallResult = Mage::getModel('eshop/api')->sentRequest();
        if (isset($apiCallResult['errors'])) {
            if (is_array($apiCallResult['errors'])) {
                foreach ($apiCallResult['errors'] as $error) {
                    Mage::getSingleton('checkout/session')->addError($error);
                }
            } else {
                Mage::getSingleton('checkout/session')->addError($apiCallResult['errors']);
            }
            $this->_redirect('checkout/cart');
        } else {
            $quoteId = Mage::getSingleton('checkout/session')->getQuote()->getId();
            Mage::getModel("eshop/ecart")->setQuote($quoteId);
            Mage::app()->getFrontController()->getResponse()->setRedirect($apiCallResult['redirectUrl']);
        }
        return;
    }
}
