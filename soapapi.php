<?php
ini_set('display_errors','1');
ini_set('max_execution_time', 60000);

$request = new SoapClient("http://localhost/vick/localmage2/index.php/soap/?wsdl&services=integrationAdminTokenServiceV1", array("soap_version" => SOAP_1_2));
$token = $request->integrationAdminTokenServiceV1CreateAdminAccessToken(array("username"=>"api", "password"=>"admin123"));
echo '<pre>';
$opts = array(
            'http'=>array(
                'header' => 'Authorization: Bearer '.$token->result
            )
        );
$context = stream_context_create($opts);

//$url = 'http://localhost/vick/localmage2/soap/default?wsdl&services=customerCustomerRepositoryV1';
//$url = 'http://localhost/vick/localmage2/soap/default?wsdl&services=customerAccountManagementV1';
$url = 'http://localhost/vick/localmage2/soap/default?wsdl&services=quoteCartRepositoryV1';

//$url = 'http://localhost/vick/localmage2/soap/default?wsdl&services=catalogProductRepositoryV1';
$client = new SoapClient($url,  array('soap_version'=>SOAP_1_2, 'stream_context'=>$context));
print_r($client->__getFunctions());
//Zend_Debug::dump($client->__getFunctions());
$soapResponse = $client->quoteCartRepositoryV1Save();
//$wes=$client->quoteCartRepositoryV1Get();
print_r($soapResponse);
die;
//$soapResponse = $client->customerCustomerRepositoryV1GetList(array('searchCriteria'=>[
$soapResponse = $client->catalogProductRepositoryV1GetList(array('searchCriteria'=>[
  'filterGroups' => [
    0 => [
      'filters' => [
         0 => [
           'field' => 'sku',
           'value' => 'sd',
           
           /*
            * 'value' => '%25Leggings%25',
			* 'condition_type' => 'like'
			* 'condition_type' => 'eq'
            * */
         ]
      ]
    ]
  ] ]));        
print_r($soapResponse);
die;
$ch = curl_init("http://localhost/vick/localmage2/V1/carts");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer ".$token->result));
 
$result = curl_exec($ch);
 
print_r(json_decode($result));
