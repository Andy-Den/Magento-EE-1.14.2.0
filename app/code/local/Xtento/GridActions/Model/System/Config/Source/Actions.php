<?php

/**
 * Product:       Xtento_GridActions (1.7.8)
 * ID:            FLIeaaH2tLI8Ro1ExjlOKSJSRlAlc69iSGtu81EZQZs=
 * Packaged:      2014-12-17T03:54:17+00:00
 * Last Modified: 2011-12-11T17:24:51+01:00
 * File:          app/code/local/Xtento/GridActions/Model/System/Config/Source/Actions.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_GridActions_Model_System_Config_Source_Actions
{

    public function toOptionArray()
    {
        $actions = Mage::getModel('gridactions/system_config_source_order_actions')->getOrderActions();
        # Add your own actions:
        # $actions[] = array('value' => '__value__', 'label' => 'your_label');        
        
        return $actions;
    }

}
