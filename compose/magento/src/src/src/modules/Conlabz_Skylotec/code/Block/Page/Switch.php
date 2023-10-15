<?php
/**
 * Store and language switcher block
 */
class Conlabz_Skylotec_Block_Page_Switch extends Mage_Page_Block_Switch
{
    public function getRawStores()
    {
        if (!$this->hasData('raw_stores')) {
            $websiteStores = Mage::app()->getWebsite()->getStores();
            $stores = array();
            foreach ($websiteStores as $store) {
                /* @var $store Mage_Core_Model_Store */
                if (!$store->getIsActive()) {
                    continue;
                }
                $store->setLocaleCode(Mage::getStoreConfig('general/locale/code', $store->getId()));
                $params = array(
                    '_query' => array()
                );
                if (!$this->isStoreInUrl()) {
                    $params['_query']['___store'] = $store->getCode();
                }
                $baseUrl = $store->getUrl('', $params);
                $store->setLangCode($this->_getLangCode($store->getCode()));

                $store->setHomeUrl($baseUrl);
                $stores[$store->getGroupId()][$store->getId()] = $store;
            }
            $this->setData('raw_stores', $stores);
        }
        return $this->getData('raw_stores');
    }
    
    protected function _getLangCode($storeCode)
    {
        $segments = explode('_', $storeCode);
        return end($segments);
    }
}
