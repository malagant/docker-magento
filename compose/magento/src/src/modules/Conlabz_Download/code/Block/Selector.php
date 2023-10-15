<?php

/**
 * @package Conlabz_Download
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Download_Block_Selector extends Mage_Core_Block_Template
{
    /**
     *
     * @return Conlabz_Download_Model_Files[]
     */
    public function getCategories()
    {
        return Mage::getModel('dstorage/files')->getCategories();
    }

    public function getProductCategories()
    {
        $division = $this->getRequest()->getParam('division');
        return Mage::getModel('dstorage/files')->getProductCategories($division);
    }

    /**
     *
     * @return string
     */
    public function getSearchTerm()
    {
        return trim($this->escapeHtml($this->getRequest()->getParam('qd')));
    }

    /**
     *
     * @param string $category
     * @return boolean
     */
    public function isCategoryActive($category)
    {
        return $this->getRequest()->getParam('file_category') === $category;
    }

    /**
     *
     * @param string $productCategory
     * @return boolean
     */
    public function isProductCategoryActive($productCategory)
    {
        return $this->getRequest()->getParam('product_category') === $productCategory;
    }
}
