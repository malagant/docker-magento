<?php
$installer = $this;
$installer->startSetup();

$installer->run("

	CREATE TABLE {$this->getTable('arendicom_cart')} (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `quote_id` varchar(200) DEFAULT NULL,
	  `start_time` varchar(50) DEFAULT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

");

$installer->endSetup();
