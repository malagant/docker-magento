<?php
/* @var $this Conlabz_Skylotec_Model_Resource_Setup */
$this->startSetup();

$this->addAttribute(
    Mage_Catalog_Model_Category::ENTITY,
    'hide_in_overview',
    array(
        'group'         => 'General Information',
        'input'         => 'select',
        'type'          => 'int',
        'label'         => 'Hide in overview',
        'source'        => 'eav/entity_attribute_source_boolean',
        'visible'       => true,
        'required'      => false,
        'visible_on_front' => true,
        'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    )
);

$this->endSetup();
