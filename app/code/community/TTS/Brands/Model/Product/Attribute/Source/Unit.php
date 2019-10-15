<?php
class TTS_Brands_Model_Product_Attribute_Source_Unit extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = Mage::getResourceModel('brands/brands_collection')
                  ->loadData()
                  ->toOptionArray(false);
        }
        return $this->_options;
    }
}