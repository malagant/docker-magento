<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_Page_Html_Footer_News extends Conlabz_Skylotec_Block_Wordpress_List_Abstract
{
    protected $_template = 'page/html/footer/news.phtml';
    
    /**
     *
     * @param int $limit
     * @return Fishpig_Wordpress_Model_Post[]
     */
    public function getLatestNews($limit = 2)
    {
        /* @var $news Fishpig_Wordpress_Model_Resource_Post_Collection */
        $news = $this->getPostCollection();
        $news->addPostTypeFilter('post');
        $news->setOrder('post_date', 'DESC');
        $news->getSelect()->limit($limit);
        return $news;
    }

    public function successNewsletterSubscription()
    {
        $customerSession = Mage::getSingleton('customer/session');
        $subscription = $customerSession->getData('trackNewsletterSubscription');
        $customerSession->setData('trackNewsletterSubscription', false);
        return ($subscription === true);
    }
}
