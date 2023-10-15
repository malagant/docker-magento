<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Helper_Output_Weight extends Conlabz_Skylotec_Helper_Output_Abstract
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
        if ($params['attribute'] === 'weight') {
            return $this->__(
                '%s',
                str_replace('.', ',', $this->convertWeight($outputHtml))
            );
        }
        return $outputHtml;
    }
}
