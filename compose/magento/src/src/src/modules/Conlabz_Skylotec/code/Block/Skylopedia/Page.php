<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Skylopedia_Page extends Fishpig_Wordpress_Block_Page_View implements Conlabz_Skylotec_Block_Tabs_Interface
{
    /**
     *
     * @var string
     */
    protected $_template = 'wordpress/page/view.phtml';

    /**
     *
     * @var Fishpig_Wordpress_Model_Post
     */
    protected $_page;

    protected function _beforeToHtml()
    {
        if (!$category = Mage::registry('current_category')) {
            return;
        }
        if ($pageId = $category->getWpEntity()) {
            /* @var $res Conlabz_Wordpress_Model_Resource_Post */
            $res = Mage::getResourceModel('wordpress/post');
            $language = Mage::helper('conwp')->getLanguageCode();
            if ($transId = $res->hasTranslation($pageId, $language)) {
                $this->_page = Mage::getModel('wordpress/post')->load($transId);
            } else {
                $this->_page = Mage::getModel('wordpress/post')->load($pageId);
            }
        }
    }

    public function getPage()
    {
        return $this->_page;
    }

    public function setPage(Fishpig_Wordpress_Model_Post $page)
    {
        $this->_page = $page;
        return $this;
    }

    public function canDisplay()
    {
        return true;
    }
}
