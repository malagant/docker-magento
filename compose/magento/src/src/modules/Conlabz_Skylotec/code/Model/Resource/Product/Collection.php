<?php
/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Model_Resource_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{
    const SORT_ATTR = 'reihenfolge';
    
    public function addAttributeToSort($attribute, $dir = 'ASC')
    {
        $attribute = self::SORT_ATTR;
        return parent::addAttributeToSort($attribute, $dir);
    }
}
