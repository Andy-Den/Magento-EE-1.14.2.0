<?php
class Gento_ReturnStoreCredit_Model_Observer extends Varien_Event_Observer
{
    public function RefundStoreCredit($observer){
        $_order = $observer->getPayment()->getOrder(); //get the order object
        $storeCredit = $_order->getBaseCustomerBalanceAmount(); //get store credit used in order

        if($storeCredit >0){
//            Check if any store credit was applied to the order
            $websiteId = Mage::getModel('core/store')->load($_order->getStoreId())->getWebsiteId(); //get the website id where order was placed.
            $customerId = $_order->getCustomerId();

            $_balance = Mage::getModel('enterprise_customerbalance/balance'); //CustomerBalance model used for store credit
            $_balance->setCustomerId($customerId)
                ->setWebsiteId($websiteId)
                ->loadByCustomer();

            //refund the amount
            $_balance->setAmountDelta($storeCredit)
                ->setUpdatedActionAdditionalInfo('Auto Refund for Order #'.$_order->getIncrementId()) //Add comments in Customer/Account Information/Store Credit tab
                ->setHistoryAction(1)
                ->save();
            $_order->addStatusHistoryComment('Auto refunded store credit: '.$storeCredit); //Add order comments to log the refund
        }
    }
}