<?php

/**
 * @package Conlabz_Wordpress
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Wordpress_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_COLLECTIONS_TO_FILTER = 'wordpress/wpml/collections_to_filter';

    /**
     *
     * @var array
     */
    protected $_collectionsToFilter;

    /**
     *
     * @return array
     */
    public function getCollectionsToFilter()
    {
        if (null === $this->_collectionsToFilter) {
            $config = Mage::getConfig()->getNode(self::XML_PATH_COLLECTIONS_TO_FILTER);
            $this->_collectionsToFilter = array_keys($config->asArray());
        }
        return $this->_collectionsToFilter;
    }

    /**
     *
     * @param type $collection
     * @return boolean
     */
    public function canFilterCollection($collection)
    {
        if ($this->_getRequest()->getParam('preview', false)) {
            return false;
        }
        foreach ($this->getCollectionsToFilter() as $collectionName) {
            if ($collection instanceof $collectionName) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return string
     */
    public function getLanguageCode($locale = null)
    {
        if (Mage::app()->getStore()->isAdmin()) {
            $store = Mage::app()->getRequest()->getParam('store');
            if ($store) {
                $currentStore = Mage::getModel('core/store')->load($store);
                $locale = $currentStore->getConfig('general/locale/code');
            } else {
                return false;
            }
        } elseif (null === $locale) {
            $locale = Mage::app()->getLocale()->getLocaleCode();
        }
        return substr($locale, 0, 2);
    }

    /**
     * check if module is enabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return Mage::getStoreConfigFlag('wordpress/module/enable_wpml');
    }

    /**
     * retrieve first found category url if post is linked
     *
     * @param Fishpig_Wordpress_Model_Post $post
     * @return string
     */
    public function getPostUrl(Fishpig_Wordpress_Model_Post $post)
    {
        $category = Mage::getResourceModel('catalog/category_collection')
            ->addAttributeToSelect(array('url_key', 'name'))
            ->addAttributeToFilter('wp_entity', $post->getId())
            ->addIsActiveFilter()
            ->getFirstItem();
        if ($category->getId()) {
            return $category->getUrl();
        }
        return $post->getUrl();
    }

    /**
     * @param Fishpig_Wordpress_Model_Post $post
     * @return string
     */
    public function getMainPostContent(Fishpig_Wordpress_Model_Post $post)
    {
        $parts = explode('<p><!--more--></p>', $post->getPostContent());
        return isset($parts[1])
            ? $parts[1]
            : $post->getPostContent();
    }
}
