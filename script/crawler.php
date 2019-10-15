<?php
include('../app/Mage.php');
//Mage::App('default');
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
error_reporting(E_ALL | E_STRICT);
Mage::setIsDeveloperMode(true);
ini_set('display_errors', 1);
$mediaApi = Mage::getModel("enterprise_pagecache/crawler");
$mediaApi->crawl();