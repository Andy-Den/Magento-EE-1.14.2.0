<?php
require_once 'Mage/Adminhtml/controllers/Sales/OrderController.php';

class Devweb_Packslip_Sales_OrderController extends Mage_Adminhtml_Sales_OrderController
{
	function getPdf($shipments)
	{
		$pdf = Mage::getModel('sales/order_pdf_shipment')->getPdf($shipments);
		$page = current($pdf->pages);
		
		return $pdf;
	}
	
	public function packAction()
	{
		$orderIds = $this->getRequest()->getPost('order_ids');
		$flag = false;
		
		if (!empty($orderIds)) {
			foreach ($orderIds as $orderId) {
				if (!$orderId) {
					continue;
				}
				$order = Mage::getModel('sales/order');
				$order->load($orderId);
				$shipment = Mage::getModel('packslip/order_shipment');
				$shipment->setOrder($order);
				$shipment->setIncrementId('PRE-'. $order->getRealOrderId());
				$flag = true;
				if (!isset($pdf)) {
					$pdf = $this->getPdf(array($shipment));
				} else {
					$pages = $this->getPdf(array($shipment));
					$pdf->pages = array_merge($pdf->pages, $pages->pages);
				}
			}
			
            if ($flag) {
                return $this->_prepareDownloadResponse(
                    'packingslip'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf', $pdf->render(),
                    'application/pdf'
                );
            } else {
                $this->_getSession()->addError($this->__('There are no printable documents related to selected orders.'));
                $this->_redirect('adminhtml/*/');
            }
		}
		
		$this->_redirect('adminhtml/*/');
	}
}
