<?php

/**
 * Class Gene_Braintree_Model_Wrapper_Braintree
 *
 * @author Dave Macaulay <dave@gene.co.uk>
 */
class Gene_Braintree_Model_Wrapper_Braintree extends Mage_Core_Model_Abstract
{

    /**
     * The paths to the needed configuration variables
     */
    CONST BRAINTREE_ENVIRONMENT_PATH = 'payment/gene_braintree/environment';
    CONST BRAINTREE_MERCHANT_ID_PATH = 'payment/gene_braintree/merchant_id';
    CONST BRAINTREE_MERCHANT_ACCOUNT_ID_PATH = 'payment/gene_braintree/merchant_account_id';
    CONST BRAINTREE_PUBLIC_KEY_PATH = 'payment/gene_braintree/public_key';
    CONST BRAINTREE_PRIVATE_KEY_PATH = 'payment/gene_braintree/private_key';

    /**
     * Store the customer
     * @var
     */
    private $customer;

    private $braintreeId;

    /**
     * Setup the environment
     *
     * @return $this
     */
    public function init()
    {
        // Setup the various configuration variables
        Braintree_Configuration::environment(Mage::getStoreConfig(self::BRAINTREE_ENVIRONMENT_PATH));
        Braintree_Configuration::merchantId(Mage::getStoreConfig(self::BRAINTREE_MERCHANT_ID_PATH));
        Braintree_Configuration::publicKey(Mage::getStoreConfig(self::BRAINTREE_PUBLIC_KEY_PATH));
        Braintree_Configuration::privateKey(Mage::getStoreConfig(self::BRAINTREE_PRIVATE_KEY_PATH));

        return $this;
    }

    /**
     * Validate the credentials within the admin area
     * @return bool
     */
    public function validateCredentials($prettyResponse = false, $alreadyInit = false, $merchantAccountId = false)
    {
        // Try to init the environment
        try {
            if(!$alreadyInit) {

                // If we're within the admin we want to grab these values from whichever store we're modifying
                if(Mage::app()->getStore()->isAdmin()) {
                    Braintree_Configuration::environment(Mage::getSingleton('adminhtml/config_data')->getConfigDataValue(self::BRAINTREE_ENVIRONMENT_PATH));
                    Braintree_Configuration::merchantId(Mage::getSingleton('adminhtml/config_data')->getConfigDataValue(self::BRAINTREE_MERCHANT_ID_PATH));
                    Braintree_Configuration::publicKey(Mage::getSingleton('adminhtml/config_data')->getConfigDataValue(self::BRAINTREE_PUBLIC_KEY_PATH));
                    Braintree_Configuration::privateKey(Mage::getSingleton('adminhtml/config_data')->getConfigDataValue(self::BRAINTREE_PRIVATE_KEY_PATH));
                } else {
                    $this->init();
                }
            }
            $this->generateToken();
        } catch (Exception $e) {

            if($prettyResponse) {
                return '<span style="color: red;font-weight: bold;" id="braintree-valid-config">' . Mage::helper('gene_braintree')->__('Invalid Credentials') . '</span><br />' . Mage::helper('gene_braintree')->__('Payments cannot be processed until this is resolved, due to this the methods will be hidden within the checkout');
            }
            return false;
        }

        // Check to see if we've been passed the merchant account ID?
        if(!$merchantAccountId) {
            if(Mage::app()->getStore()->isAdmin()) {
                $merchantAccountId = Mage::getSingleton('adminhtml/config_data')->getConfigDataValue(self::BRAINTREE_MERCHANT_ACCOUNT_ID_PATH);
            } else {
                $merchantAccountId = Mage::getStoreConfig(self::BRAINTREE_MERCHANT_ACCOUNT_ID_PATH);
            }
        }

        // Validate the merchant account ID
        try {
            Braintree_Configuration::gateway()->merchantAccount()->find($merchantAccountId);
        } catch (Exception $e) {
            if($prettyResponse) {
                return '<span style="color: orange;font-weight: bold;" id="braintree-valid-config">' . Mage::helper('gene_braintree')->__('Invalid Merchant Account ID') . '</span><br />' . Mage::helper('gene_braintree')->__('Payments cannot be processed until this is resolved. We cannot find your merchant account ID associated with the other credentials you\'ve provided, please update this field');
            }
            return false;
        }

        if($prettyResponse) {
            return '<span style="color: green;font-weight: bold;" id="braintree-valid-config">' . Mage::helper('gene_braintree')->__('Valid Credentials') . '</span><br />' . Mage::helper('gene_braintree')->__('You\'re ready to accept payments via Braintree');
        }
        return true;
    }

