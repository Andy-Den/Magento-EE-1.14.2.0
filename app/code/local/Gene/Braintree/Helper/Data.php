<?php

/**
 * Class Gene_Braintree_Helper_Data
 *
 * @author Dave Macaulay <dave@gene.co.uk>
 */
class Gene_Braintree_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Return all of the possible statuses as an array
     * @return array
     */
    public function getStatusesAsArray()
    {
        return array(
            Braintree_Transaction::AUTHORIZATION_EXPIRED => 'Authorization Expired',
            Braintree_Transaction::AUTHORIZING => 'Authorizing',
            Braintree_Transaction::AUTHORIZED => 'Authorized',
            Braintree_Transaction::GATEWAY_REJECTED => 'Gateway Rejected',
            Braintree_Transaction::FAILED => 'Failed',
            Braintree_Transaction::PROCESSOR_DECLINED => 'Processor Declined',
            Braintree_Transaction::SETTLED => 'Settled',
            Braintree_Transaction::SETTLING => 'Settling',
            Braintree_Transaction::SUBMITTED_FOR_SETTLEMENT => 'Submitted For Settlement',
            Braintree_Transaction::VOIDED => 'Voided',
            Braintree_Transaction::UNRECOGNIZED => 'Unrecognized',
            Braintree_Transaction::SETTLEMENT_DECLINED => 'Settlement Declined',
            Braintree_Transaction::SETTLEMENT_PENDING => 'Settlement Pending'
        );
    }
}