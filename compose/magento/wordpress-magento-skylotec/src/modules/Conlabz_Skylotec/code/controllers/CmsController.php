<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_CmsController extends Conlabz_Skylotec_Controller_Action_Abstract
{
    public function indexAction()
    {
        if (!$this->_initPage()) {
            return $this->norouteAction();
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _initPage()
    {
        $division = $this->getRequest()->getParam('division');
        $page = Mage::getModel('wordpress/post')
            ->getCollection()
            ->addTermFilter($division, 'division')
            ->addFieldToFilter('post_name', $this->getRequest()->getParam('category'))
            ->getFirstItem();
        if (!$page->getId()) {
            return false;
        }
        Mage::register('wordpress_page', $page);
        return $page;
    }
}
