<?php
require_once('app/Mage.php'); //Path to Magento
umask(0);
Mage::app();

$collectionConfigurable = Mage::getResourceModel('catalog/product_collection')->addAttributeToFilter('type_id', array('eq' => 'configurable'));

foreach ($collectionConfigurable as $_configurableproduct) {
    /**
     * Load product by product id
     */
    $product = Mage::getModel('catalog/product')->load($_configurableproduct->getId());

    /**
     * only process product if it is available (saleable)
     */
    if ($product->isSaleable()) {
        /**
         * Get children products (all associated children products data)
         */
        $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null,$product);

        $stockItem = $product->getStockItem();
        if($stockItem->getIsInStock()){
            /**
             * All configurable products, which are in stock
             */

            $instock_childrenisinstock = false;

            foreach ($childProducts as $childProduct) {
                $qty = Mage::getModel('cataloginventory/stock_item')->loadByProduct($childProduct)->getQty();
                if ($qty > 0) {
                    $instock_childrenisinstock = true;
                }
            }
            if (!$instock_childrenisinstock) {
                echo "<p>".$product->getName()." (".$product->getId().") is <span style='font-weight: bold;color: red'>IN STOCK<span></p>";
            }

        } else {
            /**
             * All configurable products, which are out of stock
             */

            $outofstock_childrenisinstock = false;

            foreach ($childProducts as $childProduct) {
                $qty = Mage::getModel('cataloginventory/stock_item')->loadByProduct($childProduct)->getQty();
                if ($qty > 0) {
                    $outofstock_childrenisinstock = true;
                }
            }
            if ($outofstock_childrenisinstock) {
                echo "<p>".$product->getName()." (".$product->getId().") is <span style='font-weight: bold;color: red'>OUT OF STOCK<span></p>";
            }
        }
    }
}
?>