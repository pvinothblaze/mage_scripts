<?php
ini_set('display_errors','1');
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

/// Get Website ID
$websiteId = $storeManager->getWebsite()->getWebsiteId();
//echo 'websiteId: '.$websiteId." ";

/// Get Store ID
$store = $storeManager->getStore();
$storeId = $store->getStoreId();
//echo 'storeId: '.$storeId." ";

/// Get Root Category ID
$rootNodeId = $store->getRootCategoryId();
//echo 'rootNodeId: '.$rootNodeId." ";
/// Get Root Category
$rootCat = $objectManager->get('Magento\Catalog\Model\Category');
$cat_info = $rootCat->load($rootNodeId);

/* True Condition for file exists */

echo '<pre>';
if (file_exists('hood-cat.xml')) 
{
		$xml = simplexml_load_file('hood-cat.xml');
		$json  = json_encode($xml);
		$configData = json_decode($json, true);

/* Get Products Data From xml */
	
		$data=$configData['Categories']['Category'];
		//print_r($data);
		foreach($data as $values)
		{
			$catname= $values['Name'];
			$description= $values['Description'];
			$metatitle= $values['MetaTitle'];
			$metakeyword= $values['MetaKeywords'];
			$metadescription= $values['MetaDescription'];
			$name=ucfirst($catname);
			$url=strtolower($cat);
			$cleanurl = trim(preg_replace('/ +/', '', preg_replace('/[^A-Za-z0-9 ]/', '', urldecode(html_entity_decode(strip_tags($url))))));
			$categoryFactory=$objectManager->get('\Magento\Catalog\Model\CategoryFactory');
			/// Add a new sub category under root category
			$categoryTmp = $categoryFactory->create();
			$existscat = $categoryFactory->create()->getCollection()->addFieldToFilter('name',$name);
			$categoryId = $existscat->getFirstItem()->getId();
			//print_r($existscat);
			if ($categoryId) {
				 
				 echo $log='Category " '. $name . ' " Already Exists';
				 $logger->info($log);
				 //$logger->info(print_r($yourArray, true));
			}
			else
			{
				$categoryTmp->setName($name);
				$categoryTmp->setIsActive(true);
				$categoryTmp->setUrlKey($cleanurl);
				$categoryTmp->setDescription($description);
				$categoryTmp->setParentId($rootCat->getId());
				$categoryTmp->setMetaTitle($metatitle);
				$categoryTmp->setMetaKeyword($metakeyword);
				$categoryTmp->setMetaDescription($metadescription);
				//$mediaAttribute = array ('image', 'small_image', 'thumbnail');
				//$categoryTmp->setImage('/m2.png', $mediaAttribute, true, false);// Path pub/meida/catalog/category/m2.png
				$categoryTmp->setStoreId($storeId);
				$categoryTmp->setPath($rootCat->getPath());
				$categoryTmp->save();
			}
				
		}
} else { /* Fail Condition for file exists */
	
    exit('Failed to open test.xml.');
}
?>
