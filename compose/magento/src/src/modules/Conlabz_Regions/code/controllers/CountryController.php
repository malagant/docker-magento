<?php

/**
 * @package Conlabz_Regions
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Regions_CountryController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $language = Mage::getSingleton('regions/language');
        $response = array(
            'storeCode'    => $language->getStoreCode(),
            'storeCountry' => $language->getCountryCode(),
        );
        $this->getResponse()
            ->setBody(
                Mage::helper('core')->jsonEncode($response)
            );
    }
    
    public function switchAction()
    {
        $req = $this->getRequest();
        
        $country = 'DE';
        $currentCountry = Mage::getModel('core/cookie')->get('country');
        $redirect = false;
        if ($req->getParam('country', false)) {
            $country = $req->getParam('country');
            $redirect = true;
        } elseif ($currentCountry) {
            $country = $currentCountry;
        }
        
        if ($country !== $currentCountry) {
            $redirect = true;
            $this->_removeQuote();
        }
        
        Mage::getModel('regions/country_current')
            ->setCountry($country);
        
        if ($redirect) {
            $this->getResponse()
                ->setRedirect(
                    Mage::getBaseUrl()
                );
        }
    }

    public function countryAction()
    {
        $httpHelper = Mage::helper('core/http');
        $ip = $httpHelper->getRemoteAddr();
        $acceptLang = $httpHelper->getHttpAcceptLanguage();
        $language = Mage::getSingleton('regions/language')->setLanguage($acceptLang);
        $store = Mage::app()->getCookie()->get('assigned_store');

        $response = array(
            'storeCode'    => $store ?: $language->getStoreCode(),
            'storeCountry' => $language->getCountryCode(),
            'location'     => $language->getStoreUrl($store)
        );

        $message = 'Accept-Language for IP ' . $ip . ' : ' . $acceptLang . ' ';
        $message.= var_export($response, true);
        Mage::log($message, null, 'accept-language.log', false);

        $this->getResponse()->setBody(
            Mage::helper('core')->jsonEncode($response)
        );
    }

    public function allowForeignAction()
    {
        /* @var $cookie Mage_Core_Model_Cookie */
        $cookie = Mage::getSingleton('core/cookie');
        $cookie->set('allow_foreign_country', 1, null, null, null, null, false);
    }
    
    protected function _removeQuote()
    {
        $quoteId = Mage::getSingleton('checkout/session')
            ->getQuote()->getId();
        if ($quoteId) {
            try {
                foreach (Mage::getSingleton('checkout/session')->getQuote()->getItemsCollection() as $item) {
                    Mage::getSingleton('checkout/cart')->removeItem(
                        $item->getId()
                    )->save();
                }
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }
}
