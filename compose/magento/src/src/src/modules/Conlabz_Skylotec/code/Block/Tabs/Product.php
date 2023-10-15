<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Tabs_Product extends Conlabz_Skylotec_Block_Tabs
{
    public function getTabs()
    {
        return array(
            new Varien_Object(array(
                'label' => 'Information',
                'href' => '#information',
                'is_active' => true
            )),
            new Varien_Object(array(
                'label' => 'Product details',
                'href' => '#product-details'
            )),
            new Varien_Object(array(
                'label' => 'Downloads',
                'href' => '#downloads'
            ))
        );
    }
}
