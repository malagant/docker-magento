<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Model_SetComponents
{
    const DELIMITER = ';';
    
    protected $_components;
    
    /**
     *
     * @param string $component
     * @return array
     */
    public function parseComponent($component, $hydrateObject = true)
    {
        $parts = (array) explode('x', $component, 2);
        if (count($parts) >= 2) {
            list($qty, $skuWithName) = $parts;
            list($sku, $name) = explode(' ', trim($skuWithName), 2);
            $result = array(
                'qty' => $qty,
                'sku' => $sku,
                'product_name' => $name,
                'url' => Mage::getUrl('sku/' . $sku),
                'ajax_url' => Mage::getUrl('sky/component/view/sku/' . $sku)
            );
            if ($hydrateObject) {
                return new Varien_Object($result);
            }
            return $result;
        }
        return null;
    }
    
    /**
     *
     * @param string $components
     * @return array
     */
    public function getComponentsByString($components)
    {
        if (null === $this->_components) {
            foreach (explode(static::DELIMITER, $components) as $component) {
                $component = trim($component);
                if ($component !== '') {
                    $parsedComponent = $this->parseComponent($component);
                    $this->_components[$parsedComponent->getSku()] = $parsedComponent;
                }
            }
            $resourceModel = Mage::getResourceSingleton('skylotec/product');
            $skuPairs = $resourceModel->getParentSkusByChildSkus(array_keys($this->_components));
                        
            foreach ($skuPairs as $skuPair) {
                $component = isset($this->_components[$skuPair['sku']])
                    ? $this->_components[$skuPair['sku']]
                    : null;
                if (null !== $component) {
                    $component->setParentSku($skuPair['parent_sku']);
                    $component->setUrl(Mage::getUrl('sku/' . $skuPair['parent_sku']));
                }
            }
        }
        return $this->_components;
    }
}
