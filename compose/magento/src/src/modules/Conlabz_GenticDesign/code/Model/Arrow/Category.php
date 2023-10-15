<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_GenticDesign_Model_Arrow_Category implements Conlabz_GenticDesign_Model_Arrow_Interface
{
    protected $_nextCategory;
    
    protected $_prevCategory;
    
    private function getCategoryByPositionOffset($offset)
    {
        $currentCategory = Mage::registry('current_category');

        if ($currentCategory) {
            // Get Parent category subCategories
            $categories = Mage::getModel('catalog/category')->load($currentCategory->parent_id)->getChildrenCategories();

            if (count($categories) > 0) {
                // Create subCategories Id's array
                $categoryPosIdArr = array();
                foreach ($categories as $category) {
                    array_push($categoryPosIdArr, (int)$category->getId());
                }

                $categoryPos = array_search($currentCategory->getId(), $categoryPosIdArr) + $offset;

                if (isset($categoryPosIdArr[$categoryPos])) {
                    $categoryId = $categoryPosIdArr[$categoryPos];

                    return Mage::getModel('catalog/category')->load($categoryId);
                }
            }
            return false;
        }
        return false;
    }
    
    public function getFirstUrl()
    {
        $categories = Mage::helper('genticdesign')->getMainCategories();
        $categories->setPage(1, 1);
        $category = $categories->getFirstItem();
        if ($category) {
            return $category->getUrl();
        }
    }
    
    public function getCurrentUrl()
    {
        if ($category = Mage::registry('current_category')) {
            return $category->getUrl();
        }
    }
    
    public function getCurrentLabel()
    {
        if ($category = Mage::registry('current_category')) {
            return $category->getName();
        }
    }
    
    protected function _getNextCategory()
    {
        if (null === $this->_nextCategory) {
            $this->_nextCategory = $this->getCategoryByPositionOffset(1);
        }
        return $this->_nextCategory;
    }
    
    protected function _getPrevCategory()
    {
        if (null === $this->_prevCategory) {
            $this->_prevCategory = $this->getCategoryByPositionOffset(-1);
        }
        return $this->_prevCategory;
    }

    public function getNextUrl()
    {
        if ($category = $this->_getNextCategory()) {
            return $category->getUrl();
        }
    }

    public function getPrevUrl()
    {
        if ($category = $this->_getPrevCategory()) {
            return $category->getUrl();
        }
    }
    
    public function getNextLabel()
    {
        if ($category = $this->_getNextCategory()) {
            return $category->getName();
        }
    }

    public function getPrevLabel()
    {
        if ($category = $this->_getPrevCategory()) {
            return $category->getName();
        }
    }
}
