<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_GenticDesign_Block_Socialmedia_Facebook extends Conlabz_GenticDesign_Block_Socialmedia_Default
{
    protected $_template = 'socialmedia/facebook.phtml';
    
    const SHARER_URL = 'https://www.facebook.com/sharer/sharer.php';
    
    public function getHref()
    {
        return self::SHARER_URL . '?' . $this->_buildQuery();
    }
    
    protected function _buildQuery()
    {
        return http_build_query(array(
            'p' => array(
                'url' => $this->helper('core/url')->getCurrentUrl()
            )
        ));
    }
}
