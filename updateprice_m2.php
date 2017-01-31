<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
use \Magento\Framework\App\Bootstrap;
include('../app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('\Magento\Framework\App\State');
$state->setAreaCode('frontend');
$file_handle = fopen("productdetails_agape.csv", "r");
while (!feof($file_handle) ) 
{
	$data = fgetcsv($file_handle, 1024);
	echo '<pre>';
	print_r($data); die;
	/*$_product = $objectManager->create('\Magento\Catalog\Model\Product');
	$_product->load($existproductdataid);
	$_product->setPrice($price);//price in form 11.2
	$_product->save();*/
}
?>
