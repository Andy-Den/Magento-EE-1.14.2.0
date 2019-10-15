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

$installer = $this;
/* $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('et_customergroup_rules')};

CREATE TABLE `{$this->getTable('et_customergroup_rules')}` (
  `rule_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_group` int(11) NOT NULL,
  `active_rule` tinyint(4) NOT NULL,
  `minimal_amount` decimal(8,2) NOT NULL,
  `email_template` text NOT NULL,
  PRIMARY KEY (`rule_id`),
  UNIQUE KEY `minimal_amount` (`minimal_amount`),
  UNIQUE KEY `customer_group` (`customer_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();