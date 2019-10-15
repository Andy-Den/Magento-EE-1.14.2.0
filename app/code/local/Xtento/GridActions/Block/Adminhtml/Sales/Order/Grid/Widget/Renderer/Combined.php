<?php

/**
 * Product:       Xtento_GridActions (1.7.8)
 * ID:            FLIeaaH2tLI8Ro1ExjlOKSJSRlAlc69iSGtu81EZQZs=
 * Packaged:      2014-12-17T03:54:17+00:00
 * Last Modified: 2013-04-22T22:44:14+02:00
 * File:          app/code/local/Xtento/GridActions/Block/Adminhtml/Sales/Order/Grid/Widget/Renderer/Combined.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_GridActions_Block_Adminhtml_Sales_Order_Grid_Widget_Renderer_Combined extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Select
{
    public function render(Varien_Object $row)
    {
        $html = Mage::getBlockSingleton('gridactions/adminhtml_sales_order_grid_widget_renderer_carrier')->renderCombined($row, $this->getColumn());
        $html .= '&nbsp;';
        $html .= Mage::getBlockSingleton('gridactions/adminhtml_sales_order_grid_widget_renderer_trackingnumber')->renderCombined($row, $this->getColumn());

        return $html;
    }

    /*
    * Return dummy filter.
    */
    public function getFilter()
    {
        return false;
    }
}
