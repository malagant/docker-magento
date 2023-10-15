<?php
$installer = $this;
$installer->startSetup();

$installer->run("
    ALTER TABLE {$this->getTable('conlabz_files_storage')} ADD `export_key` VARCHAR(20) NULL AFTER `product_category_id`, ADD INDEX (`export_key`) ;
");
