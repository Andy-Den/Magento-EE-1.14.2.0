<?php

/**
 * Product:       Xtento_OrderExport (1.6.6)
 * ID:            Qujl4HDX/jh1r70snvVGpfhTrMOVK6Ta5j2OrLhS9R8=
 * Packaged:      2015-01-06T15:36:16+00:00
 * Last Modified: 2013-02-10T15:47:06+01:00
 * File:          app/code/local/Xtento/OrderExport/Model/Mysql4/Destination/Collection.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Mysql4_Destination_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('xtento_orderexport/destination');
    }
}