    /**
     * Retrieve the Braintree ID from Magento
     *
     * @return bool|string
     */
    protected function getBraintreeId()
    {
        // Some basic caching
        if(!$this->braintreeId) {

            // Is the customer already logged in
            if (Mage::getSingleton('customer/session')->isLoggedIn()) {

                // Retrieve the current customer
                $customer = Mage::getSingleton('customer/session')->getCustomer();

                // Determine whether they have a braintree customer ID already
                if ($brainteeId = $customer->getBraintreeCustomerId()) {
                    $this->braintreeId = $customer->getBraintreeCustomerId();
                } else {
                    // If not let's create them one
                    $this->braintreeId = $this->buildCustomerId();
                    $customer->setBraintreeCustomerId($this->braintreeId)->save();
                }
            } else {
                if ((Mage::getSingleton('checkout/type_onepage')->getCheckoutMethod() == 'login_in' || Mage::getSingleton('checkout/type_onepage')->getCheckoutMethod() == Mage_Checkout_Model_Type_Onepage::METHOD_REGISTER)) {

                    // Check to see if we've already generated an ID
                    if($braintreeId = Mage::getSingleton('checkout/session')->getBraintreeCustomerId()) {
                        $this->braintreeId = $braintreeId;
                    } else {
                        // If the user plans to register let's build them an ID and store it in their session
                        $this->braintreeId = $this->buildCustomerId();
                        Mage::getSingleton('checkout/session')->setBraintreeCustomerId($this->braintreeId);
                    }
                }
            }

        }

        return $this->braintreeId;
    }

