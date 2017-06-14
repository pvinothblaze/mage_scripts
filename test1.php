<?php
#ini_set('display_errors','1');
ini_set('max_execution_time', 3600); //360 seconds = 5 minutes
use \Magento\Framework\App\Bootstrap;
include('../app/bootstrap.php');


/* Objectmanager for magento functionality outside mage */

	$bootstrap = Bootstrap::create(BP, $_SERVER);
	$objectManager = $bootstrap->getObjectManager();
	$url = \Magento\Framework\App\ObjectManager::getInstance();
	$storeManager = $url->get('\Magento\Store\Model\StoreManagerInterface');
	$mediaurl= $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
	$state = $objectManager->get('\Magento\Framework\App\State');
	$state->setAreaCode('frontend');
	$existproduct = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
	$productCollection = $existproduct->create();
	$data=$productCollection->getData();
	foreach ($data as $value){
		print_r($value);
		die;
		
	}
