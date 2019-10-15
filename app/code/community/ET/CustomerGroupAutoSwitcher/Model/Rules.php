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

class ET_CustomerGroupAutoSwitcher_Model_Rules extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('customergroupautoswitcher/rules');
    }

    public function getStoreEmailTemplate($storeId = 0)
    {
        if ($data = @unserialize($this->getData("email_template"))) {
            if (isset($data[$storeId])) {
                return ((int)$data[$storeId])>0?((int)$data[$storeId]):"customer_group_switched_template";
            }
        }
        return "customer_group_switched_template";
    }
}
