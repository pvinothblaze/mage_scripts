<?php
ini_set('display_errors', 1);
set_time_limit(0);
ini_set('memory_limit', '1024M');
include_once "../app/Mage.php";
include_once "../downloader/Maged/Controller.php";
Mage::init();
$app = Mage::app('default'); 
$productIds = array(2861,2860,2859,2858,2857,2856,2855,2854);
foreach ($productIds as $productId) {
    $product = Mage::getSingleton('catalog/product')
           ->unsetData()
           ->load($productId)
           ->setAttributeSetId(45)
           ->setIsMassupdate(true)
           ->save();
}
?>
