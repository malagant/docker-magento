<?php

/**
 * @package Conlabz_GenticDesign
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_GenticDesign_Model_Observer
{
    /**
     *
     * @param Varien_Event_Observer $observer
     * @return \Conlabz_GenticDesign_Model_Observer
     */
    public function addHomePageLayoutHandle(Varien_Event_Observer $observer)
    {
        $layout = $observer->getEvent()->getLayout();
        $currentUrl = Mage::getUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true));
        if (false !== strpos($currentUrl, '?')) {
            $currentUrl = strstr(
                $currentUrl,
                '?',
                true
            );
        }
        if (Mage::getUrl('') === $currentUrl) {
            $layout->getUpdate()->addHandle('homepage');
        }
        
        return $this;
    }

    /**
     * Event: controller_action_layout_render_before
     *
     * @param Varien_Event_Observer $observer
     * @return \Conlabz_GenticDesign_Model_Observer
     */
    public function setCustomPageTemplate(Varien_Event_Observer $observer)
    {
        $layout = Mage::app()->getLayout();
        $page = Mage::registry('wordpress_page');
        if ($page instanceof Fishpig_Wordpress_Model_Post) {
            $template = $page->getMetaValue('_wp_page_template');
            if (!$template || $template === 'default') {
                return;
            }
            /* @var $helper Conlabz_GenticDesign_Helper_Data */
            $helper = Mage::helper('genticdesign');
            $pageBlock = $layout->getBlock('wp.page');
            if ($pageBlock) {
                $pageBlock->setTemplate(
                    $helper->getCustomPageTemplate($page)
                );
            }
        }
        return $this;
    }
    
    /**
     * Event: controller_action_layout_render_before
     *
     * @param Varien_Event_Observer $observer
     * @return \Conlabz_GenticDesign_Model_Observer
     */
    public function addPostTypeBodyClass(Varien_Event_Observer $observer)
    {
        $layout = Mage::app()->getLayout();
        foreach (array('wordpress_post', 'wordpress_post_type') as $registryKey) {
            if ($post = Mage::registry($registryKey)) {
                $rootBlock = $layout->getBlock('root');
                if ($rootBlock instanceof Mage_Page_Block_Html) {
                    $rootBlock->addBodyClass('post-type-' . $post->getPostType());
                }
            }
        }
        return $this;
    }
    
    /**
     *
     * @param Varien_Event_Observer $observer
     * @return \Conlabz_GenticDesign_Model_Observer
     */
    public function populatePageArrows(Varien_Event_Observer $observer)
    {
        $layout = Mage::app()->getLayout();
        $arrowLeft = $layout->getBlock('page.arrow.left');
        $arrowRight = $layout->getBlock('page.arrow.right');
        if (!$arrowLeft || !$arrowRight) {
            return;
        }
        foreach ($this->_getArrowCallbacks() as $callbackBlock) {
            list($condition, $callback) = $callbackBlock;
            if ($condition()) {
                $callback($arrowLeft, $arrowRight);
                return $this;
            }
        }
        
        return $this;
    }
    
    /**
     * Event: catalog_product_collection_load_before
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function filterLineplanCollection(Varien_Event_Observer $observer)
    {
        if (!Mage::helper('genticdesign')->isLineplanEnabled()) {
            return;
        }
        $event = $observer->getEvent();
        $collection = $event->getCollection();
        Mage::getSingleton('genticdesign/catalog_lineplan')
            ->filterProductCollection($collection);
    }
    
    /**
     * Event: catalog_controller_product_view
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterLineplanProduct(Varien_Event_Observer $observer)
    {
        if (!Mage::helper('genticdesign')->isLineplanEnabled()) {
            return;
        }
        $event = $observer->getEvent();
        $product = $event->getProduct();

        /* @var $lineplan Conlabz_GenticDesign_Model_Catalog_Lineplan */
        $lineplan = Mage::getSingleton('genticdesign/catalog_lineplan');

        if (!$lineplan->canShowProduct($product)) {
            /* @var $viewHelper Mage_Catalog_Helper_Product_View */
            $viewHelper = Mage::helper('catalog/product_view');
            throw new Exception(
                'Product is not available at the moment',
                $viewHelper->ERR_NO_PRODUCT_LOADED
            );
        }
    }
    
    protected function _getArrowCallbacks()
    {
        $callbacks = array(
            'post' => array(
                function () {
                    if ($post = Mage::registry('wordpress_post')) {
                        return ($post->getPostType() === 'post');
                    }
                    return false;
                },
                function (
                    Conlabz_GenticDesign_Block_Page_Html_Arrow $arrowLeft,
                    Conlabz_GenticDesign_Block_Page_Html_Arrow $arrowRight
                ) {
                    /* @var $post Fishpig_Wordpress_Model_Post */
                    $post = Mage::registry('wordpress_post');
                    
                    if ($nextPost = $post->getNextPost()) {
                        $arrowLeft->setLink($nextPost->getPermalink());
                        $arrowLeft->setLabel($nextPost->getPostTitle());
                    } else {
                        $arrowLeft->setLabel(Mage::helper('genticdesign')->__('News overview'));
                        $arrowLeft->setLink(Mage::getUrl('news'));
                    }
                    
                    if ($previousPost = $post->getPreviousPost()) {
                        $arrowRight->setLabel($previousPost->getPostTitle());
                        $arrowRight->setLink($previousPost->getPermalink());
                    } else {
                        $arrowRight->setLabel(Mage::helper('genticdesign')->__('News overview'));
                        $arrowRight->setLink(Mage::getUrl('news'));
                    }
                }
            ),
            'product' => array(
                function () {
                    return (bool) Mage::registry('current_product');
                },
                function (
                    Conlabz_GenticDesign_Block_Page_Html_Arrow $arrowLeft,
                    Conlabz_GenticDesign_Block_Page_Html_Arrow $arrowRight
                ) {
                    $productArrow = Mage::getModel('genticdesign/arrow_product');
                    $categoryArrow = Mage::getModel('genticdesign/arrow_category');
                                                            
                    if (!$nextUrl = $productArrow->getNextUrl()) {
                        $nextUrl = $categoryArrow->getNextUrl();
                        $nextLabel = $categoryArrow->getNextLabel();
                    } else {
                        $nextLabel = $productArrow->getNextLabel();
                    }
                    
                    if (!$prevUrl = $productArrow->getPrevUrl()) {
                        $prevUrl = $categoryArrow->getPrevUrl();
                        $prevLabel = $categoryArrow->getPrevLabel();
                    } else {
                        $prevLabel = $productArrow->getPrevLabel();
                    }
                    
                    if (!$prevUrl) {
                        $prevUrl = $categoryArrow->getCurrentUrl();
                        $prevLabel = $categoryArrow->getCurrentLabel();
                    }
                    
                    $arrowLeft->setLink($prevUrl);
                    $arrowLeft->setLabel($prevLabel);
                    $arrowRight->setLink($nextUrl);
                    $arrowRight->setLabel($nextLabel);
                }
            ),
            'category' => array(
                function () {
                    return (bool) Mage::registry('current_category');
                },
                function (
                    Conlabz_GenticDesign_Block_Page_Html_Arrow $arrowLeft,
                    Conlabz_GenticDesign_Block_Page_Html_Arrow $arrowRight
                ) {
                    $categoryArrow = Mage::getModel('genticdesign/arrow_category');

                    if (!$prevUrl = $categoryArrow->getPrevUrl()) {
                        $prevUrl = Mage::getUrl('');
                        $prevLabel = Mage::helper('genticdesign')->__('Homepage');
                    } else {
                        $prevLabel = $categoryArrow->getPrevLabel();
                    }
                    
                    if (!$nextUrl = $categoryArrow->getNextUrl()) {
                        $nextUrl = Mage::helper('genticdesign')->getStoreFinderUrl();
                        $nextLabel = Mage::helper('genticdesign')->__('Storefinder');
                    } else {
                        $nextLabel = $categoryArrow->getNextLabel();
                    }
                    
                    $arrowLeft->setLink($prevUrl);
                    $arrowLeft->setLabel($prevLabel);
                    $arrowRight->setLink($nextUrl);
                    $arrowRight->setLabel($nextLabel);
                }
            ),
            'default' => array(
                function () {
                    return true;
                },
                function (
                    Conlabz_GenticDesign_Block_Page_Html_Arrow $arrowLeft,
                    Conlabz_GenticDesign_Block_Page_Html_Arrow $arrowRight
                ) {
                    $defaultArrow = Mage::getModel('genticdesign/arrow_default');
                    $arrowLeft->setLabel(Mage::helper('genticdesign')->__($defaultArrow->getPrevLabel()));
                    $arrowLeft->setLink($defaultArrow->getPrevUrl());
                    $arrowRight->setLabel(Mage::helper('genticdesign')->__($defaultArrow->getNextLabel()));
                    $arrowRight->setLink($defaultArrow->getNextUrl());
                }
            )
        );
        return $callbacks;
    }
    
    /**
     *
     * @param Varien_Event_Observer $observer
     * @return \Conlabz_GenticDesign_Model_Observer
     */
    public function injectTopmenuLinks(Varien_Event_Observer $observer)
    {
        /* @var $menu Varien_Data_Tree_Node */
        $menu = $observer->getEvent()->getMenu();
        
        $data = array(
            'name'  => 'Storefinder',
            'id'    => 'storefinder',
            'is_active' => false,
            'url'   => Mage::getUrl('haendler')
        );
        
        $mainMenuId = (int) Mage::getConfig()->getNode('frontend/menu/main_menu_id');
        $storeFinderItem = new Varien_Data_Tree_Node($data, 'id', $menu->getTree());
        $collectionNode = $menu->getChildren()->searchById('category-node-' . $mainMenuId);
        if ($collectionNode) {
            $collectionNode->addChild($storeFinderItem);
        }
        
        return $this;
    }
}
