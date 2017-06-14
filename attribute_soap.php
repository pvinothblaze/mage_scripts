<?php
error_reporting(E_ALL); 
ini_set("display_errors", 1);
ini_set("memory_limit",-1);
ini_set("max_execution_time", 3600000); //360 seconds = 5 minutes
define('CURLURL','http://agapelive.devgmi.com/');

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

$apiUser = 'admin'; 
$apiPass = 'admin@123';
$apiUrl = CURLURL.'index.php/rest/V1/integration/admin/token';
/*
    Magento 2 REST API Authentication
*/
$data = array("username" => $apiUser, "password" => $apiPass);                                                                    
$data_string = json_encode($data);                       
try{
    $ch = curl_init($apiUrl); 
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string))                                                                       
    );       
    $token = curl_exec($ch);
    $token = json_decode($token);
    if(isset($token->message)){
        echo 'token'.$token->message;
        echo '<br/>';
    }else{
        echo 'key'.$key = $token;
        echo '<br/>';
    }
}catch(Exception $e){
    echo 'Error: '.$e->getMessage();
}


/*
    Get Product By SKU REST API Magento 2
    Use above key into header
*/
$headers = array("Authorization: Bearer $key"); 
//$requestUrl = CURLURL.'index.php/rest/V1/products/24-MB01';//24-MB01 is the sku.
//$requestUrl = CURLURL.'index.php/rest/V1/products?searchCriteria[page_size]=10';// get total 10 products
//$requestUrl = CURLURL.'index.php/rest/V1/categories/24/products';// 24 category id
echo $requestUrl = CURLURL.'index.php/rest/V1/products/attributes?searchCriteria[page_size]=130';//get all products
//echo $requestUrl = CURLURL.'index.php/rest/V1/products/attributes?searchCriteria[types]=price';//get all products

$ch = curl_init();
try{
    $ch = curl_init($requestUrl); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $result = curl_exec($ch);
    $result = json_decode($result);

    if(isset($result->message)){
        echo $result->message;
    }else{
        $data=$result->items;
    }
}catch(Exception $e){
    echo 'Error: '.$e->getMessage();
}

foreach($data as $cal)
{
	
	//echo '<br/>';
	$attribute_code=$cal->attribute_code;
	
	//echo '<br/>';
	//print_r($cal->attribute_code);
	//die();
if($attribute_code=='computer_manufacturers'){
	$existattribute = $objectManager->create(\Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection::class);
	$existattribute->addFieldToFilter(\Magento\Eav\Model\Entity\Attribute\Set::KEY_ENTITY_TYPE_ID, 4);
	$existattribute->addFieldToFilter('attribute_code',$attribute_code);
	//print_r($existattribute->getData());
	$attr_code = $existattribute->load()->getFirstItem()->getAttributeCode();
	if(empty($attr_code))
	{
		echo $attr_code=$cal->attribute_code;
		echo $attr_default=$cal->default_frontend_label;
		echo $attr_type=$cal->backend_type;
		echo $attr_input=$cal->frontend_input;
		echo $attr_scope=$cal->scope;
		echo $attr_visible=$cal->is_visible;
		echo $attr_require=$cal->is_required;
		echo $attr_userdefined=$cal->is_user_defined;
		echo $attr_search=$cal->is_searchable;
		echo $attr_filter=$cal->is_filterable;
		echo $attr_compare=$cal->is_comparable;
		echo $attr_visiblefront=$cal->is_visible_on_front;
		echo $attr_listing=$cal->used_in_product_listing;
		echo $attr_unique=$cal->is_unique;
		echo $attr_promo=$cal->is_used_for_promo_rules;
		if($attr_scope=='global'){$attr_scope='1';}
		elseif($attr_scope=='store'){$attr_scope='0';}
		elseif($attr_scope=='website'){$attr_scope='2';}
		else{$attr_scope='';}
		echo '<pre>';
		$attributeoptions=$cal->options;
		$count=sizeof($attributeoptions);
		print_r($attributeoptions);
		//echo $attributeoptions->label;
		$options=array();
		for($i=0;$i<$count;$i++)
		{
			if(!empty($attributeoptions[$i]->label)){
			$options[]= $attributeoptions[$i]->label;
			$optionsdata[]=$options;
			$options='';
			}
		}
		print_r($optionsdata);
		//Add the option values to the data
		
		//die;
		
		$eavSetupFactory = $objectManager->get('\Magento\Eav\Setup\EavSetupFactory');
		$eavSetup = $eavSetupFactory->create();
		$eavSetup->addAttribute(
		\Magento\Catalog\Model\Product::ENTITY,
		$attr_code,
		[
			'group' => '',
			'type' => $attr_type,
			'backend' => '',
			'frontend' => '',
			'sort_order' => 50,
			'label' => $attr_default,
			'input' => $attr_input,
			'class' => '',
			'scope' => '1',
			'visible' => $attr_visible,
			'required' => $attr_require,
			'user_defined' => $attr_userdefined,
			'default' => $attr_default,
			'searchable' => $attr_search,
			'filterable' => $attr_filter,
			'comparable' => $attr_compare,
			'visible_on_front' => $attr_visiblefront,
			'used_in_product_listing' => $attr_listing,
			'unique' => $attr_unique,
			'used_for_promo_rules' => $attr_promo,
			 'option' => [ // temporary
                'values' => [$optionsdata
                    ],
            ],
		]
		);
		exit;
	}
}
	
}
