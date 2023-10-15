<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_GenticDesign_Model_System_Config_Backend_Date extends Mage_Core_Model_Config_Data
{
    const DATE_FORMAT_INTERNAL = 'MM-dd';
    const DATE_FORMAT_DISPLAY  = 'dd. MMMM';
    
    protected function _beforeSave()
    {
        $value = $this->getValue();
        $formattedDate = $this->_convertDateFrom(
            self::DATE_FORMAT_DISPLAY,
            self::DATE_FORMAT_INTERNAL,
            $value
        );
        $this->setValue($formattedDate);
        parent::_beforeSave();
    }
    
    protected function _afterLoad()
    {
        $value = $this->getValue();
        $formattedDate = $this->_convertDateFrom(
            self::DATE_FORMAT_INTERNAL,
            self::DATE_FORMAT_DISPLAY,
            $value
        );
        $this->setValue($formattedDate);
        parent::_afterLoad();
    }
    
    protected function _convertDateFrom($fromFormat, $toFormat, $dateValue)
    {
        $date = Mage::app()->getLocale()->date(
            $dateValue,
            $fromFormat,
            null,
            false
        );
        return $date->toString($toFormat);
    }
}
