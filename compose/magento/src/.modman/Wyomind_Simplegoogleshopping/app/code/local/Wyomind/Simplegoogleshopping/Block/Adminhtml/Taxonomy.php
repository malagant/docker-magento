<?php
/**
 * Copyright © 2018 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */
class Wyomind_Simplegoogleshopping_Block_Adminhtml_Taxonomy extends Mage_Adminhtml_Block_Template
{

    public function _ToHtml()
    {
        $txt = null;
        $i = 0;
        $io = new Varien_Io_File();

        $realPath = $io->getCleanPath(Mage::getBaseDir() . $this->getRequest()->getParam('file'));
        $io->streamOpen($realPath, "r+");
        while (false !== ($line = $io->streamRead())) {

            if (stripos($line, str_replace('__', '&', $this->getRequest()->getParam('s'))) !== FALSE) {
                $txt.= $line;
            }
        }
        return $txt;
    }

}
