<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Model_Resource_Product extends Mage_Catalog_Model_Resource_Abstract
{
    /**
     *
     * @param string|array $skus
     * @return array
     */
    public function getParentSkusByChildSkus($skus)
    {
        if (!is_array($skus)) {
            $skus = array($skus);
        }
        $read = Mage::getSingleton('core/resource')
            ->getConnection('core_read');
        
        $select = $read
            ->select()
            ->distinct()
            ->from(
                array('e' => $this->getTable('catalog/product')),
                array('e.sku', 'parent_sku' => 'p.sku')
            )
            ->join(
                array('l' => $this->getTable('catalog/product_super_link')),
                'l.product_id = e.entity_id',
                array()
            )
            ->joinLeft(
                array('p' => $this->getTable('catalog/product')),
                'l.parent_id = p.entity_id',
                array()
            )
            ->where('e.sku IN(?)', $skus);
        
        $stmt = $select->query();
        $result = $stmt->fetchAll();
        return $result;
    }
}
