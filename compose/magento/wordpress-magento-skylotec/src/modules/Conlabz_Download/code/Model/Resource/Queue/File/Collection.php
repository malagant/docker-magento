<?php

/**
 * @package Conlabz_Download
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Download_Model_Resource_Queue_File_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('dstorage/queue_file');
    }
}
