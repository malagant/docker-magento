<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_SocialMedia_Block_Opengraph extends Conlabz_SocialMedia_Block_Abstract
{

    const PROPERTY_PREFIX = 'og:';
            
    protected $_xmlPrefix = 'socialmedia/opengraph';
    
    protected $_types = array(
        'title',
        'type',
        'url',
        'image',
        'description',
        'site_name',
        'price_amount',
        'price_currency'
    );
    
    public function getTypes()
    {
        return $this->_types;
    }
    
    public function getPrefix()
    {
        return self::PROPERTY_PREFIX;
    }
            
    public function getOgType()
    {
        return $this->_getValue('og_type');
    }
            
    public function getSiteName()
    {
        return $this->_getValue('site_name');
    }
    
    public function getPriceAmount()
    {
        return $this->_getData('price_amount');
    }
    
    public function getPriceCurrency()
    {
        return $this->_getData('price_currency');
    }
}
