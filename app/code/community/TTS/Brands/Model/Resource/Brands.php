<?php

class TTS_Brands_Model_Resource_Brands extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('brands/brands', 'brands_id');
    }

    public function checkUrlKey($urlKey)
    {
        $select = $this->_getReadAdapter()->select()
            ->from(array('b' => $this->getMainTable()), 'brands_id')
            ->where('b.url_key = ?', $urlKey)
            ->limit(1);

        return $this->_getReadAdapter()->fetchOne($select);
    }
}