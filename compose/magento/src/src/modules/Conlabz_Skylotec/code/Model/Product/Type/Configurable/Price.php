<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
// @codingStandardsIgnoreStart
class Conlabz_Skylotec_Model_Product_Type_Configurable_Price extends Mage_Catalog_Model_Product_Type_Configurable_Price
// @codingStandardsIgnoreEnd
{
    /**
     * Get product final price
     *
     * @param   double $qty
     * @param   Mage_Catalog_Model_Product $product
     * @return  double
     */
    // @codingStandardsIgnoreStart
    public function getFinalPrice($qty = null, $product)
    {
        if (is_null($qty) && !is_null($product->getCalculatedFinalPrice())) {
            return $product->getCalculatedFinalPrice();
        }

        $basePrice = $this->getBasePrice($product, $qty);
        $finalPrice = $basePrice;
        $product->setFinalPrice($finalPrice);
        Mage::dispatchEvent('catalog_product_get_final_price', array('product' => $product, 'qty' => $qty));
        $finalPrice = $product->getData('final_price');

        $simpleProductPrice = $this->getSimpleProductPrice($qty, $product);
        if (false !== $simpleProductPrice) {
            $finalPrice = $simpleProductPrice;
        }
        
        $finalPrice += $this->_applyOptionsPrice($product, $qty, $basePrice) - $basePrice;
        $finalPrice = max(0, $finalPrice);

        $product->setFinalPrice($finalPrice);
        return $finalPrice;
    }
    // @codingStandardsIgnoreEnd

    public function getSimpleProductPrice($qty, $product)
    {
        $simpleOption = $product->getCustomOption('simple_product');
        if (!$simpleOption) {
            return false;
        }
        $simpleProduct = $simpleOption->getProduct();
        $price = $simpleProduct->getFinalPrice($qty, $simpleProduct);
        return $price;
    }
}
