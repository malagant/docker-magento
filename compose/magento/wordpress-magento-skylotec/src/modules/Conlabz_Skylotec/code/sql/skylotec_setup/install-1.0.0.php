<?php
/* @var $this Conlabz_Skylotec_Model_Resource_Setup */
$this->startSetup();

$this->addAttribute(
    Mage_Catalog_Model_Category::ENTITY,
    Conlabz_Skylotec_Helper_Data::ATTRIBUTE_CATEGORY_FILTER,
    array(
        'group'         => 'Display Settings',
        'input'         => 'multiselect',
        'type'          => 'text',
        'label'         => 'Filter',
        'backend'       => 'skylotec/system_config_backend_filter',
        'source'        => 'skylotec/system_config_source_filter',
        'visible'       => true,
        'required'      => false,
        'visible_on_front' => false,
        'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    )
);

$this->addAttribute(
    Mage_Catalog_Model_Category::ENTITY,
    Conlabz_Skylotec_Helper_Data::ATTRIBUTE_CATEGORY_DIVISION,
    array(
        'group'         => 'General Information',
        'input'         => 'select',
        'type'          => 'text',
        'label'         => 'Division',
        'source'        => 'skylotec/system_config_source_division',
        'visible'       => true,
        'required'      => false,
        'visible_on_front' => false,
        'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    )
);


 
$this->endSetup();
