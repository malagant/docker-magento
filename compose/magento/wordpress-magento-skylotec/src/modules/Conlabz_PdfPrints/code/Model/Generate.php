<?php

use Dompdf\Dompdf;

class Conlabz_PdfPrints_Model_Generate extends Mage_Core_Model_Abstract
{
    /**
     * @var Mage_Catalog_Model_Product
     */
    private $_product;

    protected $_categoryTitleMap = array(
        'data_list' => 'Datasheets',
        'certificate' => 'Declarations of conformity'
    );

    public function getProduct()
    {
        return $this->_product;
    }

    /**
     * Generate PDF of prouduct Data list for specific product
     *
     * @param int $productId
     * @param int $storeId
     * @return bool
     */
    public function generateProductDataList($productId, $storeId = null)
    {
        try {
            $this->_product = Mage::getModel("catalog/product");
            if (null !== $storeId) {
                $this->_product->setStoreId($storeId);
            }
            $this->_product->load($productId);
            $_product = $this->getProduct();
            if ($_product->getTypeId() !== 'simple') {
                return false;
            }
            $html = Mage::app()->getLayout()->createBlock("pdfprints/productData")->setProduct($_product)->toHtml();
            $pdfoutput = $this->renderPdf($html);
            return $pdfoutput;
        } catch (Exception $e) {
            Mage::log($e->getMessage(), null, "pdfgenerate.log", true);
            return false;
        }
    }

    /**
     * Generates PDF Certificate for specific product
     *
     * @param int $productId
     * @param int $storeId
     * @return boolean
     */
    public function generateProductCertificate($productId, $storeId = null)
    {
        try {
            $this->_product = Mage::getModel("catalog/product");
            if (null !== $storeId) {
                $this->_product->setStoreId($storeId);
            }
            $this->_product->load($productId);
            $_product = $this->getProduct();
            if ($_product->getTypeId() !== 'simple') {
                return false;
            }

            if (!Mage::helper("pdfprints")->ifCertificateAvailable($_product)) {
                Mage::log("Certificate: One of parameters are missing", null, "pdfgenerate.log", true);
                return true;
            }

            $html = Mage::app()->getLayout()->createBlock("pdfprints/certificate")->setProduct($_product)->toHtml();
            $pdfoutput = $this->renderPdf($html);

            return $pdfoutput;
        } catch (Exception $e) {
            Mage::log($e->getMessage(), null, "pdfgenerate.log", true);
            return false;
        }
    }

    private function renderPdf($html)
    {
        try {
            $dompdf = new DOMPDF([
                'pdfBackend' => 'auto',
                'defaultMediaType' => 'print',
                'defaultPaperSize' => 'a4',
                'fontHeightRatio' => 1,
                'dpi' => 96,
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
            ]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('a4');
            $dompdf->render();
        } catch (Exception $e) {
            Mage::log("Certificate: DOMPDF ERROR: " . $e->getMessage(), null, "pdfgenerate.log", true);
        }
        return $dompdf->output();
    }

    protected function removeFileIfExists($filename, $type)
    {
        $path = Mage::helper('dstorage')->getFilePath($filename, $type);
        if (is_file($path) && is_writable($path)) {
            return @unlink($path);
        }
    }

    public function saveInfoBySku($sku, $type, array $additional = array())
    {
        $productId = Mage::getModel("catalog/product")->getIdBySku($sku);
        if (!$productId) {
            return sprintf('Product %s not found in magento database.', $sku);
        }
        $filename = "";
        if ($type == "data_list") {
            $filename = "product-info-" . $productId . ".pdf";
            $categoryTitle = 'Data sheets';
            $fileTitle = 'Data sheet';
        }
        if ($type == "certificate") {
            $filename = "certificate-" . $productId . ".pdf";
            $categoryTitle = 'Conformity declarations';
            $fileTitle = 'Conformity declaration';
        }

        $this->removeFileIfExists($filename, $type);

        $fileData = array(
            'file_category' => $type,
            'file_name' => $filename,
            'category_title' => $categoryTitle,
            'file_order' => 0,
            'file_title' => Mage::helper("dstorage")->__($fileTitle . " %s %s"),
            'product_sku' => $sku,
        );
        $fileData = array_merge($fileData, $additional);
        $id = $this->getFileId($sku, $type);

        Mage::getModel("dstorage/files")->setData($fileData)->setId($id)->save();
        return true;
    }


    /**
     *
     * @param string $productSku
     * @param string $type
     * @return void|int
     */
    private function getFileId($productSku, $type)
    {
        $ifExisted = Mage::getModel("dstorage/files")->getCollection()
            ->addFieldToFilter("product_sku", $productSku)
            ->addFieldToFilter("file_category", $type);
        if (sizeof($ifExisted) > 0) {
            return $ifExisted->getFirstItem()->getData("id");
        }
    }

    /**
     * @param $pdfoutput
     * @param $filename
     * @param $type
     * @return int
     */
    private function sendFile($pdfoutput, $filename, $type)
    {
        /** @var Conlabz_Download_Helper_Data $helper */
        $helper = Mage::helper('dstorage');
        $destination = $helper->getFilePath($filename, $type);

        if (!is_dir(dirname($destination))) {
            mkdir(dirname($destination), 0777, true);
        }

        return file_put_contents($destination, $pdfoutput);
    }
}
