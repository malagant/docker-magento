<?php

class Conlabz_Skylotec_Model_Asset extends Mage_Core_Model_Abstract
{
    protected $_idFieldName = 'id';

    protected $_mimeTypes = array(
        'image/jpg',
        'image/jpeg',
        'image/gif',
        'image/png',
    );

    protected $_headers = [
        'iPIM-ClientId' => 'ipim/auth/client_id',
        'iPIM-User'     => 'ipim/auth/username',
        'iPIM-Pass'     => 'ipim/auth/password'
    ];

    public function upsert()
    {
        if (!$this->isAsset()) {
            Mage::throwException('Kind is not an asset. Using: '.$this->getKind());
        }

        if (!$this->isImage()) {
            Mage::throwException('Asset is not an image. Using: '.$this->getMimetype());
        }

        if (!($url = $this->getUrl())) {
            Mage::throwException('Asset provides no URL.');
        }
        $urlPath = parse_url($url, PHP_URL_PATH);
        $fileExt = pathinfo($urlPath, PATHINFO_EXTENSION);

        $filename = $this->getId() . '.' . $fileExt;

        $oldFile = implode(DS, [Mage::getBaseDir('var'), 'import', 'images', $filename[0].$filename[1], $filename]);
        if (file_exists($oldFile)) {
            @unlink($oldFile);
        }
        $newFilename = $this->getAssetTitle();

        $downloadsTarget = implode(DS, [Mage::getBaseDir('var'), 'import', 'images', $newFilename[0]]);
        if (!is_dir($downloadsTarget)) {
            mkdir($downloadsTarget, 0755, true);
        }

        $fileTarget = $downloadsTarget . DIRECTORY_SEPARATOR . $newFilename;
        if (file_exists($fileTarget)) {
            Mage::log('File '. $fileTarget . ' will be updated.', null, 'asset-import.log', true);
        }

        try {
            /*$response = $this->download($url);
            if (false === file_put_contents($fileTarget, $response->getBody())) {*/
            if (false === $this->downloadDistantFile($url, $fileTarget)) {
                Mage::throwException('Asset ' . $fileTarget . ' could not be saved.');
            }
            Mage::log('File '. $fileTarget . ' is written.', null, 'asset-import.log', true);
        } catch (\Exception $exception) {
            Mage::throwException('URL ' . $url . ' could not be requested. Reason: ' . $exception->getMessage());
        }

        /*$pattern = implode(DS, [Mage::getBaseDir('var'), 'import', 'json', '*_asset_relation', $this->getId() . '.json']);
        foreach (glob($pattern) as $file) {
            $data = Mage::helper('core')->jsonDecode(file_get_contents($file));
            $relation = Mage::getModel('skylotec/asset_relation')->setData($data);

            if (method_exists($relation, $relation['action'])) {
                call_user_func([$relation, $relation['action']]);
            } else {
                throw new Mage_Api_Exception('unknown_asset_relation_action', 'Unknown asset relation action.');
            }
        }*/

        try {
            $search = pathinfo($newFilename, PATHINFO_FILENAME);
            Mage::getModel('skylotec/asset_relation')->update($search, $fileTarget);
        } catch (\Exception $exception) {
            Mage::log('Could not update '. $newFilename . ' relations.', null, 'asset-import.log', true);
        }
    }

    private function isAsset(): bool
    {
        return $this->getKind() === 'asset';
    }

    private function isImage()
    {
        return in_array($this->getMimetype(), $this->_mimeTypes);
    }

    /**
     * Download a large distant file to a local destination.
     *
     * This method is very memory efficient :-)
     * The file can be huge, PHP doesn't load it in memory.
     *
     * /!\ Warning, the return value is always true, you must use === to test the response type too.
     *
     * @author dalexandre
     * @see https://gist.github.com/damienalexandre/1258787
     * @param string $url
     *    The file to download
     * @param resource|string $dest
     *    The local file path or ressource (file handler)
     * @return boolean true or the error message
     */
    public static function downloadDistantFile($url, $dest)
    {
        $options = array(
            CURLOPT_FILE => is_resource($dest) ? $dest : fopen($dest, 'w'),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_URL => $url,
            CURLOPT_FAILONERROR => true, // HTTP code > 400 will throw curl error
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $return = curl_exec($ch);

        if ($return === false) {
            return curl_error($ch);
        } else {
            return true;
        }
    }
}
