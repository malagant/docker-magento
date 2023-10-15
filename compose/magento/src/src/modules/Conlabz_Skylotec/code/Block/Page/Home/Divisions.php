<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Page_Home_Divisions extends Mage_Core_Block_Template
{
    protected $_template = 'page/home/divisions.phtml';

    protected $_categoryIds;

    /**
     *
     * @return array
     */
    public function getCategoryIds()
    {
        return (array) Mage::app()->getConfig()
            ->getNode('global/skylotec/base_category_ids');
    }

    /**
     *
     * @return Mage_Catalog_Model_Resource_Category_Collection
     */
    protected function _getCategoryCollection()
    {
        $collection = Mage::getModel('catalog/category')->getCollection();
        $collection->addIsActiveFilter()
            ->addAttributeToSelect(array('name', 'icon'));

        return $collection;
    }

    /**
     *
     * @return Mage_Catalog_Model_Category[]
     */
    public function getDivisions()
    {
        $collection = $this->_getCategoryCollection()
            ->setOrder('position', 'ASC')
            ->addAttributeToFilter('division', array('notnull' => true))
            ->addAttributeToFilter('level', 2);
        return $collection;
    }

    /**
     *
     * @return Mage_Catalog_Model_Category[]
     */
    public function getHomeCategories()
    {
        $collection = $this->_getCategoryCollection()
            ->setOrder('position', 'ASC')
            ->addAttributeToSelect('thumbnail')
            ->addAttributeToFilter('show_in_homepage', 1);
        return $collection;
    }

    /**
     * Retrieve thumbnail URL
     *
     * @return string|false
     */
    public function getThumbnailUrl(Mage_Catalog_Model_Category $category)
    {
        $url = false;
        if ($image = $category->getThumbnail()) {
            $url = Mage::getBaseUrl('media').'catalog/category/'.$image;
        }
        return $url;
    }

    /**
     *
     * @param Mage_Catalog_Model_Category $parent
     * @return Mage_Catalog_Model_Category[]
     */
    public function getChildCategories(Mage_Catalog_Model_Category $parent)
    {
        $childCategoryIds = $parent->getAllChildren(true);
        array_shift($childCategoryIds);
        $collection = $this->_getCategoryCollection();
        $collection->addIdFilter($childCategoryIds);
        $collection->getSelect()->limit(4);
        return $collection;
    }

    /**
     *
     * @param Mage_Catalog_Model_Category $category
     * @return Mage_Catalog_Model_Product[]
     */
    public function getNewProducts(Mage_Catalog_Model_Category $category)
    {
        /* @var $collection Mage_Catalog_Model_Resource_Product_Collection */
        $collection = $category->getProductCollection()
            ->addAttributeToSelect(array(
                'name',
                'small_image'
            ))
            ->addAttributeToFilter('neuheit', 1);
        $collection->getSelect()->limit(3);
        
        return $collection;
    }

    /**
     * register current category so url model of product will retrieve the url
     * including the category path
     *
     * @param Mage_Catalog_Model_Category $category
     */
    public function registerCategory(Mage_Catalog_Model_Category $category)
    {
        Mage::unregister('current_category');
        Mage::register('current_category', $category);
    }

    /**
     *
     * @param Mage_Catalog_Model_Category $category
     * @return string
     */
    public function getCategoryImage(
        Mage_Catalog_Model_Category $category,
        $size = 400,
        $crop = true
    ) {
        $placeholder = $this->getSkinUrl('images/okta.png');
        if (!$categoryImage = $category->getThumbnail()) {
            return $placeholder;
        }
        
        $subPath = 'catalog/category';
        $internalBasePath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA);
        $publicBasePath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
        $source = $internalBasePath . '/' . $subPath . '/' . $categoryImage;
        $destFile = sprintf(
            '%s-%s-%s.%s',
            pathinfo($categoryImage, PATHINFO_FILENAME),
            $size,
            (string) $crop,
            pathinfo($categoryImage, PATHINFO_EXTENSION)
        );

        $imageUrl    = $publicBasePath . $subPath . '/' . $destFile;
        $destination = $internalBasePath . '/' . $subPath . '/' . $destFile;

        if (is_file($destination)) {
            return $imageUrl;
        }

        if (!is_readable($source)) {
            return $placeholder;
        }

        $image = new Varien_Image($source);
        $sourceWidth  = $image->getOriginalWidth();
        $sourceHeight = $image->getOriginalHeight();

        if ($crop) {
            if ($sourceWidth > $sourceHeight) {
                $tempHeight = 0;
                $tempWidth = ($sourceWidth - $sourceHeight) / 2;
            } elseif ($sourceWidth > $sourceHeight) {
                $tempWidth = 0;
                $tempHeight = ($sourceHeight - $sourceWidth) / 2;
            } else {
                $tempHeight = 0;
                $tempWidth = 0;
            }
            $image->crop($tempHeight, $tempWidth, $tempWidth, $tempHeight);
            $image->save($destination);

            $image = new Varien_Image($destination);
        }
        $image->resize($size);
        $image->save($destination);

        return $imageUrl;
    }
}
