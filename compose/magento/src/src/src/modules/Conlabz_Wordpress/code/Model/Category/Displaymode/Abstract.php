<?php

/**
 * @package Conlabz_Wordpress
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
abstract class Conlabz_Wordpress_Model_Category_Displaymode_Abstract
{
    abstract public function prepare(
        Mage_Catalog_Model_Category $category,
        Mage_Core_Model_Layout $layout
    );

    abstract public function canDisplay(Mage_Catalog_Model_Category $category);
    
    public function prepareLayout(Mage_Core_Model_Layout $layout)
    {
    }
}
