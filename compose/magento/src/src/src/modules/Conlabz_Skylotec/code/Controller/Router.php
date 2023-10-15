<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard
{
    /**
     *
     * @var Zend_Controller_Router_Rewrite
     */
    protected $_router;
    
    public function initRouter(Varien_Event_Observer $observer)
    {
        $this->initRoutes();
    }
    
    /**
     *
     * @return Zend_Controller_Router_Rewrite
     */
    private function initRoutes()
    {
        if (null === $this->_router) {
            $routes = include Mage::getModuleDir('etc', 'Conlabz_Skylotec') . DS . 'routes.php';
            $router = Zend_Controller_Front::getInstance()->getRouter();
            foreach ($routes as $routeName => $specs) {
                $route = new Zend_Controller_Router_Route(
                    $specs['route'],
                    $specs['defaults'],
                    $specs['reqs']
                );
                $router->addRoute($routeName, $route);
            }
            $this->_router = $router;
        }
        return $this->_router;
    }

    /**
     *
     * @param Zend_Controller_Request_Http $request
     * @return boolean
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        $routes = $this->initRoutes()->getRoutes();
        $pathInfo = trim($request->getPathInfo(), '/');
        
        foreach ($routes as $routeName => $route) {
            if (false !== ($routeMatch = $route->match($pathInfo))) {
                $controller = $routeMatch['controller'];
                $action     = $routeMatch['action'];
                
                $controllerClassName = $this->_validateControllerClassName(
                    'Conlabz_Skylotec',
                    $controller
                );
                if (!$controllerClassName) {
                    return false;
                }
                
                $front = $this->getFront();
                $controllerInstance = Mage::getControllerInstance(
                    $controllerClassName,
                    $request,
                    $front->getResponse()
                );
                
                foreach ($routeMatch as $key => $value) {
                    $request->setParam($key, $value);
                }
                
                $request->setRouteName($routeName);
                $request->setModuleName('skylotec');
                $request->setControllerName($controller);
                $request->setActionName($action);
                $request->setDispatched(true);
                $controllerInstance->dispatch($action);
                
                return true;
            }
        }
        return false;
    }
}
