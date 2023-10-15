<?php

/**
 * @package Conlabz_GenticDesign
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_GenticDesign_Block_Socialmedia extends Mage_Core_Block_Template
{
    const DEFAULT_RENDERER = 'genticdesign/socialmedia_default';
    
    /**
     *
     * @var string
     */
    protected $_template = 'socialmedia/list.phtml';
    
    /**
     *
     * @var bool
     */
    protected $_isLink = false;
    
    /**
     * available social plugins
     *
     * @var array
     */
    protected $_buttons = array(
        'facebook' => 'genticdesign/socialmedia_facebook',
        'pinterest' => 'genticdesign/socialmedia_pinterest',
        'instagram',
        'twitter'
    );
    
    /**
     *
     * @param bool $flag
     * @return \Conlabz_GenticDesign_Block_Socialmedia
     */
    public function setIsLink($flag = true)
    {
        $this->_isLink = (bool) $flag;
        return $this;
    }
    
    /**
     *
     * @return bool
     */
    public function isLink()
    {
        return $this->_isLink;
    }
    
    /**
     *
     * @param string $type
     * @return array
     */
    public function getSocialButtons()
    {
        $buttons = array();
        foreach ($this->_buttons as $name => $renderer) {
            if (is_int($name)) {
                if (!$this->isLink()) {
                    continue;
                }
                $name = $renderer;
            }
            $renderer = $this->_getRenderer($name);
            $buttons[] = $this->getLayout()->createBlock(
                $renderer,
                '',
                array(
                    'href'   => $this->_getSocialUrl($name),
                    'class' => 'social-' . $name,
                    'name'  => $name
                )
            );
        }
        return $buttons;
    }
    
    protected function _getRenderer($name)
    {
        if ($this->isLink()) {
            return self::DEFAULT_RENDERER;
        }
        return isset($this->_buttons[$name])
            ? $this->_buttons[$name]
            : self::DEFAULT_RENDERER;
    }
    
    /**
     *
     * @todo implement type
     * @param string $name
     * @param string $type
     * @return type
     */
    protected function _getSocialUrl($name)
    {
        $socialUrls = $this->_getHelper()->getSocialMediaLinks();
        return isset($socialUrls[$name])
            ? $socialUrls[$name]
            : '#';
    }
    
    /**
     *
     * @return Conlabz_GenticDesign_Helper_Data
     */
    protected function _getHelper()
    {
        return $this->helper('genticdesign');
    }
}
