<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Skylopedia_Categories extends Mage_Core_Block_Template
{
    /**
     *
     * @var string
     */
    protected $_template = 'skylopedia/categories.phtml';
    
    /**
     *
     * @return Fishpig_Wordpress_Model_Resource_Term_Collection
     */
    public function getSkylopediaCategories()
    {
        $termCollection = Mage::getResourceModel('wordpress/term_collection');
        $termCollection->addTaxonomyFilter('skylopedia');
        return $termCollection;
    }
}
