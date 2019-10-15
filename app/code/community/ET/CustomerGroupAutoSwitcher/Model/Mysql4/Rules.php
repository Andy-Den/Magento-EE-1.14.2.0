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

class ET_CustomerGroupAutoSwitcher_Model_Mysql4_Rules extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        // Note that the logid refers to the key field in your database table.
        $this->_init('customergroupautoswitcher/rules', 'rule_id');
    }
}