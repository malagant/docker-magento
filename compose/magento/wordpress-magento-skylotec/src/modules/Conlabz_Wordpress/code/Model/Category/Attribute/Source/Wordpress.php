<?php

/**
 * @package Conlabz_Wordpress
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Wordpress_Model_Category_Attribute_Source_Wordpress extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions()
    {
        $options = $this->_getPostTypes();
        $options = array_merge($options, $this->_getPosts());
        return $options;
    }

    protected function _getPostTypes()
    {
        $postTypes = array();
        $allPostTypes = $this->_getAllPostTypes();
        foreach ($allPostTypes as $postType) {
            $postTypes[] = array(
                'value' => $postType,
                'label' => ucfirst($postType)
            );
        }
        return array(
            array(
                'label' => 'Post types',
                'value' => $postTypes
            )
        );
    }

    protected function _getAllPostTypes()
    {
        $allPostTypes = Mage::getResourceModel('wordpress/post')
            ->getAllPostTypes();
        return $allPostTypes;
    }

    protected function _getPosts()
    {
        /* @var $posts Fishpig_Wordpress_Model_Resource_Post_Collection */
        $posts = Mage::getResourceModel('conwp/post_collection');
        $posts->addIsPublishedFilter();
        $posts->addIsViewableFilter();
        Mage::getResourceSingleton('conwp/translation')->joinLanguage(
            $posts->getSelect()
        );
        $options = array();
        $posts->addOrder('post_title', 'asc');
        foreach ($posts as $post) {
            $options[] = array(
                'value' => $post->getId(),
                'label' => sprintf(
                    '%s: %s (%s)',
                    $post->getId(),
                    $post->getPostTitle(),
                    $post->getLanguageCode()
                )
            );
        }
        return array(
            array(
                'label' => 'Pages',
                'value' => $options
            )
        );
    }
}
