<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_News_Filter_Division extends Conlabz_Skylotec_Block_News_Filter_Abstract
{
    /**
     * @var Fishpig_Wordpress_Model_Resource_Term_Collection
     */
    protected $_divisions;

    /**
     * @inheritDoc
     */
    protected function _filterCollection(
        Fishpig_Wordpress_Model_Resource_Post_Collection $collection,
        $filter
    ) {
        return $collection->addTermFilter(
            $filter,
            $this->_getFilterParam()
        );
    }

    /**
     * @return Fishpig_Wordpress_Model_Resource_Term_Collection
     */
    public function getItems()
    {
        if (null === $this->_divisions) {
            $this->_divisions = Mage::getResourceModel('wordpress/term_collection');
            $this->_divisions->setOrderByName();
            $this->_divisions->addTaxonomyFilter($this->_getFilterParam());
        }
        return $this->_divisions;
    }

    /**
     * @inheritDoc
     */
    protected function _getFilterParam()
    {
        return 'division';
    }
}
