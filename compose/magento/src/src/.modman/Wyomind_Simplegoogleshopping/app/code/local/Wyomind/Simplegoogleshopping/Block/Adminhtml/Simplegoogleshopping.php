<?php
/**
 * Copyright © 2018 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

class Wyomind_Simplegoogleshopping_Block_Adminhtml_Simplegoogleshopping extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        // Controller place
        $this->_controller = 'adminhtml_simplegoogleshopping';
        $this->_blockGroup = 'simplegoogleshopping';
        // Admin header text
        $this->_headerText = 'Google Shopping';
        // New feed button text
        $this->_addButtonLabel = $this->__('Create a new data feed');

        parent::__construct();
    }
}