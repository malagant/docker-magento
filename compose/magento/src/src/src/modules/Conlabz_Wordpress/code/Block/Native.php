<?php

/**
 * @package Conlabz_Wordpress
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Wordpress_Block_Native extends Mage_Core_Block_Abstract
{
    protected function _construct()
    {
        require_once Mage::getStoreConfig('wordpress/integration/path') . '/wp-load.php';
    }
}
