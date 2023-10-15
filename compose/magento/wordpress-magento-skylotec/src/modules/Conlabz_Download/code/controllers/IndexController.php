<?php

class Conlabz_Download_IndexController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function saveAction()
    {
        $files = explode(',', $this->getRequest()->getPost('files'));
        $response = array(
            'success' => false,
            'location' => $this->_getRefererUrl()
        );

        try {
            if ($this->getRequest()->getPost('email')) {
                $customerData = new Varien_Object(array(
                    'email' =>  $this->getRequest()->getPost('email')
                ));
            } else {
                throw new Exception(
                    $this->_getHelper()->__('Please provide an email address.')
                );
            }
            Mage::getModel('dstorage/connect')->addToQueue($files, $customerData);
            $response['message'] = $this->_getHelper()->__("Your request was added to queue, we will inform you via e-mail when it will be ready.");
            $response['success'] = true;
        } catch (Exception $e) {
            Mage::logException($e);
            $response['message'] = $e->getMessage();
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    /**
     * @return Conlabz_Download_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('dstorage');
    }

    /**
     * @return Mage_Core_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('core/session');
    }
}
