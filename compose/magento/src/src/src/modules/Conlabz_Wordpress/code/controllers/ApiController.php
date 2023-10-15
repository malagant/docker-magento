<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Wordpress_ApiController extends Mage_Core_Controller_Front_Action
{
    protected $_languageStoreMap = array(
        'de' => 'eu_de',
        'en' => 'eu_en',
        'fr' => 'eu_fr',
        'it' => 'eu_it',
        'es' => 'eu_es'
    );

    public function productsAction()
    {
        Mage::app()->setCurrentStore('admin');
        $query = $this->getRequest()->getParam('q');
        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection->addAttributeToSelect('sku');
        $collection->addAttributeToSelect('name');
        $collection->getSelect()->limit(20);
        $collection->addAttributeToFilter(array(
            array(
                'attribute' => 'sku',
                'like' => '%' . $query . '%'
            ),
            array(
                'attribute' => 'name',
                'like' => '%' . $query . '%'
            )
        ));
        $this->getResponse()->setHeader('Access-Control-Allow-Origin', '*');
        $response = array();
        
        foreach ($collection as $id => $item) {
            $response[] = array(
                'id' => $id,
                'name' => $item->getName(),
                'sku' => $item->getSku()
            );
        }
        
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }
    
    public function categoriesAction()
    {
        Mage::app()->setCurrentStore('admin');
        $categories = Mage::getResourceModel('catalog/category_collection')
            ->addAttributeToSelect('name')
            ->getItems();

        $categoryList = array();
        $query = $this->getRequest()->getParam('q');

        foreach ($categories as $category) {
            $path = array_slice(explode('/', $category->getPath()), 2);
            $include = !$query;
            foreach ($path as $key => $value) {
                $categoryName = $categories[$value]->getName();
                if ($query && false !== stripos($categoryName, $query)) {
                    $include = true;
                }
                $path[$key] = $categoryName;
            }
            if (empty($path) || !$include) {
                continue;
            }
            $categoryList[] = array(
                'id' => $category->getId(),
                'name' => implode(' / ', $path)
            );
        }

        $this->getResponse()->setHeader('Access-Control-Allow-Origin', '*');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($categoryList));
    }

    public function pagelinkAction()
    {
        $response = array();

        $lang   = $this->getRequest()->getParam('lang');
        $store = $this->_getStoreByLang($lang);
        Mage::app()->setCurrentStore($store);
        $pageId = $this->getRequest()->getParam('page_id');
        $categories = Mage::getResourceModel('catalog/category_collection')
            ->addAttributeToSelect(array('url_key', 'name'))
            ->addAttributeToFilter('wp_entity', $pageId)
            ->addIsActiveFilter()
            ->getItems();
        foreach ($categories as $category) {
            $response[] = array(
                'url' => $category->getUrl()
            );
        }

        $this->getResponse()->setHeader('Access-Control-Allow-Origin', '*');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    protected function _getStoreByLang($lang, $default = 'eu_de')
    {
        return isset($this->_languageStoreMap[$lang])
            ? $this->_languageStoreMap[$lang]
            : $default;
    }
}
