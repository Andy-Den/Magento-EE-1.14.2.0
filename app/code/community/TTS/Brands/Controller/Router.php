<?php
 /** 
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @Author     Ocean <ocean1890@live.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class TTS_Brands_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    public function initControllerRouters($observer)
    {
        /* @var $front Mage_Core_Controller_Varien_Front */
        $front = $observer->getEvent()->getFront();

        $front->addRouter('brands', $this);
    }

    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }

        $pathInfo = trim($request->getPathInfo(), '/');
        $urlPart = explode('/', $pathInfo);
        if (count($urlPart) != 2 || $urlPart[0] != Mage::getStoreConfig('brands/settings/route')) {
            return false;
        }

        $brandId = Mage::getModel('brands/brands')->checkUrlKey($urlPart[1]);
        if (!$brandId) {
            return false;
        }

        $request->setModuleName('brands')
            ->setControllerName('view')
            ->setActionName('index')
            ->setParam('brands', $brandId);
        $request->setAlias(
            Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
            $pathInfo
        );

        return true;
    }
}