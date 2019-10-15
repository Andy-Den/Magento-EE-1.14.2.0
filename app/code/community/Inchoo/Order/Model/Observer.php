<?php

class Inchoo_Order_Model_Observer
{
    public function cancelPendingOrders()
    {
        $orderCollection = Mage::getResourceModel('sales/order_collection');

        $orderCollection
            ->addFieldToFilter('state', 'pending_payment')
            ->addFieldToFilter('created_at', array(
                'lt' =>  new Zend_Db_Expr("DATE_ADD('".now()."', INTERVAL -'20:00' HOUR_MINUTE)")))
            ->getSelect()
            ->order('entity_id')
            ->limit(50)
        ;

        $orders ="";
        foreach($orderCollection->getItems() as $order)
        {
            $orderModel = Mage::getModel('sales/order');
            $orderModel->load($order['entity_id']);

            if(!$orderModel->canCancel())
                continue;

            $orderModel->cancel();
            $orderModel->setStatus('canceled');
            $orderModel->save();


            // Sent mail
            $enable = Mage::getStoreConfig('cancel_order_notification/cancel_order_email_notification/cancel_order_enable');
            if($enable){

                $mailSubject = 'Order #' . $orderModel->increment_id . ' is canceled';

                // Transactional Email Template's ID
                //$templateId = 'cancel_order_email_template';
                $templateId = Mage::getStoreConfig('cancel_order_notification/cancel_order_email_notification/cancel_order_email');

                // Set sender information
                $senderName = Mage::getStoreConfig('trans_email/ident_support/name');
                $senderEmail = Mage::getStoreConfig('trans_email/ident_support/email');
                $sender = array('name' => $senderName, 'email' => $senderEmail);

                // Set recepient information
                $recepientEmail = $orderModel->getCustomerEmail();
                $recepientName = $orderModel->getCustomerName();

                // Set variables that can be used in email template
                $vars = array(
                    'orderModel' => $orderModel,
                    'orderUrl' => '',
                    'customerUrl' => '',
                );

                // Send Transactional Email
                Mage::getModel('core/email_template')->setTemplateSubject($mailSubject)
                    ->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars);

            }

        }



    }


    public function updateStockAvailability($observer)
    {
        $product = $observer->getProduct();
        $stockData = $product->getStockData();
      //  var_dump($stockData);die;

        if ($product && $stockData['qty']>0) {
            $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->getEntityId()); // Load the stock for this product
            $stock->setData('is_in_stock', 1);
            $stock->save(); // Save
        }
    }

}