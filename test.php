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
/* Log Files */

$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/hmpro.log');
$logger = new \Zend\Log\Logger();
$logger->addWriter($writer);



if (file_exists('hood-product.xml')) 
{
		$xml = simplexml_load_file('hood-product.xml');
		$json  = json_encode($xml);
		$configData = json_decode($json, true);

/* Get Products Data From xml */
	
		$data=$configData['Products']['Product'];
		
		$_product = $objectManager->create('\Magento\Catalog\Model\Product');
		
		foreach($data as $values)
		{
			$sku= $values['ID'];
			$iscat=$values['CategoryLinks'];
			$categoryname= $values['CategoryLinks']['CategoryLink']['CategoryName'];
			if(!empty($iscat)){
				$categoryFactory=$objectManager->get('\Magento\Catalog\Model\CategoryFactory');
				/// Add a new sub category under root category
				$categoryTmp = $categoryFactory->create();
				$existscat = $categoryFactory->create()->getCollection()->addFieldToFilter('name',$categoryname);
				$categoryId = $existscat->getFirstItem()->getId();
			}
			$existproduct = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
			$productCollection = $existproduct->create();
			$productCollection->addAttributeToFilter('sku', $sku);
			$data=$productCollection->getData();
			echo $existproductdataid=$data[0]['entity_id'];
			echo '<br/>';
			$e_product = $objectManager->create('\Magento\Catalog\Model\Product');
			$e_product->load($existproductdataid);
			if(!empty($iscat))
			{
				$e_product->setCategoryIds($categoryId); 
			}
			$e_product->save();
		}
	}

?>
