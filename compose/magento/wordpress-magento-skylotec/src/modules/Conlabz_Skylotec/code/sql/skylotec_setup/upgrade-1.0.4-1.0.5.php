<?php
/* @var $this Conlabz_Skylotec_Model_Resource_Setup */
$this->startSetup();

$this->addAttribute(
    Mage_Catalog_Model_Category::ENTITY,
    'custom_meta_title',
    array(
        'type'                       => 'varchar',
        'label'                      => 'Meta Title',
        'input'                      => 'text',
        'required'                   => false,
        'visible'                    => true,
        'visible_on_front'           => true,
        'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'group'                      => 'General Information',
    )
);

$this->addAttribute(
    Mage_Catalog_Model_Category::ENTITY,
    'custom_meta_description',
    array(
        'type'                       => 'text',
        'label'                      => 'Magento Meta Description',
        'input'                      => 'textarea',
        'required'                   => false,
        'visible'                    => true,
        'visible_on_front'           => true,
        'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'group'                      => 'General Information',
    )
);

$this->endSetup();
