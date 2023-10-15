<?php
class Conlabz_Eshop_Model_Config_Languages
{
    
    public function toOptionArray()
    {
        
        return array(
                   array("value" => 1, "label" => Mage::helper("eshop")->__("German")),
                   array("value" => 2, "label" => Mage::helper("eshop")->__("English")),
                   array("value" => 4, "label" => Mage::helper("eshop")->__("Italian")),
                   array("value" => 5, "label" => Mage::helper("eshop")->__("French")),
               );
    }
}
