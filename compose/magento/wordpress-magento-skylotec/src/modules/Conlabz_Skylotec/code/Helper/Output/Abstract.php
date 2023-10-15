<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
abstract class Conlabz_Skylotec_Helper_Output_Abstract extends Mage_Core_Helper_Abstract
{
    /**
     *
     * @var Parsedown
     */
    protected $_markDownParser;

    /**
     * @var HTMLPurifier
     */
    protected $_htmlPurifier;

    /**
     *
     * @var string
     */
    protected $_currentLocale;

    /**
     *
     * @var array
     */
    protected $_unitMap = array(
        'en_US' => Zend_Measure_Weight::LBS
    );
    
    /**
     *
     * @param string $text
     * @return string
     */
    public function markDown($text)
    {
        $text = trim($this->removeTags($text));
        if (null === $this->_markDownParser) {
            $this->_markDownParser = new Parsedown();
        }
        return $this->_markDownParser->text($text);
    }

    public function purify($text)
    {
        if (null === $this->_htmlPurifier) {
            $config = HTMLPurifier_Config::createDefault();
            $config->set('CSS.AllowedFonts', '');
            $config->set('HTML.Allowed', 'div,b,strong,i,em,u,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]');
            $config->set('CSS.AllowedProperties', 'font-size,font-weight,font-style,text-decoration,padding-left,margin-left,text-indent,text-align');
            $config->set('AutoFormat.AutoParagraph', true);
            $config->set('AutoFormat.RemoveEmpty', true);
            $this->_htmlPurifier = new HTMLPurifier($config);
        }
        return $this->_htmlPurifier->purify($text);
    }
    /**
     *
     * @return string
     */
    protected function _getCurrentLocale()
    {
        if (null === $this->_currentLocale) {
            $this->_currentLocale = Mage::app()->getLocale()->getLocaleCode();
        }
        return $this->_currentLocale;
    }
    
    /**
     *
     * @param string $value
     * @return string
     */
    public function convertWeight($value)
    {
        $locale = Mage::app()->getLocale()->getLocale();

        $value = Zend_Locale_Format::toNumber(
            $value,
            array(
                'locale' => $locale
            )
        );

        $weight = new Zend_Measure_Weight(
            $value,
            Zend_Measure_Weight::KILOGRAM,
            $locale
        );

        $unit = $this->getUnit();
        return $weight->convertTo($unit);
    }

    /**
     *
     * @return string
     */
    public function getUnit()
    {
        $locale = Mage::app()->getLocale()->getLocaleCode();
        return isset($this->_unitMap[$locale])
            ? $this->_unitMap[$locale]
            : Zend_Measure_Weight::KILOGRAM;
    }

    /**
     *
     * @param Mage_Catalog_Helper_Output $outputHelper
     * @param string $outputHtml
     * @param array $params
     * @return string
     */
    abstract public function productAttribute(
        Mage_Catalog_Helper_Output $outputHelper,
        $outputHtml,
        $params
    );
}
