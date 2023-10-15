<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_GenticDesign_Block_Catalog_Category_Home extends Mage_Core_Block_Template
{
    /**
     *
     * @var string
     */
    protected $_template = 'catalog/category/home.phtml';
    
    /**
     *
     * @return Mage_Catalog_Model_Category[]
     */
    public function getCategories()
    {
        return $this->helper('genticdesign')->getMainCategories();
    }
    
    /**
     *
     * @param int $parentId
     * @return Mage_Catalog_Model_Resource_Category_Collection
     */
    public function getChildCategories($parentId)
    {
        return $this->helper('genticdesign')->getChildCategories($parentId);
    }
    
    /**
     *
     * @param Mage_Catalog_Model_Category $category
     * @return string
     */
    public function getCategoryImage(Mage_Catalog_Model_Category $category, $size = 800, $rotate = 0)
    {
        $placeholder = $this->getSkinUrl('images/placeholder.png');
        if (!$categoryImage = $category->getImage()) {
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
            $rotate,
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
        $image->backgroundColor(array(255, 255, 255));
        $image->resize($size);
        if ($rotate) {
            $image->rotate($rotate);
        }
        $image->save($destination);
        
        return $imageUrl;
    }
}
