<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition End User License Agreement
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magento.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Core
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */


/**
 * Custom variabel collection
 *
 * @category    Mage
 * @package     Mage_Core
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Core_Model_Resource_Variable_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Store Id
     *
     * @var int
     */
    protected $_storeId    = 0;

    /**
     *  Define resource model
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('core/variable');
    }

    /**
     * Setter
     *
     * @param integer $storeId
     * @return Mage_Core_Model_Resource_Variable_Collection
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }

    /**
     * Getter
     *
     * @return integer
     */
    public function getStoreId()
    {
        return $this->_storeId;
    }

    /**
     * Add store values to result
     *
     * @return Mage_Core_Model_Resource_Variable_Collection
     */
    public function addValuesToResult()
    {
        $this->getSelect()
            ->join(
                array('value_table' => $this->getTable('core/variable_value')),
                'value_table.variable_id = main_table.variable_id',
                array('value_table.value'));
        $this->addFieldToFilter('value_table.store_id', array('eq' => $this->getStoreId()));
        return $this;
    }

    /**
     * Retrieve option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('code', 'name');
    }
}