    /**
     * Check to see whether this customer already exists
     *
     * @return bool|object
     */
    public function checkIsCustomer()
    {
        try {
            // Check to see that we can generate a braintree ID
            if($braintreeId = $this->getBraintreeId()) {
                $this->customer = Braintree_Customer::find($braintreeId);
                return $this->customer;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Generate a server side token with the specified account ID
     *
     * @return mixed
     */
    public function generateToken()
    {
        // Empty array
        $params = array();

        // Check to see if we've already created this email a customer account
        if($customer = $this->checkIsCustomer()) {
            $params['customerId'] = $customer->id;
        }

        // Use the class to generate the token
        return Braintree_ClientToken::generate($params);
    }

    /**
     * Build the customers ID, md5 a uniquid
     * @param Mage_Sales_Model_Order $order
     *
     * @return string
     * @throws Mage_Core_Exception
     */
    private function buildCustomerId()
    {
        return md5(uniqid('braintree_',true));
    }

    /**
     * Build a Magento address model into a Braintree array
     *
     * @param Mage_Sales_Model_Order_Address $address
     *
     * @return array
     */
    private function buildAddress(Mage_Sales_Model_Order_Address $address)
    {
        // Build up the initial array
        $return = array(
            'firstName'         => $address->getFirstname(),
            'lastName'          => $address->getLastname(),
            'streetAddress'     => $address->getStreet1(),
            'locality'          => $address->getCity(),
            'postalCode'        => $address->getPostcode(),
            'countryCodeAlpha2' => $address->getCountry()
        );

        // Any extended address?
        if ($address->getStreet2()) {
            $return['extendedAddress'] = $address->getStreet2();
        }

        // Region
        if ($address->getRegion()) {
            $return['region'] = $address->getRegionCode();
        }

        // Check to see if we have a company
        if ($address->getCompany()) {
            $return['company'] = $address->getCompany();
        }

        return $return;
    }

    /**
     * Build up the customers data onto an object
     *
     * @param Mage_Sales_Model_Order $order
     *
     * @return array
     */
    private function buildCustomer(Mage_Sales_Model_Order $order, $includeId = true)
    {
        $customer = array(
            'firstName' => $order->getCustomerFirstname(),
            'lastName'  => $order->getCustomerLastname(),
            'email'     => $order->getCustomerEmail(),
            'phone'     => $order->getBillingAddress()->getTelephone()
        );

        // Shall we include the customer ID?
        if($includeId) {
            $customer['id'] = $this->getBraintreeId();
        }

        // Handle empty data with alternatives
        if(empty($customer['firstName'])) {
            $customer['firstName'] = $order->getBillingAddress()->getFirstname();
        }
        if(empty($customer['lastName'])) {
            $customer['lastName'] = $order->getBillingAddress()->getLastname();
        }
        if(empty($customer['email'])) {
            $customer['email'] = $order->getBillingAddress()->getEmail();
        }

        return $customer;
    }

    /**
     * Build up the sale request
     *
     * @param $amount
     * @param array $paymentDataArray
     * @param Mage_Sales_Model_Order $order
     * @param bool $submitForSettlement
     * @param bool $deviceData
     * @param bool $storeInVault
     * @param bool $threeDSecure
     * @param array $extra
     * @return array
     * @throws Mage_Core_Exception
     */
    public function buildSale(
        $amount,
        array $paymentDataArray,
        Mage_Sales_Model_Order $order,
        $submitForSettlement = true,
        $deviceData = false,
        $storeInVault = false,
        $threeDSecure = false,
        $extra = array()
    ) {
        // Check we always have an ID
        if (!$order->getIncrementId()) {
            Mage::throwException('Your order has become invalid, please try refreshing.');
        }

        // Store whether or not we created a new method
        $createdMethod = false;

        // If the user is already a customer and wants to store in the vault we've gotta do something a bit special
        if($storeInVault && $this->checkIsCustomer() && isset($paymentDataArray['paymentMethodNonce'])) {

            // Create the payment method with this data
            $paymentMethodCreate = array(
                'customerId' => $this->getBraintreeId(),
                'paymentMethodNonce' => $paymentDataArray['paymentMethodNonce'],
                'billingAddress' => $this->buildAddress($order->getBillingAddress())
            );

            // Log the create array
            Gene_Braintree_Model_Debug::log(array('Braintree_PaymentMethod' => $paymentMethodCreate));

            // Create a new billing method
            $result = Braintree_PaymentMethod::create($paymentMethodCreate);

            // Log the response from Braintree
            Gene_Braintree_Model_Debug::log(array('Braintree_PaymentMethod:result' => $paymentMethodCreate));

            // Verify the storing of the card was a success
            if(isset($result->success) && $result->success == true) {

                /* @var $paymentMethod Braintree_CreditCard */
                $paymentMethod = $result->paymentMethod;

                // Check to see if the token is set
                if(isset($paymentMethod->token) && !empty($paymentMethod->token)) {

                    // We no longer need this nonce
                    unset($paymentDataArray['paymentMethodNonce']);

                    // Instead use the token
                    $paymentDataArray['paymentMethodToken'] = $paymentMethod->token;

                    // Create a flag for other methods
                    $createdMethod = true;
                }

            } else {
                Mage::throwException($result->message . Mage::helper('gene_braintree')->__(' Please try again or attempt refreshing the page.'));
            }
        }

        // Build up the initial request parameters
        $request = array(
            'amount'             => $amount,
            'orderId'            => $order->getIncrementId(),
            'merchantAccountId'  => Mage::getStoreConfig(self::BRAINTREE_MERCHANT_ACCOUNT_ID_PATH),
            'channel'            => 'MagentoVZero',
            'options'            => array(
                'submitForSettlement' => $submitForSettlement,
                'storeInVault'        => $storeInVault
            )
        );

        // Input the allowed payment method info
        $allowedPaymentInfo = array('paymentMethodNonce','paymentMethodToken','token','cvv');
        foreach($paymentDataArray as $key => $value) {
            if(in_array($key, $allowedPaymentInfo)) {
                if($key == 'cvv') {
                    $request['creditCard']['cvv'] = $value;
                } else {
                    $request[$key] = $value;
                }
            } else {
                Mage::throwException($key.' is not allowed within $paymentDataArray');
            }
        }

        // Include the customer if we're creating a new one
        if(!$this->checkIsCustomer() && (Mage::getSingleton('customer/session')->isLoggedIn() ||
                (Mage::getSingleton('checkout/type_onepage')->getCheckoutMethod() == 'login_in' || Mage::getSingleton('checkout/type_onepage')->getCheckoutMethod() == Mage_Checkout_Model_Type_Onepage::METHOD_REGISTER))) {
            $request['customer'] = $this->buildCustomer($order);
        } else {
            // If the customer exists but we aren't using the vault we want to pass a customer object with no ID
            $request['customer'] = $this->buildCustomer($order, false);
        }

        // Do we have any deviceData to send over?
        if ($deviceData) {
            $request['deviceData'] = $deviceData;
        }

        // Include the shipping address
        if ($order->getShippingAddress()) {
            $request['shipping'] = $this->buildAddress($order->getShippingAddress());
        }

        // Include the billing address
        if ($order->getBillingAddress()) {
            $request['billing'] = $this->buildAddress($order->getBillingAddress());
        }

        // Is 3D secure enabled?
        if($threeDSecure !== false && !$createdMethod) {
            $request['options']['three_d_secure']['required'] = true;
        }

        // Any extra information we want to supply
        if(!empty($extra) && is_array($extra)) {
            $request = array_merge($request, $extra);
        }

        return $request;
    }

    /**
     * Attempt to make the sale
     *
     * @param $saleArray
     *
     * @return array
     */
    public function makeSale($saleArray)
    {
        // Call the braintree library
        return Braintree_Transaction::sale(
            $saleArray
        );
    }

    /**
     * Submit a payment for settlement
     *
     * @param $transactionId
     * @param $amount
     *
     * @throws Mage_Core_Exception
     */
    public function submitForSettlement($transactionId, $amount)
    {
        // Attempt to submit for settlement
        $result = Braintree_Transaction::submitForSettlement($transactionId, $amount);

        return $result;
    }

    /**
     * Find a transaction
     *
     * @param $transactionId
     *
     * @throws Braintree_Exception_NotFound
     */
    public function findTransaction($transactionId)
    {
        return Braintree_Transaction::find($transactionId);
    }

    /**
     * If we're trying to charge a 3D secure card in the vault we need to build a special nonce
     * @param $paymentMethodToken
     *
     * @return mixed
     */
    public function getThreeDSecureVaultNonce($paymentMethodToken)
    {
        $this->init();

        $result = Braintree_PaymentMethodNonce::create($paymentMethodToken);
        return $result->paymentMethodNonce->nonce;
    }

    /**
     * Try and load the Braintree customer from the stored customer ID
     * @param $braintreeCustomerId
     *
     * @return Braintree_Customer
     */
    public function getCustomer($braintreeCustomerId)
    {
        try {
            return Braintree_Customer::find($braintreeCustomerId);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Check a customer owns the method we're trying to modify
     * @param $paymentMethod
     *
     * @return bool
     */
    public function customerOwnsMethod($paymentMethod)
    {
        // Grab the customer ID from the customers account
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getBraintreeCustomerId();

        // Detect which type of payment method we've got here
        if($paymentMethod instanceof Braintree_PayPalAccount) {

            // Grab the customer
            $customer = $this->getCustomer($customerId);

            // Store all the tokens in an array
            $customerTokens = array();

            // Check the customer has PayPal Accounts
            if(isset($customer->paypalAccounts)) {

                /* @var $payPalAccount Braintree_PayPalAccount */
                foreach($customer->paypalAccounts as $payPalAccount) {
                    if(isset($payPalAccount->token)) {
                        $customerTokens[] = $payPalAccount->token;
                    }
                }
            } else {
                return false;
            }

            // Check to see if this customer account contains this token
            if(in_array($paymentMethod->token, $customerTokens)) {
                return true;
            }

            return false;

        } else if(isset($paymentMethod->customerId) && $paymentMethod->customerId == $customerId) {

            return true;
        }

        return false;
    }

}