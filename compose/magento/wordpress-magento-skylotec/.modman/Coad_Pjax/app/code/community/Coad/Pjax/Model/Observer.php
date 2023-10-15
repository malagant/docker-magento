<?php

/**
 * @package Coad_Pjax
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Coad_Pjax_Model_Observer
{
    /**
     * Event: controller_action_layout_generate_blocks_after
     * 
     * @param Varien_Event_Observer $observer
     * @return \Coad_Pjax_Model_Observer
     */
    public function pjaxContent(Varien_Event_Observer $observer)
    {
        $event   = $observer->getEvent();
        $layout  = $event->getLayout();
        $request = $event->getAction()->getRequest();
        
        $pjax = $this->_getPjax();
        $pjax->determinePjaxLayout($layout, $request);
        
        return $this;
    }
    
    /**
     * 
     * @param Varien_Event_Observer $observer
     * @return \Coad_Pjax_Model_Observer
     */
    public function wrapPjaxContainer(Varien_Event_Observer $observer)
    {
        $block = $observer->getBlock();
        $pjax = $this->_getPjax();
        if (!in_array($block->getNameInLayout(), $pjax->getContainers())) {
            return $this;
        }
        $transport = $observer->getTransport();
        $html = sprintf(
            $pjax->getBlockWrapper($block->getNameInLayout()),
            $transport->getHtml()
        );
        $transport->setHtml($html);        
        return $this;
    }
    
    /**
     * 
     * @return Coad_Pjax_Model_Pjax
     */
    protected function _getPjax()
    {
        return Mage::getSingleton('pjax/pjax');
    }
}
