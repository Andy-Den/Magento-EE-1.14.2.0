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

class ET_CustomerGroupAutoSwitcher_Block_Adminhtml_Rules_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'rules_form',
            array('legend'=>Mage::helper('customergroupautoswitcher')->__('General information'))
        );

        $fieldset->addField('active_rule', 'select', array(
            'name'     => 'active_rule',
            'label'    => Mage::helper('customergroupautoswitcher')->__('Active'),
            "values"   =>Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));

        $fieldset->addField('customer_group', 'select', array(
            'name'     => 'customer_group',
            'required' => true,
            'label'    => Mage::helper('customergroupautoswitcher')->__('Customer Group'),
            "values"   =>Mage::getModel('adminhtml/system_config_source_customer_group')->toOptionArray(),
        ));

        $fieldset->addField('minimal_amount', 'text', array(
            'label'    => Mage::helper('customergroupautoswitcher')->__('Amount'),
            'required' => true,
            'name'     => 'minimal_amount',
            'note'     => 
                'Custromer will be moved to this group if total orders amount is bigger or equal (in base currency)',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset = $form->addFieldset(
                'rules_form_template',
                array('legend' => Mage::helper('customergroupautoswitcher')->__('Email Template'))
            );

            foreach (Mage::getSingleton('adminhtml/system_store')->getStoreCollection() as $store) {
                $fieldset->addField('email_template_' . $store->getId(), 'select', array(
                    'label'  => $store->getName(),
                    'name'   => "email_template[". $store->getId() .']',
                    "values" => Mage::getModel('adminhtml/system_config_source_email_template')->toOptionArray(),
                ));
            }
        } else {
            $defaultStoreId = Mage::app()
                ->getWebsite(true)
                ->getDefaultGroup()
                ->getDefaultStoreId();

            $fieldset->addField('email_template_'.$defaultStoreId, 'select', array(
                'name'      => 'email_template['.$defaultStoreId.']',
                'label'     => Mage::helper('customergroupautoswitcher')->__('Email Template'),
                "values"    => Mage::getModel('adminhtml/system_config_source_email_template')->toOptionArray(),
            ));
        }

        if (Mage::getSingleton('adminhtml/session')->getCustomerGroupAutoSwitcherData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getCustomerGroupAutoSwitcherData());
            $data = Mage::getSingleton('adminhtml/session')->getCustomerGroupAutoSwitcherData();
            if (isset($data['email_template'])) {
                $this->_setEmailTemplates($data['email_template']);
            }
            Mage::getSingleton('adminhtml/session')->setCustomerGroupAutoSwitcherData(null);
        } elseif (Mage::registry('customergroupautoswitcherrules_field')) {
            $form->setValues(Mage::registry('customergroupautoswitcherrules_field')->getData());
            if (Mage::registry('customergroupautoswitcherrules_field')->getEmailTemplate()) {
                $this->_setEmailTemplates(Mage::registry('customergroupautoswitcherrules_field')->getEmailTemplate());
            }
        }
        return parent::_prepareForm();
    }

    protected function _setEmailTemplates($emailTemplates)
    {
        if (!is_array($emailTemplates)) {
            if (!($emailTemplates = @unserialize($emailTemplates))) {
                $emailTemplates=array();
            }
        }

        foreach ($emailTemplates as $store=>$value) {
            if ($element = $this->getForm()->getElement('email_template_' . $store)) {
                $element->setValue($value);
            }
        }
    }
}