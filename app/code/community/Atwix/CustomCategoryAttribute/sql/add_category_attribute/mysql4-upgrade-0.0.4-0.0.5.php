<?php
$this->startSetup();

$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'show_two_lines', array(
    'group'         => 'General',
    'input'         => 'select',
    'type'          => 'int',
    'label'         => 'Has it 2 lines name?',
    'source'        => 'eav/entity_attribute_source_boolean',
    'backend'       => '',
    'visible'       => true,
    'required'      => false,
    'user_defined'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));


$this->endSetup();