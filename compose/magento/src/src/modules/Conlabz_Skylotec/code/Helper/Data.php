<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Helper_Data extends Mage_Core_Helper_Abstract
{
    const ATTRIBUTE_CATEGORY_FILTER = 'available_filters';
    const ATTRIBUTE_CATEGORY_DIVISION = 'division';
    const ATTRIBUTE_NOT_SALABLE = 'nicht_bestellbar';

    protected $_postTypeTitleMap = array(
        'post' => 'News',
        'skylopedia' => 'Skylopedia',
        'page' => 'Page',
        'athlete' => 'Athlete'
    );

    /**
     *
     * @param string $text
     * @param integer $length
     * @param boolean $stripTags
     * @return string clipped $text
     */
    public function clipText($text, $length = 123, $stripTags = true, $markDown = false)
    {
        if ($markDown) {
            $text = Mage::helper('skylotec/output')->markDown($text);
        }
        if ($stripTags) {
            $text = $this->stripTags($text, null, true);
        }
        if (strlen($text) > $length) {
            return substr($text, 0, strpos($text, ' ', $length)) . ' ...';
        }
        return $text;
    }

    public function getDefaultBackgroundImageUrl()
    {
        if ($category = Mage::registry('current_category')) {
            $division = $this->getDivisionRecursively($category);
        } else {
            $division = $this->_getRequest()->getParam('division');
        }
        if (!$division) {
            $division = 'industry';
        }
        $imageUrl = Mage::getDesign()->getSkinUrl('images/teaser-bg-' . $division . '.jpg');
        return $imageUrl;
    }

    /**
     *
     * @param Mage_Catalog_Model_Category $category
     * @return string
     */
    public function getDivisionRecursively(Mage_Catalog_Model_Category $category)
    {
        if (!$division = $category->getData('division')) {
            $parent = $category->getParentCategory();
            if ($parent->getId()) {
                $division = $this->getDivisionRecursively($parent);
            }
        }
        return $division;
    }

    public function getAttributeIcons(Mage_Catalog_Model_Product $product, $attrCode)
    {

        $icons = array();
        $attribute = $product->getResource()
            ->getAttribute($attrCode);
        $values = explode(',', $attribute->getFrontend()
                ->getValue($product));

        foreach ($values as $value) {
            Varien_Profiler::start('ATTR_optionid');
            $optionId = $attribute->getSource()->getOptionId(trim($value));
            Varien_Profiler::stop('ATTR_optionid');
            $imgSrc = Mage::helper('attributeoptionimage')->getAttributeOptionThumb($optionId);
            if ('' !== $imgSrc) {
                $icon = new Varien_Object();
                $icon->setData(array(
                    'title' => $value,
                    'image_src' => $imgSrc
                ));
                $icons[] = $icon;
            }
        }
        return $icons;
    }

    /**
     *
     * @param Mage_Catalog_Model_Product $product
     * @param string $attrCode
     * @return string rendered attribute icons block
     */
    public function renderAttributeIcons(Mage_Catalog_Model_Product $product, $attrCode)
    {
        return Mage::getBlockSingleton('skylotec/catalog_product_view_attributes_icons')
                ->setAttrCode($attrCode)
                ->setProduct($product)
                ->toHtml();
    }

    /**
     *
     * @param string $route
     * @param array $params
     * @return boolean|string
     */
    public function getUrl($route, array $params = array(), $reuseParams = true)
    {
        /* @var $router Zend_Controller_Router_Rewrite */
        $router = Zend_Controller_Front::getInstance()->getRouter();
        if ($route = $router->getRoute($route)) {
            if ($reuseParams) {
                $reqParams = $this->_getRequest()->getParams();
                $params = array_merge($reqParams, $params);
            }
            return Mage::getUrl($route->assemble($params));
        }
        return false;
    }

    /**
     *
     * @param Mana_Filters_Block_Filter $filter
     * @return boolean
     */
    public function isFilterActive(Mana_Filters_Block_Filter $filter)
    {
        foreach ($filter->getItems() as $item) {
            if ($item->getMSelected()) {
                return true;
            }
        }
        return false;
    }

    /**
     *
     * @param Mana_Filters_Block_Filter $filter
     * @return array
     */
    public function getActiveFilterItems(Mana_Filters_Block_Filter $filter)
    {
        $items = array();
        foreach ($filter->getItems() as $item) {
            if ($item->getMSelected()) {
                $items[] = $item->getLabel();
            }
        }
        return $items;
    }

    /**
     *
     * @param Mage_Catalog_Model_Product $product
     * @return false|string
     */
    public function getCategoryIcon(Mage_Catalog_Model_Product $product)
    {
        /* @var $collection Mage_Catalog_Model_Resource_Category_Collection */
        $collection = Mage::getModel('catalog/category')->getCollection();
        $collection->addIsActiveFilter()
            ->addIdFilter($product->getCategoryIds())
            ->addAttributeToSelect('icon')
            ->addAttributeToFilter('icon', array(
                'notnull' => true
            ));
        $collection->getSelect()->order(['level DESC', 'position'])->limit(1);
        $category = $collection->getFirstItem();
        if ($category->getId()) {
            return $category->getIcon();
        }
        return false;
    }

    /**
     *
     * @return boolean|string
     */
    public function getCurrentCategoryIcon()
    {
        if ($category = Mage::registry('current_category')) {
            return $category->getIcon();
        }
        return false;
    }

    /**
     *
     * @return boolean|string
     */
    public function getCurrentCategory()
    {
        if ($category = Mage::registry('current_category')) {
            return $category;
        }
        return false;
    }

    /**
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Mage_Catalog_Model_Resource_Category_Collection
     */
    public function getBranches(Mage_Catalog_Model_Product $product)
    {
        /* @var $collection Mage_Catalog_Model_Resource_Category_Collection */
        $collection = Mage::getModel('catalog/category')->getCollection();
        $collection->addIsActiveFilter()
            ->addIdFilter($product->getCategoryIds())
            ->addAttributeToFilter('is_branche', array('eq' => 1))
            ->addAttributeToSelect(array('icon', 'name'))
            ->addAttributeToFilter('icon', array(
                'notnull' => true
            ));
        return $collection;
    }

    /**
     *
     * @param Mage_Catalog_Model_Product $product
     * @return int
     */
    public function getVariantCount(Mage_Catalog_Model_Product $product)
    {
        if ($product->isConfigurable()) {
            return count(explode('||', $product->getChildrenSkus()));
        }
        return false;
    }

    /**
     *
     * @param string $type
     * @return string
     */
    public function getPostTypeTitle($type)
    {
        if (isset($this->_postTypeTitleMap[$type])) {
            return $this->__($this->_postTypeTitleMap[$type]);
        }
        return $this->__(lcfirst($type));
    }

    /**
     *
     * @param string $videoId
     * @return string
     */
    public function getVideoUrl($videoId)
    {
        return Mage::getBlockSingleton('skylotec/gdata')
                ->getVideoUrl($videoId);
    }

    /**
     *
     * @param string $videoId
     * @return string
     */
    public function getVideoThumbnail($videoId)
    {
        return Mage::getBlockSingleton('skylotec/gdata')
                ->getVideoThumbnail($videoId);
    }

    /**
     *
     * @param Conlabz_Download_Model_Files $file
     * @return Varien_Image
     */
    public function image(Conlabz_Download_Model_Files $file, $callable)
    {
        $image = new Varien_Image($file->getPreviewPath());
        return call_user_func($callable, $image);
    }

    /**
     *
     * @param Mage_Catalog_Model_Product $product
     * @param string $attributeCode
     * @return string
     */
    public function renderIcons(Mage_Catalog_Model_Product $product, $attributeCode)
    {
        Varien_Profiler::start(__METHOD__);
        $entityType = Mage::getModel('eav/config')->getEntityType('catalog_product');
        $attributeModel = Mage::getModel('eav/entity_attribute')->loadByCode($entityType, $attributeCode);
        $_collection = Mage::getResourceModel('eav/entity_attribute_option_collection')
            ->setAttributeFilter($attributeModel->getId())
            ->setStoreFilter(0);

        $rawValues = explode(',', $product->getData($attributeCode));
        $options = array();
        $urlModel = $product->getUrlModel();
        foreach ($_collection as $option) {
            if (in_array($option->getId(), $rawValues)) {
                $option->setData('icon', $urlModel->formatUrlKey($option->getValue()));
                $options[] = $option;
            }
        }

        $block = $this->getLayout()->createBlock(
            'core/template',
            'icons_' . $attributeCode,
            array(
                'template' => 'catalog/product/view/icons.phtml',
                'icons' => $options
            )
        );
        Varien_Profiler::stop(__METHOD__);
        return $block->toHtml();
    }

    public function canDisplayAttribute(array $attribute)
    {
        //max_zulaessiges_gewicht soll nun bereiche abdecken können
        //if ($attribute['code'] === 'max_zulaessiges_gewicht') {
        //    $amount = (int) $attribute['value'];
        //    return $amount > 100;
        //}
        // Nur anzeigen, wenn es mehr als "0 Personen" sind
        if ($attribute['code'] === 'max_personenzahl') {
            return ((int) $attribute['value'] > 0);
        }

        if ($attribute['code'] === 'additional_web_text') {
            return false;
        }
        return true;
    }
}
