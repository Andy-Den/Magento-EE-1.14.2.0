<?php
include('app/Mage.php');
//Mage::App('default');
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
error_reporting(E_ALL | E_STRICT);
Mage::setIsDeveloperMode(true);
ini_set('display_errors', 1);
ob_implicit_flush (1);

$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$_products = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('type_id','configurable');
$i =0;
$total = count($_products);
$count = 0;
foreach($_products as $_prod)
{
    $_product = Mage::getModel('catalog/product')->load($_prod->getId());
    $_images = $_product->getMediaGalleryImages();
    //var_dump($_images->getItems());die;
    $_arrSwitch = array();
    if($_images){
        foreach($_images as $_image){
            $_arrSwitch[$_image->getData('value_id')]='';
        }
    }
    $i ++;
    //var_dump($_arrSwitch, serialize($_arrSwitch));
    $_product->setCjmImageswitcher(serialize($_arrSwitch));

    $_product->setCjmMoreviews(serialize($_arrSwitch));
    $_product->save();
    //die;
    echo "<br/> processing product $i of $total ";
    // Loop through product images
    //$_product->save();

}
echo "<br/> Finished updated $count products switchcode";
