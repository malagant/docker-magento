<?php

/**
 * @package
 * @author Cornelius Adams (conlabz GmbH) <cornelius.adams@conlabz.de>
 */
class Conlabz_Download_Model_ThumbnailsGenerator
{
    /**
     * @return array
     */
    public function getPdfs()
    {
        $globPath = (string) Mage::app()->getConfig()
            ->getNode('global/download/thumbnails/glob_path');
        return glob($globPath, GLOB_BRACE);
    }

    /**
     * generate all thumbnails
     */
    public function generate()
    {

        $pdfs = $this->getPdfs();
        $adapter = new Zend_ProgressBar_Adapter_Console();
        $progressBar = new Zend_ProgressBar($adapter, 0, count($pdfs));
        $i = 1;
        foreach ($pdfs as $file) {
            $this->generateThumbnail($file);
            $progressBar->update($i++);
        }
        $progressBar->finish();
    }

    /**
     *
     * @param string $file
     * @param int $size
     * @return void
     */
    private function generateThumbnail($file, $size = 600)
    {
        $destination = sprintf(
            '%s/thumbnails/%s.jpg',
            dirname($file),
            pathinfo($file, PATHINFO_FILENAME)
        );
        if (is_file($destination)) {
            return;
        }
        $destinationDir = dirname($destination);
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0777, true);
        }

        $img = new imagick();
        $img->readimage($file . '[0]');
        $img->scaleimage($size, 0);
        $img->setimageformat('jpg');
        $img->writeimage($destination);
    }
}
