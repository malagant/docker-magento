<?php

/**
 * @package Conlabz_GenticDesign
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_GenticDesign_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_HOME_CATEGORIES = 'design/homepage/categories';
    const XML_PATH_LINEPLAN_ENABLED = 'catalog/lineplan/enabled';
    const XML_PATH_LINEPLAN_START_WINTER = 'catalog/lineplan/start_winter';
    const XML_PATH_LINEPLAN_START_SUMMER = 'catalog/lineplan/start_summer';
    
    const COLOR_TYPE_BG     = 'bg';
    const COLOR_TYPE_FONT   = 'color';
    const STOREFINDER_URL   = 'haendler';
    
    /**
     *
     * @var string
     */
    protected $_customPageTemplatesBase = 'wordpress/page/custom';
    
    /**
     *
     * @var int
     */
    protected $_pageCounter = 1;
    
    /**
     *
     * @var array
     */
    protected $_colorsClasses = array(
        'yellow',
        'blue',
        'orange',
        'green',
        'red',
        'pink'
    );
        
    protected $_sayings = array();
    
    /**
     * nth image in catalog will be rotated
     *
     * map: position => rotation in degree
     *
     * @var array
     */
    protected $_imageRotationAngles = array(
        2 =>  90,
        4 => 270,
        5 =>  90
    );

    /**
     * product gallery images
     *
     * @var array
     */
    protected $_galleryRotationAngles = array(
        1 => 270,
        3 =>  90
    );

    /**
     * related products images
     *
     * @var array
     */
    protected $_relatedRotationAngles = array(
        1 => 270,
    );

    /**
     * category images
     *
     * @var array
     */
    protected $_categoryRotationAngles = array(
        2 => 90
    );
    
    /**
     *
     * @var array
     */
    protected $_socialMediaLinks = array(
        'facebook'  => 'https://de-de.facebook.com/genticclothing',
        'instagram' => 'http://instagram.com/genticclothing',
        'pinterest' => 'https://www.pinterest.com/genticclothing',
        'twitter'   => 'https://twitter.com/genticclothing'
    );
    
    /**
     * retrieve random color class
     *
     * @return string
     */
    public function getRandomColorClassname($type = self::COLOR_TYPE_FONT)
    {
        return $type . '-' . $this->getRandomColor($type);
    }
    
    /**
     *
     * @param string $color
     * @param string $type
     * @return string
     */
    public function getClassname($color, $type = self::COLOR_TYPE_FONT)
    {
        return $type . '-' . $color;
    }
    
    /**
     *
     * @param string $type
     * @return string
     */
    public function getRandomColor($type = self::COLOR_TYPE_FONT)
    {
        if ($type === self::COLOR_TYPE_BG) {
            $this->_colorsClasses[] = 'white';
            $this->_colorsClasses[] = 'black';
        }
        return $this->_colorsClasses[array_rand($this->_colorsClasses)];
    }

    /**
     *
     * @return array
     */
    public function getRandomFontAndBackgroundColorClassnames()
    {
        $backgroundColor = $this->getRandomColor(self::COLOR_TYPE_BG);
        if ($backgroundColor !== 'white') {
            $fontColor = 'white';
        } else {
            do {
                $fontColor = $this->getRandomColor(self::COLOR_TYPE_FONT);
            } while ($backgroundColor === $fontColor);
        }
        
        return array(
            $this->getClassname($backgroundColor, self::COLOR_TYPE_BG),
            $this->getClassname($fontColor, self::COLOR_TYPE_FONT)
        );
    }
    
    /**
     * retrieve random saying
     *
     * @return string
     */
    public function getRandomSaying()
    {
        if (!count($this->_sayings)) {
            $postType = Mage::helper('wp_addon_cpt')->getPostType('bam');
            $bamCollection = $postType->getPostCollection()->addIsViewableFilter();
            foreach ($bamCollection as $bam) {
                $this->_sayings[] = $bam->getPostTitle();
            }
        }
        return $this->_sayings[array_rand($this->_sayings)];
    }
    
    /**
     *
     * @param integer $index
     * @return integer
     */
    public function getImageRotationAngle($index)
    {
        return isset($this->_imageRotationAngles[$index])
            ? $this->_imageRotationAngles[$index]
            : 0;
    }
    
    /**
     *
     * @param integer $index
     * @return integer
     */
    public function getGalleryRotationAngle($index)
    {
        return isset($this->_galleryRotationAngles[$index])
            ? $this->_galleryRotationAngles[$index]
            : 0;
    }
    
    public function getCategoryRotationAngle($index)
    {
        return isset($this->_categoryRotationAngles[$index])
            ? $this->_categoryRotationAngles[$index]
            : 0;
    }
    
    /**
     *
     * @param integer $index
     * @return integer
     */
    public function getRelatedRotationAngle($index)
    {
        return isset($this->_relatedRotationAngles[$index])
            ? $this->_relatedRotationAngles[$index]
            : 0;
    }
    
    /**
     *
     * @return array
     */
    public function getHomeCategoryIds()
    {
        return explode(',', Mage::getStoreConfig(self::XML_PATH_HOME_CATEGORIES));
    }
    
    /**
     * template-homepage.php => homepage.phtml
     *
     * @param string $templateName
     * @return string
     */
    public function convertWpTemplateName($templateName)
    {
        $templateName = pathinfo($templateName, PATHINFO_FILENAME);
        return str_replace('template-', '', $templateName) . '.phtml';
    }
    
    /**
     *
     * @param Fishpig_Wordpress_Model_Post $page
     * @return string
     */
    public function getCustomPageTemplate(Fishpig_Wordpress_Model_Post $page)
    {
        return sprintf(
            '%s/%s',
            $this->_customPageTemplatesBase,
            $this->convertWpTemplateName(
                $page->getMetaValue('_wp_page_template')
            )
        );
    }
    
    /**
     *
     * @param Fishpig_Wordpress_Model_Post $page
     */
    public function renderChildPage(Fishpig_Wordpress_Model_Post $page, $iterator = 1)
    {
        $template = 'wordpress/page/view/child.phtml';
        if ($this->hasCustomTemplate($page)) {
            $template = $this->getCustomPageTemplate($page);
        }
        $block = $this->getLayout()->createBlock(
            'wordpress/page_view',
            'wp_page_block_' . $this->_pageCounter++,
            array(
                'template' => $template,
                'iterator' => $iterator,
                'page' => $page
            )
        );
        return $block->toHtml();
    }
    
    /**
     * check if page has a custom template
     *
     * @param Fishpig_Wordpress_Model_Post $page
     * @return boolean
     */
    public function hasCustomTemplate(Fishpig_Wordpress_Model_Post $page)
    {
        return ($page->getMetaValue('_wp_page_template') !== 'default');
    }

    /**
     * @param $post
     * @param $metaKey
     * @param string $type
     * @param string $class
     * @return string
     */
    public function renderPostImage($post, $metaKey, $type = 'medium', $class = '')
    {
        $image = $post->getMetaValue($metaKey);
        if ($image instanceof Fishpig_Wordpress_Model_Image) {
            $imageUrl = $image->getImageByType($type);
            if (!$imageUrl) {
                $imageUrl = $image->getFullSizeImage();
            }
            return '<img src="' . $imageUrl . '" alt="' . $image->getPostTitle() . '" class="' . ($class ? $class : $metaKey) . '">';
        }
        return '';
    }
    
    /**
     *
     * @return array
     */
    public function getSocialMediaLinks()
    {
        return $this->_socialMediaLinks;
    }
    
    public function getPageBackgroundStyle($page)
    {
        if ($backgroundImage = $page->getMetaValue('background-image')) {
            $backgroundUrl = $backgroundImage->getFullSizeImage();
            return sprintf(' style="background-image: url(\'%s\')" ', $backgroundUrl);
        }
        return '';
    }
    
    /**
     *
     * @return string
     */
    public function getStoreFinderUrl()
    {
        return $this->_getUrl(self::STOREFINDER_URL);
    }
    
    /**
     *
     * @param string $url
     * @return string
     */
    public function normalizeUrl($url)
    {
        $url = trim($url);
        if (preg_match('#^https?://#', $url)) {
            return $url;
        }
        return 'http://' . $url;
    }
    
    /**
     *
     * @return bool
     */
    public function isLineplanEnabled($storeId = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_LINEPLAN_ENABLED, $storeId);
    }
    
    /**
     *
     * @return string
     */
    public function getLineplanWinterStartDate($storeId = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_LINEPLAN_START_WINTER, $storeId);
    }
    
    /**
     *
     * @return string
     */
    public function getLineplanSummerStartDate($storeId = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_LINEPLAN_START_SUMMER, $storeId);
    }
    
    /**
     *
     * @return Mage_Catalog_Model_Resource_Category_Collection
     */
    public function getMainCategories()
    {
        $mainMenuId = (int) Mage::getConfig()->getNode('frontend/menu/main_menu_id');
        return $this->getChildCategories($mainMenuId);
    }
    
    /**
     *
     * @param int $parentId
     * @return Mage_Catalog_Model_Resource_Category_Collection
     */
    public function getChildCategories($parentId)
    {
        /* @var $category Mage_Catalog_Model_Category */
        $category = Mage::getModel('catalog/category');
        return $category->getCollection()
            ->addAttributeToFilter('parent_id', $parentId)
            ->addAttributeToSort('position', 'ASC')
            ->addAttributeToSelect('meta_title')
            ->addAttributeToSelect('image');
    }
}
