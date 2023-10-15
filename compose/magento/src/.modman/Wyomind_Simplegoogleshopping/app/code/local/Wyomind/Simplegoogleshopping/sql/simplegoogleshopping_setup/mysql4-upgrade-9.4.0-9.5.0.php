<?php
/**
 * Copyright © 2018 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

$installer = $this;

$installer->startSetup();

$collection = Mage::getSingleton('simplegoogleshopping/simplegoogleshopping')->getCollection();
foreach ($collection as $feed) {
    $categories = json_decode($feed->getSimplegoogleshoppingCategories());
    $newCategories = array();
    foreach ($categories as $category) {
        $ids = explode("/",$category->line);
        $newCategories[end($ids)] = array('c' => $category->checked?"1":"0", 'm' => $category->mapping);
    }
    $feed->setSimplegoogleshoppingCategories(json_encode($newCategories));
}
$collection->save();

$installer->getConnection()->addColumn(
    $installer->getTable('simplegoogleshopping'), 'googleshopping_taxonomy', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 200,
        'nullable'  => true,
        'comment'   => 'Taxonomy file'
    )
);

$installer->endSetup();