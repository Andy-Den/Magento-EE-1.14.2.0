<?php

/**
 * Class Gene_Braintree_CheckoutController
 *
 * @author Dave Macaulay <dave@gene.co.uk>
 */
class Gene_Braintree_CheckoutController extends Mage_Core_Controller_Front_Action
{
    /**
     * The front-end is requesting the grand total of the quote
     */
    public function quoteTotalAction()
    {
        // Grab the quote
        $quote = Mage::getSingleton('checkout/type_onepage')->getQuote();

        // Build up our JSON response
        $jsonResponse = array(
            'billingName' => $quote->getBillingAddress()->getName(),
            'billingPostcode' => $quote->getBillingAddress()->getPostcode(),
            'grandTotal' => $quote->getGrandTotal(),
            'currencyCode' => $quote->getQuoteCurrencyCode()
        );

        // Set the response
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($jsonResponse));
        return false;
    }

}