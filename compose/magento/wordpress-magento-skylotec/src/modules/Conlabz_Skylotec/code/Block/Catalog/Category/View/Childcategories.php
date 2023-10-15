<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Catalog_Category_View_Childcategories extends Conlabz_Skylotec_Block_Rewrite_Category_View
{
    /**
     *
     * @var string
     */
    protected $_template = 'catalog/category/view/childcategories.phtml';

    /**
     *
     * @return bool
     */
    public function hasChildren()
    {
        return $this->getCurrentCategory()->hasChildren();
    }

    public function getChildren()
    {
        return $this->getCurrentCategory()->getChildrenCategories();
    }

    public function getDescription()
    {
        return $this->getCurrentCategory()->getInternetOnly();
    }
}
