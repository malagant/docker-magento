<?php
class Conlabz_Download_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_STORAGE_URL = 'cdownloads/settings/url';
    const XML_PATH_QUEUE_URL = 'cdownloads/settings/queueurl';
    const XML_PATH_QUEUE_KEY = 'cdownloads/settings/queueurl_key';
    const XML_PATH_DOWNLOADER_URL = 'cdownloads/settings/downloader_url';

    const XML_PATH_SKYDOWNLOAD_URL = 'cdownloads/settings/skydownload_url';
    const XML_PATH_SKYDOWNLOAD_CSS = 'cdownloads/settings/skydownload_css';

    private $_categoryId = false;
    private $_page = false;
    
    public function getStorageUrl()
    {
        return Mage::getStoreConfig(self::XML_PATH_STORAGE_URL);
    }

    public function getDownloaderUrl()
    {
        return Mage::getStoreConfig(self::XML_PATH_DOWNLOADER_URL);
    }

    public function getQueueUrl()
    {
        return Mage::getStoreConfig(self::XML_PATH_QUEUE_URL);
    }
    
    public function getQueueKey()
    {
        return Mage::getStoreConfig(self::XML_PATH_QUEUE_KEY);
    }
    
    /*
     * Get image storage tree - return json object OR array of data
     */
    public function getStorageContent($categories = false, $asJson = false)
    {
        
        if ($url = $this->getStorageUrl()) {
            $params = "?catch=1";
            if ($categories) {
                $params .= "&type=categories";
            }
            if ($this->_categoryId) {
                $params .= "&category=".$this->_categoryId;
            }
            if ($this->_page) {
                $params .= "&page=".$this->_page;
            }
            
            $content = file_get_contents($url.$params);
            if ($content) {
                if ($asJson) {
                    return $content;
                } else {
                    return json_decode($content, true);
                }
            }
        }
        return false;
    }
    
    /*
     * Get storage categories tree
     */
    public function getStorageCategories()
    {
        
        $categories = $this->getStorageContent(true);
        if (!$categories) {
            return array();
        }
        return $categories;
    }
    
    /*
     * Get storage categories content
     */
    public function getStorageFiles($categoryId = false, $page = 1)
    {
        
        $this->_categoryId = $categoryId;
        $this->_page = $page;
        
        $tree = $this->getStorageContent();
        $files = array();
        
        if (!$tree) {
            return array();
        }
        
        if ($categoryId) {
            if (isset($tree['tree'][$categoryId])) {
                return array(
                                'tree'=>$tree['tree'][$categoryId],
                                'pages'=>$tree['pages']
                            );
            }
        }
        return $tree;
    }

    /**
     * @return string
     */
    public function getBasePath($file = null)
    {
        $path = Mage::getBaseDir() . '/skylofiles';
        if (null !== $file) {
            $path .= DS . trim($file, '\\/');
        }
        return $path;
    }

    /**
     * @param string $filename
     * @param string $category
     * @param null|string $language
     * @return string
     */
    public function getFilePath($filename, $category, $language = null)
    {
        if ($category === 'data_list') {
            if (null === $language) {
                $language = substr(Mage::getStoreConfig('general/locale/code'), 0, 2);
            }
            $category .= '/' . $language;
        }
        return $this->getBasePath() . DS . $category . DS . $filename;
    }

    /**
     * @param string $filename
     * @param string $category
     * @return string
     */
    public function getDownloadUrl($filename, $category)
    {
        $filename = ltrim($filename);

        if ($category == "data_list") {
            return Mage::getUrl("pdfprint/index/datalist", array("f" => $filename));
        }
        if ($category == "certificate") {
            return Mage::getUrl("pdfprint/index/certificate", array("f" => $filename));
        }

        return $this->getUrlByPath($this->getFilePath($filename, $category));
    }

    public function getUrlByPath($path)
    {
        $url = str_replace(
            Mage::getBaseDir() . '/',
            Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB),
            $path
        );
        return $url;
    }

    public function getCategoryName($name)
    {
        return $this->__($name);
    }

    public function getSkyDownloadUrl($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_SKYDOWNLOAD_URL, $store);
    }

    public function getSkyDownloadCss($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_SKYDOWNLOAD_CSS, $store);
    }
}
