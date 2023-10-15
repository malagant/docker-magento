<?php

class Conlabz_Skylotec_AssetController extends Mage_Core_Controller_Front_Action
{
    /**
     * @var Mage_Core_Controller_Request_Http
     */
    protected $_request;

    public function preDispatch()
    {
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        parent::preDispatch();
        return $this;
    }

    public function indexAction()
    {
        if (!$this->isAuthorized()) {
            $this->getResponse()->setHttpResponseCode(401);
            return;
        }

        try {
            if (!($data = $this->getAsset())) {
                throw new Mage_Api_Exception('asset_not_readable', 'Asset data is not readable.');
            }
            $asset = Mage::getModel('skylotec/asset')->setData($data);
            $this->dump($data);
            $this->log($data);

            if (method_exists($asset, $asset['action'])) {
                call_user_func([$asset, $asset['action']]);
            } else {
                throw new Mage_Api_Exception('unknown_asset_action', 'Unknown asset action.');
            }
        } catch (\Throwable $exception) {
            $this->getResponse()->setHttpResponseCode(422);
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode([
                'error' => true,
                'message' => $exception->getMessage()
            ]));
            $this->log($exception->getMessage(), Zend_log::ERR, true);
            return;
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(['success' => true]));
    }

    public function relationAction()
    {
        $this->getResponse()->setHttpResponseCode(405);
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode([
            'error' => true,
            'message' => 'Deprecated API method.'
        ]));
    }

    /**
     * @return Mage_Core_Controller_Request_Http|Zend_Controller_Request_Http
     */
    protected function _getRequest()
    {
        if (!$this->_request) {
            $this->_request = Mage::app()->getRequest();
        }
        return $this->_request;
    }

    /**
     * @return bool
     */
    private function isAuthorized()
    {
        $request = $this->_getRequest();
        $user = $request->getHeader('iPIM-User');
        $pass = $request->getHeader('iPIM-Pass');
        return (
            $user === (string) Mage::getStoreConfig('ipim/auth/username') &&
            $pass === (string) Mage::getStoreConfig('ipim/auth/password')
        );
    }

    /**
     * @return mixed
     */
    public function getAsset()
    {
        $request = $this->_getRequest();
        $json = $request->getRawBody();
        return Mage::helper('core')->jsonDecode($json);
    }

    /**
     * @param string $message
     */
    private function log($message, $level = null, $forMonitoring = false)
    {
        $file = $forMonitoring ? 'asset-error.log' : 'asset-import.log';
        Mage::log($message, $level, $file, true);
    }

    protected function dump($data)
    {
        $json = Mage::helper('core')->jsonEncode($data);

        $target = implode(DS, [Mage::getBaseDir('var'), 'import', 'json', $data['kind']]);
        $file  = $target . DS . $data['id'].'.json';

        if (!is_dir(dirname($file))) {
            @mkdir(dirname($file), 0755, true);
        }
        file_put_contents($file, $json);
    }
}