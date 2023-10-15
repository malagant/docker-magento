<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Model_Product_Image extends Mage_Catalog_Model_Product_Image
{
    /**
     * @return string
     */
    public function getUrl()
    {
        $baseDir = Mage::getBaseDir('media');
        $modified = filemtime($this->_newFile);
        $path = str_replace($baseDir . DS, "", $this->_newFile);
        return Mage::getBaseUrl('media') . str_replace(DS, '/', $path) . '?' . $modified;
    }
}
