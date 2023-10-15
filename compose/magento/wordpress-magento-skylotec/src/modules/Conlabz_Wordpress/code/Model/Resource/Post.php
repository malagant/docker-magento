<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
// @codingStandardsIgnoreStart
class Conlabz_Wordpress_Model_Resource_Post extends Fishpig_Wordpress_Model_Resource_Post
// @codingStandardsIgnoreEnd
{
    /**
     * @var array
     */
    protected static $_uriCache;

    /**
     * Get an array of post ID's and permalinks
     * $filters is applied but if empty, all permalinks are returned
     *
     * @param array $filters = array()
     * @return array|false
     */
    // @codingStandardsIgnoreStart
    public function getPermalinks(array $filters = array(), $postType)
    {
        $tokens = $postType->getExplodedPermalinkStructure();
        $fields = $this->getPermalinkSqlFields();

        $select = $this->_getReadAdapter()
            ->select()
            ->from(array('main_table' => $this->getMainTable()), array('id' => 'ID', 'permalink' => $this->getPermalinkSqlColumn()))
            ->where('post_type = ?', $postType->getPostType())
            ->where('post_status IN (?)', array('publish', 'protected', 'private'));

        foreach ($filters as $field => $value) {
            if (isset($fields[$field])) {
                $select->where($fields[$field] . '=?', urlencode($value));
            }
        }

        Mage::getResourceSingleton('conwp/translation')->joinLanguage($select);

        if ($routes = $this->_getReadAdapter()->fetchPairs($select)) {
            foreach ($routes as $id => $permalink) {
                $routes[$id] = urldecode($this->completePostSlug($permalink, $id, $postType));
            }

            return $routes;
        }

        return false;
    }
    // @codingStandardsIgnoreEnd

    /**
     * Custom load SQL
     *
     * @param string $field - field to match $value to
     * @param string|int $value - $value to load record based on
     * @param Mage_Core_Model_Abstract $object - object we're trying to load to
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        if (!$this->_getHelper()->isEnabled()) {
            return $select;
        }
        //Mage::getResourceSingleton('conwp/translation')->joinLanguage($select, true, 'e');
        return $select;
    }

    /**
     *
     * @return Conlabz_Wordpress_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('conwp');
    }

    /**
     * Retrieve all possible page URIs for specific language
     *
     * @return array
     */
    public function getAllUris()
    {
        if (!$this->_getHelper()->isEnabled()) {
            return parent::getAllUris();
        }
        if (is_array(self::$_uriCache)) {
            return self::$_uriCache;
        }
        $select = $this->_getReadAdapter()
            ->select()
            ->from(array('main_table' => $this->getMainTable()), array('id' => 'ID','url_key' =>  'post_name', 'parent' => 'post_parent'))
            ->where('main_table.post_type = ?', 'page')
            ->where('main_table.post_status = ?', 'publish');

        Mage::getResourceSingleton('conwp/translation')->joinLanguage($select);

        self::$_uriCache = Mage::helper('wordpress/router')->generateRoutesFromArray(
            $this->_getReadAdapter()->fetchAll($select)
        );

        return self::$_uriCache;
    }

    public function hasTranslation($elementId, $language)
    {
        try {
            $table = Mage::getSingleton('core/resource')
                ->getMappedTableName('icl_translations');
            $select = $this->_getReadAdapter()
                ->select(array('trid'))
                ->from(array('t' => $table))
                ->where('t.element_id = ?', $elementId)
                ->limit(1);
            $row = $this->_getReadAdapter()->fetchRow($select);
            if (isset($row['trid'])) {
                $select = $this->_getReadAdapter()
                    ->select()
                    ->from(array('t' => $table))
                    ->where('t.trid = ?', $row['trid'])
                    ->where('t.language_code = ?', $language)
                    ->where('t.source_language_code IS NOT NULL')
                    ->limit(1);
                $row = $this->_getReadAdapter()->fetchRow($select);
                if (isset($row['element_id'])) {
                    return $row['element_id'];
                }
            }
        } catch (\Throwable $e) {
        }
        return false;
    }

    /**
     * Retrieve an array of post types
     *
     * @param array|bool $excludeDefault = false
     * @return array
     */
    public function getAllPostTypes($excludeDefault = false)
    {
        $select = $this->_getReadAdapter()
            ->select()
            ->distinct()
            ->from($this->getMainTable(), 'post_type');

        if ($excludeDefault === true) {
            $select->where('post_type NOT IN (?)', array(
                'post',
                'page',
                'nav_menu_item',
                'revision',
                'attachment',
            ));
        } elseif (is_array($excludeDefault)) {
            $select->where('post_type NOT IN (?)', $excludeDefault);
        }

        return $this->_getReadAdapter()->fetchCol($select);
    }
}
