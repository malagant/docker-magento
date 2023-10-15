<?php

/**
 * @package Coad_Pjax
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Coad_Pjax_Model_Pjax
{
    const PJAX_CONTAINER_PREFIX = 'pjax-';
    const PJAX_QUERY_PARAM = '_pjax';
    const FRAGMENTS_HEADER = 'X-Fragments';
       
    /**
     * when one of these request headers are set, we just retrieve the content
     * 
     * @var array
     */
    protected $_headers = array(
        'X-PJAX',
        'X-Fragment-Loader'
    );
    
    /**
     * these blocks will be wrapped with a <div id="pjax-[container-blockname]"></div>
     * 
     * @var array
     */
    protected $_containers = array(
        'content',
        'mana.catalog.leftnav',
        'teaser'        
    );
    
    /**
     * add header
     * 
     * @param string $header
     * @return \Coad_Pjax_Model_Observer
     */
    public function addHeader($header)
    {
        if (!in_array($header, $this->_headers)) {
            $this->_headers[] = $header;
        }
        return $this;
    }
    
    /**
     * add pjax container
     * 
     * @param string $container
     * @return \Coad_Pjax_Model_Observer
     */
    public function addContainer($container)
    {
        if (!in_array($container, $this->_containers)) {
            $this->_containers[] = $container;
        }
        return $this;
    }
    
    /**
     * 
     * @param Mage_Core_Model_Layout $layout
     * @param Mage_Core_Controller_Request_Http $request
     * @return \Coad_Pjax_Model_Pjax
     */
    public function determinePjaxLayout(
        Mage_Core_Model_Layout $layout, 
        Mage_Core_Controller_Request_Http $request
    )
    {
        if ($this->_isPjaxRequest($request)) {
            $url = $this->_getUrl();
            if ($fragments = $request->getHeader(self::FRAGMENTS_HEADER)) {
                $fragments = explode(',', $fragments);
                $response = array();
                foreach ($fragments as $blockName) {
                    $block = $layout->getBlock($blockName);
                    if ($block) {
                        $response[$blockName] = $block->toHtml();
                    }
                }
                $layout->getBlock('root')
                    ->setTemplate('coad/pjax/json.phtml')
                    ->setResponseData($response);
            } else {
                $pjaxContainer = $this->_getHelper()->extractBlockFromContainer(
                    $request->getQuery(self::PJAX_QUERY_PARAM)
                );            
                $url->setQueryParam(self::PJAX_QUERY_PARAM, null);
                $layout->getBlock('root')
                    ->setPjaxContainer($pjaxContainer)
                    ->setTemplate('coad/pjax/content.phtml');
            }
        }
        return $this;
    }
    
    /**
     * 
     * @return Coad_Pjax_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('pjax');
    }
    
    /**
     * 
     * @return string
     */
    public function getPjaxContainerName($name)
    {
        return self::PJAX_CONTAINER_PREFIX . $name;
    }
    
    /**
     * 
     * @param string $blockname
     * @return string
     */
    public function getBlockWrapper($blockname)
    {
        return '<div data-pjax-block="' . $blockname . '">%s</div>';
    }
    
    /**
     * 
     * @return Mage_Core_Model_Url
     */
    protected function _getUrl()
    {
        return Mage::getSingleton('core/url');
    }
    
    /**
     * 
     * @param Mage_Core_Controller_Request_Http $request
     * @return boolean
     */
    protected function _isPjaxRequest(Mage_Core_Controller_Request_Http $request)
    {
        foreach ($this->_headers as $header) {
            if ($request->getHeader($header)) {
                return true;
            }
        }
        return false;
    }
    
    public function getHeaders()
    {
        return $this->_headers;
    }

    public function getContainers()
    {
        return $this->_containers;
    }

    public function setHeaders($headers)
    {
        $this->_headers = $headers;
        return $this;
    }

    public function setContainers($containers)
    {
        $this->_containers = $containers;
        return $this;
    }
}
