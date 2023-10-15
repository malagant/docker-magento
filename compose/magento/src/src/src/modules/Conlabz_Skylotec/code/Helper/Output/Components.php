<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Helper_Output_Components extends Conlabz_Skylotec_Helper_Output_Abstract
{
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
        if ($params['attribute'] === 'set_komponenten') {
            /* @var $componentModel Conlabz_Skylotec_Model_SetComponents */
            $componentsModel = Mage::getSingleton('skylotec/setComponents');
            $components = $componentsModel->getComponentsByString($outputHtml);
            if (!$components) {
                return $outputHtml;
            }
            /* @var $componentsBlock Conlabz_Skylotec_Block_SetComponents */
            $componentsBlock = Mage::app()->getLayout()
                ->createBlock('skylotec/setComponents');
            $componentsBlock->setSetComponents(
                $components
            );
            return $componentsBlock->toHtml();
        }
        return $outputHtml;
    }
}
