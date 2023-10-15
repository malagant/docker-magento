<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_News_Filter_Category extends Conlabz_Skylotec_Block_News_Filter_Abstract
{
    protected $_categories;

    /**
     * @inheritDoc
     */
    protected function _filterCollection(
        Fishpig_Wordpress_Model_Resource_Post_Collection $collection,
        $filter
    ) {
        return $collection->addCategorySlugFilter($filter);
    }

    /**
     * @return Fishpig_Wordpress_Model_Resource_Term_Collection
     * @throws Exception
     */
    public function getItems()
    {
        if (null === $this->_categories) {
            $categories = $this->_getCollection();
            $categories->addTaxonomyFilter($this->_getFilterParam());
            $this->_categories = $categories;
        }
        return $this->_categories;
    }

    /**
     * @return string
     */
    protected function _getFilterParam()
    {
        return 'category';
    }
}
