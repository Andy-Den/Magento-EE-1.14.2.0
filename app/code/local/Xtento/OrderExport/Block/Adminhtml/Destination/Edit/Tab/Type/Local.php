<?php

/**
 * Product:       Xtento_OrderExport (1.6.6)
 * ID:            Qujl4HDX/jh1r70snvVGpfhTrMOVK6Ta5j2OrLhS9R8=
 * Packaged:      2015-01-06T15:36:16+00:00
 * Last Modified: 2013-02-09T22:16:47+01:00
 * File:          app/code/local/Xtento/OrderExport/Block/Adminhtml/Destination/Edit/Tab/Type/Local.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Block_Adminhtml_Destination_Edit_Tab_Type_Local
{
    // Local Directory Configuration
    public function getFields($form)
    {
        $fieldset = $form->addFieldset('config_fieldset', array(
            'legend' => Mage::helper('xtento_orderexport')->__('Local Directory Configuration'),
        ));

        $fieldset->addField('path', 'text', array(
            'label' => Mage::helper('xtento_orderexport')->__('Export Directory'),
            'name' => 'path',
            'note' => Mage::helper('xtento_orderexport')->__('Path to the directory where the exported file will be saved. Use an absolute path or specify a path relative to the Magento root directory by putting a dot at the beginning. Example to export into the var/export/ directory located in the root directory of Magento: ./var/export/  Example to export into an absolute directory: /var/www/test/ would export into the absolute path /var/www/test (and not located in the Magento installation)'),
            'required' => true
        ));
    }
}