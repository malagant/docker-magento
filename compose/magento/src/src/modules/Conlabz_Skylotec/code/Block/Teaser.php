<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Teaser extends Mage_Core_Block_Template
{
    protected $_template = 'page/html/teaser.phtml';

    public function setTitle($title, $translate = true)
    {
        if ($translate) {
            $title = $this->__($title);
        }
        return $this->setData('title', $title);
    }

    public function getTitle()
    {
        return $this->getData('title');
    }

    public function canDisplay()
    {
        return $this->getData('can_display');
    }
    
    public function getImageUrl()
    {
        if ($image = $this->getData('image')) {
            return $this->getSkinUrl($image);
        }
        return false;
    }

    public function getIcon()
    {
        return false;
    }

    public function getDescription()
    {
        if ($description = $this->getData('description')) {
            return $description;
        }
        return false;
    }

    public function setDescription($description, $translate = true)
    {
        if ($translate) {
            $description = $this->__($description);
        }
        return $this->setData('description', $description);
    }
}
