<?php

/**
 * Product:       Xtento_GridActions (1.7.8)
 * ID:            FLIeaaH2tLI8Ro1ExjlOKSJSRlAlc69iSGtu81EZQZs=
 * Packaged:      2014-12-17T03:54:17+00:00
 * Last Modified: 2014-07-26T17:55:40+02:00
 * File:          app/code/local/Xtento/GridActions/Model/System/Config/Source/Order/Status.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_GridActions_Model_System_Config_Source_Order_Status
{
    public function toOptionArray()
    {
        $statuses[] = array('value' => 'no_change', 'label' => Mage::helper('adminhtml')->__('-- No custom status --'));

        if (Mage::helper('xtcore/utils')->mageVersionCompare(Mage::getVersion(), '1.5.0.0', '>=')) {
            # Support for custom order status introduced in Magento 1.5
            $orderStatus = Mage::getModel('sales/order_config')->getStatuses();
            foreach ($orderStatus as $status => $label) {
                $statuses[] = array('value' => $status, 'label' => Mage::helper('adminhtml')->__((string)$label));
            }
        } else {
            $orderStatus = Mage::getModel('adminhtml/system_config_source_order_status')->toOptionArray();
            foreach ($orderStatus as $status) {
                if ($status['value'] == '') {
                    continue;
                }
                $statuses[] = array('value' => $status['value'], 'label' => Mage::helper('adminhtml')->__((string)$status['label']));
            }
            // Alternate method to fetch statuses for below 1.5:
            /*
            $orderStatus = Mage::getSingleton('sales/order_config')->getStatuses();
            foreach ($orderStatus as $status => $label) {
                if ($status == '') {
                    continue;
                }
                $statuses[] = array('value' => $status, 'label' => Mage::helper('adminhtml')->__((string)$label));
            }
            */
        }
        return $statuses;
    }

    // Function to just put all order status "codes" into an array.
    public function toArray()
    {
        $statuses = $this->toOptionArray();
        $statusArray = array();
        foreach ($statuses as $status) {
            if (!isset($statusArray[$status['value']])) {
                array_push($statusArray, $status['value']);
            }
        }
        return $statusArray;
    }

    static function isEnabled()
    {

            return true;

    }
}