<?php
/**
 * Product description block
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Conlabz_Skylotec_Block_Catalog_Product_View_Attributes extends Mage_Core_Block_Template
{
    protected $_product = null;

    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = Mage::registry('product');
        }
        return $this->_product;
    }
    
    public function setProduct($product)
    {
        $this->_product = $product;
        return $this;
    }

    /**
     * $excludeAttr is optional array of attribute codes to
     * exclude them from additional data array
     *
     * @param array $excludeAttr
     * @return array
     */
    public function getAdditionalData(array $excludeAttr = array())
    {
        $data = array();
        $product = $this->getProduct();
        $attributes = $product->getAttributes();
        foreach ($attributes as $attribute) {
            if ($attribute->getIsVisibleOnFront() && !in_array($attribute->getAttributeCode(), $excludeAttr)) {
                $value = $attribute->getFrontend()->getValue($product);
                if (is_array($value)) {
                    $value = implode(' / ', $value);
                } elseif ((string) $value === '') {
                    continue;
                } elseif ($attribute->getFrontendInput() == 'price' && is_string($value)) {
                    $value = Mage::app()->getStore()->convertPrice($value, true);
                }

                if (is_string($value) && strlen($value)) {
                    $data[$attribute->getAttributeCode()] = array(
                        'label' => $attribute->getStoreLabel(),
                        'value' => $value,
                        'code'  => $attribute->getAttributeCode(),
                        'sort_order' => $attribute->getSortOrder(),
                    );
                }
            }
        }
        return $this->sortAttributes($data);
    }

    private function sortAttributes(array $attributes)
    {
        $attributesToSort = array_filter($attributes, function ($attribute) {
            return (int) $attribute['sort_order'] < 1;
        });
        $sortedAttributes = array_diff_key($attributes, $attributesToSort);

        uasort($attributesToSort, function ($a, $b) {
            return strnatcmp($a['label'], $b['label']);
        });
        return array_merge($sortedAttributes, $attributesToSort);
    }
}
