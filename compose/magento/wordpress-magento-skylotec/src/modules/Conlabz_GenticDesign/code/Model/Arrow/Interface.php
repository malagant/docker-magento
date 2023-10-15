<?php

/**
 * @package Conlabz_GenticDesign
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
interface Conlabz_GenticDesign_Model_Arrow_Interface
{
    public function getNextUrl();
    public function getPrevUrl();
    public function getNextLabel();
    public function getPrevLabel();
}
