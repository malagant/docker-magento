<?php

class Conlabz_Eshop_Model_Mysql4_Ecart extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('eshop/ecart', 'id');
    }
}
