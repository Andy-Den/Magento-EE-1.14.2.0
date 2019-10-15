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

class ET_CustomerGroupAutoSwitcher_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function calculateNewGroup($customerModel)
    {
        $currentSum = Mage::getResourceModel('sales/sale_collection')
            ->setCustomerFilter($customerModel)
            ->setOrderStateFilter(Mage_Sales_Model_Order::STATE_COMPLETE, false)
            ->load()->getTotals()->getBaseLifetime();
        $ruleCollection = Mage::getResourceModel("customergroupautoswitcher/rules_collection ");
        $ruleCollection->getSelect()->where("active_rule>?", 0)
            ->where("minimal_amount<=?", $currentSum)->order("minimal_amount DESC")->limit(1);

        foreach ($ruleCollection as $lastRule) {
            return $lastRule;
        }

        return Mage::getModel("customergroupautoswitcher/rules")->setData(
            "customer_group",
            $customerModel->getGroupId()
        );
    }

    public function calculateMoveDirection($customerModel, $newGroup)
    {
        $newSum = 0;
        $ruleCollection = Mage::getResourceModel("customergroupautoswitcher/rules_collection ");
        $ruleCollection->getSelect()->where("active_rule>?", 0)->where("customer_group=?", $newGroup);
        foreach ($ruleCollection as $lastRule) {
            $newSum=$lastRule->getMinimalAmount();
        }

        $ruleCollection = Mage::getResourceModel("customergroupautoswitcher/rules_collection ");
        $ruleCollection->getSelect()
            ->where("active_rule>?", 0)
            ->where("customer_group=?", $customerModel->getGroupId());

        foreach ($ruleCollection as $currentRule) {
            if ($currentRule->getMinimalAmount() > $newSum) {
                return "down";
            }
            return "up";
        }

        return "new";
    }


    public function sendGroupChangeEmail($storeId, $newRule, $customer)
    {

        /*
        $mailer = Mage::getModel('core/email')
            ->setTemplate('email/order.phtml')
            ->setType('html')
            ->setTemplateVar('order', $order)
            ->setTemplateVar('quote', $this->getQuote())
            ->setTemplateVar('name', $name)
            ->setToName($name)
            ->setToEmail($email)
            ->send();
        */

        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);

        Mage::getModel('core/email_template')
            ->setDesignConfig(array('area'=>'frontend', 'store'=>$storeId))
            ->sendTransactional(
                $newRule->getStoreEmailTemplate($storeId),
                //Mage::getStoreConfig(self::XML_PATH_FORGOT_EMAIL_IDENTITY),
                Mage::getStoreConfig('customergroupautoswitcher/email/email_identity', $storeId),
                $customer->getEmail(),
                $customer->getName(),
                array(
                    'user'=>$customer,
                    "group"=>Mage::getModel("customer/group")->load($customer->getGroupId()),
                    "rule"=>$newRule,
                ),
                $storeId
            );
        $translate->setTranslateInline(true);
        return $this;
    }


    public function getConfigForStore($storeId)
    {
        $configuration=array(
            "switching"=>array(),
            "mailing"=>array(),
        );
        if (Mage::getStoreConfig('customergroupautoswitcher/general/switch_new', $storeId)) {
            $configuration["switching"][] = "new";
        }
        if (Mage::getStoreConfig('customergroupautoswitcher/general/switch_up', $storeId)) {
            $configuration["switching"][] = "up";
        }
        if (Mage::getStoreConfig('customergroupautoswitcher/general/switch_down', $storeId)) {
            $configuration["switching"][] = "down";
        }

        if (Mage::getStoreConfig('customergroupautoswitcher/email/send_new', $storeId)) {
            $configuration["mailing"][] = "new";
        }
        if (Mage::getStoreConfig('customergroupautoswitcher/email/send_up', $storeId)) {
            $configuration["mailing"][] = "up";
        }
        if (Mage::getStoreConfig('customergroupautoswitcher/email/send_down', $storeId)) {
            $configuration["mailing"][] = "down";
        }

        return $configuration;
    }


    public function log($customer, $toGroup)
    {
        if (Mage::getStoreConfig('customergroupautoswitcher/general/enable_log')) {
            $file = 'et_customergroupautoswitcher.log';
                $forLog = array($customer->getName() . " (" . $customer->getId() . ")");
                $forLog[] = Mage::getModel("customer/group")
                    ->load($customer->getGroupId())->getCode() ."(". $customer->getGroupId().")";
                $forLog[] = Mage::getModel("customer/group")
                    ->load($toGroup)->getCode() ."(". $toGroup.")";
                $message = implode(" - ", $forLog);
            Mage::log($message, Zend_Log::DEBUG, $file, true);
        }
        return true;
    }
}