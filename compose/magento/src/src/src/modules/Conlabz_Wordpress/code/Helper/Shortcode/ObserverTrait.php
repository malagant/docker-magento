<?php

/**
 * @package Conlabz_Wordpress
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
trait Conlabz_Wordpress_Helper_Shortcode_ObserverTrait
{
    /**
     * @param Varien_Event_Observer $observer
     */
    public function onWordpressShortcodeApply(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $content = $event->getContent();
        $sContent = $content->getContent();
        $this->_apply(
            $sContent,
            $event->getObject()
        );
        $content->setContent($sContent);
    }
}
