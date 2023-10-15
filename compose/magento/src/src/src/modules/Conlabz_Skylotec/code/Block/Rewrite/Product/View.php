<?php
/**
 * @package Conlabz_Skylotec
 * @author
 */

class Conlabz_Skylotec_Block_Rewrite_Product_View extends Mage_Catalog_Block_Product_View
{
    /**
     * @return Mage_Catalog_Block_Product_View
     */
    protected function _prepareLayout()
    {
        $parent = parent::_prepareLayout();

        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $product = $this->getProduct();
            if (!($description = $product->getCustomMetaDescription())) {
                $description = $headBlock->getDescription();
            }

            $product->setMetaDescription($description);
            $headBlock->setDescription(Mage::helper('core/string')->substr($description, 0, 255));
        }

        return $parent;
    }
}
