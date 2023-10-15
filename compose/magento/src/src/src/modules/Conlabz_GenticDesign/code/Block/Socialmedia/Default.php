<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_GenticDesign_Block_Socialmedia_Default extends Mage_Core_Block_Template
{
    protected $_template = 'socialmedia/default.phtml';
            
    public function __toString()
    {
        return $this->toHtml();
    }
}
