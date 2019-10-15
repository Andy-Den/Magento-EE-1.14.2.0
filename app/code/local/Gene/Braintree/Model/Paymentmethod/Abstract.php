<?php

/**
 * Class Gene_Braintree_Model_Paymentmethod_Abstract
 *
 * @author Dave Macaulay <dave@gene.co.uk>
 */
abstract class Gene_Braintree_Model_Paymentmethod_Abstract extends Mage_Payment_Model_Method_Abstract
{

    /**
     * Verify that the module has been setup
     * @param null $quote
     *
     * @return bool
     */
    public function isAvailable($quote = null)
    {
        // Check the Braintree lib version is above 2.32, as this is when 3D secure appeared
        if(Braintree_Version::get() < 2.32) {
            return false;
        }

        // Check that the module is fully setup
        if (!Mage::getStoreConfig(Gene_Braintree_Model_Wrapper_Braintree::BRAINTREE_ENVIRONMENT_PATH)
            || !Mage::getStoreConfig(Gene_Braintree_Model_Wrapper_Braintree::BRAINTREE_MERCHANT_ID_PATH)
            || !Mage::getStoreConfig(Gene_Braintree_Model_Wrapper_Braintree::BRAINTREE_PUBLIC_KEY_PATH)
            || !Mage::getStoreConfig(Gene_Braintree_Model_Wrapper_Braintree::BRAINTREE_PRIVATE_KEY_PATH)
        ) {
            // If not the payment methods aren't available
            return false;
        }

        // Try and validate the stored credentials
        if(!Mage::getModel('gene_braintree/wrapper_braintree')->validateCredentials()) {

            // Only add this in if it's not the last notice
            $latestNotice = Mage::getModel('adminnotification/inbox')->loadLatestNotice();

            // Validate there is a latest notice
            if($latestNotice && $latestNotice->getId()) {

                // Check to see if the title contains our error
                if(strpos($latestNotice->getTitle(), 'Braintree Configuration Invalid') === false) {

                    // If it doesn't add it again!
                    Mage::getModel('adminnotification/inbox')->addCritical('Braintree Configuration Invalid - No payments can be processed','The configuration values in the Magento Braintree v.zero module are incorrect, until these values are corrected the system can not function.');
                }

            } else {

                // Otherwise there hasn't been any other notices
                Mage::getModel('adminnotification/inbox')->addCritical('Braintree Configuration Invalid - No payments can be processed','The configuration values in the Magento Braintree v.zero module are incorrect, until these values are corrected the system can not function.');
            }

            return false;
        }

        // Return the normal functionality
        return parent::isAvailable($quote);
    }

    /**
     * Return the helper
     * @return Mage_Payment_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('gene_braintree');
    }

    /**
     * Return the wrapper class
     *
     * @return Gene_Braintree_Model_Wrapper_Braintree
     */
    protected function _getWrapper()
    {
        return Mage::getSingleton('gene_braintree/wrapper_braintree');
    }

    /**
     * Return configuration values
     * @param $value
     *
     * @return mixed
     */
    protected function _getConfig($key)
    {
        return Mage::getStoreConfig('payment/'.$this->_code.'/'.$key);
    }

    /**
     * Is the vault enabled?
     * @return bool
     */
    public function isVaultEnabled()
    {
        return $this->_getConfig('use_vault');
    }

}