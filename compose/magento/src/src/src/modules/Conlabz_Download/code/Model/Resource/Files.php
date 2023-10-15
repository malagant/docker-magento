<?php
class Conlabz_Download_Model_Resource_Files extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('dstorage/files', 'id');
    }

    /**
     * @param string $sku
     * @return int
     */
    public function deleteBySku($sku)
    {
        return $this->_getWriteAdapter()->delete(
            $this->getMainTable(),
            ['product_sku = ?' => $sku]
        );
    }
}
