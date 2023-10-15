<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Post_Ajax extends Mage_Core_Block_Template
{
    protected $_template = 'wordpress/post/view.phtml';

    /**
     *
     * @var Fishpig_Wordpress_Model_Post
     */
    protected $_post;

    /**
     *
     * @return type
     */
    public function getPost()
    {
        return $this->_post;
    }

    /**
     *
     * @param Fishpig_Wordpress_Model_Post $post
     * @return Conlabz_Skylotec_Block_Post_Ajax
     */
    public function setPost(Fishpig_Wordpress_Model_Post $post)
    {
        $this->_post = $post;
        return $this;
    }
}
