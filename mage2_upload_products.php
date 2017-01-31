<?php
//ini_set('display_errors', 1);
//ini_set('memory_limit', '4086M');
set_time_limit(0);
ini_set('memory_limit', '1024M');
//ini_set('max_execution_time', 36000);
use \Magento\Framework\App\Bootstrap;
use \Magento\Framework\Setup\ModuleContextInterface;
include('../app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('\Magento\Framework\App\State');
$state->setAreaCode('frontend');
$eavSetupFactory=$objectManager->get('\Magento\Eav\Setup\EavSetupFactory'); 
$attributeRepo =  $objectManager->get('\Magento\Catalog\Api\ProductAttributeRepositoryInterface');

//************* Disabled all products by product type ('event-ticket') and integrate with api ******** //

$productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
$collection = $productCollection->create()
        ->addAttributeToFilter('type_id', array('in' => array('event-ticket')))
        ->addAttributeToFilter('integrate_with_student_manager','66')
        ->addAttributeToSelect('*')
        ->addAttributeToFilter('status','1')
        ->load();

//$file=  'diable'.date("Ymdhis");
//$myfile = fopen($file.".txt", "w") or die("Unable to open file!");
foreach ($collection as $product)
{

  $proid=$product->getId();
  $eventManager = $objectManager->get('\Magenest\Ticket\Model\EventFactory');
  $event = $eventManager->create();
  $event->loadByProductId($proid);
  if($proid==$event->getProductId())
  {
    //$productRepository=  $objectManager->get('\Magento\Catalog\Api\ProductRepositoryInterface');
    //$product = $objectManager->create('\Magento\Catalog\Model\Product')->load($proid);
    $product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED);
    $product->setIntegrateWithStudentManager('67'); // Integrate With SM API Attribute 66='Yes' , 67='No'
    $product->save();
    echo $writedata= 'Disabled Product Id '.$proid."\n";
    //fwrite($myfile, $writedata);
  }

} 


                      $existproduct = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
                      $productCollection = $existproduct->create();
                      
                  
                      if($productCollection->addAttributeToFilter('class_id', $classid)->getData())
                      {
    //************* Condition if Class-id is exist in magento compare with SM ******** //
						$productCollection->addAttributeToFilter('iwsm','66');
                        $existproductdata = $productCollection->addAttributeToFilter('class_id', $classid)->getData();
                        $existproductdataid=$existproductdata[0]['entity_id'];
                        $_product = $objectManager->create('\Magento\Catalog\Model\Product');
                        $_product->load($existproductdataid);
                        
                       }
                       else
                       {
    
        //************* Create New Product if class-id is not already exists in Magento **********//
                          $_product = $objectManager->create('\Magento\Catalog\Model\Product');
                          $_product->setWebsiteIds(array(1));
                          $_product->setAttributeSetId(4);
                          $_product->setTypeId('event-ticket');
                          $_product->setCreatedAt(strtotime('now')); //product creation time
                          $_product->setName($classname); // Product Name
                          $_product->setSku(formatsku($classname));
                          $_product->setIntegrateWithStudentManager('66'); // Integrate With SM API Attribute 66='Yes' , 67='No'
                          $_product->setClassId($classid);
                          //$_product->setWeight(4.0000);
                          $rand=rand(0,9999);
                          $url=$classname.'-'.$rand;
                          $_product->setUrlKey($url);
                          $_product->setStatus(1);
                         
                            //print_r($catids);
                         
                          //$category_id= array('4,5');
                          $_product->setCategoryIds($catids); // Product Category
                          $_product->setTaxClassId(0); //tax class (0 - none, 1 - default, 2 - taxable, 4 - shipping)
                          $_product->setVisibility(4); //catalog and search visibility
                          //$_product->setManufacturer(28); //manufacturer id
                          $_product->setSubjects($subjectid);
                          $_product->setEventLocation($locationid);
                          $_product->setEventDays($eventids);
                          $_product->setEventDate($eventdates);
                          $_product->setEventDaytime($daytimeevent);
                          $_product->setSessionCount($countdate);
                          $_product->setTestDate(eventdate($testdate));
                          $_product->setPrice($price);//price in form 11.2
                          $_product->setMetaTitle($classname);
                          $_product->setMetaKeyword($classname);
                          $_product->setMetaDescription($description);
                          $_product->setDescription($description);
                          $_product->setShortDescription($description);
                          $_product->setStockData(array(
                          'use_config_manage_stock' => 0, //'Use config settings' checkbox
                          'manage_stock' => 1, //manage stock
                          'min_sale_qty' => 1, //Minimum Qty Allowed in Shopping Cart
                          'max_sale_qty' => 2, //Maximum Qty Allowed in Shopping Cart
                          'is_in_stock' => 1, //Stock Availability
                          'qty' => $qty //qty
                          )
                          );
							 $_product->setQuantityAndStockStatus(['qty' => $qty, 'is_in_stock' => 1]);
                          $_product->save();
                          $simple_product_id = $_product->getId();
                           $newinsert= "simple product id: ".$simple_product_id;
                          //Mage::log("simple product id: ".$simple_product_id);
                          echo $txt=$classid."\t".$newinsert."\n";
                          fwrite($newentryfile, $txt);
                            //fwrite($newentryfile, $txt);
                         
                          $product = $_product;
                          $_product='';
                          $_products = $objectManager->create('\Magenest\Ticket\Observer\Backend\EventTicketObserver');
                          $_products->importEventExec($product);
                          $eventManager = $objectManager->get('\Magenest\Ticket\Model\EventFactory');
                          $event = $eventManager->create();
                          $event->loadByProductId($simple_product_id);
                          //echo $event->setStartTime();
                          //echo $event->setStartTime();
                          $locsdate=$sdate.$sstime;
                          $locedate=$tdate.$setime;
                          $lcstarttime = date("M j, Y g:i a",strtotime($locsdate)); 
                          $lcendtime = date("M j, Y g:i a",strtotime($locedate)); 
                          $event->setStartTime($lcstarttime);
                          $event->setEndTime($lcendtime);
                          $event->setLocation($locationname);
                          $event->save();
                          //print_r($event->getData());
                        }
                      }
                      
      
      // Reindex Script
      try{
			$indexerFactory=$objectManager->create('Magento\Indexer\Model\IndexerFactory');
			$indexerCollectionFactory=$objectManager->create('Magento\Indexer\Model\Indexer\CollectionFactory');
			$indexerCollection = $indexerCollectionFactory->create();
			$ids = $indexerCollection->getAllIds();
			foreach ($ids as $id) 
			{
				echo 'Re-indexing';
				echo $id;
				echo '<br/>';
				$idx = $indexerFactory->create()->load($id);
				$idx->reindexAll($id); // this reindexes all
			}
		}catch(Exception $e){
			echo $msg = 'Error : '.$e->getMessage();die();
		}
		// Ends Here
?>
