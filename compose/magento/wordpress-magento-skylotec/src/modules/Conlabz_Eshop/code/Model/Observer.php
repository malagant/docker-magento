<?php
class Conlabz_Eshop_Model_Observer
{
    
    public function checkCart()
    {
    
        $quoteId = Mage::getSingleton('checkout/session')->getQuote()->getId();
        
        if ($quoteId) {
            Mage::getModel("eshop/ecart")->checkQuote($quoteId);
        }
    }
}
