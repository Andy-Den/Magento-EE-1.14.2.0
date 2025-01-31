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
 * @category    Magento
 * @package     Magento_Db
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

/**
 * Magento_Db_Object_Trigger
 *
 * @category    Magento
 * @package     Magento_Db
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Magento_Db_Object_Trigger extends Magento_Db_Object implements Magento_Db_Object_Interface
{
    /**
     * @var string
     */
    protected $_dbType  = 'TRIGGER';

    /**
     * @var array
     */
    protected $_data = array();

    /**
     * @return bool
     */
    public function isExists()
    {
        if (!isset($this->_data['triggers'][$this->_schemaName])) {
            $this->describe();
        }

        if (isset($this->_data['triggers'][$this->_schemaName][$this->_objectName])) {
            return true;
        }

        return false;
    }

    public function describe()
    {
        $columns = array(
            'TRIGGER_NAME',
            'EVENT_MANIPULATION',
            'EVENT_OBJECT_CATALOG',
            'EVENT_OBJECT_SCHEMA',
            'EVENT_OBJECT_TABLE',
            'ACTION_ORDER',
            'ACTION_CONDITION',
            'ACTION_STATEMENT',
            'ACTION_ORIENTATION',
            'ACTION_TIMING',
            'ACTION_REFERENCE_OLD_TABLE',
            'ACTION_REFERENCE_NEW_TABLE',
            'ACTION_REFERENCE_OLD_ROW',
            'ACTION_REFERENCE_NEW_ROW',
            'CREATED',
        );
        $sql = 'SELECT ' . implode(', ', $columns)
            . ' FROM ' . $this->_adapter->quoteIdentifier(array('INFORMATION_SCHEMA','TRIGGERS'))
            . ' WHERE ';

        $schema = $this->getSchemaName();
        if ($schema) {
            $sql .= $this->_adapter->quoteIdentifier('EVENT_OBJECT_SCHEMA')
                . ' = ' . $this->_adapter->quote($schema);
        } else {
            $sql .= $this->_adapter->quoteIdentifier('EVENT_OBJECT_SCHEMA')
                . ' != ' . $this->_adapter->quote('INFORMATION_SCHEMA');
        }

        $results = $this->_adapter->query($sql);

        $data = array();
        foreach ($results as $row) {
            $row = array_change_key_case($row, CASE_LOWER);
            if (null !== $row['created']) {
                $row['created'] = new DateTime($row['created']);
            }
            $data[$row['trigger_name']] = $row;
        }
        $this->_data['triggers'][$schema] = $data;

        return $data;
    }
}

