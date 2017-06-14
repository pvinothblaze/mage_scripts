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

/*
 * $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/attribute.log');
$logger = new \Zend\Log\Logger();
$logger->addWriter($writer);
* */

$attributeInfo = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Eav\Attribute')->getCollection();

foreach($attributeInfo as $attributes)
{
   echo '<pre>';
     //print_r($attributes->getData());
	//echo $attributeId = $attributes->getAttributeId();
   // echo '<br/>';
  // You can get all fields of attribute here
}
$attributeRepo =  $objectManager->get('\Magento\Catalog\Api\Data\ProductAttributeInterface')->getCollection();
foreach($attributeRepo as $items) 
{
   echo '<pre>';
   if($items->getEntityTypeId()=='4')
   {
		//print_r($items->getData());
	}
   
}


$coll = $objectManager->create(\Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection::class);
// add filter by entity type to get product attributes only
// '4' is the default type ID for 'catalog_product' entity - see 'eav_entity_type' table)
// or skip the next line to get all attributes for all types of entities
$coll->addFieldToFilter(\Magento\Eav\Model\Entity\Attribute\Set::KEY_ENTITY_TYPE_ID, 4);
$coll->addFieldToFilter('attribute_code','small_image');
//$attrAll = $coll->load()->getItems();
echo $attrAll = $coll->load()->getFirstItem()->getAttributeCode();
die;
foreach($attrAll as $items) 
{
    echo '<pre>';
	print_r($items->getData());
}
die();
$customer_attributes = $objectManager->get('Magento\Catalog\Model\Product')->getAttributes();

$attributesArrays = array();
echo '<pre>';
foreach($customer_attributes as $cal=>$val){
	//print_r($val->getData());
	die();
	$attribute_code= $val['attribute_code'];
	$backend_type= $val['backend_type'];
	$frontend_label= $val['frontend_label'];
	$is_required= $val['is_required'];
	$is_user_defined= $val['is_user_defined'];
	$attributesArrays[] = array(
            'attribute_code' => $attribute_code,
            'backend_type' => $backend_type,
            'frontend_label' => $backend_type,
            'backend_type' => $frontend_label,
            'is_required' => $is_required,
            'is_user_defined' => $is_user_defined,
       );
	
}
print_r($attributesArrays);
?>
