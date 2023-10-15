<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Contact extends Mage_Core_Block_Template
{
    protected $_template = 'contacts/phone.phtml';

    /**
     *
     * @return array
     */
    public function getCountries()
    {
        return Mage::getResourceModel('directory/country_collection')
            ->loadData()
            ->toOptionArray(false);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getCountrySelectHtml($name = 'country')
    {
        $select = $this->getLayout()->createBlock('core/html_select')
            ->setName($name)
            ->setId($name)
            ->setTitle(Mage::helper('checkout')->__('Country'))
            ->setClass('validate-select')
            ->setOptions($this->getCountries());
        return $select->toHtml();
    }

    /**
     *
     * @return array
     */
    public function getE164()
    {
        $list = array();
        $file = Mage::getModuleDir('etc', 'Conlabz_Skylotec') . DS . 'country-phone-codes.xml';
        $xml = simplexml_load_file($file);
        foreach ($xml->country as $c) {
            $phoneCode = (string) $c->attributes()->phoneCode;
            $list[$phoneCode] = array(
                'value' => strtoupper($c->attributes()->code),
                'label' => $phoneCode
            );
        }
        ksort($list);
        return $list;
    }

    /**
     * @return boolean
     */
    public function success()
    {
        $session = Mage::getSingleton('customer/session');
        $success = $session->getContactFormSuccess();
        $session->unsetData('contact_form_success');
        return $success;
    }
}
