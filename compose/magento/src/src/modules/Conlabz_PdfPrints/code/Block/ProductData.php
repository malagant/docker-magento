<?php

class Conlabz_PdfPrints_Block_ProductData extends Mage_Core_Block_Template
{

    public function _prepareLayout()
    {

        $this->setTemplate("pdfprints/output/product_data_list.phtml");

        $this->_skipAttributes = array('sku', 'norm', 'ean', 'e_class_nummer', 'zoll_nr', 'additional_web_text');

        parent::_prepareLayout();
    }

    public function getProductAttributes()
    {

        $attributes = $this->getProduct()->getAttributes();

        $data = array();

        $temperatureUsed = false;

        foreach ($attributes as $attribute) {
            if (in_array($attribute->getAttributeCode(), $this->_skipAttributes)) {
                continue;
            }
            if (!$this->_canDisplay($attribute, $this->getProduct())) {
                continue;
            }
            $group = 0;
            if ($tmp = $attribute->getData('attribute_group_id')) {
                $group = $tmp;
            }

            //$attribute->setStoreId(1);

            if ($attribute->getIsVisibleOnFront()) {
                $value = $attribute->getFrontend()->getValue($this->getProduct());

                if (($attribute->getAttributeCode() == "temperaturbereich_von" || $attribute->getAttributeCode() == "temperaturbereich_bis") && $value) {
                    if ($temperatureUsed) {
                        continue;
                    } else {
                        $temperatureUsed = true;
                        $value = Mage::helper("core")->__("from %s to %s", $this->getProduct()->getTemperaturbereichVon(), $this->getProduct()->getTemperaturbereichBis());
                        $data[$group][$attribute->getAttributeCode()]['value'] = $value;
                        $data[$group][$attribute->getAttributeCode()]['label'] = Mage::helper("core")->__("Temperature");
                        continue;
                    }
                }

                $isYes = false;
                if ($attribute->getFrontendInput() == "boolean") {
                    if (!$this->getProduct()->getData($attribute->getAttributeCode())) {
                        $value = false;
                    } else {
                        $isYes = true;
                    }
                }


                if ($value) {
                    $value = Mage::helper("catalog/output")->productAttribute($this->getProduct(), $value, $attribute->getAttributeCode());

                    if ($this->getProduct()->hasData($attribute->getAttributeCode())) {
                        if ($value != "Nein" && $value != "No" && $value != "nein" && $value != "no" && $value != "Non") {
                            $data[$group][$attribute->getAttributeCode()]['value'] = false;

                            $data[$group][$attribute->getAttributeCode()]['multi'] = false;
                            if ($attribute->getFrontendInput() == "multiselect") {
                                $data[$group][$attribute->getAttributeCode()]['multi'] = true;
                            }

                            if ($value != "Ja" && $value != "ja" && $value != "si" && $value != "Yes" && $attribute->getFrontendInput() != "boolean") {
                                if (!$isYes) {
                                    $data[$group][$attribute->getAttributeCode()]['value'] = $value;
                                }
                            }

                            $productAttribute = $this->getProduct()->getResource()->getAttribute($attribute->getAttributeCode());
                            $productAttribute->unsetData("store_label");

                            $label = "";

                            $deStoreLabel = $productAttribute->getStoreLabel(Mage::app()->getStore()->getId());
                            $enStoreLabel = $productAttribute->getStoreLabel(2);
                            $enStoreLabel = "";

                            if ($deStoreLabel) {
                                $label .= $deStoreLabel;
                            }
                            if ($enStoreLabel && $deStoreLabel != $enStoreLabel) {
                                if ($label) {
                                    $label .= " / " . $enStoreLabel;
                                }
                            }
                            if (!$label) {
                                $label = $productAttribute->getStoreLabel();
                            }

                            $from = array("eÌ€","Ã ","eÌ");
                            $to   = array("Ã¨","Ã ","Ã©");

                            $data[$group][$attribute->getAttributeCode()]['value'] = str_replace($from, $to, $data[$group][$attribute->getAttributeCode()]['value']);
                            $data[$group][$attribute->getAttributeCode()]['label'] = $label;
                        }
                    }
                }
            }
        }
        return $data;
    }

    public function getImageProduct()
    {
        $image = $this->getProduct()->getImage();
        if (!$image) {
            $parents = Mage::getModel("catalog/product_type_configurable")->getParentIdsByChild($this->getProduct()->getId());
            if (sizeof($parents) == 1) {
                $parent = Mage::getModel("catalog/product")->load($parents[0]);
                return $parent;
            }
        }
        return $this->getProduct();
    }
    public function getGroupTitle($groupId)
    {

        $groupModel = Mage::getModel('eav/entity_attribute_group')->load($groupId);
        return $groupModel->getAttributeGroupName();
    }

    public function getImageUrl()
    {

        return (string) Mage::helper('catalog/image')->init($this->getProduct(), 'image')->resize(270);
    }

    public function getMediaGallery()
    {

        return $this->getProduct()->getMediaGalleryImages();
    }
    protected function _canDisplay($attribute, $product)
    {
        if ($attribute->getAttributeCode() === 'max_zulaessiges_gewicht') {
            $weight = (int) $attribute->getFrontend()->getValue($product);
            if ($weight <= 100) {
                return false;
            }
        }

        return true;
    }
}
