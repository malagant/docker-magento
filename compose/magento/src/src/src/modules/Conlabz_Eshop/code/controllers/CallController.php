<?php
class Conlabz_Eshop_CallController extends Mage_Core_Controller_Front_Action
{
    
    public function callAction()
    {
        
        $api = Mage::getModel('eshop/api')->buildRequestBody();
    }
}
