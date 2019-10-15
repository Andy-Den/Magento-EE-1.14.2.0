<?php
/**
 * NOTICE OF LICENSE
 *
 * You may not give, sell, distribute, sub-license, rent, lease or lend
 * any portion of the Software or Documentation to anyone.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @category   ET
 * @package    ET_CustomerGroupAutoSwitcher
 * @copyright  Copyright (c) 2012 ET Web Solutions (http://etwebsolutions.com)
 * @contacts   support@etwebsolutions.com
 * @license    http://shop.etwebsolutions.com/etws-license-commercial-v1/   ETWS Commercial License (ECL1)
 */

class ET_CustomerGroupAutoSwitcher_Model_Observer
{
    public function updategroups($observer)
    {
        $order = $observer->getDataObject();
        $storeId = $order->getStoreId();

        $configuration = Mage::helper("customergroupautoswitcher")->getConfigForStore($storeId);

        if (!$order->getCustomerIsGuest()) {
            $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
            $currentGroup = $customer->getGroupId();
            $newRule = Mage::helper("customergroupautoswitcher")->calculateNewGroup($customer);
            if ($newRule->getCustomerGroup() != $currentGroup) {
                $movingDirection = Mage::helper("customergroupautoswitcher")
                    ->calculateMoveDirection($customer, $newRule->getCustomerGroup());
                if (in_array($movingDirection, $configuration["switching"])) {
                    Mage::helper("customergroupautoswitcher")->log($customer, $newRule->getCustomerGroup());
                    $customer->setGroupId($newRule->getCustomerGroup());
                    $customer->save();
                    if (in_array($movingDirection, $configuration["mailing"])) {
                        Mage::helper("customergroupautoswitcher")->sendGroupChangeEmail($storeId, $newRule, $customer);
                    }
                }
            }
        }
    }
}