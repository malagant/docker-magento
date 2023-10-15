<?php
$installer = $this;
$installer->startSetup();

$installer->run("
    ALTER TABLE {$this->getTable('conlabz_files_storage')} ADD `category_title` VARCHAR(200) NULL AFTER `file_category`;
");
