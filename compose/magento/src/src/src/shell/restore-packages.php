<?php

include __DIR__ . '/abstract.php';

class Conlabz_Shell_RestorePackages extends Mage_Shell_Abstract
{
    public function run()
    {
        $contents       = file_get_contents(__DIR__ . '/../downloader/cache.cfg');
        $targetDir      = __DIR__ . '/../var/package';
        $uncompressed   = gzuncompress($contents);
        $unserialized   = unserialize($uncompressed);
        $packages       = $unserialized['channels_by_name']['community']['packages'];

        foreach ($packages as $package) {
            $fileName = $this->_getFileName($package);
            $targetFile = $targetDir . '/' . $fileName;
            if (!file_exists($targetFile)) {
                file_put_contents($targetFile, $package['xml']);
                echo 'Restored Package: ' . pathinfo($fileName, PATHINFO_FILENAME) . PHP_EOL;
            }
        }        
    }
    
    protected function _getFileName(array $package) 
    {
        return sprintf('%s-%s.xml', $package['name'], $package['version']);
    }
}

$shell = new Conlabz_Shell_RestorePackages();
$shell->run();
