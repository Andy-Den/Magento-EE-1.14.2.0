<?php
class TTS_Brands_Block_Brands extends Mage_Core_Block_Template{

	public function getBrands()
	{
		return $this->helper('brands/data')->getBrands();
	}

    protected function _prepareLayout()
    {
        // add Home breadcrumb
        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs) {
            $title = $this->__("Brands");

            $breadcrumbs->addCrumb('home', array(
                'label' => $this->__('Home'),
                'title' => $this->__('Go to Home Page'),
                'link'  => Mage::getBaseUrl()
            ))->addCrumb('brands', array(
                'label' => $title,
                'title' => $title
            ));
        }

        // modify page title
        $title = $this->__("Brands");
        $this->getLayout()->getBlock('head')->setTitle($title);

        return parent::_prepareLayout();
    }

	public function getBrandName()
	{
		$id = (int) $this->getRequest()->getParam('brand', false);
		$data = Mage::getResourceModel('brands/brands_collection')->getImgById($id)->getData();
    	return $data[0]['title']?$data[0]['title']:'not found';
	}
}
