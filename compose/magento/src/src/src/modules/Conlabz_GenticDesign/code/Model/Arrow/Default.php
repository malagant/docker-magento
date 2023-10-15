<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_GenticDesign_Model_Arrow_Default implements Conlabz_GenticDesign_Model_Arrow_Interface
{
    protected $_mapping = array(
        '#^/(en|de)?/?$#' => array(
            'contact',
            'Contact',
            'collection/femme-fatale.html',
            'Femme Fatale'
        ),
        '#/haendler/?$#' => array(
            'collection/accessoires-kit.html',
            'Accessoires Kit',
            'about',
            'About'
        ),
        '#/about/?$#' => array(
            'haendler',
            'Storefinder',
            'baem',
            'Bam!'
        ),
        '#/contact/?#' => array(
            'about',
            'About',
            '',
            'Homepage'
        )
    );
    
    protected function _getLinks()
    {
        $currentPath = Mage::app()->getRequest()->getRequestUri();
        foreach ($this->_mapping as $regex => $uris) {
            if (preg_match($regex, $currentPath)) {
                list($prevUrl, $prevLabel, $nextUrl, $nextLabel) = $uris;
                return array(
                    'next_url' => $nextUrl,
                    'next_label' => $nextLabel,
                    'prev_url' => $prevUrl,
                    'prev_label' => $prevLabel
                );
            }
        }
        return false;
    }
    
    public function getNextUrl()
    {
        if ($links = $this->_getLinks()) {
            return Mage::getUrl($links['next_url']);
        }
    }

    public function getPrevUrl()
    {
        if ($links = $this->_getLinks()) {
            return Mage::getUrl($links['prev_url']);
        }
    }
    
    public function getNextLabel()
    {
        if ($links = $this->_getLinks()) {
            return Mage::helper('genticdesign')->__($links['next_label']);
        }
    }

    public function getPrevLabel()
    {
        if ($links = $this->_getLinks()) {
            return Mage::helper('genticdesign')->__($links['prev_label']);
        }
    }
}
