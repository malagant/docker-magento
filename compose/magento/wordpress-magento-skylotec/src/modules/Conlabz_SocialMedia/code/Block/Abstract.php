<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
abstract class Conlabz_SocialMedia_Block_Abstract extends Mage_Core_Block_Template
{
    protected $_template = 'conlabz/socialmedia/default.phtml';
    
    protected $_metaElements;
    
    protected $_headBlock;
    
    protected $_init = false;
    
    protected function _init()
    {
        if ($this->_init) {
            return;
        }
        $description = $this->getHeadBlock()->getDescription();
        $this->setDescription($description);
        
        /* @var $product Mage_Catalog_Model_Product */
        if ($product = Mage::registry('product')) {
            $this->setTitle($product->getName());
            $this->setPriceAmount($product->getFinalPrice());
            $this->setPriceCurrency(Mage::app()->getStore()->getCurrentCurrencyCode());
            $this->setImage((string) Mage::helper('catalog/image')->init($product, 'image')->resize(1200));
            $productDescription = $product->getDescription();
            if (!empty($productDescription)) {
                $this->setDescription($productDescription);
            }
        }
        /* @var $category Mage_Catalog_Model_Category */
        if ($category = Mage::registry('current_category')) {
            $this->setTitle($category->getTitle());
            if ($description = $category->getDescription()) {
                $this->setDescription($description);
            }
        }
        $this->_init = true;
    }
            
    protected function _getValue($type)
    {
        if (!$this->hasData($type)) {
            $xmlKey = $this->_xmlPrefix . '/' . $type;
            $this->setData($type, Mage::getStoreConfig($xmlKey));
        }
        return $this->getData($type);
    }
    
    public function getTitle()
    {
        $title = $this->getHeadBlock()->getTitle();
        if (!$title) {
            return $this->_getValue('title');
        }
        return $title;
    }
    
    public function getHeadBlock()
    {
        if (null === $this->_headBlock) {
            $this->_headBlock = $this->getLayout()->getBlock('head');
        }
        return $this->_headBlock;
    }
            
    public function getMetaElements()
    {
        $this->_init();
        Mage::dispatchEvent('social_media_get_meta_elements', array(
            'prefix' => $this->getPrefix(),
            'block'  => $this
        ));
        if (null === $this->_metaElements) {
            $this->_metaElements = array();
            foreach ($this->getTypes() as $type) {
                if ($type === 'url') {
                    $content = $this->getUri();
                } else {
                    $content = $this->getDataUsingMethod($type);
                }
                if (!$content) {
                    continue;
                }
                $element = new Varien_Object(array(
                    'property' => $this->getPrefix() . $type,
                    'content' => $content
                ));
                $this->_metaElements[] = $element;
            }
        }
        return $this->_metaElements;
    }
    
    public function getUri()
    {
        if (($product = Mage::registry('product')) &&
            $this->helper('catalog/product')->canUseCanonicalTag()
        ) {
            $params = array('_ignore_category' => true);
            return $product->getUrlModel()->getUrl($product, $params);
        }
        if (($category = Mage::registry('current_category')) &&
            $this->helper('catalog/category')->canUseCanonicalTag()
        ) {
            return $category->getUrl();
        }
        return $this->helper('core/url')->getCurrentUrl();
    }
    
    public function getImage()
    {
        if (!$this->getData('image')) {
            if ($image = $this->_getValue('image')) {
                return Mage::getBaseUrl('media')  . 'socialmedia/' . $image;
            }
        }
        return $this->getData('image');
    }
    
    public function getDescription()
    {
        return $this->getData('description');
    }

    public function setMetaElements(array $metaElements = null)
    {
        $this->_metaElements = $metaElements;
        return $this;
    }

    abstract public function getTypes();
    
    abstract public function getPrefix();
}
