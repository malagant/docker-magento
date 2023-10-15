<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Page_Html_Navigation extends Mage_Core_Block_Template
{
    protected $_template = 'page/html/navigation.phtml';
    
    protected function _getDefaults()
    {
        return array(
            'type' => 'Conlabz_Skylotec_Model_Navigation_Page_Mage',
            'route' => 'division'
        );
    }
    
    protected function setDefaults(array &$page)
    {
        $defaults = $this->_getDefaults();
        foreach ($defaults as $key => $value) {
            if (!isset($page[$key])) {
                $page[$key] = $value;
            }
        }
        if (isset($page['pages'])) {
            foreach ($page['pages'] as &$subPage) {
                $subPage = $this->setDefaults($subPage);
            }
        }
        return $page;
    }
    
    public function loadNavigation()
    {
        $navigationConfig = include Mage::getModuleDir('etc', 'Conlabz_Skylotec') . DS . 'navigation.php';
        
        foreach ($navigationConfig as &$page) {
            $page = $this->setDefaults($page);
        }
        
        $navigation = new Zend_Navigation($navigationConfig);
        return $navigation;
    }
    
    public function renderNavigation($maxDepth = null)
    {
        $helper = new Zend_View_Helper_Navigation_Menu();
        if (null !== $maxDepth) {
            $helper->setMaxDepth($maxDepth);
        }
        $helper->setView(new Zend_View());
        return $helper->render($this->loadNavigation());
    }
}
