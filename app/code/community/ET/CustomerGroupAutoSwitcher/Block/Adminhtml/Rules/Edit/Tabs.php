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

class ET_CustomerGroupAutoSwitcher_Block_Adminhtml_Rules_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('rules_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('customergroupautoswitcher')->__('General information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('customergroupautoswitcher')->__('Rule Information'),
            'title'     => Mage::helper('customergroupautoswitcher')->__('Rule Information'),
            'content'   => $this->getLayout()
                ->createBlock('customergroupautoswitcher/adminhtml_rules_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}