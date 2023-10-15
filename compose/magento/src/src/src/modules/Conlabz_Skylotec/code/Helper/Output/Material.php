<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Helper_Output_Material extends Conlabz_Skylotec_Helper_Output_Abstract
{
    /**
     * valid material attribute codes
     *
     * @var array
     */
    protected $_materials = array(
        'material_band',
        'material_brustoese',
        'material_halteoese',
        'material_rettungsoese',
        'material_rueckenoese',
        'material_rueckhalteoese',
        'material_seil',
        'material_sitzgurtoese',
        'material_steigschutzoese',
        'material_schloss'
    );

    /**
     *
     * @var Conlabz_Skylotec_Model_Material
     */
    protected $_materialsModel;

    /**
     *
     * @param Mage_Catalog_Helper_Output $outputHelper
     * @param string $outputHtml
     * @param array $params
     * @return string
     */
    public function productAttribute(
        Mage_Catalog_Helper_Output $outputHelper,
        $outputHtml,
        $params
    ) {
        if (in_array($params['attribute'], $this->_materials)) {
            return $this->_getMaterialsModel()->getMaterialNameByCode($outputHtml);
        }
        return $outputHtml;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    protected function _renderMaterials(Mage_Catalog_Model_Product $product)
    {
        $materials = array();
        for ($i = 1; $i <= 3; $i++) {
            if ($materialValue = $product->getData('material_' . $i)) {
                $material = $this->_getMaterialsModel()->getMaterialNameByCode($materialValue);
                if ($coating = $this->_getMaterialsModel()->getCoatingNameByCode($product->getData('coating_' . $i))) {
                    $material .= ' (' . $coating . ')';
                }
                if ($materialContent = $product->getData('material_anteil_' . $i)) {
                    $material = $materialContent . '% ' . $material;
                }
                $materials[] = $material;
            }
        }
        return implode(', ', $materials);
    }

    /**
     *
     * @return Conlabz_Skylotec_Model_Material
     */
    protected function _getMaterialsModel()
    {
        if (null === $this->_materialsModel) {
            $this->_materialsModel =  Mage::getSingleton('skylotec/material');
        }
        return $this->_materialsModel;
    }
}
