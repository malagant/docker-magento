<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
abstract class Conlabz_Skylotec_Controller_Action_Abstract extends Mage_Core_Controller_Front_Action
{
    public function addActionLayoutHandles()
    {
        $req = $this->getRequest();
        $update = $this->getLayout()->getUpdate();
        $update->addHandle('division_' . $req->getControllerName());
        parent::addActionLayoutHandles();
        foreach ($this->_getDivisionHandles() as $handle) {
            $update->addHandle($handle);
        }
    }
    
    /**
     *
     * @return string
     */
    protected function _getDivisionHandles()
    {
        $req = $this->getRequest();
        $divisionHandles = array();
        $handleParts = array(
            'division',
            $req->getControllerName(),
            $req->getActionName()
        );
        $divisionHandles[] = implode('_', $handleParts);
        
        $divisionParts = array(
            $req->getParam('division'),
            $req->getControllerName(),
            $req->getActionName()
        );
        $divisionHandles[] = implode('_', $divisionParts);
        
        if ($category = $req->getParam('category')) {
            $divisionHandles[] = implode('_', array(
                'division',
                $req->getControllerName(),
                'category'
            ));
            $categoryParts = array(
                'division',
                $req->getControllerName(),
                preg_replace('/[^a-z0-9_]/i', '_', $category)
            );
            $divisionHandles[] = implode('_', $categoryParts);
        }
        
        return $divisionHandles;
    }
    
    /**
     * default indexAction
     */
    public function indexAction()
    {
        $this->loadLayout();
        #var_dump($this->getRequest()->getParams(), $this->getLayout()->getUpdate()->getHandles());
        $this->renderLayout();
    }
}
