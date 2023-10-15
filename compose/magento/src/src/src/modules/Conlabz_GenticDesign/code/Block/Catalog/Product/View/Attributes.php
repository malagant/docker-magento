<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_GenticDesign_Block_Catalog_Product_View_Attributes extends Mage_Catalog_Block_Product_View_Attributes
{
    /**
     * @var Magento_Db_Adapter_Pdo_Mysql
     */
    protected $_read;

    /**
     * @var string
     */
    protected $_tbl_eav_attribute_option;

    public function getAdditionalData(array $excludeAttr = array())
    {
        $data = array();
        $product = $this->getProduct();
        $attributes = $product->getAttributes();
        $showAttributes = array(
            'material',
            'size'
        );
        foreach ($attributes as $attribute) {
            if (!in_array($attribute->getAttributeCode(), $showAttributes)) {
                continue;
            }
            if ($attribute->getIsVisibleOnFront() && !in_array($attribute->getAttributeCode(), $excludeAttr)) {
                if (!$product->getData($attribute->getAttributeCode())) {
                    continue;
                }
                $value = $attribute->getFrontend()->getValue($product);
                if (is_string($value) && strlen($value)) {
                    $data[$attribute->getAttributeCode()] = array(
                        'label' => $attribute->getStoreLabel(),
                        'value' => $value,
                        'code'  => $attribute->getAttributeCode()
                    );
                }
            }
        }
        
        if ($product->isConfigurable()) {
            $productAttributeOptions = $product->getTypeInstance(true)
                ->getConfigurableAttributesAsArray($product);
            foreach ($productAttributeOptions as $attributeOption) {
                $values = $this->_getSortedValues($attributeOption['values']);
                $data[$attributeOption['attribute_code']] = array(
                    'label' => Mage::helper('genticdesign')->__($attributeOption['frontend_label']),
                    'value' => implode(', ', $values),
                    'code'  => $attributeOption['attribute_code']
                );
            }
        }
        return $data;
    }

    /**
     * Sort the options based off their position.
     * Workaround due to bug in Magento 1.9.1.0
     *
     * @see http://magento.stackexchange.com/questions/45396/magento-1-9-1-configurable-product-attribute-sorting
     * @param array $values
     * @return array
     */
    protected function _getSortedValues(array $values)
    {
        if (count($values)) {
            if (!$this->_read || !$this->_tbl_eav_attribute_option) {
                $resource = Mage::getSingleton('core/resource');

                $this->_read = $resource->getConnection('core_read');
                $this->_tbl_eav_attribute_option = $resource->getTableName('eav_attribute_option');
            }

            $optionIds = array();
            foreach ($values as $value) {
                $optionIds[] = $value['value_index'];
            }

            $sql = $this->_read->select()
                ->from($this->_tbl_eav_attribute_option)
                ->where('option_id IN(?)', $optionIds)
                ->order(array('sort_order ASC'));
            $result = $this->_read->fetchCol($sql);

            $sortedValues = array();
            foreach ($values as $value) {
                foreach ($result as $position => $optionId) {
                    if ($optionId == $value['value_index']) {
                        $sortedValues[$position] = $value['store_label'];
                    }
                }
            }
            ksort($sortedValues);
        }
        return $sortedValues;
    }
}
