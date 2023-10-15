<?php

/**
 * @package Cnlabz_Wordpress
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Wordpress_Helper_Gallery extends Mage_Core_Helper_Abstract
{
        
    /**
     *
     * @param Fishpig_Wordpress_Model_Abstract $post
     * @param string $metaKey
     * @return bool
     */
    public function hasGallery(Fishpig_Wordpress_Model_Abstract $post, $metaKey = 'gallery')
    {
        return count($post->getMetaValue($metaKey)) > 0;
    }
    
    /**
     *
     * @param mixed $postOrImages
     * @return string rendered gallery
     */
    public function renderGallery($postOrImages, $metaKey = 'gallery')
    {
        if ($postOrImages instanceof Fishpig_Wordpress_Model_Abstract) {
            $images = $postOrImages->getMetaValue($metaKey);
        } else {
            $images = $postOrImages;
        }
        /* @var $block Conlabz_Wordpress_Block_Acf_Gallery */
        $block = Mage::app()->getLayout()->createBlock(
            'conwp/acf_gallery',
            ''
        );
        if (!is_array($images)) {
            return '';
        }
        $block->setGalleryImages($images);
        $html = $block->toHtml();
        $html = str_replace("\n", "", $html);
        $html = preg_replace("/\s+/", " ", $html);
        return $html;
    }
}
