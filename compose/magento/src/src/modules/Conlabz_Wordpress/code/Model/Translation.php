<?php

/**
 * @package Conlabz_Wordpress
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Wordpress_Model_Translation extends Fishpig_Wordpress_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('conwp/translation');
    }
}
