<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_News_List extends Conlabz_Skylotec_Block_Wordpress_List_Abstract
{
    protected $_template = 'news/list.phtml';

    protected $_postCollection;
    
    protected $_divisionCollection;
    
    public function getPostCollection()
    {
        if (null === $this->_postCollection) {
            $collection = parent::getPostCollection();
            $collection->addPostTypeFilter('post');
            $this->_addDivisionFilter($collection);
            $collection->addMetaFieldToSelect('subtitle');
            if ($archive = $this->getRequest()->getParam('archive')) {
                $this->addArchiveFilter($collection, $archive);
            }
            $this->_postCollection = $collection;
        }

        return $this->_postCollection;
    }

    /**
     * @param Fishpig_Wordpress_Model_Post $post
     * @return array
     */
    public function getDivisions(Fishpig_Wordpress_Model_Post $post)
    {
        /** @var Fishpig_Wordpress_Model_Resource_Term_Collection $collection */
        $collection = Mage::getResourceModel('wordpress/term_collection');
        $collection->addTaxonomyFilter('division');
        $collection->addPostIdFilter($post->getId());
        $divisions = array();
        foreach ($collection as $division) {
            $divisions[] = $this->__($division->getName());
        }
        return $divisions;
    }

    /**
     *
     * @param Fishpig_Wordpress_Model_Resource_Collection_Abstract $collection
     * @param string $archive
     */
    protected function addArchiveFilter(
        Fishpig_Wordpress_Model_Resource_Collection_Abstract $collection,
        $archive
    ) {
        if (false !== strpos($archive, '-') && strlen($archive) === 7) {
            $archive = new DateTime($archive . '-01');
            $from = $archive->format('Y-m-d');
            $to   = $archive->format('Y-m-t');
            $collection->addFieldToFilter('post_date_gmt', array(
                'to' => $to
            ));
            $collection->addFieldToFilter('post_date_gmt', array(
                'from' => $from
            ));
        }
    }

    public function canDisplay()
    {
        return true;
    }
}
