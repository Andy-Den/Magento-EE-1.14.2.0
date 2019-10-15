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

class ET_CustomerGroupAutoSwitcher_Block_Adminhtml_Rules extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_rules';
        $this->_blockGroup = 'customergroupautoswitcher';
        $this->_headerText = Mage::helper('customergroupautoswitcher')->__('Rules');
        $this->_addButtonLabel = Mage::helper('customergroupautoswitcher')->__('Add New Rule');

        $this->_addButton(
            'import',
            array(
                'label'     => Mage::helper('customergroupautoswitcher')->__('Apply Rules')."<sup>*</sup>",
                'onclick'   => "location.href='".$this->getUrl('*'.'/'.'*'.'/apply')."'",
                'class'     => 'go',
            )
        );

        parent::__construct();
    }

    public function getGridHtml()
    {
        return '<div style="float:right;clear:both;"><sup>*</sup>'
            . Mage::helper('customergroupautoswitcher')
            ->__('Each user will be moved to group according to it total amount')
            .'</div><div style="clear:both;"></div>' . parent::getGridHtml();
    }
}