<?php

/**
 * Product:       Xtento_StockImport (2.2.3)
 * ID:            hP1u+MkFuWCNii6DNKWj0Z9DGtXJm+UAxW8tNX6mHhE=
 * Packaged:      2015-01-11T18:18:35+00:00
 * Last Modified: 2013-06-26T22:55:32+02:00
 * File:          app/code/local/Xtento/StockImport/Block/Adminhtml/Log.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_StockImport_Block_Adminhtml_Log extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'xtento_stockimport';
        $this->_controller = 'adminhtml_log';
        $this->_headerText = Mage::helper('xtento_stockimport')->__('Stock Import - Execution Log');
        parent::__construct();
        $this->_removeButton('add');
    }

    protected function _toHtml()
    {
        return $this->getLayout()->createBlock('xtento_stockimport/adminhtml_widget_menu')->toHtml() . parent::_toHtml();
    }
}