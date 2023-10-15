<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Model_Navigation_Page_Mage extends Zend_Navigation_Page
{
    /**
     *
     * @var Zend_Controller_Router_Rewrite
     */
    protected $_router;
    
    /**
     *
     * @var string
     */
    protected $_route = 'division';
    
    /**
     *
     * @var array
     */
    protected $_params;
    
    /**
     *
     * @var string
     */
    protected $_uri;
    
    public function getUri()
    {
        return $this->_uri;
    }

    public function setUri($uri)
    {
        $this->_uri = $uri;
        return $this;
    }
        
    public function getLabel()
    {
        $label = parent::getLabel();
        return Mage::helper('skylotec')->__($label);
    }
    
    /**
     *
     * @return
     */
    public function getRoute()
    {
        return $this->_route;
    }

    /**
     *
     * @param string $route
     * @return \Conlabz_Skylotec_Model_Navigation_Page_Mage
     */
    public function setRoute($route)
    {
        $this->_route = $route;
        return $this;
    }
        
    /**
     *
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->_params = $params;
        return $this;
    }
    
    /**
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }
    
    /**
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getParam($key, $default = null)
    {
        return isset($this->_params[$key])
            ? $this->_params[$key]
            : $default;
    }
        
    /**
     *
     * @return Zend_Controller_Router_Rewrite
     */
    public function getRouter()
    {
        if (null === $this->_router) {
            $this->_router = Zend_Controller_Front::getInstance()
                ->getRouter();
        }
        return $this->_router;
    }

    /**
     *
     * @param Zend_Controller_Router_Rewrite $router
     * @return Conlabz_Skylotec_Model_Navigation_Page_Mage
     */
    public function setRouter(Zend_Controller_Router_Rewrite $router)
    {
        $this->_router = $router;
        return $this;
    }
    
    public function isActive($recursive = false)
    {
        $request = Mage::app()->getRequest();
        $pathInfo = trim($request->getOriginalPathInfo(), '/');
        if ($uri = $this->getUri()) {
            $uri = trim($uri, '/');
            if ($uri === $pathInfo) {
                return true;
            }
            $parts = explode('/', $pathInfo);
            $uriPart = pathinfo($uri, PATHINFO_FILENAME);
            if ($uriPart === $parts[0]) {
                return true;
            }
        }
        
        $href = $this->getHref();
        $route = $this->getRouter()->getRoute($this->_route);
        $uri = $route->assemble($request->getParams());
        if ($href === Mage::getUrl($uri)) {
            return true;
        }
        
        return parent::isActive($recursive);
    }
    
    /**
     *
     * @return string
     */
    public function getHref()
    {
        if ($uri = $this->getUri()) {
            if ($uri === '#') {
                return $uri;
            }
            $extension = pathinfo($uri, PATHINFO_EXTENSION);
            $href = Mage::getUrl($uri);
            if (!empty($extension)) {
                $href = trim($href, '/');
            }
            return $href;
        }
        $route = $this->getRouter()->getRoute($this->_route);
        $uri = $route->assemble($this->getParams());
        return Mage::getUrl($uri);
    }
}
