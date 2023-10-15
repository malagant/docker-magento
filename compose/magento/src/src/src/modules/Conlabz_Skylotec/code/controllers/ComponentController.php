<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_ComponentController extends Mage_Core_Controller_Front_Action
{
    /**
     *
     */
    public function viewAction()
    {
        $sku = $this->getRequest()->getParam('sku');
        $productId = Mage::getModel('catalog/product')
            ->getIdBySku($sku);
        $product = Mage::getModel('catalog/product')->load($productId);
        Mage::register('product', $product);
        
        $this->loadLayout();
        $this->renderLayout();
    }
}
