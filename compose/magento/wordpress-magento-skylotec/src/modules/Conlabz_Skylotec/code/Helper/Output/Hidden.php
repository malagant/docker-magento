<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Helper_Output_Hidden extends Conlabz_Skylotec_Helper_Output_Abstract
{
    /**
     * @inheritDoc
     */
    public function productAttribute(
        Mage_Catalog_Helper_Output $outputHelper,
        $outputHtml,
        $params
    ) {
        /** @var Mage_Catalog_Model_Product $product */
        $product = $params['product'];
        $attribute = $params['attribute'];
        if (!$product->isConfigurable()) {
            return $outputHtml;
        }
        $hiddenAttributes = $this->_getHiddenAttributes($product);
        if (in_array($attribute, $hiddenAttributes)) {
            return $this->__('Select a model...');
        }
        return $outputHtml;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return array
     */
    protected function _getHiddenAttributes(Mage_Catalog_Model_Product $product)
    {
        $hiddenAttributes = explode(PHP_EOL, $product->getHiddenAttributes());
        return $hiddenAttributes;
    }
}
