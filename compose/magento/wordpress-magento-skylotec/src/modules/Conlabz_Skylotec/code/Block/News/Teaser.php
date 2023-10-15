<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_News_Teaser extends Fishpig_Wordpress_Block_Post_View implements Conlabz_Skylotec_Block_Teaser_Interface
{
    public function getTitle()
    {
        return false;
    }

    public function canDisplay()
    {
        return $this->getPost()->getMetaValue('header_image');
    }

    public function getImageUrl()
    {
        $headerImage = $this->getPost()->getMetaValue('header_image');
        return isset($headerImage['url']) ? $headerImage['url'] : '';
    }

    public function getIcon()
    {
        return false;
    }

    public function getDescription()
    {
        return '';
    }
}
