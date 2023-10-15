<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Model_System_Config_Source_Division extends Mage_Eav_Model_Entity_Attribute_Source_Table
{
    /**
     * @param bool $withEmpty
     * @param false $defaultValues
     * @return array|mixed
     */
    public function getAllOptions($withEmpty = true, $defaultValues = false)
    {
        $values = array(
            ''                  => '',
            'industry'          => 'Industry',
            'sport'             => 'Sport',
            'permanent-systems' => 'Permanent Systems'
        );
        foreach ($values as $value => $label) {
            $options[] = array(
                'value' => $value,
                'label' => $label
            );
        }
        return $options;
    }
}
