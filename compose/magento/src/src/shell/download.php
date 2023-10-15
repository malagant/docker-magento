<?php

require __DIR__ . '/abstract.php';

class Conlabz_Shell_Download extends Mage_Shell_Abstract
{
    public function usageHelp()
    {
        $help = parent::usageHelp();
        $help.= <<<USAGE

  --thumbs              generate thumbnails
  --download-queue      process download queue
  --cleanup-packages    cleanup old packages

USAGE;
        echo $help;
    }

    public function run()
    {
        if ($this->getArg('thumbs')) {
            $generator = Mage::getModel('dstorage/thumbnailsGenerator');
            $generator->generate();
        } elseif ($this->getArg('download-queue')) {
            /** @var Conlabz_Download_Model_Queue $queue */
            $queue = Mage::getModel('dstorage/queue');
            $queue->process();
        } elseif ($this->getArg('cleanup-packages')) {
            /** @var Conlabz_Download_Model_Observer $observer */
            $observer = Mage::getModel('dstorage/observer');
            $observer->cleanupPackages();
        } else {
            $this->usageHelp();
        }
    }
}

$shell = new Conlabz_Shell_Download();
$shell->run();
