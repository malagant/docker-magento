<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_GenticDesign_Block_Page_Html_Arrow extends Mage_Core_Block_Template
{
    const DIRECTION_LEFT  = 'left';
    const DIRECTION_RIGHT = 'right';
    
    /**
     * valid directions
     *
     * @var array
     */
    protected $_validDirections = array(
        self::DIRECTION_LEFT,
        self::DIRECTION_RIGHT
    );
    
    /**
     *
     * @var string
     */
    protected $_link;
    
    /**
     *
     * @var string
     */
    protected $_label;
    
    /**
     *
     * @var string
     */
    protected $_template = 'page/html/arrow.phtml';
    
    /**
     *
     * @var string
     */
    protected $_direction;
    
    /**
     *
     * @param string $direction
     */
    public function setDirection($direction)
    {
        if (in_array($direction, $this->_validDirections)) {
            $this->_direction = $direction;
        } else {
            $this->_direction = self::DIRECTION_LEFT;
        }
    }
    
    /**
     *
     * @return string
     */
    public function getDirection()
    {
        return $this->_direction;
    }
    
    /**
     *
     * @return string
     */
    public function getLink()
    {
        return $this->_link;
    }
    
    /**
     *
     * @param type $link
     * @return \Conlabz_GenticDesign_Block_Arrow
     */
    public function setLink($link)
    {
        $this->_link = $link;
        return $this;
    }
    
    /**
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->_label;
    }

    /**
     *
     * @param string $label
     * @return \Conlabz_GenticDesign_Block_Page_Html_Arrow
     */
    public function setLabel($label)
    {
        $this->_label = $label;
        return $this;
    }
        
    /**
     *
     * @return bool
     */
    public function canDisplay()
    {
        return (!empty($this->_link) && !empty($this->_direction));
    }
}
