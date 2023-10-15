<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
abstract class Conlabz_Skylotec_Block_News_Filter_Abstract extends Mage_Core_Block_Template
{
    /**
     * @var string
     */
    protected $_template = 'news/filter/default.phtml';

    /**
     * @var Fishpig_Wordpress_Model_Resource_Term_Collection
     */
    protected $_collection;

    /**
     * @var Mage_Core_Model_Url
     */
    protected $_coreUrl;

    /**
     * @return Fishpig_Wordpress_Model_Resource_Post_Collection
     */
    protected function _getPostCollection()
    {
        $parentBlock = $this->getParentBlock()->getParentBlock();
        if ($parentBlock instanceof Conlabz_Skylotec_Block_News_List) {
            return $parentBlock->getPostCollection();
        }
    }

    /**
     * @return Fishpig_Wordpress_Model_Resource_Term_Collection
     */
    protected function _getCollection()
    {
        if (null === $this->_collection) {
            $this->_collection = Mage::getResourceModel('wordpress/term_collection');
            /** @var Conlabz_Wordpress_Model_Resource_Translation $resource */
            $resource = Mage::getResourceSingleton('conwp/translation');
            $resource->joinTaxonomyLanguage($this->_collection->getSelect());
        }
        return $this->_collection;
    }

    /**
     * @return Mage_Core_Model_Url
     */
    protected function _getCoreUrl()
    {
        if (null === $this->_coreUrl) {
            $this->_coreUrl = Mage::getModel('core/url');
            $currentUrl = Mage::helper('core/url')->getCurrentUrl();
            $this->_coreUrl->parseUrl($currentUrl);
        }
        return $this->_coreUrl;
    }

    /**
     * @param Fishpig_Wordpress_Model_Term $term
     * @return string
     */
    public function getItemUrl(Fishpig_Wordpress_Model_Term $term)
    {
        $slug = $term->getSlug();

        /** @var Mage_Core_Model_Url $urlModel */
        $urlModel = $this->_getCoreUrl();

        $query = $urlModel->getQueryParams();

        if ($this->isActive($term) && isset($query[$this->_getFilterParam()])) {
            unset($query[$this->_getFilterParam()]);
        } else {
            $query[$this->_getFilterParam()] = $slug;
        }

        /** @var Mage_Catalog_Model_Category $category */
        $currentCategory = Mage::registry('current_category');
        if ($currentCategory) {
            $requestPath = $currentCategory->getRequestPath();
        } else {
            $requestPath = Mage::helper('core/url')->getCurrentUrl();
        }

        $url = $urlModel->getUrl('', array(
            '_direct' => $requestPath,
            '_query'  => $query
        ));

        return $url;
    }

    /**
     * @param Fishpig_Wordpress_Model_Term $term
     * @return boolean
     */
    public function isActive(Fishpig_Wordpress_Model_Term $term)
    {
        return $this->getRequest()->getParam($this->_getFilterParam()) === $term->getSlug();
    }

    /**
     * @return bool|Fishpig_Wordpress_Model_Term
     * @throws Exception
     */
    public function getActiveItem()
    {
        $activeSlug = $this->getRequest()->getParam($this->_getFilterParam());
        foreach ($this->getItems() as $term) {
            if ($term->getSlug() === $activeSlug) {
                return $term;
            }
        }
        return false;
    }

    protected function _beforeToHtml()
    {
        if ($filter = $this->getRequest()->getParam($this->_getFilterParam())) {
            $this->_filterCollection($this->_getPostCollection(), $filter);
        }
        return parent::_beforeToHtml();
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        if (!$this->getData('label')) {
            $this->setData('label', 'Filter');
        }
        return $this->getData('label');
    }

    /**
     * @param Fishpig_Wordpress_Model_Resource_Post_Collection $collection
     * @param $filter string
     * @return mixed
     */
    abstract protected function _filterCollection(Fishpig_Wordpress_Model_Resource_Post_Collection $collection, $filter);

    /**
     * @return Fishpig_Wordpress_Model_Resource_Term_Collection
     */
    abstract public function getItems();

    /**
     * @return string
     */
    abstract protected function _getFilterParam();
}
