<?php
$this->startSetup();

$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'show_sub_categories', array(
    'group'         => 'General',
    'input'         => 'select',
    'type'          => 'int',
    'label'         => 'Use static link?',
    'source'        => 'eav/entity_attribute_source_boolean',
    'backend'       => '',
    'visible'       => true,
    'required'      => false,
    'user_defined'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'static_block_name', [
    'group'         => 'General',
    'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Static block for top brands',
    'backend'       => '',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
]);
$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'static_block_name2', array(
    'group'         => 'General',
    'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Static link for this category',
    'backend'       => '',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));
$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'static_block_name3', array(
    'group'         => 'General',
    'input'         => 'text',
    'type'          => 'text',
    'label'         => "First static block for this category",
    'backend'       => '',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'static_block_name4', array(
    'group'         => 'General',
    'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Second static block for this category',
    'backend'       => '',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$this->endSetup();