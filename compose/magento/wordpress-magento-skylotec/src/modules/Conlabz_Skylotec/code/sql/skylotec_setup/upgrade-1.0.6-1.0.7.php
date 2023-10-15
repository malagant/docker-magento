<?php
/* @var $this Conlabz_Skylotec_Model_Resource_Setup */
$this->startSetup();

$this->updateAttribute('catalog_category', 'is_anchor', 'is_global', Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE);

$this->endSetup();
