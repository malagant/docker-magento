<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Skylopedia extends Fishpig_Wordpress_Addon_CPT_Block_View
{
    /**
     *
     * @return string
     */
    public function getTitle()
    {
        if ($category = Mage::registry('current_category')) {
            if ($title = $category->getMetaTitle()) {
                return $title;
            }
        }
        return $this->__('Skylopedia');
    }
}
