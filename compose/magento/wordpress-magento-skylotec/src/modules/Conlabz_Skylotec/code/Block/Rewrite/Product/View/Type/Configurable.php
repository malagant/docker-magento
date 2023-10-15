<?php
/**
 * @package
 * @author     David Pommer (conlabz GmbH) <david.pommer@conlabz.de>
 */

class Conlabz_Skylotec_Block_Rewrite_Product_View_Type_Configurable extends Mage_Catalog_Block_Product_View_Type_Configurable
{
    public function getJsonConfig()
    {
        $config = Mage::helper('core')->jsonDecode(parent::getJsonConfig());
        $attributes = isset($config['attributes']) ? $config['attributes'] : [];
        if (!empty($attributes)) {
            foreach ($attributes as $id => $attribute) {
                $options = $attribute['options'];
                if (count($options) > 1) {
                    usort($options, function ($a, $b) {
                        return strnatcmp($a['label'], $b['label']);
                    });
                    $config['attributes'][$id]['options'] = $options;
                }
            }
        }

        return Mage::helper('core')->jsonEncode($config);
    }
}
