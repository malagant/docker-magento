<?php

/**
 * @package Conlabz_Wordpress
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Wordpress_Helper_Shortcode_PostGallery extends Fishpig_Wordpress_Helper_Shortcode_Abstract
{
    use Conlabz_Wordpress_Helper_Shortcode_ObserverTrait;

    public function getTag()
    {
        return 'post_gallery';
    }

    /**
     * @param string $content
     */
    protected function _apply(&$content)
    {
        /*$shortcodes = $this->_getShortcodes($content);
        /** @var Conlabz_Wordpress_Helper_Gallery $galleryHelper *
        $galleryHelper = Mage::helper('conwp/gallery');
        if (!$galleryHelper->hasGallery($object)) {
            return;
        }
        $html = $galleryHelper->renderGallery($object);
        if (false === $shortcodes) {
            $content .= $html;
        } else {
            foreach ($shortcodes as $shortcode) {
                $content = str_replace($shortcode->getHtml(), $html, $content);
            }
        }*/
    }
}
