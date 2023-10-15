<?php

/**
 * @package Conlabz_Wordpress
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Wordpress_Model_Category_Displaymode_Wppage extends Conlabz_Wordpress_Model_Category_Displaymode_Abstract
{
    /**
     *
     * @var Fishpig_Wordpress_Model_Post
     */
    protected $_page;

    /**
     *
     * @param Mage_Catalog_Model_Category $category
     * @param Mage_Core_Model_Layout $layout
     */
    public function prepare(
        Mage_Catalog_Model_Category $category,
        Mage_Core_Model_Layout $layout
    ) {
        $page = Mage::registry('wordpress_post');
        if (!$page) {
            $page = $this->getPage();
            Mage::register('wordpress_post', $page, true);
        }
        $update = $layout->getUpdate();
        $update->addHandle('catalog_category_view');
        $update->addHandle('wordpress_page_view');
        $update->addHandle('wordpress_page_view_' . $page->getId());
    }

    /**
     *
     * @param Mage_Core_Model_Layout $layout
     */
    public function prepareLayout(Mage_Core_Model_Layout $layout)
    {
        /* @var $root Mage_Page_Block_Html */
        $root = $layout->getBlock('root');
        $root->addBodyClass('wordpress-page-view');

        $page = $this->getPage();
        $template = $page->getMetaValue('_wp_page_template');
        if (empty($template)) {
            $template = 'default';
        }
        $root->addBodyClass('page-template-' . str_replace('.php', '', $template));

        /** @var Fishpig_Wordpress_Addon_WordPressSEO_Helper_Data $helper */
        $helper = Mage::helper('wp_addon_wordpressseo');
        $helper->processRouteWordPressPostView($page);
    }

    /**
     *
     * @param Mage_Catalog_Model_Category $category
     * @return bool
     */
    public function canDisplay(Mage_Catalog_Model_Category $category)
    {
        $pageId = $category->getWpEntity();
        $this->_page = Mage::getModel('wordpress/post')->load($pageId);
        
        /* @var $res Conlabz_Wordpress_Model_Resource_Post */
        $res = Mage::getResourceModel('wordpress/post');

        $language = Mage::helper('conwp')->getLanguageCode();
        if ($transId = $res->hasTranslation($this->_page->getId(), $language)) {
            $this->_page = Mage::getModel('wordpress/post')->load($transId);
        }
        return $this->_page->getId();
    }

    /**
     *
     * @return Fishpig_Wordpress_Model_Post
     */
    public function getPage()
    {
        return $this->_page;
    }
}
