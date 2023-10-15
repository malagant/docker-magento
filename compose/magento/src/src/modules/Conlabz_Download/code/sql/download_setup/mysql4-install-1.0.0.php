<?php
$installer = $this;
$installer->startSetup();

$installer->run("

CREATE TABLE {$this->getTable('conlabz_files_storage')} (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_category` varchar(200) DEFAULT NULL,
  `file_name` varchar(500) DEFAULT NULL,
  `file_order` varchar(100) NOT NULL DEFAULT '0',
  `file_title` varchar(500) DEFAULT NULL,
  `product_sku` varchar(500) DEFAULT NULL,
  `product_title` varchar(500) DEFAULT NULL,
  `product_category` varchar(500) DEFAULT NULL,
  `product_category_id` int(11) DEFAULT NULL,
  `update_data` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO {$this->getTable('conlabz_files_storage')} (`id`, `file_category`, `file_name`, `file_order`, `file_title`, `product_sku`, `product_title`, `product_category`, `product_category_id`, `update_data`)
VALUES
	(1,'user_manual','Beispiel-AT-Steuer.pdf','0','Beispiel-AT-Steuer','A-027','MILAN 2.0','Abseilgerät',1,'2013-09-12 10:47:30'),
	(2,'user_manual','order2013-09-06_14-53-22.pdf','0','order2013-09-06_14-53-22','A-028','MILAN 3.0','Category 1',2,'2013-09-12 10:53:27'),
	(3,'user_manual','order2013-09-09_10-48-06.pdf','0','order2013-09-09_10-48-06','A-028','MILAN 3.0','Category 1',2,'2013-09-12 10:53:35'),
	(4,'user_manual','order2013-09-09_10-50-11.pdf','0','order2013-09-09_10-50-11','A-029','MILAN 4.0','Category 1',2,'2013-09-12 10:53:40'),
	(5,'user_manual','order2013-09-09_10-53-12.pdf','0','order2013-09-09_10-53-12','A-030','MILAN 5.0','Category 1',2,'2013-09-12 10:53:55'),
	(6,'user_manual','order2013-09-09_10-54-40.pdf','0','order2013-09-09_10-54-40','A-030','MILAN 5.0','Category 1',2,'2013-09-12 10:54:01'),
	(7,'user_manual','order2013-09-09_11-03-37.pdf','0','order2013-09-09_11-03-37','A-030','MILAN 5.0','Category 2',3,'2013-09-12 10:54:06'),
	(8,'product_image','1368545134_2b.jpg','0','1368545134_2b','A-028','MILAN 3.0','Category 2',3,'2013-09-12 10:57:48'),
	(9,'product_image','1369845430_125363.png','0','1369845430_125363','A-028','MILAN 3.0','Category 2',3,'2013-09-12 10:57:59'),
	(10,'product_image','1369845452_125487.png','0','1369845452_125487','A-029','MILAN 4.0','Category 2',3,'2013-09-12 10:58:03'),
	(11,'product_image','1369845459_125527.png','0','1369845459_125527','A-029','MILAN 4.0','Category 3',4,'2013-09-12 10:58:08'),
	(12,'product_image','1369845479_125639.png','0','1369845479_125639','A-030','MILAN 5.0','Category 3',4,'2013-09-12 10:58:12'),
	(13,'product_image','1369845494_125723.png','0','1369845494_125723','A-030','MILAN 5.0','Category 3',4,'2013-09-12 10:58:16'),
	(14,'product_image','1369845498_125747.png','0','1369845498_125747','A-030','MILAN 5.0','Category 3',4,'2013-09-12 10:58:20'),
	(15,'product_image','1369845498_125751.png','0','1369845498_125751','A-027','MILAN 2.0','Category 3',4,'2013-09-12 10:58:24'),
	(16,'product_image','1369845506_125795.png','0','1369845506_125795','A-027','MILAN 2.0','Category 3',4,'2013-09-12 10:58:28'),
	(17,'certificate','1376648988-frachtkosten_2013.xlsx','0','1376648988-frachtkosten_2013','A-028','MILAN 3.0','Category 4',5,'2013-09-12 10:58:36'),
	(18,'certificate','CleverReach Documentation German.docx','0','CleverReach Documentation German','A-028','MILAN 3.0','Abseilgerät',1,'2013-09-12 10:58:45'),
	(19,'certificate','CodeReview.doc','0','CodeReview','A-027','MILAN 2.0','Abseilgerät',1,'2013-09-12 10:58:49'),
	(20,'certificate','Description for attributes.odt','0','Description for attributes','A-029','MILAN 4.0','Abseilgerät',1,'2013-09-12 10:58:53'),
	(21,'certificate','ebay_import_manual.docx','0','ebay_import_manual','A-030','MILAN 5.0','Abseilgerät',1,'2013-09-12 10:58:56');



");

$installer->endSetup();
