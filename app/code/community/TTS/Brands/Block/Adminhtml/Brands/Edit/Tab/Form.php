<?php
class TTS_Brands_Block_Adminhtml_Brands_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /*public function getEntireRange(){
         $brands =  Mage::getModel('brands/brands')->getCollection();
         foreach ($brands as $item) {
            return $item['filler']; 
         }
    }*/
    
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $this->setForm($form);
        $fieldset = $form->addFieldset('brands_form', array('legend'=>Mage::helper('brands')->__('Brand information')));

        $fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('brands')->__('Title'),
            'required'  => true,
            'name'      => 'title',
			'style'     => 'width:450px;',
        ));
        $fieldset->addField('url_key', 'text', array(
            'label' => Mage::helper('brands')->__('Url Key'),
            'required'  => false,
            'name'  => 'url_key',
            'style' => 'width:450px;',
            'class'     => 'validate-identifier',
        ));


        $fieldset->addField('description', 'editor', array(
            'label'     => Mage::helper('brands')->__('Description'),
            'required'  => true,
            'name'      => 'description',
			'wysiwyg'   => true,
			'style'     => 'height:12em;width:450px;',
        ));

        $fieldset->addField('brand_image', 'file', array(
            'label'     => Mage::helper('brands')->__('Image'),
            'required'  => false,
            'name'      => 'image',
			'style'     => 'width:450px;',
        ));

        $fieldset->addField('image', 'hidden', array(
            'required'  => false,
            'name'      => 'image-hidden',
        ));
        $lastEvent="";
        $fieldset->addType('extended_label','TTS_Brands_Lib_Varien_Data_Form_Element_ExtendedLabel');
        $fieldset->addField('mycustom_element', 'extended_label', array(
            'name'          => 'mycustom_element',
            'required'      => false,
            'value'     => $this->getLastEventLabel($lastEvent),
        ));
        
        /*$fieldset->addField('filler', 'checkbox', array(
          'label'     => Mage::helper('brands')->__('Show Slider'),
          'name'      => 'filler',
          'checked'    => $this->getEntireRange()==1 ? 'true' : 'false',
          'onclick' => 'this.value = this.checked ? 1 : 0;',   
          'after_element_html' => '<small>Show</small>',
        ));*/
        
        $fieldset->addField('filler', 'select', array(
          'label'     => Mage::helper('brands')->__('Top brands'),
          'name'      => 'filler',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => 'Yes',
              ),

              array(
                  'value'     => 0,
                  'label'     => 'No',
              ),
          ),
        ));

        
        $fieldset->addField('pagetitle', 'text', array(
            'label'     => Mage::helper('brands')->__('Page Title'),
            'required'  => false,
            'name'      => 'pagetitle',
			'style'     => 'width:450px;',
        ));

        $fieldset->addField('pagekeyword', 'textarea', array(
            'label'     => Mage::helper('brands')->__('Meta Keywords'),
            'required'  => false,
            'name'      => 'pagekeyword',
			'style'     => 'width:450px;',
        ));

        $fieldset->addField('pagedescription', 'textarea', array(
            'label'     => Mage::helper('brands')->__('Meta Description'),
            'required'  => false,
            'name'      => 'pagedescription',
			'style'     => 'width:450px;',
        ));
        
        $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('brands')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('brands')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('brands')->__('Disabled'),
              ),
          ),
        ));



        if ( Mage::getSingleton('adminhtml/session')->getbrandsData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getbrandsData());
            Mage::getSingleton('adminhtml/session')->setbrandsData(null);
        } elseif ( Mage::registry('brands_data') ) {
            $form->setValues(Mage::registry('brands_data')->getData());
        }

        return parent::_prepareForm();
    }
}