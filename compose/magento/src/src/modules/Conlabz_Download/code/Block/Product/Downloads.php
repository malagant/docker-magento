<?php

/**
 * @package Conlabz_Download
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Download_Block_Product_Downloads extends Mage_Catalog_Block_Product_View
{
    protected $_template = '';

    public function getDownloads($grouped = true)
    {
        $product = $this->getProduct();
        $skus = array();
        if ($product->isConfigurable()) {
            $children = $product->getTypeInstance(true)->getUsedProducts(null, $product);
            foreach ($children as $child) {
                $skus[] = $child->getSku();
            }
        } else {
            $skus[] = $product->getSku();
        }
        $collection = Mage::getModel('dstorage/files')->getCollection()
            ->addFieldToFilter('product_sku', array(
                'in' => $skus
            ))
            ->setOrder('file_category');

        if ($grouped) {
            return $this->_groupDownloads($collection);
        }

        return $collection;
    }

    /**
     *
     * @param type $collection
     * @return array
     */
    protected function _groupDownloads($collection)
    {
        $categories = array();
        foreach ($collection as $download) {
            $categoryId = $download->getFileCategory();
            if (!isset($categories[$categoryId])) {
                $categories[$categoryId] = array(
                    'title' => $download->getCategoryTitle(),
                    'downloads' => array()
                );
            }
            $categories[$categoryId]['downloads'][] = $download;
        }

        return $categories;
    }



    public function getFiles($sku, $type)
    {

        $collection = Mage::getModel('dstorage/files')->getCollection()
            ->addFieldToFilter('product_sku', $sku)
            ->addFieldToFilter('file_category', $type);
        return $collection;
    }

    public function getDownloadableChilds($download)
    {
        $childsArray = array($download);
        if ($download->getFileCategory() == "data_list" || $download->getFileCategory() == "certificate") {
            if ($this->getProduct()->isConfigurable()) {
                $childProducts = Mage::getModel('catalog/product_type_configurable')
                    ->getUsedProducts(null, $this->getProduct());
                $childsArray = array();
                foreach ($childProducts as $childProduct) {
                    $childFiles = $this->getFiles($childProduct->getSku(), $download->getFileCategory());
                    foreach ($childFiles as $childFile) {
                        if ($childFile->getFileCategory() == "certificate") {
                            if (Mage::helper("pdfprints")->ifCertificateAvailable($childProduct)) {
                                $childsArray[] = $childFile;
                            } else {
                                $childFile->delete();
                            }
                        } else {
                            $childsArray[] = $childFile;
                        }
                    }
                }
            } else {
                if ($download->getFileCategory() == "certificate") {
                    if (!Mage::helper("pdfprints")->ifCertificateAvailable($this->getProduct())) {
                        $download->delete();
                        return array();
                    }
                }
            }
        }
        return $childsArray;
    }
}
