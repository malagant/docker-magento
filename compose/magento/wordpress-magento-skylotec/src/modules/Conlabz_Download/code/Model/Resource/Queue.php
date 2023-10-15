<?php

/**
 * @package Conlabz_Download
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Download_Model_Resource_Queue extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('dstorage/queue', 'id');
    }

    /**
     *
     * @param Conlabz_Download_Model_Queue_File $file
     * @return \Conlabz_Download_Model_Resource_Queue
     */
    public function addFile(Conlabz_Download_Model_Queue_File $file)
    {
        $this->getFiles()->addItem($file);
        return $this;
    }

    /**
     * @return bool
     */
    public function isRunning()
    {
        $select = $this->_getReadAdapter()->select();
        $select->from($this->getMainTable())
            ->columns('id')
            ->where('status = ?', Conlabz_Download_Model_Queue::STATE_RUNNING)
            ->limit(1);
        return (bool) $this->_getReadAdapter()->fetchOne($select);
    }
}
