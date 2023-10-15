<?php

/**
 * @package Conlabz_Wordpress
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Wordpress_Model_Observer extends Fishpig_Wordpress_Model_Observer
{
    const XML_PATH_DM_HANDLE_MAP = 'global/catalog/category/display_mode/handle_map';
    const XML_PATH_DM_MODEL_MAP  = 'global/catalog/category/display_mode/model_map';

    /**
     *
     * @param string $displayMode
     * @return array
     */
    protected function _getHandlesForDisplayMode($displayMode)
    {
        $configHandleMap = Mage::getConfig()->getNode(self::XML_PATH_DM_HANDLE_MAP)->asArray();
        return isset($configHandleMap[$displayMode])
            ? array_keys($configHandleMap[$displayMode])
            : array();
    }

    /**
     *
     * @param string $displayMode
     * @return Conlabz_Wordpress_Model_Category_Displaymode_Abstract|false
     */
    protected function _getModelForDisplayMode($displayMode)
    {
        $configModelMap = Mage::getConfig()->getNode(self::XML_PATH_DM_MODEL_MAP)->asArray();
        return isset($configModelMap[$displayMode])
            ? (string) $configModelMap[$displayMode]
            : false;
    }

    /**
     * Event: core_collection_abstract_load_before
     *
     * @param Varien_Event_Observer $observer
     */
    public function joinPostCollectionLanguage(Varien_Event_Observer $observer)
    {
        if (!$this->_getHelper()->isEnabled()) {
            return;
        }
        $event = $observer->getEvent();
        /* @var $collection Mage_Core_Model_Resource_Db_Collection_Abstract */
        $collection = $event->getCollection();

        if (!$this->_getHelper()->canFilterCollection($collection)) {
            return;
        }

        $select = $collection->getSelect();

        Mage::getResourceSingleton('conwp/translation')->joinLanguage(
            $select
        );
    }

    /**
     * Event: catalog_controller_category_init_after
     *
     * @param Varien_Event_Observer $observer
     */
    public function prepareCategoryPage(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $action = $event->getAction();
        $pageId = $action->getRequest()->getParam('page_id');
        $isPreview = $action->getRequest()->getParam('preview');
        $fullActionName = $action->getFullActionName();

        $category = $this->_getCategory();

        if ($pageId && $isPreview) {
            $wpEntity = $pageId;
            $displayMode = Conlabz_Wordpress_Model_Category::DM_WP_PAGE;
        } elseif ($fullActionName === 'catalog_category_view') {
            $displayMode = $category->getDisplayMode();
            $wpEntity = $category->getData('wp_entity');
        } else {
            return;
        }

        $layout = Mage::app()->getLayout();
        if ($handles = $this->_getHandlesForDisplayMode($displayMode)) {
            $update = $layout->getUpdate();
            foreach ($handles as $handle) {
                $update->addHandle($handle);
                $update->addHandle($handle . '_' . $wpEntity);
            }
            $update->removeHandle('homepage');
            $update->removeHandle('catalog_category_default');
        }

        if (($displayModel = $this->_getDisplayModel($displayMode))) {
            $displayModel->prepare($category, $layout);
        }
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    protected function _getCategory()
    {
        $category = Mage::registry('current_category');
        if (!$category) {
            return Mage::getModel('catalog/category');
        }
        return $category;
    }

    /**
     * Event: controller_action_layout_generate_blocks_after
     *
     * @param Varien_Event_Observer $observer
     */
    public function prepareCategoryPageLayout(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $action = $event->getAction();
        $layout = $event->getLayout();
        if ($action->getFullActionName() !== 'catalog_category_view') {
            return;
        }
        $category = $this->_getCategory();
        $this->_extractCategoryRequestParams($category);
        $displayMode = $category->getDisplayMode();
        if ($displayModel = $this->_getDisplayModel($displayMode)) {
            if (method_exists($displayModel, 'getPage')) {
                Mage::dispatchEvent('wordpress_render_layout_before', array('object' => $displayModel->getPage(), 'action' => $action));
            }
            $displayModel->prepareLayout($layout);
        }
    }

    protected function _extractCategoryRequestParams(Mage_Catalog_Model_Category $category)
    {
        $params = explode("\n", $category->getRequestParams());
        $request = Mage::app()->getRequest();
        foreach ($params as $param) {
            if (!trim($param)) {
                continue;
            }
            list ($key, $value) = explode('=', $param);
            $request->setParam($key, $value);
        }
    }
    
    /**
     *
     * @param string $displayMode
     * @return mixed
     */
    protected function _getDisplayModel($displayMode)
    {
        if ($class = $this->_getModelForDisplayMode($displayMode)) {
            /* @var $model Conlabz_Wordpress_Model_Category_Displaymode_Abstract */
            $model = Mage::getSingleton($class);
            return $model;
        }
        return false;
    }

    /**
     * Event: catalog_controller_category_init_after
     *
     * @param Varien_Event_Observer $observer
     * @throws Mage_Core_Exception
     */
    public function checkDisplayMode(Varien_Event_Observer $observer)
    {
        $category = $observer->getEvent()->getCategory();
        if ($displayModel = $this->_getDisplayModel($category->getDisplayMode())) {
            if (!$displayModel->canDisplay($category)) {
                throw new Mage_Core_Exception(
                    sprintf('Can not display category %s', $category->getName())
                );
            }
        }
    }
    
    /**
     *
     * @param Varien_Event_Observer $observer
     */
    public function addCategoryAttributes(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $select = $event->getSelect();
        $select->columns(array(
            'display_mode',
            'wp_entity',
            'custom_url',
            'request_params'
        ));
    }

    /**
     *
     * @param Varien_Event_Observer $observer
     */
    public function addSocialMediaMetaData(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $block = $event->getBlock();

        Mage::getSingleton('conwp/socialmedia')
            ->addSocialMediaMetaData($block, $event->getPrefix());
    }

    /**
     *
     * @return Conlabz_Wordpress_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('conwp');
    }


    /**
     * Event: controller_front_send_response_before
     *
     * @param Varien_Event_Observer $observer
     */
    public function wpPageRedirect(Varien_Event_Observer $observer)
    {
        $action = $observer->getFront();
        $category = $this->_getCategory();

        if ($category->getDisplayMode() === Conlabz_Wordpress_Model_Category::DM_WP_PAGE_LINK) {
            $page = Mage::getModel('wordpress/post')->load($category->getData('wp_entity'));
            $action->getResponse()->setRedirect($page->getUrl());
            return;
        }
    }

    /**
     * Event: controller_front_send_response_before
     *
     * @param Varien_Event_Observer $observer
     */
    public function injectWordPressContentObserver(Varien_Event_Observer $observer)
    {
        $category = $this->_getCategory();

        if ((int) $category->getId() && $category->getDisplayMode() !== Conlabz_Wordpress_Model_Category::DM_WP_PAGE) {
            return $this;
        }

        return parent::injectWordPressContentObserver($observer);
    }
}
