<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
abstract class Conlabz_Skylotec_Block_Wordpress_List_Abstract extends Fishpig_Wordpress_Block_Post_List_Wrapper_Abstract
{
    /**
     * filter for sport, industry, permanent systems ...
     *
     * @param Fishpig_Wordpress_Model_Resource_Post_Collection $collection
     * @return \Conlabz_Skylotec_Block_Wordpress_List_Abstract
     */
    protected function _addDivisionFilter(
        Fishpig_Wordpress_Model_Resource_Post_Collection $collection
    ) {
        if ($division = $this->getRequest()->getParam('division')) {
            $collection->addTermFilter($division, 'division');
        }
        return $this;
    }

    /**
     *
     * @return Fishpig_Wordpress_Model_Resource_Post_Collection
     */
    public function getPostCollection()
    {
        $collection = parent::getPostCollection();
        $this->_addDivisionFilter($collection);
        if ($pager = $this->getChild('pager')) {
            $pager->setCollection($collection);
        }
        return $collection;
    }

    public function getPagerHtml()
    {
        if ($pager = $this->getChild('pager')) {
            $pager->setCollection($this->getPostCollection());
            return $pager->toHtml();
        }
        return '';
    }
}
