<?php

/**
 * @package Conlabz_Download
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Download_Model_Resource_Queue_File extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('dstorage/queue_files', 'id');
    }
}
