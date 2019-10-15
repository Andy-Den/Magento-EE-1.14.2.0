<?php
$installer = $this;

$installer->startSetup();

$installer->run("

 -- DROP TABLE IF EXISTS `{$this->getTable('tts_brands')}`;
CREATE TABLE IF NOT EXISTS `{$this->getTable('tts_brands')}` (
  `brands_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` longtext,
  `pagetitle` varchar(255) DEFAULT NULL,
  `pagekeyword` text,
  `pagedescription` text,
  `filler` smallint(6),
  `status` smallint(6),
  PRIMARY KEY (`brands_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
");

$installer->installEntities();

$attribute_code = 'brands';
$eavAttributeTable = $installer->getTable('eav_attribute');
$catalogEavAttributeTable = $installer->getTable('catalog_eav_attribute');

$attribute_id = $installer->getConnection()->fetchOne("SELECT attribute_id FROM `{$eavAttributeTable}` WHERE attribute_code='{$attribute_code}'");
$sql = "UPDATE `{$catalogEavAttributeTable}` SET `is_filterable` = '1' WHERE `attribute_id` ={$attribute_id};";
$installer->run($sql);

$installer->endSetup();