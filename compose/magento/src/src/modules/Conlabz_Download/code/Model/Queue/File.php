<?php

/**
 * @package Conlabz_Download
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Download_Model_Queue_File extends Mage_Core_Model_Abstract
{
    /**
     *
     * @var Conlabz_Download_Model_Queue
     */
    protected $_queue;

    public function _construct()
    {
        parent::_construct();
        $this->_init('dstorage/queue_file');
    }

    /**
     *
     * @param Conlabz_Download_Model_Queue $queue
     * @return Conlabz_Download_Model_Queue_File
     */
    public function setQueue(Conlabz_Download_Model_Queue $queue)
    {
        $this->_queue = $queue;
        $this->setQueueId($queue->getId());
        return $this;
    }
}
