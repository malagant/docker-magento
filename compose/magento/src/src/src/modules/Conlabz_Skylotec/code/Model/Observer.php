<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Model_Observer
{
    private $called = 1;

    /**
     * Event: m_before_load_filter_collection
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterCategoryFilters(Varien_Event_Observer $observer)
    {
        if ($category = Mage::registry('current_category')) {
            if (!$availableFilters = $category->getAvailableFilters()) {
                return;
            }
            // fix for flat categories
            if (!is_array($availableFilters)) {
                $availableFilters = explode(',', $availableFilters);
            }
            /* @var $collection Mana_Filters_Resource_Filter2_Store_Collection */
            $collection = $observer->getCollection();
            $collection->addFieldToFilter('code', array(
                'in' => $availableFilters
            ));
        }
    }

    /**
     * @event catalog_product_is_salable_after
     * @param Varien_Event_Observer $observer
     */
    public function isProductSalable(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        /** @var Mage_Catalog_Model_Product $product */
        $product = $event->getProduct();

        $salable = $event->getSalable();

        if ($product->isConfigurable()) {
            $childrenSalable = false;
            $children = $product->getTypeInstance(true)->getUsedProducts(null, $product);
            foreach ($children as $child) {
                if ($child->isSalable()) {
                    $childrenSalable = true;
                    break;
                }
            }
            $salable->setIsSalable($childrenSalable);
            return;
        }

        if ($product->getData(Conlabz_Skylotec_Helper_Data::ATTRIBUTE_NOT_SALABLE)) {
            $salable->setIsSalable(false);
        }
    }
    
    /**
     * Event: catalog_controller_category_init_after
     *
     * @param Varien_Event_Observer $observer
     */
    public function addCategoryDivisionToRequest(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        /* @var $category Mage_Catalog_Model_Category */
        $category = $event->getCategory();
        
        $request = Mage::app()->getRequest();
        $categoryDivision = $this->_getHelper()->getDivisionRecursively($category);
        $request->setParam('division', $categoryDivision);
    }
    
    /**
     *
     * @param Varien_Event_Observer $observer
     */
    public function addCategoryAttributes(Varien_Event_Observer $observer)
    {
        $select = $observer->getSelect();
        $select->columns(array(
            'icon',
            'hide_in_overview',
            'internet_only',
            'custom_meta_title',
            'custom_meta_description',
            'children_sort_by_alpha'
        ), 'main_table');
    }

    /**
     * Event: catalog_helper_output_construct
     *
     * @param Varien_Event_Observer $observer
     */
    public function addOutputHelperAggregate(Varien_Event_Observer $observer)
    {
        $outputHelpers = Mage::app()->getConfig()->getNode('global/skylotec/output_helpers')->asArray();
        $outputHelpers = array_filter(array_keys($outputHelpers));
        $outputHelper = $observer->getEvent()->getHelper();
        foreach ($outputHelpers as $helper) {
            $outputHelper->addHandler('productAttribute', Mage::helper('skylotec/output_' . $helper));
        }
    }

    /**
     * Event: category_attribute_source_mode
     *
     * @param Varien_Event_Observer $observer
     */
    public function addCategorySkylopediaType(Varien_Event_Observer $observer)
    {
        $dto = $observer->getEvent()->getDto();
        $options = $dto->getOptions();
        $options[] = array(
            'value' => Conlabz_Skylotec_Model_Category_Displaymode_Skylopedia::DM_SKYLOPEDIA,
            'label' => 'Skylopedia'
        );
        $dto->setOptions($options);
    }

    /**
     *
     * @return Conlabz_Skylotec_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('skylotec');
    }

    public function insertInitialCartItems(Varien_Event_Observer $observer)
    {
        /** @var $checkoutSession Mage_Checkout_Model_Session */
        $session = $observer->getEvent()->getCheckoutSession();

        if ($this->called++ > 1 || $session->getData('initial_cart_items_added')) {
            return;
        }
        $cart = Mage::getSingleton('checkout/cart');

        $sku = (array) Mage::getStoreConfig('checkout/cart/initial_products_in_cart');
        foreach ($sku as $itemSku) {
            $model = Mage::getModel('catalog/product');
            if (!($id = $model->getIdBySku($itemSku))) {
                continue;
            }
            $product = $model->load($id);
            if ($product->getName() === null) {
                continue;
            }
            if ($stockItem = $product->getStockItem()) {
                $stockItem->setCustomerGroupId(Mage_Customer_Model_Group::NOT_LOGGED_IN_ID);
            }
            $cart->addProduct($product, 1);
            $cart->save();
        }
        $session->setData('initial_cart_items_added', true);
    }

    public function categoryOrder(Varien_Event_Observer $observer)
    {
        $select = $observer->getSelect();
        $parent = $observer->getParentNode();
        $category = Mage::registry('current_category');
        if ($parent instanceof Mage_Catalog_Model_Category &&
            $category instanceof Mage_Catalog_Model_Category &&
            (int) $parent->getEntityId() === (int) $category->getEntityId() &&
            (int) $category->getChildrenSortByAlpha() === 1
        ) {
            $select->reset(Zend_Db_Select::ORDER)->order('main_table.name');
        }
    }
}
