<?php
class Devweb_Packslip_Model_Observer
{
	public function addMassAction($observer)
	{
		$block = $observer->getEvent()->getBlock();
		
		if (is_a($block, 'Mage_Adminhtml_Block_Widget_Grid_Massaction_Abstract') && is_a($block->getParentBlock(), 'Mage_Adminhtml_Block_Sales_Order_Grid')) {
			$block->addItem('pdfpackslip', array(
				'label'=> Mage::helper('sales')->__('Pre-Packingslips'),
				'url'  => $block->getUrl('adminhtml/sales_order/pack'),
			));
		}
	}
}