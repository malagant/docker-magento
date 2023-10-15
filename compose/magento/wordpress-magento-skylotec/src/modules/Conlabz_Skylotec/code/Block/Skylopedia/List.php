<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Skylopedia_List extends Conlabz_Skylotec_Block_Wordpress_List_Abstract
{
    protected $_template = 'skylopedia/list.phtml';
    
    protected $_postCollection;

    /**
     *
     * @return Fishpig_Wordpress_Model_Post[]
     */
    public function getPostCollection()
    {
        if (null === $this->_postCollection) {
            $collection = parent::getPostCollection();
            $collection->addPostTypeFilter('skylopedia');
            $this->_postCollection = $collection;
        }
        return $this->_postCollection;
    }

    public function getCategories(Fishpig_Wordpress_Model_Post $post, $commaSeparated = true)
    {
        /** @var Fishpig_Wordpress_Model_Resource_Term_Collection $collection */
        $collection = Mage::getResourceModel('wordpress/term_collection');
        $collection->addTaxonomyFilter('skylopedia');
        $collection->addPostIdFilter($post->getId());
        $categories = [];
        foreach ($collection as $category) {
            $categories[] = $this->__($category->getName());
        }
        if ($commaSeparated) {
            return implode(', ', $categories);
        }
        return $categories;
    }
    
    public function canDisplay()
    {
        return $this->getPostCollection()->count();
    }
}
