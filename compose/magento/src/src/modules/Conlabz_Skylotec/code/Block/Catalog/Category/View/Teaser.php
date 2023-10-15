<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Catalog_Category_View_Teaser extends Conlabz_Skylotec_Block_Teaser
{
    /**
     *
     * @return Mage_Catalog_Model_Category
     */
    public function getCategory()
    {
        return Mage::registry('current_category');
    }

    public function getClassname()
    {
        return 'teaser-category';
    }

    public function getTitle()
    {
        if ($title = $this->getCategory()->getCustomPageTitle()) {
            return $title;
        }
        if ($title = $this->getCategory()->getMetaTitle()) {
            return $title;
        }
        return $this->getCategory()->getName();
    }

    public function getImageUrl()
    {
        $category = $this->getCategory();

        $placeholder = $this->getSkinUrl('images/category-teaser-placeholder.png');
        $categoryImage = $category->getImage();
        $fileName = Mage::getBaseDir("media")."/catalog/category/".$categoryImage;
        if (!$categoryImage || !file_exists($fileName)) {
            return $placeholder;
        }
        return $this->getCategory()->getImageUrl();
    }

    public function getDescription()
    {
        return $this->getCategory()->getDescription();
    }

    public function getIcon()
    {
        return $this->getCategory()->getIcon();
    }

    public function canDisplay()
    {
        return true;
    }
}
