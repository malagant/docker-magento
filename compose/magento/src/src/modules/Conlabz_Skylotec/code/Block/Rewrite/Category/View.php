<?php
/**
 * @package Conlabz_Skylotec
 * @author
 */

class Conlabz_Skylotec_Block_Rewrite_Category_View extends Mage_Catalog_Block_Category_View
{
    /**
     * @return Mage_Catalog_Block_Category_View
     */
    protected function _prepareLayout()
    {
        $parent = parent::_prepareLayout();

        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $category = $this->getCurrentCategory();
            if ($title = $category->getCustomMetaTitle()) {
                $headBlock->setTitle($title);
            }

            if (!($description = $category->getCustomMetaDescription())) {
                $description = $headBlock->getDescription();
            }

            $category->setMetaDescription($description);
            $headBlock->setDescription(Mage::helper('core/string')->substr($description, 0, 255));
        }

        return $parent;
    }
}
