<?php
 /** 
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @Author     Ocean <ocean1890@live.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/* @var $installer TTS_Brands_Model_Resource_Eav_Mysql4_Setup*/
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('brands/brands'), 'url_key', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => 100,
    'nullable'  => true,
    'comment'   => 'Url key',
));

Mage::getResourceModel('brands/brands_collection')
    ->load()
    ->save();

$installer->endSetup();