<?php
/**
 * Copyright © 2018 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */
$installer = $this;

$installer->startSetup();

$collection = Mage::getSingleton('simplegoogleshopping/simplegoogleshopping')->getCollection();
foreach ($collection as $feed) {
    $pattern = $feed->getSimplegoogleshoppingXmlitempattern();
    $search = array('$myPattern=null', '$myPattern= null', '$myPattern =null', '$myPattern = null');
    $feed->setSimplegoogleshoppingXmlitempattern(str_replace($search, '$this->skip()', $pattern));
    
}
$collection->save();

$installer->endSetup();