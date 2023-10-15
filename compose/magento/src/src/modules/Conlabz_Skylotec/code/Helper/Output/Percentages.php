<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Helper_Output_Percentages extends Conlabz_Skylotec_Helper_Output_Abstract
{
    /**
     *
     * @var array
     */
    protected $_percentages = array(
        'dynamische_dehnung',
        'mantelanteil',
        'statische_dehnung'
    );

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
        if (in_array($params['attribute'], $this->_percentages)) {
            return $this->_convertPercentage($outputHtml);
        }
        return $outputHtml;
    }

    /**
     *
     * @param float $float
     * @return string
     */
    protected function _convertPercentage($float)
    {
        if (!is_numeric($float)) {
            return $float;
        }
        $percent = round($float * 100, 1);
        return Zend_Locale_Format::toNumber(
            $percent,
            array('locale' => $this->_getCurrentLocale())
        ) . '%';
    }
}
