<?php
class Conlabz_Eshop_Model_Mysql4_Ecart_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('eshop/ecart');
    }
}
