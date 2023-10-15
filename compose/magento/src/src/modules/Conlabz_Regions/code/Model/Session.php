<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Regions_Model_Session extends Mage_Core_Model_Session_Abstract
{
    protected function _construct()
    {
        $this->init('regions');
    }
}
