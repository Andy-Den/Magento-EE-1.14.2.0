<?php

class TTS_Brands_Model_Brands extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('brands/brands');
    }

    public function getProductUrl()
    {
        return $this->getBrandUrlById($this->getId());
    }

    public function getShortDescription()
    {
        return $this->getDescription();
    }

    public function getUrl()
    {
        if ($this->getUrlKey()) {
            return Mage::getUrl(Mage::getStoreConfig('brands/settings/route')."/{$this->getUrlKey()}");
        }

        return Mage::getUrl(Mage::getStoreConfig('brands/settings/route').'/view', array('brand' => $this->getId()));
    }

    public function getName()
    {
        return $this->_getData('title');
    }

    private function getBrandUrlById($id)
    {
        return Mage::getBaseUrl() . Mage::getStoreConfig('brands/settings/route').'/view/?brand=' . $id;
    }

    public function getBrandImageUrl()
    {
        $image = $this->getData('image');
        $path = Mage::getBaseDir('media') . DS . 'catalog' . DS . 'brands' . DS . $image;
        if (file_exists($path)) {
            $img_url = Mage::getBaseUrl('media') . 'catalog/brands/' . $image;
        } else {
            $img_url = Mage::getBaseUrl('skin') . 'frontend/gento/default/images/catalog/product/placeholder/image.jpg';
        }
        return $img_url;
    }

    public function checkUrlKey($urlKey)
    {
        return $this->_getResource()->checkUrlKey($urlKey);
    }

    protected function _beforeSave()
    {
        if (!$this->getData('url_key')) {
            $url_key_data = $this->getUrlKey();
            $brand_title =$this->getData('title');
            if($url_key_data==""){
           $str = Mage::helper('catalog/product_url')->format($brand_title);
            }
            else {
                $str = $url_key_data;
            }
            $urlKey = preg_replace('#[^0-9a-z]+#i', '-', $str);
            $urlKey = strtolower($urlKey);
            $urlKey = trim($urlKey, '-');
            $this->setData('url_key', $urlKey);
       }

        return parent::_beforeSave();
    }
}