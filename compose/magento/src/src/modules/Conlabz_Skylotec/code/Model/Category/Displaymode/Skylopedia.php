<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Model_Category_Displaymode_Skylopedia extends Conlabz_Wordpress_Model_Category_Displaymode_Abstract
{
    const DM_SKYLOPEDIA = 'SKYLOPEDIA';

    public function prepare(
        Mage_Catalog_Model_Category $category,
        Mage_Core_Model_Layout $layout
    ) {
    }

    public function canDisplay(Mage_Catalog_Model_Category $category)
    {
        return true;
    }

    public function prepareLayout(Mage_Core_Model_Layout $layout)
    {
    }
}
