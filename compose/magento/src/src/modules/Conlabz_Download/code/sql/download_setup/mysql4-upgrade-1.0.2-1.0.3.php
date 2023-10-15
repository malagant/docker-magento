<?php
$installer = $this;
$installer->startSetup();

$installer->run("
    ALTER TABLE {$this->getTable('conlabz_files_storage')} ADD `division` VARCHAR(50) NULL,  ADD INDEX (`division`)
");
