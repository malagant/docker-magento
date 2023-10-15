<?php
$installer = $this;
$installer->startSetup();

$installer->run("

ALTER TABLE `{$this->getTable('dstorage/queue_files')}`
  DROP `filename`;

ALTER TABLE  `{$this->getTable('dstorage/queue_files')}` ADD  `file_id` INT NOT NULL

");
$installer->endSetup();
