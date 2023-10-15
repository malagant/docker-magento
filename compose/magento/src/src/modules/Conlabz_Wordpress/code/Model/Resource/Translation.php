<?php

/**
 * @package Conlabz_Wordpress
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Wordpress_Model_Resource_Translation extends Fishpig_Wordpress_Model_Resource_Abstract
{
    const FALLBACK_LANGUAGE = 'en';

    protected function _construct()
    {
        $this->_init('icl_translations', 'translation_id');
    }

    /**
     *
     * @param Zend_Db_Select $select
     * @return \Zend_Db_Select
     */
    public function joinLanguage(Zend_Db_Select $select, $where = true, $alias = 'main_table')
    {
        $store = $this->_getStoreId();
        if (!Mage::getStoreConfigFlag('wordpress/module/enable_wpml', $store)) {
            return $select;
        }
        $languageCode = Mage::helper('conwp')->getLanguageCode();
        if (!$languageCode) {
            $where = false;
        }
        $table = Mage::getSingleton('core/resource')
            ->getMappedTableName($this->getMainTable());
        
        $select
            //->group(array(sprintf('%s.ID', $alias)))
            ->columns('t.language_code')
            ->join(
                array('t' => $table),
                sprintf('t.element_id = %s.ID AND ', $alias) .
                sprintf('t.element_type = CONCAT("post_", %s.post_type)', $alias)
            );
        if ($where) {
            $select->where('t.language_code = ?', $languageCode);
        }
        return $select;
    }

    protected function _getStoreId()
    {
        if (Mage::app()->getStore()->isAdmin()) {
            return Mage::app()->getRequest()->getParam('store');
        }
        return null;
    }

    /**
     * @param Zend_Db_Select $select
     * @param bool $where
     * @param string $alias
     * @return Zend_Db_Select
     * @throws Zend_Db_Select_Exception
     */
    public function joinTaxonomyLanguage(Zend_Db_Select $select, $where = true, $alias = 'main_table')
    {
        $languageCode = Mage::helper('conwp')->getLanguageCode();
        if (!$languageCode) {
            $where = false;
        }
        $table = Mage::getSingleton('core/resource')
            ->getMappedTableName($this->getMainTable());

        $select
            ->group(array(sprintf('%s.term_id', $alias)))
            ->columns('t.language_code')
            ->join(
                array('t' => $table),
                sprintf('t.element_id = %s.term_id', $alias)
            );
        if ($where) {
            $select->where('t.language_code = ?', $languageCode);
        }
        return $select;
    }
}
