<?php
class Conlabz_Eshop_Model_Currency
{
    
    /*
     * Get Shop Currency
     *
     * @return get Store Currency Code
     */
    public function getShopCurrency()
    {
        
        return Mage::app()->getStore(Mage::app()->getStore()->getId)->getCurrentCurrencyCode();
    }
    
    /*
     * GEt Arendicom API currency key
     *
     * @return int key | false
     */
    public function getApiCurrencyId()
    {
        
        $currencies = self::getCurrenciesList();
        if (isset($currencies[$this->getShopCurrency()])) {
            return $currencies[$this->getShopCurrency()];
        }
        return false;
    }
    
    private function getCurrenciesList()
    {
        
        return array("EUR" => 1, "CHF" => 3, "GBP" => 5);
    }
}
