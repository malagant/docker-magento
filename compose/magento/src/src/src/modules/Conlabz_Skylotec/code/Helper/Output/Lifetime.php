<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Helper_Output_Lifetime extends Conlabz_Skylotec_Helper_Output_Abstract
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
        if ($params['attribute'] === 'max_lebensdauer') {
            return $this->__('%s Years', $outputHtml);
        }
        return $outputHtml;
    }
}
