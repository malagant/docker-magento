<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Catalog_Product_View_Buy extends Mage_Catalog_Block_Product_View
{
    /**
     * @return array
     */
    public function getAttributes()
    {
        $product = $this->getProduct();
        $attributeCodes = Mage::app()->getConfig()->getNode('global/skylotec/buy_attributes')->asArray();
        $attributeCodes = array_keys($attributeCodes);
        $attributes = array();

        foreach ($product->getAttributes() as $attribute) {
            $attributeCode = $attribute->getAttributeCode();
            if (!in_array($attributeCode, $attributeCodes) ||
                !$product->getData($attributeCode)
            ) {
                continue;
            }
            $value = $attribute->getFrontend()->getValue($product);
            if (is_string($value) && strlen($value)) {
                $attributes[$attributeCode] = array(
                    'label' => $attribute->getStoreLabel(),
                    'value' => $value,
                    'code'  => $attribute->getAttributeCode()
                );
            }
        }
        return $attributes;
    }

    /**
     * @return Mage_CatalogInventory_Model_Stock_Item
     */
    protected function _getStock()
    {
        $stock   = Mage::getModel('cataloginventory/stock_item')
            ->loadByProduct($this->getProduct());
        return $stock;
    }

    /**
     * @return int
     */
    public function getQty()
    {
        return (int) $this->getProduct()->getQtyExport();
    }
}
