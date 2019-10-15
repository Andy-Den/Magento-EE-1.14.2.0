<?php
class Mage_Catalog_Block_Product_Bestseller extends Mage_Catalog_Block_Product_List
{
    protected $_productCollection;
    protected $_sort_by;

    protected function _prepareLayout()
    {
        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbsBlock->addCrumb('home', array(
                'label'=>Mage::helper('catalog')->__('Home'),
                'title'=>Mage::helper('catalog')->__('Go to Home Page'),
                'link'=>Mage::getBaseUrl()
            ));
        }

        parent::_prepareLayout();
    }

    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $toolbar = $this->getToolbarBlock();
        $toolbar->removeOrderFromAvailableOrders('position');
        return $this;
    }

    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            $collection = Mage::getModel('catalog/product')->getCollection();

            $attributes = Mage::getSingleton('catalog/config')
                ->getProductAttributes();
            Mage::getModel('catalog/layer')->prepareProductCollection($collection);
            $collection->getSelect()->order('rand()');
            $collection->addAttributeToSelect($attributes)
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->addAttributeToFilter('bestseller', 1)
                ->setOrder('ordered_qty', 'desc')
                ->setPageSize(10);

            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
            $this->_productCollection = $collection;
        }
        return $this->_productCollection;
    }

    public function getProductCollection()
    {
        return $this->_getProductCollection();
    }

    protected function _toHtml()
    {
        if ($this->_getProductCollection()->count()){
            return parent::_toHtml();
        }
        return '';
    }

}