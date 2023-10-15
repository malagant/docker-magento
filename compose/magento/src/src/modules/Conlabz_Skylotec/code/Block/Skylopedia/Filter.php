<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Skylopedia_Filter extends Mage_Core_Block_Template
{
    protected $_template = 'skylopedia/filter.phtml';

    /**
     *
     * @var Fishpig_Wordpress_Model_Resource_Term_Collection
     */
    protected $_taxonomies;

    protected $_requestFilters;

    public function getPostCollection()
    {
        if (!$listBlock = $this->getLayout()->getBlock('wordpress_post_list')) {
            return array();
        }
        return $listBlock->getPostCollection();
    }

    protected function _getRequestFilters()
    {
        if (null === $this->_requestFilters) {
            $requestFilters = $this->getRequest()->getParam('category', '');
            $requestFilters = array_filter(explode(',', $requestFilters));
            $validatedFilters = array();
            foreach ($this->_getTaxonomies() as $taxonomy) {
                if (in_array($taxonomy->getSlug(), $requestFilters)) {
                    $validatedFilters[] = $taxonomy->getSlug();
                }
            }
            $this->_requestFilters = $validatedFilters;
        }
        return $this->_requestFilters;
    }

    /**
     *
     * @return Fishpig_Wordpress_Model_Resource_Term_Collection
     */
    protected function _getTaxonomies()
    {
        if (null === $this->_taxonomies) {
            $this->_taxonomies = Mage::getResourceModel('wordpress/term_collection')
                ->addTaxonomyFilter('skylopedia');
        }
        return $this->_taxonomies;
    }

    /**
     *
     * @return \Varien_Object[]
     */
    public function getFilters()
    {
        $taxonomies = $this->_getTaxonomies();
        $filters = array();
        foreach ($taxonomies as $filter) {
            $filters[] = new Varien_Object(array(
                'label' => $filter->getName(),
                'url' => $this->_getFilterUrl($filter),
                'is_active' => $this->_isActive($filter)
            ));
        }
        $this->_filterCollection();
        return $filters;
    }

    protected function _filterCollection()
    {
        $requestFilters = $this->_getRequestFilters();
        if (count($requestFilters)) {
            $this->getPostCollection()->addTermFilter($requestFilters, 'skylopedia');
        }
        return $this;
    }

    /**
     *
     * @param Fishpig_Wordpress_Model_Term $filter
     * @return bool
     */
    protected function _isActive(Fishpig_Wordpress_Model_Term $filter)
    {
        return in_array($filter->getSlug(), $this->_getRequestFilters());
    }

    /**
     *
     * @param Fishpig_Wordpress_Model_Term $filter
     * @return string
     */
    protected function _getFilterUrl(Fishpig_Wordpress_Model_Term $filter)
    {
        $requestFilters = $this->_getRequestFilters();
        $query = array();
        if ($this->_isActive($filter)) {
            $query = array_diff($requestFilters, array($filter->getSlug()));
        } else {
            $query = array_merge($requestFilters, array($filter->getSlug()));
        }
        $urlParams = array(
            '_use_rewrite' => true,
            '_query' => array(
                'category' => implode(',', $query)
            )
        );
        if (!count($query)) {
            $urlParams['_query'] = array();
        }
        return Mage::getUrl('*/*/*', $urlParams);
    }
}
