<?php
/**
 *
 */
class Conlabz_Wordpress_Model_Category_Attribute_Source_Mode extends Mage_Catalog_Model_Category_Attribute_Source_Mode
{
    /**
     * Returns all mode options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $options = parent::getAllOptions();
        $options[] = array(
            'value' => Conlabz_Wordpress_Model_Category::DM_WP_PAGE,
            'label' => Mage::helper('conwp')->__('Wordpress page')
        );
        $options[] = array(
            'value' => Conlabz_Wordpress_Model_Category::DM_WP_PAGE_LINK,
            'label' => Mage::helper('conwp')->__('Wordpress page link')
        );
        $options[] = array(
            'value' => Conlabz_Wordpress_Model_Category::DM_WP_LIST,
            'label' => Mage::helper('conwp')->__('Wordpress List')
        );
        $options[] = array(
            'value' => Conlabz_Wordpress_Model_Category::DM_NONE,
            'label' => 'None'
        );
        $optionsDto = new Varien_Object(array(
            'options' => $options
        ));
        Mage::dispatchEvent('category_attribute_source_mode', array(
            'dto' => $optionsDto
        ));
        $options = $optionsDto->getData('options');
        return $options;
    }
}
