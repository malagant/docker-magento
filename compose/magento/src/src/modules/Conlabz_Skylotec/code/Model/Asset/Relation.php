<?php

class Conlabz_Skylotec_Model_Asset_Relation extends Mage_Core_Model_Abstract
{

    protected $_idFieldName = 'id';

    /**
     * @var Mage_Catalog_Model_Product_Attribute_Media_Api
     */
    protected $_mediaApi;

    protected $mediaAttributes = ['image', 'small_image', 'thumbnail'];

    /**
     * @var array|mixed|null
     */
    protected $_relationMap = [
        'product_asset_relation' => 'product_number',
        'item_asset_relation' => 'item_original_no'
    ];

    public function upsert()
    {
        $filename = (string) $this->getId();

        if (!$this->isAssetRelation()) {
            Mage::throwException('Kind is not an asset relation. Using: '.$this->getKind());
        }

        if (!$this->isImage()) {
            Mage::throwException('Relation usetype is unknown. Using: '.$this->getUsetype());
        }

        if (!$product = $this->getProduct()) {
            Mage::throwException('Product ' . $this->getProductSku() . ' is unknown.');
        }

        $assetPosition = max((int) $this->getOrder(), 1) - 1;

        if ($file = $this->getMediaFile($product->getId(), $filename)) {
            $this->_getMediaApi()->remove($product->getId(), $file);
            Mage::log('File '. $filename . ' removed for update.', null, 'asset-import.log', true);
        }

        $globPath = implode(DS, [
            Mage::getBaseDir('var'), 'import', 'images', $filename[0].$filename[1], $filename . '.{jpg,jpeg,png}'
        ]);
        $images = glob($globPath, GLOB_BRACE);
        if (empty($images)) {
            Mage::throwException('Asset ' . $filename . '.{jpg,jpeg,png} could not be found.');
        }
        $fileTarget = current($images);

        /** @var Mage_Catalog_Model_Product $product */
        $mediaAttribute = ($this->getUsetype() === 'standardimage' && $assetPosition === 0) ? $this->mediaAttributes : null;
        $product->addImageToMediaGallery($fileTarget, $mediaAttribute, false, false);
        $product->save();

        $items = $this->_getMediaApi()->items($product->getId());
        $path = implode(DIRECTORY_SEPARATOR, ['', $filename[0], $filename[1], $filename]);
        $position = 0;
        foreach ($items as &$item) {
            ($assetPosition === $position) && $position++;
            $item['position'] = $position++;
            if (strpos($item['file'], $path) === 0) {
                $item['label'] = $this->getUsetype()[0];
                $item['position'] = $assetPosition;
            }
            $this->_getMediaApi()->update($product->getId(), $item['file'], $item);
            Mage::log('Relation '. $this->getId() . ' - '.$product->getSku() . ' - ' . $item['file'] . ' updated.', null, 'asset-import.log', true);
        }
    }

    public function delete()
    {
        $filename = (string) $this->getId();

        if (!$this->isAssetRelation()) {
            Mage::throwException('Kind is not an asset relation. Using: '.$this->getKind());
        }

        if (!$product = $this->getProduct()) {
            Mage::throwException('Product ' . $this->getProductSku() . ' is unknown.');
        }

        if ($file = $this->getMediaFile($product->getId(), $filename)) {
            $this->_getMediaApi()->remove($product->getId(), $file);
            Mage::log('Relation '. $this->getId() . ' - '.$product->getSku() . ' deleted.', null, 'asset-import.log', true);

            $items = $this->_getMediaApi()->items($product->getId());
            if (!empty($items)) {
                $item = current($items);
                $item['types'] = $this->mediaAttributes;
                $this->_getMediaApi()->update($product->getId(), $item['file'], $item);
            }
        } else {
            Mage::throwException('Asset ' . $filename . ' could not be found.');
        }
    }

    public function update($search, $src)
    {
        $ioAdapter = new Varien_Io_File();

        /** @var Conlabz_Skylotec_Model_Resource_Product_Attribute_Backend_Media $media */
        $media = Mage::getResourceModel('catalog/product_attribute_backend_media')->getMediaBy($search);
        $processedFiles = [];
        foreach ($media as $image) {
            try {
                $fileName = Mage::getBaseDir('media'). DS . 'catalog' . DS . 'product' . $image['file'];
                if (!in_array($fileName, $processedFiles)) {
                    $ioAdapter->cp($src, $fileName);
                    $ioAdapter->chmod($fileName, 644);
                    $processedFiles[] = $fileName;
                    Mage::log('File '. $fileName . ' will be updated.', null, 'asset-import.log', true);
                }
            } catch (Exception $e) {
                Mage::log('Could not update '. $image['file'] . ' for product entity ' . $image['product_id'], null, 'asset-import.log', true);
            }
            $this->_getMediaApi()->update($image['product_id'], $image['file'], $image);
        }
        Mage::getModel('catalog/product_image')->clearCache();
    }

    private function getProduct()
    {
        if (!$this->_product) {
            $this->_product = Mage::getModel('catalog/product')->loadByAttribute('sku', $this->getProductSku());
        }
        return $this->_product;
    }

    private function getProductSku()
    {
        $relation = $this->getRelation();
        return $relation[$this->_relationMap[$this->getKind()]];
    }

    private function isAssetRelation(): bool
    {
        return in_array($this->getKind(), array_keys($this->_relationMap));
    }

    private function isImage()
    {
        return in_array($this->getUsetype(), ['standardimage', 'detailimage', 'applicationimage']);
    }

    private function getMediaFile($product, $filename)
    {
        $path = implode(DIRECTORY_SEPARATOR, ['', $filename[0], $filename[1], $filename]);
        $items = $this->_getMediaApi()->items($product);
        foreach ($items as $item) {
            if (strpos($item['file'], $path) === 0) {
                return $item['file'];
            }
        }
        return false;
    }

    private function _getMediaApi()
    {
        if (!$this->_mediaApi) {
            /** @var Mage_Catalog_Model_Product_Attribute_Media_Api $mediaApi */
            $this->_mediaApi = Mage::getModel("catalog/product_attribute_media_api");
        }
        return $this->_mediaApi;
    }
}
