<?php

/**
 * @package Conlabz_Skylotec
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
interface Conlabz_Skylotec_Block_Teaser_Interface
{
    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return bool
     */
    public function canDisplay();

    /**
     * @return string
     */
    public function getImageUrl();

    /**
     * @return string
     */
    public function getIcon();

    /**
     * @return string
     */
    public function getDescription();
}
