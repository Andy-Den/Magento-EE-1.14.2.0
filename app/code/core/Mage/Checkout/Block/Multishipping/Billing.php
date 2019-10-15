<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition End User License Agreement
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magento.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

/**
 * Multishipping billing information
 *
 * @category   Mage
 * @package    Mage_Checkout
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Checkout_Block_Multishipping_Billing extends Mage_Payment_Block_Form_Container
{
    /**
     * Prepare children blocks
     */
    protected function _prepareLayout()
    {
        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $headBlock->setTitle(
                Mage::helper('checkout')->__('Billing Information - %s', $headBlock->getDefaultTitle())
            );
        }

        return parent::_prepareLayout();
    }

    /**
     * Check payment method model
     *
     * @param Mage_Payment_Model_Method_Abstract|null $method
     * @return bool
     */
    protected function _canUseMethod($method)
    {
        return $method && $method->canUseForMultishipping() && parent::_canUseMethod($method);
    }

    /**
     * Retrieve code of current payment method
     *
     * @return mixed
     */
    public function getSelectedMethodCode()
    {
        if ($method = $this->getQuote()->getPayment()->getMethod()) {
            return $method;
        }
        return false;
    }

    /**
     * Retrieve billing address
     *
     * @return Mage_Sales_Model_Quote_Address
     */
    public function getAddress()
    {
        $address = $this->getData('address');
        if (is_null($address)) {
            $address = Mage::getSingleton('checkout/type_multishipping')->getQuote()->getBillingAddress();
            $this->setData('address', $address);
        }
        return $address;
    }

    /**
     * Retrieve quote model object
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        return Mage::getSingleton('checkout/session')->getQuote();
    }

    /**
     * Getter
     *
     * @return float
     */
    public function getQuoteBaseGrandTotal()
    {
        return (float)$this->getQuote()->getBaseGrandTotal();
    }

    /**
     * Retrieve url for select billing address
     *
     * @return string
     */
    public function getSelectAddressUrl()
    {
        return $this->getUrl('*/multishipping_address/selectBilling');
    }

    /**
     * Retrieve data post destination url
     *
     * @return string
     */
    public function getPostActionUrl()
    {
        //return $this->getUrl('*/*/billingPost');
        return $this->getUrl('*/*/overview');
    }

    /**
     * Retrieve back url
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/backtoshipping');
    }
}
