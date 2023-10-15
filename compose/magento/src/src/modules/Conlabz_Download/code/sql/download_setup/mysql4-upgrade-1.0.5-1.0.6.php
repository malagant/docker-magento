<?php
$installer = $this;
$installer->startSetup();

$installer->run("

ALTER TABLE `{$this->getTable('dstorage/queue')}` ADD  `message` TEXT NOT NULL

");
$installer->endSetup();
