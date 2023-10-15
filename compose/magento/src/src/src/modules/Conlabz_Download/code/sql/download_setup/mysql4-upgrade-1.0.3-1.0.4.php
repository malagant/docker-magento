<?php
$installer = $this;
$installer->startSetup();

$installer->run("

CREATE TABLE IF NOT EXISTS `{$this->getTable('downloads_queue')}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `result_key` varchar(500) DEFAULT NULL,
  `store` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `{$this->getTable('downloads_queue_files')}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `queue_id` int(11) unsigned DEFAULT NULL,
  `filename` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `queue_id` (`queue_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

ALTER TABLE `{$this->getTable('downloads_queue_files')}`
  ADD CONSTRAINT `{$this->getTable('downloads_queue_files')}_ibfk_1` FOREIGN KEY (`queue_id`) REFERENCES `{$this->getTable('downloads_queue')}` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

");

$installer->endSetup();
