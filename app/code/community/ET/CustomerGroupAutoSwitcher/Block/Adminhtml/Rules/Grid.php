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

class ET_CustomerGroupAutoSwitcher_Block_Adminhtml_Rules_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('customergroupautoswitcherGrid');
        $this->setDefaultSort('minimal_amount');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('customergroupautoswitcher/rules')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('active_rule', array(
            'header'    => Mage::helper('customergroupautoswitcher')->__('Active'),
            'align'     =>'left',
            'index'     => 'active_rule',
            'type'      => 'options',
            "options"    =>$this->_toOptions(Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray()),
        ));

        $this->addColumn('customer_group', array(
            'header'    => Mage::helper('customergroupautoswitcher')->__('Customer Group'),
            'align'     =>'left',
            'index'     => 'customer_group',
            'type'      => 'options',
            "options"   =>$this->_toOptions(
                Mage::getModel('adminhtml/system_config_source_customer_group')->toOptionArray()
            )
        ));

        $this->addColumn('minimal_amount', array(
            'header'    => Mage::helper('customergroupautoswitcher')->__('Amount'),
            'align'     =>'left',
            //'width'     => '150px',
            'index'     => 'minimal_amount',
        ));

        $this->addColumn('action', array(
            'header'    =>  Mage::helper('customergroupautoswitcher')->__('Action'),
            'width'     => '100',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption'   => Mage::helper('customergroupautoswitcher')->__('Edit'),
                    'url'       => array('base'=> '*'.'/'.'*'.'/edit'),
                    'field'     => 'id'
                )
            ),
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'stores',
            'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _toOptions($data)
    {
        $tmpArray = array();
        foreach ($data as $value) {
            $tmpArray[$value["value"]]=$value["label"];
        }
        return $tmpArray;
    }
}
