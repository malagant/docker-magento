<?php
class Conlabz_Skylotec_AjaxController extends Mage_Core_Controller_Front_Action
{
    public function configurableAction()
    {
        $productId = $this->getRequest()->getParam('product', null);
        $superAttributes = $this->getRequest()->getParam('super_attribute', null);
        if (null === $productId) {
            return;
        }
        $configProduct = Mage::getModel('catalog/product')
            ->setId($productId);

        $childProduct = Mage::getModel('catalog/product_type_configurable')
            ->getProductByAttributes($superAttributes, $configProduct);

        /* @var $childProduct Mage_Catalog_Model_Product */
        $childProduct = $childProduct->load($childProduct->getId());
        Mage::register('product', $childProduct);

        $this->loadLayout(array(
            'catalog_product_view'
        ));

        $blocks = array(
            '#information' => 'product.properties',
            '#product-details' => 'product.details',
            '#downloads' => 'product.downloads.list',
            '#buy' => 'product.buy',
            '#product-main-info' => 'product.info.addtocart.main',
            '#product-add-to-cart-button' => 'product.info.addtocart.button',
            '#product-image-main' => 'product.info.image'
        );

        $response = array();
        $layout = $this->getLayout();
        foreach ($blocks as $selector => $blockName) {
            $block = $layout->getBlock($blockName);
            if ($block) {
                $response[$selector] = $block->toHtml();
            }
        }

        $this->getResponse()->setBody(
            Mage::helper('core')->jsonEncode($response)
        );
    }

    public function fullpostAction()
    {
        $postType = $this->getRequest()->getParam('post_type');
        $postId = $this->getRequest()->getParam('post_id');
        $post = Mage::getModel('wordpress/post');
        $post->setPostType($postType);
        $post = $post->load($postId);

        /* @var $block Conlabz_Skylotec_Block_Post_Ajax */
        $block = Mage::getBlockSingleton('skylotec/post_ajax');
        $block->setPost($post);

        $this->getResponse()->setBody(
            $block->toHtml() .
            sprintf(
                '<a href="%s" class="btn btn-article">%s</a>',
                $post->getPermaLink(),
                $this->__('Go to post')
            )
        );
    }
}
