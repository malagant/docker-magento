<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Page_Home_Teaser extends Conlabz_Skylotec_Block_Wordpress_List_Abstract
{
    /**
     *
     */
    public function getTeaser()
    {
        $collection = parent::getPostCollection();
        $collection->addPostTypeFilter('teaser-home');
        $collection->addMetaFieldToSelect('teaser_url');
        return $collection;
    }

    public function getTeaserImageUrl(Fishpig_Wordpress_Model_Post $post)
    {
        if ($image = $post->getFeaturedImage()) {
            return $image->getFullSizeImage();
        }
        return $this->getSkinUrl('images/okta.png');
    }
}
