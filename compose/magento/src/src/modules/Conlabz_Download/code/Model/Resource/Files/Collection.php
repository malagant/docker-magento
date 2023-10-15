<?php

/**
 * @package Conlabz_Download
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Download_Model_Resource_Files_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     *
     * @var array
     */
    protected $_searchableColumns = array(
        'product_sku',
        'product_title'
    );

    public function _construct()
    {
        parent::_construct();
        $this->_init('dstorage/files');
    }

    public function _beforeLoad()
    {
        $this->addFieldToFilter('category_title', array('notnull' => true));
        $this->addFieldToFilter('product_title', array('notnull' => true));
        return parent::_beforeLoad();
    }

    /**
     *
     * @param string $division
     * @return Conlabz_Download_Model_Resource_Files_Collection
     */
    public function addDivisionFilter($division)
    {
        $this->addFieldToFilter('division', array(
            array('finset'  => $division),
            array('null'    => true)
        ));
        return $this;
    }

    /**
     *
     * @param string $searchTerm
     * @return Conlabz_Download_Model_Resource_Files_Collection
     */
    public function search($searchTerm)
    {
        $conditions = array();
        for ($i = 0; $i < count($this->_searchableColumns); $i++) {
            $conditions[] = array(
                'like' => '%' . $searchTerm . '%'
            );
        }
        $this->addFieldToFilter(
            $this->_searchableColumns,
            $conditions
        );
        return $this;
    }

    /**
     * @param Conlabz_Download_Model_Queue $queue
     * @return $this
     */
    public function addQueueFilter(Conlabz_Download_Model_Queue $queue)
    {
        $ids = array();
        foreach ($queue->getFilesCollection() as $file) {
            $ids[] = $file->getFileId();
        }
        $this->getSelect()
            ->where('main_table.id IN(?)', $ids);
        return $this;
    }
}
