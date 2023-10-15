<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Skylotec_Block_News_Archive extends Conlabz_Skylotec_Block_Wordpress_List_Abstract
{
    public function getArchives()
    {
        $collection = $this->getPostCollection();
        $this->_addDivisionFilter($collection);

        /* @var $select Varien_Db_Select */
        $select = $collection->getSelect();
        $select->columns(array(
            'archive_param' => "DATE_FORMAT(`post_date`,'%Y-%m')",
            'month' => 'DATE_FORMAT(post_date, "%M")',
            'year'  => 'YEAR(post_date)'
        ));

        $select->reset(Zend_Db_Select::GROUP);
        $select->group('archive_param');

        return $collection;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getArchiveLabel()
    {
        $archive = $this->getRequest()->getParam('archive');
        if (!$archive) {
            return false;
        }
        $date = new DateTime($archive . '-01');
        $month = $this->__($date->format('F'));
        $year = $date->format('Y');
        return $this->__('Archive: %s %s', $month, $year);
    }

    public function getArchivesYearMonths()
    {
        $archives = array();
        foreach ($this->getArchives() as $archive) {
            $year = $archive->getYear();
            if (!isset($archives[$year])) {
                $archives[$year] = array();
            }
            $archives[$year][] = $archive;
        }
        return $archives;
    }

    public function isCurrentArchive($archive)
    {
        return $this->getRequest()->getParam('archive') === $archive->getArchiveParam();
    }

    public function getArchiveUrl($archive)
    {
        $query = array(
            'archive' => $archive->getArchiveParam()
        );
        if ($this->isCurrentArchive($archive)) {
            $query = null;
        }
        return Mage::getUrl('*/*/*', array(
            '_use_rewrite' => true,
            '_query' => $query
        ));
    }
}
