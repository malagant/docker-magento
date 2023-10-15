<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_SocialMedia_Block_Meta extends Mage_Core_Block_Abstract
{
    protected $_types = array();
    
    public function addType($type)
    {
        $this->_types[] = $type;
        return $this;
    }
    
    public function getTypes()
    {
        return $this->_types;
    }
    
    public function _toHtml()
    {
        $html = '';
        foreach ($this->getTypes() as $type) {
            $block = $this->getLayout()->getBlockSingleton($type);
            $html .= $block->toHtml();
        }
        return $html;
    }
}
