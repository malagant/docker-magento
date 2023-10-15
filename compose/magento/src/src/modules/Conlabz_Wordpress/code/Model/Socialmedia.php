<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Wordpress_Model_Socialmedia
{
    public function addSocialMediaMetaData(Conlabz_SocialMedia_Block_Abstract $block, $prefix)
    {
        if ($page = Mage::registry('wordpress_page')) {
            if ($featuredImage = $page->getFeaturedImage()) {
                $block->setImage($featuredImage->getFullSizeImage());
            }
        }
    }
}
