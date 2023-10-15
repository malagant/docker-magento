<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_SocialMedia_Block_Twitter extends Conlabz_SocialMedia_Block_Abstract
{
    const PROPERTY_PREFIX = 'twitter:';
            
    protected $_xmlPrefix = 'socialmedia/twitter';
    
    protected $_types = array(
        'card',
        'site',
        'title',
        'image',
        'description',
        'creator',
        'data1',
        'label1'
    );
    
    public function getPrefix()
    {
        return self::PROPERTY_PREFIX;
    }
    
    public function getTypes()
    {
        return $this->_types;
    }
    
    public function getCard()
    {
        return $this->_getValue('card');
    }
    
    public function getSite()
    {
        return $this->_getValue('site');
    }
        
    public function getCreator()
    {
        return $this->_getValue('creator');
    }
    
    public function getData1()
    {
        if ($this->getPriceAmount()) {
            return $this->_getCurrencySymbol()
                . $this->getPriceAmount();
        }
    }
    
    public function getLabel1()
    {
        if ($this->getPriceAmount()) {
            return 'Price';
        }
    }
    
    protected function _getCurrencySymbol()
    {
        $currencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();
        return Mage::app()->getLocale()->currency($currencyCode)->getSymbol();
    }
}
