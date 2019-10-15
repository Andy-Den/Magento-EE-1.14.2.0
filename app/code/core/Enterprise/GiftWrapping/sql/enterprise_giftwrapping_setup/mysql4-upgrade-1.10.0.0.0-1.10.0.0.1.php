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
 * @package     Enterprise_GiftWrapping
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

$installer = $this;
/* @var $installer Enterprise_GiftWrapping_Model_Resource_Mysql4_Setup */

$installer->addAttribute('catalog_product', 'gift_wrapping_available', array(
    'group'         => 'Gift Options',
    'backend'       => 'catalog/product_attribute_backend_boolean',
    'frontend'      => '',
    'label'         => 'Allow Gift Wrapping',
    'input'         => 'select',
    'source'        => 'catalog/product_attribute_source_boolean',
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'       => true,
    'required'      => false,
    'user_defined'  => false,
    'default'       => '2',
    'visible_on_front' => false
));

$installer->addAttribute('catalog_product', 'gift_wrapping_price', array(
    'group'         => 'Gift Options',
    'type'          => 'decimal',
    'backend'       => 'catalog/product_attribute_backend_price',
    'frontend'      => '',
    'label'         => 'Price for Gift Wrapping',
    'input'         => 'price',
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible'       => true,
    'required'      => false,
    'user_defined'  => false,
    'visible_on_front' => false
));
