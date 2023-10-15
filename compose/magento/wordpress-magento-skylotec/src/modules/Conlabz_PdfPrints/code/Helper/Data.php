<?php

class Conlabz_PdfPrints_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getCertificateInstance($code)
    {

        $instances = array(
            "CE0075" => "CTC, France (CE0075)",
            "CE0082" => "APAVE SUDEUROPE SAS, France (CE0082)",
            "CE0120" => "SGS UNITED KINGDOM LIMITED, United Kingdom (CE0120)",
            "CE0121" => "DGUV, Germany (CE0121)",
            "CE0123" => "TÜV SÜD PRODUCT SERVICE GMBH, Germany (CE0123)",
            "CE0158" => "DEKRA EXAM GMBH, Germany (CE0158)",
            "CE0299" => "DGUV FACHAUSSCHUSS PSA, Germany (CE0299)",
            "CE0321" => "SATRA, United Kingdom (CE0321)",
            "CE0333" => "AFNOR, France (CE0333)",
            "CE0497" => "CSI SPA, Italy (CE0497)",
            "CE0511" => "ALLGEMEINE UNFALLVERSICHERUNGSANSTALT, Austria (CE0511)",
            "CE0555" => "HOHENSTEIN LABORATORIES GMBH & CO. KG, Germany (CE0555)",
            "CE2008" => "DOLOMITICERT S.C.A.R.L., Italy (CE2008)",
            "CE1080" => "MATERIALPRÜFUNGSANSTALT UNI STUTTGART, Germany (CE1080)",
            "CE1015" => "STROJIRENSKY ZKUSEBNI USTAV S.P., Czech Republic (CE1015)",
            "CE0408" => "TÜV Österreich",
            "CE1976" => "PZT GMBH, Germany (CE1976)"
        );

        $codes = explode(";", $code);

        $names = array();
        foreach ($codes as $code) {
            if (isset($instances[$code])) {
                $names[] = $instances[$code];
            } else {
                $names[] = $code;
            }
        }
        return implode("; ", $names);
    }

    public function ifCertificateAvailable($_product)
    {

        if (!$_product->getNorm() ||
            !$_product->getBmpNummer() ||
            !$_product->getBmpZertifizierungsstelle() ||
            !$_product->getBmpDatum()
        ) {
            return false;
        }
        return true;
    }

    public function extractProductId($filename)
    {
        $filename = pathinfo($filename, PATHINFO_FILENAME);
        $filename = str_replace('product-info-', '', $filename);
        $filename = str_replace('certificate-', '', $filename);
        $productId = $filename;
        return $productId;
    }
}
