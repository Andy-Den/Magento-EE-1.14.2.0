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

class ET_CustomerGroupAutoSwitcher_Block_Adminhtml_Rules_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'customergroupautoswitcher';
        $this->_controller = 'adminhtml_rules';
        $this->_updateButton('save', 'label', Mage::helper('customergroupautoswitcher')->__('Save Rule'));
        $this->_removeButton('reset');
    }

    public function getHeaderText()
    {
        $rule = Mage::registry('customergroupautoswitcherrules_field');
        if ($rule->getId()) {
            return Mage::helper('customergroupautoswitcher')->__("Edit Group Rule");
        } else {
            return Mage::helper('customergroupautoswitcher')->__('New Group Rule');
        }
    }
}