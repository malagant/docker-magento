<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Page_Teaser extends Conlabz_Skylotec_Block_Teaser
{
    /**
     *
     * @return Fishpig_Wordpress_Model_Post
     */
    public function getPage()
    {
        return Mage::registry('wordpress_post');
    }

    public function getTitle()
    {
        return $this->getPage()->getPostTitle();
    }

    public function getImageUrl()
    {
        $parentPost = $this->getPage()->getParentPost();
        if ($image = $this->getPage()->getFeaturedImage()) {
            return $image->getFullSizeImage();
        } elseif ($parentPost && ($parentImage = $parentPost->getFeaturedImage())) {
            return $parentImage->getFullSizeImage();
        }
        return false;
    }

    public function getDescription()
    {
        return $this->getPage()->getMetaValue('subtitle');
    }

    public function canDisplay()
    {
        return true;
    }
}
