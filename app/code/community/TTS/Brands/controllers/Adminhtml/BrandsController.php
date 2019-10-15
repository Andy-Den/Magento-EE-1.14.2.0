<?php
class TTS_Brands_Adminhtml_BrandsController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('catalog/brands')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Catalog'), Mage::helper('adminhtml')->__('Brands'));
        return $this;
    }

    public function indexAction() {
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('brands/adminhtml_brands'));
        $this->renderLayout();
    }

    public function editAction()
    {
        $brandsId     = $this->getRequest()->getParam('id');
        $brandsModel  = Mage::getModel('brands/brands')->load($brandsId);

        if ($brandsModel->getId() || $brandsId == 0) {

            Mage::register('brands_data', $brandsModel);

	        $this->loadLayout()
	            ->_setActiveMenu('catalog/brands')
	            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Catalog'), Mage::helper('adminhtml')->__('Brands'));
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            $this->_addContent($this->getLayout()->createBlock('brands/adminhtml_brands_edit'))
                 ->_addLeft($this->getLayout()->createBlock('brands/adminhtml_brands_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('brands')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        if ( $this->getRequest()->getPost() ) {
            try {
                $postData = $this->getRequest()->getPost();
                $brandsModel = Mage::getModel('brands/brands');

                $name = $this->uploadImage();
				if (empty($name) && !empty($postData['image-hidden'])) {
					$name = $postData['image-hidden'];
				}

                $brandsModel->setId($this->getRequest()->getParam('id'));
				$brandsModel->setTitle($postData['title']);
                $brandsModel->setUrlKey($postData['url_key']);
				$brandsModel->setDescription($postData['description']);
				$brandsModel->setPagetitle($postData['pagetitle']);
				$brandsModel->setPagekeyword($postData['pagekeyword']);
				$brandsModel->setPagedescription($postData['pagedescription']);
                $brandsModel->setStatus($postData['status']);
                $brandsModel->setFiller($postData['filler']);
                $brandsModel->setImage($name);
                $brandsModel->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setbrandsData(false);

                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setbrandsData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $brandsModel = Mage::getModel('brands/brands');

                $brandsModel->setId($this->getRequest()->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }
    /**
     * Product grid for AJAX request.
     * Sort and filter result for example.
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
               $this->getLayout()->createBlock('importedit/adminhtml_brands_grid')->toHtml()
        );
    }

    private function uploadImage()
    {

        if(isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
            try {
			    $uploader = new Varien_File_Uploader('image');
			    $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));

			    $uploader->setAllowRenameFiles(false);

			    $uploader->setFilesDispersion(false);

			    $path = Mage::getBaseDir() . DS . 'media' . DS . 'catalog' . DS . 'brands';

                $filename = str_replace(' ','-',$_FILES['image']['name']);
			    $uploader->save($path, $filename);

            } catch (Exception $e) {
               Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
			return $filename;//$_FILES['image']['name'];
        }
		return '';
    }
}