<?php

/**
 * @package Coad_Pjax
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Coad_Pjax_Helper_Data
    extends Mage_Core_Helper_Abstract
{
    /**
     * 
     * @param string $container
     * @return string
     */
    public function extractBlockFromContainer($container)
    {
        $blockname = str_replace(
            Coad_Pjax_Model_Pjax::PJAX_CONTAINER_PREFIX,
            '', 
            $container
        );
        return ltrim($blockname, '#');
    }
}
