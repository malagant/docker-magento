<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Athlete_List extends Fishpig_Wordpress_Block_Post_List_Wrapper_Abstract
{
    protected $_template = 'athlete/list.phtml';

    public function getPostCollection()
    {
        $collection = parent::getPostCollection()
            ->addPostTypeFilter('athlete');
        return $collection;
    }
    
    public function canDisplay()
    {
        return true;
    }
}
