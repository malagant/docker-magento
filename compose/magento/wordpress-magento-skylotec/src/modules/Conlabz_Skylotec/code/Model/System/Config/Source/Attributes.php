<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Model_System_Config_Source_Attributes
{
    public function toOptionArray()
    {
        $collection = Mage::getResourceModel('catalog/product_attribute_collection');
        $options = array();
        foreach ($collection as $item) {
            if (!$item->getAttributeCode() || !$item->getFrontendLabel()) {
                continue;
            }
            $options[] = array(
                'value' => $item->getAttributeCode(),
                'label' => $item->getFrontendLabel()
            );
        }
        return $options;
    }
}
