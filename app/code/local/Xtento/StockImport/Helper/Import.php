<?php

/**
 * Product:       Xtento_StockImport (2.2.3)
 * ID:            hP1u+MkFuWCNii6DNKWj0Z9DGtXJm+UAxW8tNX6mHhE=
 * Packaged:      2015-01-11T18:18:35+00:00
 * Last Modified: 2014-11-16T20:52:04+01:00
 * File:          app/code/local/Xtento/StockImport/Helper/Import.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_StockImport_Helper_Import extends Mage_Core_Helper_Abstract
{
    public function getImportBkpDir()
    {
        return Mage::getBaseDir('var') . DS . "import_bkp" . DS;
    }

    public function getProcessorName($processor)
    {
        $processors = Mage::getSingleton('xtento_stockimport/import')->getProcessors();
        $processorName = $processors[$processor];
        return $processorName;
    }

    public function getMultiWarehouseSupport()
    {
        if (Mage::helper('xtcore/utils')->isExtensionInstalled('Innoexts_Warehouse')) {
            return true;
        }
        if (Mage::helper('xtcore/utils')->isExtensionInstalled('MDN_AdvancedStock')) {
            return true;
        }
        if (Mage::helper('xtcore/utils')->isExtensionInstalled('Aitoc_Aitquantitymanager')) {
            return true;
        }
        return false;
    }
}