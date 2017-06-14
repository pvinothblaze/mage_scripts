<?php
ini_set('display_errors','1');
ini_set("memory_limit",-1);
ini_set("max_execution_time", 3600000); //360 seconds = 5 minutes
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


function getimg($url) {         
    $imagename=explode('images\products',$url);
	$img=$imagename[1];
	$name= stripslashes($img);
	$filepath = '../pub/media/import/'.$name; //path for temp storage folder: ./media/import/
	$save=file_put_contents($filepath, file_get_contents(trim($url))); //store the image from external url to the temp storage folder
	$finalpath='import/'.$name; 
    if($save){
		return $finalpath;
	}
} 

/* True Condition for file exists */

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
			
			$proname= $values['ProductName'];
			$sku= $values['ID'];
			$price= $values['ListPrice'];
			$specialprice= $values['SitePrice'];
			$metakeyword= $values['MetaKeywords'];
			$metadescription= $values['MetaDescription'];
			$metatitle= $values['MetaTitle'];
			$created= $values['CreationDate'];
			$description= $values['LongDescription'];
			$shortdescription= $values['ShortDescription'];
			$miniqty= $values['MinimumQty'];
			$qty= $values['InventoryAvailableQty'];
			$managestock= $values['InventoryStatus'];
			$smimage= $values['ImageFileSmall'];
			$medimage= $values['ImageFileMedium'];
			$iscat=$values['CategoryLinks'];
			$categoryname= $values['CategoryLinks']['CategoryLink']['CategoryName'];
			if(!empty($iscat)){
				$categoryFactory=$objectManager->get('\Magento\Catalog\Model\CategoryFactory');
// Add a new sub category under root category
				$categoryTmp = $categoryFactory->create();
				$existscat = $categoryFactory->create()->getCollection()->addFieldToFilter('name',$categoryname);
				$categoryId = $existscat->getFirstItem()->getId();
			}
			 $existproduct = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
             $productCollection = $existproduct->create();
                      
			 if($productCollection->addAttributeToFilter('sku', $sku)->getData())
			 {
				

					$data=$productCollection->getData();
					$existproductdataid=$data[0]['entity_id'];
					$e_product = $objectManager->create('\Magento\Catalog\Model\Product');
                    $e_product->load($existproductdataid);
					$smimagePath = "http://www.hoodmart.com/".$smimage; // path of the image
					$medimagePath = "http://www.hoodmart.com/".$medimage; // path of the image
					$simg=getimg($smimagePath);
					$mimg=getimg($medimagePath);
					
/*	Add Images To The Product*/
					if($simg)
					{
						$e_product->addImageToMediaGallery($simg, array('small_image', 'thumbnail'), false, false);
						echo $log='Product " '. $sku . ' " Small/Thumbnail Uploaded';
						echo '<br>';
						$logger->info($log);
					}
					if($mimg)
					{
						$e_product->addImageToMediaGallery($mimg, array('image'), false, false);
						echo $log='Product " '. $sku . ' " Base Image Uploaded';
						echo '<br>';
						$logger->info($log);
					}
					if(!empty($iscat))
					{
						$e_product->setCategoryIds($categoryId); 
					}
					$e_product->save();
					$log='Product " '. $sku . ' " Already Exists';
					$logger->info($log);
					//$logger->info(print_r($yourArray, true));
				
			}
			else
			{
				try
				{
					$_product = $objectManager->create('\Magento\Catalog\Model\Product');
					$_product->setWebsiteIds(array(1));
					$_product->setAttributeSetId(4);
					$_product->setTypeId('simple');
					$_product->setCreatedAt($created); //product creation time
					$_product->setName($proname); // Product Name
					$_product->setSku($sku);
					//$_product->setWeight(4.0000);
					$_product->setStatus(1);
					if(!empty($iscat))
					{
						$_product->setCategoryIds($categoryId); 
					}
					//$_product->setCategoryIds($category_id); // Product Category
					$_product->setTaxClassId(0); //tax class (0 - none, 1 - default, 2 - taxable, 4 - shipping)
					$_product->setVisibility(4); //catalog and search visibility
					//$_product->setManufacturer(28); //manufacturer id
					//$_product->setColor(24);
					//print_r($_product);die;
					//$_product->setNewsFromDate('06/26/2014'); //product set as new from
					//$_product->setNewsToDate('06/30/2014'); //product set as new to
					//$_product->setCountryOfManufacture('AF'); //country of manufacture (2-letter country code)
					$_product->setPrice($price) ;//price in form 11.22
					//$_product->setCost(22.33); //price in form 11.22
					$_product->setSpecialPrice($specialprice); //special price in form 11.22
					//$_product->setSpecialFromDate('06/1/2016'); //special price from (MM-DD-YYYY)
					//$_product->setSpecialToDate('06/30/2014'); //special price to (MM-DD-YYYY)
					//$_product->setMsrpEnabled(1); //enable MAP
					//$_product->setMsrpDisplayActualPriceType(1); //display actual price (1 - on gesture, 2 - in cart, 3 - before order confirmation, 4 - use config)
					//$_product->setMsrp(99.99); //Manufacturer's Suggested Retail Price
					$_product->setMetaTitle($metatitle);
					$_product->setMetaKeyword($metakeyword);
					$_product->setMetaDescription($metadescription);
					$_product->setDescription($description);
					$_product->setShortDescription($shortdescription);
					$_product->setStockData(
						array(
							'use_config_manage_stock' => 0, //'Use config settings' checkbox
							'manage_stock' => $managestock, //manage stock
							'min_sale_qty' => $miniqty, //Minimum Qty Allowed in Shopping Cart
							//'max_sale_qty' => 2, //Maximum Qty Allowed in Shopping Cart
							'is_in_stock' => 1, //Stock Availability
							'qty' => $qty //qty
							)
					);
					$smimagePath = "http://www.hoodmart.com/".$smimage; // path of the image
					$medimagePath = "http://www.hoodmart.com/".$medimage; // path of the image
					$simg=getimg($smimagePath);
					$mimg=getimg($medimagePath);
					
					/*Add Images To The Product*/
					if($simg)
					{
						$_product->addImageToMediaGallery($simg, array('small_image', 'thumbnail'), false, false);
						echo $log='Product " '. $sku . ' " Small/thumb Uploaded';
						$logger->info($log);
					}
					if($mimg)
					{
						$_product->addImageToMediaGallery($mimg, array('image'), false, false);
						echo $log='Product " '. $sku . ' " Image Uploaded';
						$logger->info($log);
					}
					$_product->save();
					$simple_product_id = $_product->getId();
					echo $log="simple product created id: ".$simple_product_id."\n";
					$logger->info($log);
					//$logger->info(print_r($yourArray, true));
				}
				catch(Exception $e)
				{
					//echo $e->getMessage();
				}
			}
			
		}
    
} else { /* Fail Condition for file exists */
	
    exit('Failed to open test.xml.');
}



?>
