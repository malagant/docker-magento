<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_SetComponents extends Mage_Core_Block_Template
{
    /**
     * block template
     *
     * @var string
     */
    protected $_template = 'conlabz/set-components.phtml';
    
    /**
     *
     * @var array
     */
    protected $_setComponents = array();
    
    /**
     *
     * @return array
     */
    public function getSetComponents()
    {
        return $this->_setComponents;
    }
    
    /**
     *
     * @param Varien_Object $component
     * @return \Conlabz_Skylotec_Block_SetComponents
     */
    public function addSetComponent(Varien_Object $component)
    {
        $this->_setComponents[] = $component;
        return $this;
    }

    /**
     *
     * @param type $setComponents
     * @return \Conlabz_Skylotec_Block_SetComponents
     */
    public function setSetComponents($setComponents)
    {
        $this->_setComponents = $setComponents;
        return $this;
    }
    
    /**
     * check if there are set components
     *
     * @return boolean
     */
    public function hasSetComponents()
    {
        return !empty($this->_setComponents);
    }
}
