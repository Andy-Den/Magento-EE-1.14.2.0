<?php
class Inchoo_AutoInvoicemail_Model_Observer
{

    public function automaticallyInvoiceEmail($observer)
    {
        try {
            /* @var $order Magento_Sales_Model_Order_Invoice */

            $invoice = $observer->getEvent()->getInvoice();

             if($_SESSION['can_send_invoice_email'] == ""){
                 $invoice->sendEmail();
             }
            else if($_SESSION['can_send_invoice_email'] == 0){
                $_SESSION['can_send_invoice_email'] = 1;
            }
            else {
                $invoice->sendEmail();
            }
            $_SESSION['can_send_invoice_email'] = 1;


        } catch (Mage_Core_Exception $e) {
            Mage::log("Not sent email:" . $e->getMessage());
        }

    }

}