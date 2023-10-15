<?php

/**
 * @package Conlabz_Download
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Download_Model_Observer
{
    const DM_DOWNLOAD = 'DOWNLOAD';
    const PACKAGE_TTL = 604800; // 1 Week

    /**
     * Event: category_attribute_source_mode
     *
     * @param Varien_Event_Observer $observer
     */
    public function addCategoryDownloadType(Varien_Event_Observer $observer)
    {
        $dto = $observer->getEvent()->getDto();
        $options = $dto->getOptions();
        $options[] = array(
            'value' => self::DM_DOWNLOAD,
            'label' => 'Downloads'
        );
        $dto->setOptions($options);
    }

    /**
     * cleanup old download packages
     */
    public function cleanupPackages()
    {
        $globPattern = $this->_getHelper()->getBasePath('*.zip');
        foreach (glob($globPattern) as $file) {
            if ((time() - filemtime($file)) >= self::PACKAGE_TTL) {
                if (@unlink($file)) {
                    Mage::log('cleaned up file: '. $file);
                } else {
                    Mage::log('Unable to remove file: ' . $file);
                }
            }
        }
    }

    /**
     * @return Conlabz_Download_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('dstorage');
    }
}
