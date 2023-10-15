<?php
/* @var $this Conlabz_Skylotec_Model_Resource_Setup */
$this->startSetup();

$this->addAttribute(
    Mage_Catalog_Model_Category::ENTITY,
    'icon',
    array(
        'group'         => 'General Information',
        'input'         => 'text',
        'type'          => 'text',
        'label'         => 'Icon',
        'visible'       => true,
        'required'      => false,
        'visible_on_front' => true,
        'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    )
);

$this->endSetup();
