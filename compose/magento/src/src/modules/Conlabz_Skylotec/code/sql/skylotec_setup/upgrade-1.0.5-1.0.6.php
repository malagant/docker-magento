<?php
/* @var $this Conlabz_Skylotec_Model_Resource_Setup */
$this->startSetup();

$this->addAttribute(
    Mage_Catalog_Model_Category::ENTITY,
    'internet_only',
    array(
        'type'                       => 'text',
        'label'                      => 'Internet only',
        'input'                      => 'textarea',
        'required'                   => false,
        'visible'                    => true,
        'visible_on_front'           => true,
        'is_wysiwyg_enabled'         => true,
        'is_html_allowed_on_front'   => true,
        'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'group'                      => 'General Information',
    )
);

$this->endSetup();
