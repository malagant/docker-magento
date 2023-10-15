<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Catalog_Product_View_Attributes_Icons extends Conlabz_Skylotec_Block_Catalog_Product_View_Attributes
{
    protected $_template = 'catalog/product/view/attributes/icons.phtml';
        
    public function getIcons($attrCode)
    {
        $product = $this->getProduct();
        return $this->helper('skylotec')
            ->getAttributeIcons($product, $attrCode);
    }
}
