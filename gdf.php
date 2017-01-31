<?php
error_reporting(E_ALL); 
ini_set("display_errors", 1);

$config=array();
$config["hostname"] = "localhost/vick/mage1/";
$config["login"] = "soap";
$config["password"] = "admin123";
$config["customer_as_guest"] = TRUE;
$proxy = new SoapClient('http://'.$config["hostname"].'/index.php/api/soap/?wsdl', array('trace'=>1));
$sessionId = $proxy->login($config["login"], $config["password"]);
$sessionId;

echo "\nGoDataFeed Version...\n";
echo $version = $proxy->call( $sessionId, 'godatafeed_services.version');


echo "\nCreated Quote...\n";
echo $shoppingCartIncrementId = $proxy->call( $sessionId, 'godatafeed_services_cart.cart_custom_create',1);


$customer = array(
       "firstname" => "Name",
       "lastname" => "BB",
       "email" => "steve@gmail.com",
        "website_id" => "1",
        "store_id" => "1",
       "mode" => "guest",
       //"group_id" => "5",
       // "entity_id" => "2",
   );
$resultCustomerSet = $proxy->call( $sessionId, 'godatafeed_services_cart_customer.cart_customer_custom_set', array( $shoppingCartIncrementId, $customer),1);
if ($resultCustomerSet === TRUE) {
    echo "\nOK Customer is set";    
} else {
    echo "\nOK Customer is NOT set";    
}

$arrAddresses = array(
    array(
        "mode" => "shipping",
        "firstname" => "vick",
        "lastname" => "keane",
        "company" => "gmi",
        "street" => "testStreet",
        "city" => "Treviso",
        "region" => "AL",
        "postcode" => "31056",
        "country_id" => "US",
        "telephone" => "0123456789",
        "fax" => "0123456789",
        "is_default_shipping" => 0,
        "is_default_billing" => 0
    ),
    array(
        "mode" => "billing",
        "firstname" => "steve",
        "lastname" => "thompson",
        "company" => "gmi",
        "street" => "testStreet",
        "city" => "Treviso",
        "region" => "AL",
        "postcode" => "31056",
        "country_id" => "US",
        "telephone" => "0123456789",
        "fax" => "0123456789",
        "is_default_shipping" => 0,
        "is_default_billing" => 0
    )
);
echo "\nSetting addresses...";
$resultCustomerAddresses = $proxy->call($sessionId, "godatafeed_services_cart_customer.cart_customer_custom_addresses", array($shoppingCartIncrementId, $arrAddresses));
if ($resultCustomerAddresses === TRUE) {
    echo "\nOK address is set\n"; 
} else {
    echo "\nKO address is not set\n"; 
}

// set payment method
$paymentMethodString= "free";

$paymentMethod = array(
    "method" => $paymentMethodString
);
$resultPaymentMethod = $proxy->call($sessionId, "godatafeed_services_cart_payment.payment_custom_method", array($shoppingCartIncrementId, $paymentMethod));
echo "\nPayment method $resultPaymentMethod.";


//$couponCode = "Coupon-1";
//$resultCartCouponRemove = $proxy->call($sessionId,"cart_coupon.add",array($shoppingCartIncrementId,$couponCode));

$items = array(
    array(
        "sku" => "6-8074-15-13-SFP",
        "qty" => "1",
        "price" => "120.00",
        "no_discount" => '0',
        "discount_amount" => '90.00',
        "tax_amount" => '7.43',
	),
	array(
        "sku" => "9-302-1-109-SFP",
        "qty" => "1",
        "price" => "78.00",
        "no_discount" => '1',
        "discount_amount" => '0',
	)
        
    );

echo "\nProduct Add .....:\n";
$shoppingCartItems = $proxy->call($sessionId, "godatafeed_services_cart_product.cart_product_custom_add", array($shoppingCartIncrementId, $items));
echo "\nPayment method $shoppingCartItems.";
//print_r($shoppingCartItems);
die;

echo "\nProduct Add .....:\n";
$Order = $proxy->call($sessionId, "godatafeed_services_cart.cart_custom_create_order", array($shoppingCartIncrementId, '1.00','Ground',$items));
echo "\nPayment method $Order.";
?>
