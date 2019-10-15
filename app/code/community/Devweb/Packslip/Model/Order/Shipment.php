<?php

class Devweb_Packslip_Model_Order_Shipment extends Mage_Sales_Model_Order_Shipment
{
	public function setOrder(Mage_Sales_Model_Order $order)
	{
		parent::setOrder($order);
		
		$this->_items = array();
		$items = $order->getAllItems();
		
		foreach ($items as $item) {
			$shipmentItem = Mage::getModel('sales/order_shipment_item');
			$shipmentItem->setShipment($this);
			$shipmentItem->setProductId($item->getProductId());
			$shipmentItem->setOrderItem($item);
			$shipmentItem->setQty($item->getQtyToShip());
			$shipmentItem->setSku($item->getSku());
			if (!$shipmentItem->getName()) {
				$shipmentItem->setName($item->getName());
			}
			$this->_items[] = $shipmentItem;
		}
	}
}
