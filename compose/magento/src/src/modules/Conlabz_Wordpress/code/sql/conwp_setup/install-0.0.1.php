<?php
$this->startSetup();

$this->addAttribute(
    Mage_Catalog_Model_Category::ENTITY,
    'wp_entity',
    array(
        'group'         => 'Display Settings',
        'input'         => 'select',
        'type'          => 'text',
        'label'         => 'Wordpress Type',
        'source'        => 'conwp/category_attribute_source_wordpress',
        'visible'       => true,
        'required'      => false,
        'visible_on_front' => false,
        'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    )
);

$this->endSetup();
