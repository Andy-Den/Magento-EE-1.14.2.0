<?php
/**
* @author Amasty Team
* @copyright Amasty
* @package Amasty_Orderstatus
*/
class Amasty_Orderstatus_Model_Mysql4_Status_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('amorderstatus/status');
    }
}