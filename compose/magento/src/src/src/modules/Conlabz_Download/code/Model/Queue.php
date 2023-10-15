<?php

/**
 * @package Conlabz_Download
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Download_Model_Queue extends Mage_Core_Model_Abstract
{
    const STATE_PENDING = 'PENDING';
    const STATE_RUNNING = 'RUNNING';
    const STATE_SUCCESS = 'SUCCESS';
    const STATE_ERROR   = 'ERROR';

    const TEMPLATE_EMAIL = 'download_package';

    /**
     *
     * @var Conlabz_Download_Model_Resource_Queue_File_Collection
     */
    protected $_filesCollection;

    public function _construct()
    {
        parent::_construct();
        $this->_init('dstorage/queue');
    }

    /**
     *
     * @return Conlabz_Download_Model_Resource_Queue_File_Collection
     */
    public function getFilesCollection()
    {
        if (null === $this->_filesCollection) {
            $this->_filesCollection = Mage::getModel('dstorage/queue_file')->getCollection()
                ->addFieldToFilter('queue_id', $this->getId());
        }
        return $this->_filesCollection;
    }

    protected function _getPending()
    {
        $collection = Mage::getResourceModel('dstorage/queue_collection')
            ->addFieldToFilter('status', self::STATE_PENDING);
        return $collection;
    }

    /**
     * @return bool
     */
    protected function _isRunning()
    {
        return $this->getResource()->isRunning();
    }

    /**
     *
     * @param Conlabz_Download_Model_Queue_File $file
     * @return $this
     */
    public function addFile(Conlabz_Download_Model_Queue_File $file)
    {
        $file->setQueue($this);
        if (!$file->getId()) {
            $this->getFilesCollection()->addItem($file);
            $this->_hasDataChanges = true;
        }
        return $this;
    }

    public function process()
    {
        foreach ($this->_getPending() as $item) {
            if ($this->_processItem($item)) {
                $item->setStatus(self::STATE_SUCCESS);
            } else {
                $item->setStatus(self::STATE_ERROR);
            }
            $item->save();
        }
    }

    protected function _processItem(self $item)
    {
        $currentStore = Mage::app()->getStore()->getCode();
        $storeCode = Mage::app()->getStore($item->getStore())->getCode();
        Mage::app()->setCurrentStore($storeCode);
        Mage::getSingleton('core/translate')->init('frontend', true);
        $item->setStatus(self::STATE_RUNNING)->save();
        $senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');
        $senderName  = Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_STORE_STORE_NAME);
        $toEmail     = $item->getEmail();

        $locale = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE, $item->getStore());

        /** @var Mage_Core_Model_Email_Template $emailTemplate */
        $emailTemplate = Mage::getModel('core/email_template')->loadDefault('download_package', $locale);
        $emailTemplate->setSenderName($senderName);
        $emailTemplate->setSenderEmail($senderEmail);

        $vars = array(
            'download_url' => $this->_compress($item)
        );

        Mage::app()->setCurrentStore($currentStore);

        try {
            if (!$emailTemplate->send($toEmail, null, $vars)) {
                $item->setMessage('Unable to send mail');
                return false;
            }
            return true;
        } catch (Exception $e) {
            $item->setMessage($e->getMessage());
            return false;
        }
    }

    private function _compress(self $item)
    {
        $zip = new ZipArchive();
        $targetFile = $this->_getHelper()->getBasePath($this->_getPackageName($item->getId()));

        /** @var Conlabz_Download_Model_Resource_Files_Collection $files */
        $files = Mage::getResourceModel('dstorage/files_collection');
        $files->addQueueFilter($item);

        if ($zip->open($targetFile, ZIPARCHIVE::CREATE) === true) {
            /** @var Conlabz_Download_Model_Files $file */
            foreach ($files as $file) {
                if ($file->isVirtual()) {
                    if ($output = $this->_generate($file, $item->getStore())) {
                        $zip->addFromString(basename($file->getPath()), $output);
                    }
                } else {
                    $zip->addFile($file->getPath(), basename($file->getPath()));
                }
            }
            $zip->close();
            if (file_exists($targetFile)) {
                return $this->_getHelper()->getUrlByPath($targetFile);
            }
        }
        return false;
    }

    /**
     * @param Conlabz_Download_Model_Files $file
     * @param null|int $storeId
     * @return boolean
     */
    protected function _generate(Conlabz_Download_Model_Files $file, $storeId = null)
    {
        /** @var Conlabz_PdfPrints_Helper_Data $helper */
        $helper = Mage::helper('pdfprints');
        $productId = $helper->extractProductId($file->getFileName());

        /** @var Conlabz_PdfPrints_Model_Generate $generator */
        $generator = Mage::getSingleton('pdfprints/generate');
        if ($file->getFileCategory() === 'certificate') {
            return $generator->generateProductCertificate($productId, $storeId);
        }
        if ($file->getFileCategory() === 'data_list') {
            return $generator->generateProductDataList($productId, $storeId);
        }
    }

    /**
     * @return Conlabz_Download_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('dstorage');
    }

    /**
     * @param int $itemId
     * @return string
     */
    protected function _getPackageName($itemId)
    {
        return md5($itemId . uniqid()) . '.zip';
    }

    protected function _afterSave()
    {
        parent::_afterSave();
        foreach ($this->getFilesCollection() as $file) {
            $file->setQueueId($this->getId());
        }
        $this->getFilesCollection()->save();
    }
}
