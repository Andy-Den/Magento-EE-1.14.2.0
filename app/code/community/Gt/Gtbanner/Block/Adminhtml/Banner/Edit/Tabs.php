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
 * Admin banner left menu
 *
 * @category   Gt
 * @package    Gt_Gtbanner
 * @author     Gentotech <http://www.gentotech.com/>
 */
class Gt_Gtbanner_Block_Adminhtml_Banner_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('gtbanner_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('gtbanner')->__('Banner Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('general_section', array(
            'label'     => Mage::helper('gtbanner')->__('General Information'),
            'title'     => Mage::helper('gtbanner')->__('General Information'),
            'content'   => $this->getLayout()->createBlock('gtbanner/adminhtml_banner_edit_tab_form')->toHtml(),
        ))->addTab('image_section', array(
            'label'     => Mage::helper('gtbanner')->__('Banner Images'),
            'title'     => Mage::helper('gtbanner')->__('Banner Images'),
            'content'   => $this->getLayout()->createBlock('gtbanner/adminhtml_banner_edit_tab_image')->toHtml(),
        ))->addTab('page_section', array(
            'label'     => Mage::helper('gtbanner')->__('Display on Pages'),
            'title'     => Mage::helper('gtbanner')->__('Display on Pages'),
            'content'   => $this->getLayout()->createBlock('gtbanner/adminhtml_banner_edit_tab_page')->toHtml(),
        ))->addTab('category_section', array(
            'label'     => Mage::helper('gtbanner')->__('Display on Categories'),
            'title'     => Mage::helper('gtbanner')->__('Display on Categories'),
            'content'   => $this->getLayout()->createBlock('gtbanner/adminhtml_banner_edit_tab_category')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}