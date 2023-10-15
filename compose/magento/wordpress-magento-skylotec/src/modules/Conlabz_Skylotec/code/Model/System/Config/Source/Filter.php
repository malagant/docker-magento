<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Model_System_Config_Source_Filter extends Mage_Eav_Model_Entity_Attribute_Source_Table
{
    /**
     * @param bool $withEmpty
     * @param false $defaultValues
     * @return array|string[][]
     */
    public function getAllOptions($withEmpty = true, $defaultValues = false)
    {
        /* @var $attributes Mage_Catalog_Model_Resource_Product_Attribute_Collection */
        $attributes = Mage::getResourceModel('catalog/product_attribute_collection');
        $attributes->addFieldToFilter('is_filterable', array(
            'eq' => 1
        ));
        $options = array(array(
            'label' => 'Kategorien',
            'value' => 'category'
        ));
        foreach ($attributes as $attribute) {
            $options[] = array(
                'label' => $attribute->getFrontendLabel(),
                'value' => $attribute->getAttributeCode()
            );
        }
        return $options;
    }
}
