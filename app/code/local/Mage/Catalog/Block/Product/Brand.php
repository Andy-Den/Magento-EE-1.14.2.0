<?php
class Mage_Catalog_Block_Product_Brand extends Mage_Catalog_Block_Product_List
{
    protected  $_brand;

    protected function _prepareLayout()
    {
        // add Home breadcrumb
        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs) {
            $title = $this->getBrand()->getTitle();

            $breadcrumbs->addCrumb('home', array(
                'label' => $this->__('Home'),
                'title' => $this->__('Go to Home Page'),
                'link'  => Mage::getBaseUrl()
            ))->addCrumb('brands', array(
                'label' => $this->__("Brands"),
                'title' => $this->__("Brands"),
                'link'  => Mage::getUrl('brands')
            ))->addCrumb('item', array(
                'label' => $title,
                'title' => $title,
            ));
        }

        // modify page title
        if ($this->getBrand()->getPagetitle()) {
            $title = $this->getBrand()->getPagetitle();
        } else {
            $title = $this->__("Brands - %s", $this->getBrand()->getTitle());
        }
        $this->getLayout()->getBlock('head')->setTitle($title);

        // modify page keywords
        if ($this->getBrand()->getPagekeyword()) {
            $keywords = $this->getBrand()->getPagekeyword();
            $this->getLayout()->getBlock('head')->setKeywords($keywords);
        }

        // modify page description
        if ($this->getBrand()->getPagedescription()) {
            $description = $this->getBrand()->getPagedescription();
            $this->getLayout()->getBlock('head')->setDescription($description);
        }

        return parent::_prepareLayout();
    }

    public function getBrand()
    {
        if(is_null($this->_brand)){
            $this->_brand = Mage::registry('current_brand');
        }
        return $this->_brand;
    }

    public function setBrandFilter(){
        $this->addAttributeFilter('brands', $this->getBrand()->getId());
    }
}