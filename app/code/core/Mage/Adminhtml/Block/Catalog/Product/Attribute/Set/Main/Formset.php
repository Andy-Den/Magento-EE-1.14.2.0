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
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

/**
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Mage_Adminhtml_Block_Catalog_Product_Attribute_Set_Main_Formset extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Prepares attribute set form
     *
     */
    protected function _prepareForm()
    {
        $data = Mage::getModel('eav/entity_attribute_set')
            ->load($this->getRequest()->getParam('id'));

        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('set_name', array('legend'=> Mage::helper('catalog')->__('Edit Set Name')));
        $fieldset->addField('attribute_set_name', 'text', array(
            'label' => Mage::helper('catalog')->__('Name'),
            'note' => Mage::helper('catalog')->__('For internal use.'),
            'name' => 'attribute_set_name',
            'required' => true,
            'class' => 'required-entry validate-no-html-tags',
            'value' => $data->getAttributeSetName()
        ));

        if( !$this->getRequest()->getParam('id', false) ) {
            $fieldset->addField('gotoEdit', 'hidden', array(
                'name' => 'gotoEdit',
                'value' => '1'
            ));

            $sets = Mage::getModel('eav/entity_attribute_set')
                ->getResourceCollection()
                ->setEntityTypeFilter(Mage::registry('entityType'))
                ->load()
                ->toOptionArray();

            $fieldset->addField('skeleton_set', 'select', array(
                'label' => Mage::helper('catalog')->__('Based On'),
                'name' => 'skeleton_set',
                'required' => true,
                'class' => 'required-entry',
                'values' => $sets,
            ));
        }

        $form->setMethod('post');
        $form->setUseContainer(true);
        $form->setId('set_prop_form');
        $form->setAction($this->getUrl('*/*/save'));
        $form->setOnsubmit('return false;');
        $this->setForm($form);
    }
}
