<?php

/**
 * @package Conlabz_Skylotec
 * @author David Pommer (conlabz GmbH) <david.pommer@conlabz.de>
 */
class Conlabz_Skylotec_Model_Resource_Product_Attribute_Backend_Media extends Mage_Catalog_Model_Resource_Product_Attribute_Backend_Media
{
    public function getMediaBy($search)
    {
        $adapter = $this->_getReadAdapter();

        // Select gallery images for product
        $select = $adapter->select()
            ->from(
                array('main'=>$this->getMainTable()),
                array('value AS file', 'product_id' => 'entity_id')
            )
            ->joinLeft(
                array('value' => $this->getTable(self::GALLERY_VALUE_TABLE)),
                $adapter->quoteInto('main.value_id = value.value_id AND value.store_id = ?', (int) Mage::app()->getStore()->getId()),
                array('label','position','disabled')
            )
            ->where('main.attribute_id = ?', $this->_getAttributeId())
            ->where('main.value LIKE ?', '%'.$search.'%')
            ->order('value.position ' . Varien_Db_Select::SQL_ASC);

        return $adapter->fetchAll($select);
    }
}
