<?php
$this->startSetup();

$this->addAttribute(
    Mage_Catalog_Model_Category::ENTITY,
    'custom_url',
    array(
        'group'         => 'Display Settings',
        'input'         => 'text',
        'type'          => 'text',
        'label'         => 'Custom URL',
        'source'        => 'conwp/category_attribute_source_wordpress',
        'visible'       => true,
        'required'      => false,
        'visible_on_front' => false,
        'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    )
);

$this->endSetup();
