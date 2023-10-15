<?php

/**
 * @package Coad_Pjax 
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Coad_Pjax_Block_Javascript
    extends Mage_Core_Block_Template
{
    /**
     * template file
     * 
     * @var string
     */
    protected $_template = 'coad/pjax/javascript.phtml';
    
    /**
     * retrieve default pjax options
     * 
     * @return array
     */
    public function getOptions()
    {
        return array(
            'timeout'        => 2000,   //ajax timeout in milliseconds after which a full refresh is forced
            'push'           => true,   //use pushState to add a browser history entry upon navigation
            'replace'        => false,  //replace URL without adding browser history entry
            'maxCacheLength' => 20,     //maximum cache size for previous container contents
            'scrollTo'       => 0,      //vertical position to scroll to after navigation
            'type'           => 'GET',  //see $.ajax
            'dataType'       => 'html', //see $.ajax
            'fragment'       => 'body', //CSS selector for the fragment to extract from ajax response'
        );
    }
    
    /**
     * retrieve options as json string
     * 
     * @return string json
     */
    public function getOptionsJson()
    {
        return json_encode($this->getOptions());
    }
    
    /**
     * 
     * @return string
     */
    public function getPjaxContainerName($name)
    {
        return $this->_getPjax()->getPjaxContainerName($name);
    }
    
    /**
     * 
     * @param string $name
     * @return string
     */
    public function getContainerSelector($name)
    {
        return '#' . $this->getPjaxContainerName($name);
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
