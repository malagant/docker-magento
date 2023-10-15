<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_GenticDesign_Block_Wordpress_Artist extends Mage_Core_Block_Template
{
    protected $_template = 'wordpress/homepage/artist.phtml';
    
    /**
     *
     * @return Fishpig_Wordpress_Model_Post
     */
    public function getLatestArtist()
    {
        /* @var $post Fishpig_Wordpress_Model_Post */
        $post = Mage::getResourceModel('wordpress/post_collection')
            ->addIsPublishedFilter()
            ->addPostTypeFilter('artist')
            ->setPageSize(1)
            ->getFirstItem();
        
        return $post;
    }
}
