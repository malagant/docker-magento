<?php
class Conlabz_Eshop_Helper_Data extends Mage_Core_Helper_Abstract
{
    const DEFAULT_COUNTRY = '1';

    public function getCountryCodes()
    {
        return array(
            "PL"=>"183",
            "PT"=>"188",
            "RO"=>"193",
            "SE"=>"201",
            "SI"=>"204",
            "SK"=>"206",
            "DE"=>"1",
            "AT"=>"5",
            "CH"=>"4",
            "IT"=>"3",
            "GB"=>"2",
            "BE"=>"28",
            "BG"=>"30",
            "CY" => "60",
            "CZ"=>"61",
            "DK"=>"64",
            "EE"=>"69",
            "ES"=>"7",
            "FI"=>"75",
            "FR"=>"6",
            "GR"=>"94",
            "HU"=>"105",

            "NL"=>"170",
            "LU"=>"139",
            "HR"=>"103",
            "BA"=>"25",
            "RS"=>"194",
            "TK"=>"227",
            "LV"=>"140",
            "LT"=>"138",
            "IE"=>"107",
            "IS"=>"114",
            "NO"=>"171",
            "US"=>"235",
            "CA"=>"44",
            "AU"=>"21",
            "AD"=>"8",
            "AE"=>"9"
        );
    }

    public function getCountryCode($country)
    {
        $countries = $this->getCountryCodes();
        if (isset($countries[$country])) {
            return $countries[$country];
        }
        return self::DEFAULT_COUNTRY;
    }
}
