<?php
class Conlabz_PdfPrints_Block_Certificate extends Mage_Core_Block_Template
{
    
    public function _prepareLayout()
    {
        
        $this->setTemplate("pdfprints/output/certificate.phtml");
        parent::_prepareLayout();
    }
    public function getNorm()
    {
        
        if ($this->getProduct()->getAttributeText('norm')) {
            return implode(", ", (array) $this->getProduct()->getAttributeText('norm'));
        }
    }
    public function getInternatNorm()
    {
        
        if ($this->getProduct()->getAttributeText('internat_norm')) {
            return implode(", ", (array) $this->getProduct()->getAttributeText('internat_norm'));
        }
    }
    public function getBmpZertifizierungsstelle()
    {
        
        return $this->getProduct()->getAttributeText('bmp_zertifizierungsstelle');
    }
    public function getBmpDatum()
    {
        
        $date = $this->getProduct()->getBmpDatum();
        if ($date) {
            $date = explode(" ", $date);
            return $date[0];
        }
    }
}
