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
 * @category    Enterprise
 * @package     Enterprise_GiftCardAccount
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

$installer = $this;
/* @var $installer Enterprise_GiftCardAccount_Model_Mysql4_Setup */
$installer->startSetup();

$installer->addAttribute('quote', 'gift_cards_amount', array('type'=>'decimal'));
$installer->addAttribute('quote', 'base_gift_cards_amount', array('type'=>'decimal'));

$installer->addAttribute('quote_address', 'gift_cards_amount', array('type'=>'decimal'));
$installer->addAttribute('quote_address', 'base_gift_cards_amount', array('type'=>'decimal'));

$installer->addAttribute('quote', 'gift_cards_amount_used', array('type'=>'decimal'));
$installer->addAttribute('quote', 'base_gift_cards_amount_used', array('type'=>'decimal'));

$installer->endSetup();
