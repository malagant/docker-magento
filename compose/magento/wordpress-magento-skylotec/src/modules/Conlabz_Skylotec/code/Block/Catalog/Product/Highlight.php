<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Catalog_Product_Highlight extends Mage_Catalog_Block_Category_View
{
    /**
     *
     * @var string
     */
    protected $_template = 'catalog/product/highlight.phtml';

    /**
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getHighlightProduct()
    {
        $currentCategory = $this->getCurrentCategory();
        if ($currentCategory->getIsBranche()) {
            $this->getParentBlock()->setClassname('teaser-default');
            return false;
        }
        /* @var $collection Mage_Catalog_Model_Resource_Product_Collection */
        $collection = $currentCategory->getProductCollection();
        $collection->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
        $collection->addAttributeToSelect(array(
            'small_image',
            'name',
            'sku'
        ));
        $collection->addAttributeToSort('reihenfolge', 'ASC');
        $collection->getSelect()->limit(1);
        $product = $collection->getFirstItem();
        if (!$product->getId()) {
            return false;
        }
        return $product;
    }

    public function getDefaultImage()
    {
        $category = $this->getCurrentCategory();
        $bg = 'industry';
        if ($division = $category->getDivision()) {
            $bg = $division;
        }
        return $this->getSkinUrl(sprintf('images/top-bg-%s.jpg', $bg));
    }
}
