<?php
class Conlabz_Eshop_IController extends Mage_Core_Controller_Front_Action
{
    
    public function sAction()
    {

        $productId = $this->getRequest()->getParam('p');
        $product = Mage::getModel('catalog/product')->load($productId);
        $imageUrl = $product->getImageUrl();
        
        $fileName = Mage::getBaseDir("media")."/catalog/product".$product->getImage();
        
                //$basePath - origin file location
                $imageObj = new Varien_Image($fileName);
                $imageObj->constrainOnly(true);
                $imageObj->keepAspectRatio(true);
                $imageObj->keepFrame(false);
                //$width, $height - sizes you need (Note: when keepAspectRatio(TRUE), height would be ignored)
                $imageObj->resize(200, 200);
                $imageObj->display();
                
                
                //$newPath - name of resized image
//                $imageObj->save($newPath);

//                echo $fileName;
//                exit;
        
//      $fp = fopen($fileName, 'rb');
//      header("Content-Type: image/png");
//      header("Content-Length: " . filesize($fileName));
//      fpassthru($fp);
    }
}
