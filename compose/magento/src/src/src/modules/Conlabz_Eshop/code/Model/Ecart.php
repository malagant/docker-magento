<?php
class Conlabz_Eshop_Model_Ecart extends Mage_Core_Model_Abstract
{
    
    public function _construct()
    {

        parent::_construct();
        $this->_init('eshop/ecart');
    }
    public function setQuote($quoteId)
    {
        
        $previous = $this->getCollection()->addFieldToFilter("quote_id", $quoteId);
        if (sizeof($previous) > 0) {
            $previous = $previous->getFirstItem();
            Mage::getModel("eshop/ecart")->setData(array("start_time"=>time()))->setId($previous->getId())->save();
        } else {
            Mage::getModel("eshop/ecart")->setData(array("quote_id"=>$quoteId, "start_time"=>time()))->setId(null)->save();
        }
        
        return true;
    }
    public function checkQuote($quoteId)
    {
        
        $previous = $this->getCollection()->addFieldToFilter("quote_id", $quoteId);
        if (sizeof($previous) > 0) {
            $previous = $previous->getFirstItem();
            
            if ($previous->getData("start_time") < time()-60*30) {
                Mage::getSingleton('checkout/session')->clear();
            } else {
                Mage::getModel("eshop/ecart")->load($previous->getId())->delete();
            }
        }
        return true;
    }
}
