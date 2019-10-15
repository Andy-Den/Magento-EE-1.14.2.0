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
 * @category    Enterprise
 * @package     Enterprise_Staging
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */


/**
 * Staging Resource Adapter Interface
 *
 * @category    Enterprise
 * @package     Enterprise_Staging
 * @author      Magento Core Team <core@magentocommerce.com>
 */
interface Enterprise_Staging_Model_Resource_Adapter_Interface
{
    /**
     * Enter description here ...
     *
     * @param Enterprise_Staging_Model_Staging $staging
     * @param unknown_type $event
     */
    public function checkfrontendRun(Enterprise_Staging_Model_Staging $staging, $event = null)
;

    /**
     * Enter description here ...
     *
     * @param Enterprise_Staging_Model_Staging $staging
     * @param unknown_type $event
     */
    public function createRun(Enterprise_Staging_Model_Staging $staging, $event = null)
;

    /**
     * Enter description here ...
     *
     * @param Enterprise_Staging_Model_Staging $staging
     * @param unknown_type $event
     */
    public function updateRun(Enterprise_Staging_Model_Staging $staging, $event = null)
;

    /**
     * Enter description here ...
     *
     * @param Enterprise_Staging_Model_Staging $staging
     * @param unknown_type $event
     */
    public function backupRun(Enterprise_Staging_Model_Staging $staging, $event = null)
;

    /**
     * Enter description here ...
     *
     * @param Enterprise_Staging_Model_Staging $staging
     * @param unknown_type $event
     */
    public function mergeRun(Enterprise_Staging_Model_Staging $staging, $event = null)
;

    /**
     * Enter description here ...
     *
     * @param Enterprise_Staging_Model_Staging $staging
     * @param unknown_type $event
     */
    public function rollbackRun(Enterprise_Staging_Model_Staging $staging, $event = null)
;
}
