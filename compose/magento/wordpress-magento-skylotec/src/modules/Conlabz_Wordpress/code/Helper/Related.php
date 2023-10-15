<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Wordpress_Helper_Related extends Mage_Core_Helper_Abstract
{

    const ACF_SEPARATOR     = '###';
    const PRODUCTS_FIELD    = 'products';
    const CATEGORIES_FIELD  = 'categories';
    const ID_PREFIX         = 'ID:';
    
    /**
     *
     * @param type $id
     * @return string
     */
    protected function _getFilterLikeTerm($id)
    {
        $term = '%' . self::ID_PREFIX . $id . self::ACF_SEPARATOR . '%';
        return $term;
    }
    
    /**
     *
     * @param Fishpig_Wordpress_Model_Abstract $post
     * @param string $key
     * @return array
     */
    public function getRelatedProductIds(
        Fishpig_Wordpress_Model_Abstract $post,
        $key = self::PRODUCTS_FIELD
    ) {
        return $this->getRelatedIds($post, $key);
    }
    
    /**
     *
     * @param Fishpig_Wordpress_Model_Abstract $post
     * @param string $key
     * @return array
     */
    public function getRelatedCategoryIds(
        Fishpig_Wordpress_Model_Abstract $post,
        $key = self::CATEGORIES_FIELD
    ) {
        return $this->getRelatedIds($post, $key);
    }
    
    /**
     *
     * @param Fishpig_Wordpress_Model_Abstract $post
     * @param string $key
     * @return array
     */
    public function getRelatedIds(Fishpig_Wordpress_Model_Abstract $post, $key)
    {
        $ids = array();
        if (!$post->getMetaValue($key)) {
            return $ids;
        }
        foreach ($post->getMetaValue($key) as $productString) {
            $parts = explode(self::ACF_SEPARATOR, $productString);
            $ids[] = (int) str_replace(self::ID_PREFIX, '', current($parts));
        }
        return $ids;
    }
    
    /**
     *
     * @param Fishpig_Wordpress_Model_Resource_Collection_Abstract $collection
     * @param Mage_Catalog_Model_Product $product
     * @return Fishpig_Wordpress_Model_Resource_Collection_Abstract
     */
    public function addProductFilter(
        Fishpig_Wordpress_Model_Resource_Collection_Abstract $collection,
        Mage_Catalog_Model_Product $product
    ) {
        $collection->addMetaFieldToSelect(self::PRODUCTS_FIELD);
        $collection->addMetaFieldToFilter(self::PRODUCTS_FIELD, array(
            'like' => $this->_getFilterLikeTerm($product->getId())
        ));
        return $collection;
    }
        
    /**
     *
     * @param Fishpig_Wordpress_Model_Resource_Collection_Abstract $collection
     * @param Mage_Catalog_Model_Category $category
     * @return Fishpig_Wordpress_Model_Resource_Collection_Abstract
     */
    public function addCategoryFilter(
        Fishpig_Wordpress_Model_Resource_Collection_Abstract $collection,
        Mage_Catalog_Model_Category $category
    ) {
        $collection->addMetaFieldToSelect(self::CATEGORIES_FIELD);
        $collection->addMetaFieldToFilter(self::CATEGORIES_FIELD, array(
            'like' => $this->_getFilterLikeTerm($category->getId())
        ));
        return $collection;
    }
}
