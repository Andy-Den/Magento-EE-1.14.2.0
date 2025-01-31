<?php

/**
 * Class Gene_Braintree_Model_Source_Cctype
 *
 * @author Dave Macaulay <dave@gene.co.uk>
 */
class Gene_Braintree_Model_Source_Cctype extends Mage_Payment_Model_Source_Cctype
{
    /**
     * Allowed credit card types
     *
     * @return array
     */
    public function getAllowedTypes()
    {
        return array(
            'VI',
            'MC',
            'AE',
            'DI',
            'JCB',
            'OT',
            'ME'
        );
    }
}
