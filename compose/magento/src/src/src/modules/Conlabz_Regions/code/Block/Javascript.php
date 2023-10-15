<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Regions_Block_Javascript extends Mage_Core_Block_Template
{
    /**
     * @var string
     */
    protected $_template = 'regions/javascript.phtml';

    /**
     * @return string
     */
    public function getCurrentStore()
    {
        return Mage::app()->getStore()->getCode();
    }
}
