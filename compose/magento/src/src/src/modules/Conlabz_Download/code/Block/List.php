<?php

/**
 * @package Conlabz_Download
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Download_Block_List extends Mage_Core_Block_Template
{
    /**
     *
     * @var Conlabz_Download_Model_Resource_Files_Collection
     */
    protected $_files;

    /**
     * @return Conlabz_Download_Model_Files[]
     */
    public function getFiles()
    {
        if (null === $this->_files) {
            /** @var Conlabz_Download_Model_Resource_Files_Collection $collection */
            $collection = Mage::getModel('dstorage/files')->getCollection();
            $request = $this->getRequest();
            if ($division = $request->getParam('division')) {
                $collection->addDivisionFilter($division);
            }
            if ($searchTerm = $request->getParam('qd')) {
                $collection->search($searchTerm);
            }
            if ($fileCategory = $request->getParam('file_category')) {
                $collection->addFieldToFilter('file_category', $fileCategory);
            }
            if ($productCategory = $request->getParam('product_category')) {
                $collection->addFieldToFilter('product_category', array(
                    'finset' => $productCategory
                ));
            }
            $collection->getSelect()->order(array('file_title', 'product_title'));
            $this->_files = $collection;
        }
        return $this->_files;
    }

    protected function _beforeToHtml()
    {
        if ($pager = $this->getChild('pager')) {
            $pager->setCollection($this->getFiles());
        }
        return parent::_beforeToHtml();
    }

    public function getPagerHtml()
    {
        if ($pager = $this->getChild('pager')) {
            $pager->setCollection($this->getFiles());
            return $pager->toHtml();
        }
        return '';
    }
}
