<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Gt
 * @package     Gt_Gtbanner
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Page config model
 *
 * @category   Gt
 * @package    Gt_Gtbanner
 * @author     Gentotech <http://www.gentotech.com/>
 */
class Gt_Gtbanner_Model_Config_Source_Page
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $_collection = Mage::getSingleton('cms/page')->getCollection()
                ->addFieldToFilter('is_active', 1);

        $_result = array();
        foreach ($_collection as $item) {
            $data = array(
                'value' => $item->getData('page_id'),
                'label' => $item->getData('title'));
            $_result[] = $data;
        }
        return $_result;
    }
}
