<?php

/**
 * @package Conlabz_Wordpress
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Wordpress_Block_Acf_Gallery extends Mage_Core_Block_Template
{
    protected $_template = 'wordpress/acf/gallery.phtml';
    
    /**
     *
     * @var array
     */
    protected $_galleryImages = array();
    
    /**
     *
     * @return Fishpig_Wordpress_Model_Image
     */
    public function getMainImage()
    {
        reset($this->_galleryImages);
        return current($this->_galleryImages);
    }
    
    /**
     *
     * @return bool
     */
    public function hasImages()
    {
        return count($this->_galleryImages) > 0;
    }
    
    /**
     *
     * @return Fishpig_Wordpress_Model_Image[]
     */
    public function getGalleryImages()
    {
        return $this->_galleryImages;
    }

    /**
     *
     * @param array $galleryImages
     * @return \Conlabz_Wordpress_Block_Acf_Gallery
     */
    public function setGalleryImages(array $galleryImages)
    {
        $this->_galleryImages = $galleryImages;
        return $this;
    }
}
