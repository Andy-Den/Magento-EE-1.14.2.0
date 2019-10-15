<?php
 
class TTS_Brands_Block_Product_List extends Mage_Catalog_Block_Product_List {

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


    public function getHeaderText()
    {
        if( $this->getBrand()->getTitle() ) {
            return Mage::helper('brands')->__("Brands - '%s'", $this->htmlEscape($this->getBrand()->getTitle()));
        } else {
            return false;
        }
    }
    public function getBrand()
    {
        return Mage::registry('current_brand');
    }

    protected function _getProductCollection1()
    {
        if (is_null($this->_productCollection)) {
			$collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addFieldToFilter(array(
                    array('attribute'=>'brands','eq'=>(int) $this->getRequest()->getParam('brand', false)),
                ))
			/*	->joinField('category_id','catalog/category_product','category_id','product_id=entity_id',null,'left')
				->joinField('level','catalog/category','level','entity_id=category_id',null,'left')		
				->addFieldToFilter('category_id',array('neq'=>2))
				->addFieldToFilter('level',array('eq'=>2))*/
                ->addStoreFilter(Mage::app()->getStore()->getId())
                ->addMinimalPrice()
                ->addUrlRewrite()
                ->groupByAttribute('entity_id')
                ->setOrder('category_id','ASC');
            $this->_productCollection = $collection;
            Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($this->_productCollection);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInSiteFilterToCollection($this->_productCollection);
        }

        return $this->_productCollection;
    }
  protected function _getProductCollection()
    {

        if (is_null($this->_productCollection)) {
            $brandId = (int) $this->getBrand()->getBrands_id();
            $this->_productCollection = Mage::getModel('catalog/product')->getCollection();
            $this->_productCollection->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addFieldToFilter(array(
                array('attribute'=>'brands','eq'=>$brandId),
            ));
            Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($this->_productCollection);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInSiteFilterToCollection($this->_productCollection);
        }

        return $this->_productCollection;
    }
	public function getLoadedProductCollection()
	{
		return $this->_getProductCollection();
	}
}