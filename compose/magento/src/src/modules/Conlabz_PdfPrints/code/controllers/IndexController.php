<?php

class Conlabz_PdfPrints_IndexController extends Mage_Core_Controller_Front_Action
{
    public function datalistAction()
    {
        $filename = $this->getRequest()->getParam("f");
        $productId = str_replace("product-info-", "", $filename);
        $productId = str_replace(".pdf", "", $productId);

        if ($file = Mage::getModel("pdfprints/generate")->generateProductDataList($productId)) {
            return $this->_sendPdf($file, $filename);
        }
    }

    public function certificateAction()
    {
        $filename = $this->getRequest()->getParam("f");
        $productId = str_replace("certificate-", "", $filename);
        $productId = str_replace(".pdf", "", $productId);

        if ($file = Mage::getModel("pdfprints/generate")->generateProductCertificate($productId)) {
            return $this->_sendPdf($file, $filename);
        }
    }

    protected function _sendPdf($output, $filename)
    {
        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Content-type', 'application/force-download')
            ->setHeader('Content-Disposition', 'attachment' . '; filename=' . $filename);
        $this->getResponse()->setBody($output);
    }

    /**
     * @return Conlabz_Download_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('dstorage');
    }
}
