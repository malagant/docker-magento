<?php
$this->startSetup();

$this->addAttribute(
    Mage_Catalog_Model_Category::ENTITY,
    'request_params',
    array(
        'group'         => 'Display Settings',
        'input'         => 'textarea',
        'type'          => 'text',
        'label'         => 'Request params',
        'visible'       => true,
        'required'      => false,
        'visible_on_front' => false,
        'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    )
);

$this->endSetup();
