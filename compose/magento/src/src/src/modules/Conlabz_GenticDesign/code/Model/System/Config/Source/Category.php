<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_GenticDesign_Model_System_Config_Source_Category
{
    public function toOptionArray($addEmpty = true)
    {
        $collection = Mage::getModel('catalog/category')->getCollection();
        $collection->addAttributeToSelect('name')->addIsActiveFilter();
        $options = array();
        if ($addEmpty) {
            $options[] = array(
                'label' => Mage::helper('adminhtml')->__('-- Please Select --'),
                'value' => ''
            );
        }
        foreach ($collection as $category) {
            if ($category->getName() != "") { // to skip blank category name
                $options[] = array(
                   'label' => $category->getName(),
                   'value' => $category->getId()
                );
            }
        }

        return $options;
    }
}
