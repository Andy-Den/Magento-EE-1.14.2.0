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

class ET_CustomerGroupAutoSwitcher_Adminhtml_RulesController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('promo')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Catalog'),
                Mage::helper('customergroupautoswitcher')->__('ET Customer Group Auto Switcher'),
                Mage::helper('customergroupautoswitcher')->__('Rules')
            );

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()->renderLayout();
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }


    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $ruleId     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('customergroupautoswitcher/rules')->load($ruleId);

        if ($model->getId() || $ruleId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('customergroupautoswitcherrules_field', $model);
        }

        $this->loadLayout();
        $this->_setActiveMenu('catalog');

        $this->_addContent($this->getLayout()->createBlock('customergroupautoswitcher/adminhtml_rules_edit'))
            ->_addLeft($this->getLayout()->createBlock('customergroupautoswitcher/adminhtml_rules_edit_tabs'));

        $this->renderLayout();
    }


    private function deleteAllData()
    {
        $modelc = Mage::getModel('customergroupautoswitcher/rules')->getCollection();
        foreach ($modelc as $model) {
            $model->delete();
        }
    }


    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('customergroupautoswitcher/rules');
            $model->setData($data);
            $model->setId($this->getRequest()->getParam('id'));

            try {
                $emailTemplate = $this->getRequest()->getParam('email_template');
                if (is_array($emailTemplate)) {
                    $model->setData("email_template", serialize($emailTemplate));
                }
                $model->save();

                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('customergroupautoswitcher')->__('Rule successfully saved'));
                Mage::getSingleton('adminhtml/session')->setCustomerGroupAutoSwitcherData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setCustomerGroupAutoSwitcherData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')
            ->addError(Mage::helper('customergroupautoswitcher')->__('Unable to find rule to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('customergroupautoswitcher/rules');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('customergroupautoswitcher')->__('Rule was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }


    public function applyAction()
    {
        foreach (Mage::getModel('customer/customer')->getCollection() as $customerCollectionObject) {
            $customer = Mage::getModel('customer/customer')->load($customerCollectionObject->getId());
            $storeId = $customer->getStoreId();
            $currentGroup = $customer->getGroupId();
            $configuration = Mage::helper("customergroupautoswitcher")->getConfigForStore($storeId);
            $newRule = Mage::helper("customergroupautoswitcher")->calculateNewGroup($customer);
            if ($newRule->getCustomerGroup() != $currentGroup) {
                $movingDirection = Mage::helper("customergroupautoswitcher")
                    ->calculateMoveDirection($customer, $newRule->getCustomerGroup());
                if (in_array($movingDirection, $configuration["switching"])) {
                    Mage::helper("customergroupautoswitcher")->log($customer, $newRule->getCustomerGroup());
                    $customer->setGroupId($newRule->getCustomerGroup());
                    $customer->save();
                    if (in_array($movingDirection, $configuration["mailing"])) {
                        Mage::helper("customergroupautoswitcher")->sendGroupChangeEmail($storeId, $newRule, $customer);
                    }
                }
            }
        }

        Mage::getSingleton('adminhtml/session')
            ->addSuccess(Mage::helper('customergroupautoswitcher')->__('Rules was successfully applied'));
        $this->_redirect('*/*/');
    }
}