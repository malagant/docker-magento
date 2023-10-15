<?php

class Conlabz_Download_Model_Connect extends Mage_Core_Model_Abstract
{
    /**
     *
     * @param array $files
     * @param Varien_Object ]null $customerData
     * @return Mage_Core_Model_Abstract
     */
    public function addToQueue(array $files, Varien_Object $customerData = null)
    {
        if (empty($files)) {
            Mage::throwException(Mage::helper('dstorage')->__('Please select at least one file for download'));
        }

        return $this->_insert($this->_getFiles($files), $customerData);
    }

    /**
     * @param Mage_Core_Model_Resource_Db_Collection_Abstract $files
     * @param Varien_Object|null $customerData
     * @return Mage_Core_Model_Abstract
     * @throws Exception
     */
    protected function _insert($files, Varien_Object $customerData = null)
    {
        /** @var Conlabz_Download_Model_Queue $queue */
        $queue = Mage::getModel('dstorage/queue');
        $queue->setEmail($customerData->getEmail());
        $queue->setStatus(Conlabz_Download_Model_Queue::STATE_PENDING);
        $queue->setStore(Mage::app()->getStore()->getId());
        foreach ($files as $file) {
            /** @var Conlabz_Download_Model_Queue_File $queueFile */
            $queueFile = Mage::getModel('dstorage/queue_file');
            $queueFile->setFileId($file->getId());
            $queue->addFile($queueFile);
        }
        return $queue->save();
    }

    /**
     *
     * @param array $files
     * @return array
     */
    private function _getFiles(array $files)
    {
        $collection = Mage::getModel('dstorage/files')->getCollection();
        $collection->addFieldToSelect('id');
        $collection->addFieldToFilter('id', array(
            'in' => $files
        ));
        return $collection;
    }
}
