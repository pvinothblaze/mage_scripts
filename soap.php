<?php
error_reporting(E_ALL); 
ini_set("display_errors", 1);

define('BASEURL','http://agapelive.devgmi.com/');

$apiUser = 'admin'; 
$apiPass = 'admin@123';
$apiUrl = BASEURL.'index.php/rest/V1/integration/admin/token';
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
//$requestUrl = BASEURL.'index.php/rest/V1/products/24-MB01';//24-MB01 is the sku.
//$requestUrl = BASEURL.'index.php/rest/V1/products?searchCriteria[page_size]=10';// get total 10 products
//$requestUrl = BASEURL.'index.php/rest/V1/categories/24/products';// 24 category id
echo $requestUrl = BASEURL.'index.php/rest/V1/products/attributes?searchCriteria[page_size]=130';//get all products
//echo $requestUrl = BASEURL.'index.php/rest/V1/products/attributes?searchCriteria[types]=price';//get all products

$ch = curl_init();
try{
    $ch = curl_init($requestUrl); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
	
	echo '<pre>';
	
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
//$output = json_decode($data, true);

foreach($data as $cal){
	echo '<br/>';
	echo $cal->attribute_code;
	print_r($cal->options);
	
	
}
