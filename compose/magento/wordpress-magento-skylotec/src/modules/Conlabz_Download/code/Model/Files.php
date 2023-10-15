<?php

class Conlabz_Download_Model_Files extends Mage_Core_Model_Abstract
{
    const PAGINATION_LIMIT = 10;
    const DEFAULT_EXT = 'pdf';
    const SEARCH_IN_TITLE = true;
    const SEARCH_IN_SKU = true;

    /**
     * not on file system
     *
     * @var array
     */
    protected $_virtual = array(
        'data_list',
        'certificate'
    );

    protected $_validImageExtensions = array('png', 'jpg', 'jpeg');
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('dstorage/files');
    }

    /**
     *
     * @return bool
     */
    public function isVirtual()
    {
        return in_array($this->getFileCategory(), $this->_virtual);
    }
    
    /**
     * retrieve product categories list
     *
     * @return array products categories list
     */
    public function getProductCategories($division = null)
    {
        $categoriesCollection = $this->getCollection()
            ->distinct(true)
            ->addFieldToSelect('product_category');
        if ($division) {
            $categoriesCollection->addFieldToFilter('division', array(
                'finset' => $division
            ));
        }
        $categoriesCollection->addFieldToFilter('product_category', array('notnull' => true));
        $categories = array();
        foreach ($categoriesCollection as $item) {
            $tempCategories = explode(',', $item->getData('product_category'));
            foreach ($tempCategories as $tempCategory) {
                $categories[$tempCategory] = $tempCategory;
            }
        }
        asort($categories);
        return $categories;
    }

    /**
     * Get Files categories list
     *
     * @return array - categories list
     */
    public function getCategories()
    {
        $categoriesCollection = $this->getCollection()->distinct(true)
            ->addFieldToSelect('file_category')
            ->addFieldToSelect('category_title');
        return $categoriesCollection;
    }

    /**
     * Get files list for category filter
     *
     * @param int - File Category ID
     * @param int - Product Category ID
     * @param int - page
     * @param bool - return just count of pages or return entries
     *
     */
    public function getFiles($categoryId = false, $productCategoryId = false, $page = 1, $count = false)
    {
        $collection = $this->getCollection();

        if ($categoryId) {
            $collection->addFieldToFilter("file_category", $categoryId);
        }
        if ($productCategoryId) {
            $collection->addFieldToFilter("product_category_id", $productCategoryId);
        }

        $tree = array();
        if (!$count) {
            $collection->setPageSize(self::PAGINATION_LIMIT)->setCurPage($page);
            $collection->setOrder("file_category");

            foreach ($collection as $item) {
                if (!isset($tree[$item->getFileCategory()])) {
                    $tree[$item->getFileCategory()] = array();
                }

                $tree[$item->getFileCategory()][$item->getId()] = $item;
            }
        } else {
            return ceil(sizeof($collection) / self::PAGINATION_LIMIT);
        }

        return $tree;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_getHelper()->getDownloadUrl(
            $this->getFileName(),
            $this->getFileCategory()
        );
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->_getHelper()->getFilePath(
            $this->getFileName(),
            $this->getFileCategory()
        );
    }

    /**
     * Get Unique products list for category filter
     *
     * @param bool|int File Category ID
     * @param bool|int Product Category ID
     * @param int - page
     * @param bool  return just count of pages or return entries
     *
     * @return float|object
     */
    public function getProductsAsFiles($categoryId = false, $productCategoryId = false, $page = 1, $count = false)
    {
        $collection = $this->getCollection();

        //Filter by file category
        if ($categoryId) {
            $collection->addFieldToFilter("file_category", $categoryId);
        }

        //Filter by product category
        if ($productCategoryId) {
            $collection->addFieldToFilter("product_category_id", $productCategoryId);
        }

        // If return normal list -> then add paginator
        if (!$count) {
            $collection->setPageSize(self::PAGINATION_LIMIT)->setCurPage($page);
            $collection->setOrder("product_title");
        }

        // If model has search query set - make search
        if ($searchQuery = $this->getSearchQuery()) {
            $searchAttributes = array();
            $searchValues = array();
            if (self::SEARCH_IN_TITLE) {
                $searchAttributes[0] = 'product_title';
                $searchValues[0]["like"] = "%" . $searchQuery . "%";
            }
            if (self::SEARCH_IN_SKU) {
                $searchAttributes[1] = 'product_sku';
                $searchValues[1]["like"] = "%" . $searchQuery . "%";
            }
            $collection->addFieldToFilter($searchAttributes, $searchValues);
        }
        // get Unique values of products (by SKU)
        $collection->distinct(true)
            ->addFieldToSelect('product_sku')
            ->addFieldToSelect('product_title')
            ->load();

        // If count == true, return number of pages
        if ($count) {
            return ceil(sizeof($collection) / self::PAGINATION_LIMIT);
        }

        return $collection;
    }

    /*
     * Get Files for special product
     *
     * @param string - product SKU
     * @param int - File Category ID
     * @param int - Product Category ID
     *
     * @return object - files collection
     */

    public function getProductFiles($sku, $categoryId = false, $productCategoryId = false)
    {

        $collection = $this->getCollection();

        //Filter by file category
        if ($categoryId) {
            $collection->addFieldToFilter("file_category", $categoryId);
        }

        //Filter by product category
        if ($productCategoryId) {
            $collection->addFieldToFilter("product_category_id", $productCategoryId);
        }

        //Filter By SKU
        $collection->addFieldToFilter('product_sku', $sku);

        return $collection;
    }

    /**
     * get filename, if no extension, add default
     */
    public function getFileName()
    {
        $filename = $this->getData('file_name');
        $pathInfo = pathinfo($filename);
        if (empty($pathInfo['extension'])) {
            return $filename .  '.' .  self::DEFAULT_EXT;
        }
        return $filename;
    }

    /**
     *
     * @return string
     */
    public function getPreviewPath()
    {
        $filePath = $this->_getHelper()->getFilePath(
            $this->getFileName(),
            $this->getFileCategory()
        );

        if ($this->getFileCategory() === 'images') {
            return $filePath;
        }

        $dir = dirname($filePath);
        $filename = pathinfo($this->getFileName(), PATHINFO_FILENAME);

        $thumbnail =  $dir . '/thumbnails/' . $filename . '.jpg';
        return $thumbnail;
    }

    /**
     *
     * @return bool
     */
    public function hasPreview()
    {
        $thumbnail = $this->getPreviewPath();
        $hasPreview = (
            (
                is_readable($thumbnail) ||
                $this->getFileCategory() === 'images'
            ) &&
            $this->getFileCategory() !== 'user_manual'
        );
        return $hasPreview;
    }

    /**
     * @return string
     */
    public function getPreviewUrl($width = null, $height = 150)
    {
        $fileName = pathinfo($this->getFileName(), PATHINFO_FILENAME);
        $extension = strtolower(pathinfo($this->getFileName(), PATHINFO_EXTENSION));
        if (!in_array($extension, $this->_validImageExtensions)) {
            $extension = 'jpg';
        }

        $destination = sprintf(
            '%s/skylofiles/preview/%sx%s/%s/%s.%s',
            Mage::getBaseDir(),
            $width,
            $height,
            $fileName[0],
            $fileName,
            $extension
        );
        
        if (!is_file($destination) && is_readable($this->getPreviewPath())) {
            $image = new Varien_Image($this->getPreviewPath());
            $image->resize($width, $height);
            $image->keepFrame(false);
            $image->keepTransparency(true);
            if (!is_dir(dirname($destination))) {
                mkdir(dirname($destination), 0777, true);
            }
            $image->save($destination);
        }
        
        $url = $this->_getHelper()->getUrlByPath($destination);
        return $url;
    }

    /**
     * @return bool
     */
    public function canDisplay()
    {
        if ($this->isVirtual()) {
            return true;
        }
        return is_file($this->_getHelper()->getFilePath($this->getFileName(), $this->getFileCategory()));
    }

    /**
     * @return Conlabz_Download_Helper_data
     */
    protected function _getHelper()
    {
        return Mage::helper('dstorage');
    }
}
