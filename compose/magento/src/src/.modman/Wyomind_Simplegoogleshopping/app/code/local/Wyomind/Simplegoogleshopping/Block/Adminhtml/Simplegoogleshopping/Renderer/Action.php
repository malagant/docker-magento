<?php
/**
 * Copyright © 2018 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */
class Wyomind_Simplegoogleshopping_Block_Adminhtml_Simplegoogleshopping_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{

    public function render(Varien_Object $row)
    {
        $this->getColumn()->setActions(
            array(
                    array(
                        'url' => "javascript:updater.generate('".$this->getUrl(
                            '*/simplegoogleshopping/generate', 
                            array('simplegoogleshopping_id' => $row->getSimplegoogleshoppingId())
                        )."');",
                        'confirm' => Mage::helper('simplegoogleshopping')->__(
                            'Generate a data feed can take a while. '
                            . 'Are you sure you want to generate it now ?'
                        ),
                        'caption' => Mage::helper('simplegoogleshopping')->__('Generate'),
                    ),
                    array(
                        'url' => $this->getUrl(
                            '*/simplegoogleshopping/edit', 
                            array('id' => $row->getSimplegoogleshoppingId())
                        ),
                        'caption' => Mage::helper('simplegoogleshopping')->__('Edit'),
                    ),
                    array(
                        'url' => $this->getUrl(
                            '*/simplegoogleshopping/sample', 
                            array('simplegoogleshopping_id' => $row->getSimplegoogleshoppingId(), 'limit' => 10)
                        ),
                        'caption' => Mage::helper('simplegoogleshopping')->__('Preview') . " ("
                                    . Mage::getStoreConfig('simplegoogleshopping/system/preview') . " "
                                    . Mage::helper('simplegoogleshopping')->__('products') . ")",
                        'popup' => true
                    ),
                    array(
                        'url' => $this->getUrl(
                            '*/simplegoogleshopping/showReport', 
                            array('simplegoogleshopping_id' => $row->getSimplegoogleshoppingId())
                        ),
                        'caption' => Mage::helper('simplegoogleshopping')->__('Show report'),
                        'popup' => true
                    ),
                    array(
                        'url' => $this->getUrl(
                            '*/simplegoogleshopping/delete', 
                            array('id' => $row->getSimplegoogleshoppingId())
                        ),
                        'confirm' => Mage::helper('simplegoogleshopping')->__(
                            'Are you sure you want to delete this feed ?'
                        ),
                        'caption' => Mage::helper('simplegoogleshopping')->__('Delete'),
                    )
                )
        );
        return parent::render($row);
    }

}
