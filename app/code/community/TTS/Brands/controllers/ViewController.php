<?php

class TTS_Brands_ViewController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $brandId = (int)$this->getRequest()->getParam('brands', false);
        $brand = Mage::getModel('brands/brands')->load($brandId);
        Mage::register('current_brand', $brand);

        $this->loadLayout();
        $this->renderLayout();
    }
}