<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Model_Observer_Subnavigation_Category
{
    const MAIN_MENU_LEVEL = 1;

    /**
     *
     * @param Varien_Event_Observer $observer
     * @return void
     * @throws Exception
     */
    public function addSubcategories(Varien_Event_Observer $observer)
    {
        if (!$parentCategory = Mage::registry('current_category')) {
            return;
        }
        $event = $observer->getEvent();
        /* @var $collection Varien_Data_Collection */
        $collection = $event->getCollection();

        $childCategories = $parentCategory->getChildrenCategories();

        if (!count($childCategories)) {
            $greatParentCategory = $parentCategory->getParentCategory();
            if ((int) $greatParentCategory->getLevel() <= self::MAIN_MENU_LEVEL) {
                return;
            }
            $childCategories = $greatParentCategory
                ->getChildrenCategories();
        }

        foreach ($childCategories as $childCategory) {
            $collection->addItem($this->_mapItem($childCategory));
        }
    }
    
    /**
     *
     * @param Mage_Catalog_Model_Category $category
     * @return Varien_Object
     */
    protected function _mapItem(Mage_Catalog_Model_Category $category)
    {
        $href = null;
        if ($category->getDisplayMode() !== 'NONE') {
            $href = $category->getCustomUrl() ?? $category->getUrl();
        }
        $item = new Varien_Object(array(
            'href' => $href,
            'name' => $category->getName(),
            'title' => $category->getTitle(),
            'is_active' => $this->_getIsActive($category->getRequestPath())
        ));
        return $item;
    }
    
    protected function _getIsActive($uri)
    {
        $request = Mage::app()->getRequest();
        $pathInfo = trim($request->getOriginalPathInfo(), '/');
        $uri = trim($uri, '/');
        if ($pathInfo === $uri) {
            return true;
        }
        
        $dirPathInfo = dirname($pathInfo);
        $filenameUri = dirname($uri) . '/' . pathinfo($uri, PATHINFO_FILENAME);
        
        if ($dirPathInfo === $filenameUri) {
            return true;
        }
        
        
        return false;
    }
}
