<?php

/**
 * @package Conlabz_Skylotec
 * @author David Pommer (conlabz GmbH) <david.pommer@conlabz.de>
 */
class Conlabz_Skylotec_Model_Resource_Category_Flat extends Mage_Catalog_Model_Resource_Category_Flat
{
    protected function _loadNodes($parentNode = null, $recursionLevel = 0, $storeId = 0, $onlyActive = true)
    {
        $_conn = $this->_getReadAdapter();
        $startLevel = 1;
        $parentPath = '';
        if ($parentNode instanceof Mage_Catalog_Model_Category) {
            $parentPath = $parentNode->getPath();
            $startLevel = $parentNode->getLevel();
        } elseif (is_numeric($parentNode)) {
            $selectParent = $_conn->select()
                ->from($this->getMainStoreTable($storeId))
                ->where('entity_id = ?', $parentNode)
                ->where('store_id = ?', $storeId);
            $parentNode = $_conn->fetchRow($selectParent);
            if ($parentNode) {
                $parentPath = $parentNode['path'];
                $startLevel = $parentNode['level'];
            }
        }
        $select = $_conn->select()
            ->from(
                array('main_table' => $this->getMainStoreTable($storeId)),
                array('entity_id',
                    new Zend_Db_Expr('main_table.' . $_conn->quoteIdentifier('name')),
                    new Zend_Db_Expr('main_table.' . $_conn->quoteIdentifier('path')),
                    'is_active',
                'is_anchor')
            )

            ->where('main_table.include_in_menu = ?', '1')
            ->order('main_table.position');

        if ($onlyActive) {
            $select->where('main_table.is_active = ?', '1');
        }

        /** @var $urlRewrite Mage_Catalog_Helper_Category_Url_Rewrite_Interface */
        $urlRewrite = $this->_factory->getCategoryUrlRewriteHelper();
        $urlRewrite->joinTableToSelect($select, $storeId);

        if ($parentPath) {
            $select->where($_conn->quoteInto("main_table.path like ?", "$parentPath/%"));
        }
        if ($recursionLevel != 0) {
            $levelField = $_conn->quoteIdentifier('level');
            $select->where($levelField . ' <= ?', $startLevel + $recursionLevel);
        }

        $inactiveCategories = $this->getInactiveCategoryIds();

        if (!empty($inactiveCategories)) {
            $select->where('main_table.entity_id NOT IN (?)', $inactiveCategories);
        }

        // Allow extensions to modify select (e.g. add custom category attributes to select)
        Mage::dispatchEvent('catalog_category_flat_loadnodes_before', array(
            'select' => $select,
            'parent_node' => $parentNode
        ));

        $arrNodes = $_conn->fetchAll($select);
        $nodes = array();
        foreach ($arrNodes as $node) {
            $node['id'] = $node['entity_id'];
            $nodes[$node['id']] = Mage::getModel('catalog/category')->setData($node);
        }

        return $nodes;
    }
}
