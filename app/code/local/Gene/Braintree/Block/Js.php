<?php

/**
 * Class Gene_Braintree_Block_Js
 *
 * @author Dave Macaulay <dave@gene.co.uk>
 */
class Gene_Braintree_Block_Js extends Mage_Core_Block_Template
{
    /**
     * is 3D enabled?
     * @return int
     */
    protected function is3DEnabled()
    {
        // Return an int
        if(Mage::getModel('gene_braintree/paymentmethod_creditcard')->is3DEnabled()) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Generate and return a token
     * @return mixed
     */
    protected function getClientToken()
    {
        return Mage::getModel('gene_braintree/wrapper_braintree')->init()->generateToken();
    }

    /**
     * Only render if the payment method is active
     * @return string
     */
    protected function _toHtml()
    {
        // Check the payment method is active
        if(Mage::getModel('gene_braintree/paymentmethod_creditcard')->isAvailable() || Mage::getModel('gene_braintree/paymentmethod_paypal')->isAvailable()) {
            return parent::_toHtml();
        }

        return '';
    }

    /**
     * Shall we do a single use payment?
     * @return string
     */
    protected function getSingleUse()
    {
        // We prefer to do future payments, so anything else is future
        $paymentAction = Mage::getStoreConfig('payment/gene_braintree_paypal/payment_type');
        if($paymentAction == Gene_Braintree_Model_Source_Paypal_Paymenttype::GENE_BRAINTREE_PAYPAL_SINGLE_PAYMENT) {
            return 'true';
        }

        return 'false';
    }

    protected function getAcceptedCards()
    {

    }

}