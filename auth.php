<?php
/**
* Example of simple product POST using Admin account via Magento REST API. OAuth authorization is used
*/
ini_set('display_errors', 1);
$callbackUrl = "http://localhost/vick/mage19/oauth_admin.php";
$temporaryCredentialsRequestUrl = "http://localhost/vick/mage19/oauth/initiate?oauth_callback=" . urlencode($callbackUrl);
$adminAuthorizationUrl = 'http://localhost/vick/mage19/admin/oauth_authorize';
$accessTokenRequestUrl = 'http://localhost/vick/mage19/oauth/token';
$apiUrl = 'http://localhost/vick/mage19/api/rest';
$consumerKey = '3fd982d4e7678d39fbde0dbe4c276ed8';
$consumerSecret = '9fbf749ddf2f4fb61d912d40b216cb54';

session_start();
if (!isset($_GET['oauth_token']) && isset($_SESSION['state']) && $_SESSION['state'] == 1) {
    $_SESSION['state'] = 0;
}
try {
    $authType = ($_SESSION['state'] == 2) ? OAUTH_AUTH_TYPE_AUTHORIZATION : OAUTH_AUTH_TYPE_URI;
    $oauthClient = new OAuth($consumerKey, $consumerSecret, OAUTH_SIG_METHOD_HMACSHA1, $authType);
    $oauthClient->enableDebug();

    if (!isset($_GET['oauth_token']) && !$_SESSION['state']) {
        $requestToken = $oauthClient->getRequestToken($temporaryCredentialsRequestUrl);
        $_SESSION['secret'] = $requestToken['oauth_token_secret'];
        $_SESSION['state'] = 1;
        header('Location: ' . $adminAuthorizationUrl . '?oauth_token=' . $requestToken['oauth_token']);
        exit;
    } else if ($_SESSION['state'] == 1) {
        $oauthClient->setToken($_GET['oauth_token'], $_SESSION['secret']);
        $accessToken = $oauthClient->getAccessToken($accessTokenRequestUrl);
        $_SESSION['state'] = 2;
        $_SESSION['token'] = $accessToken['oauth_token'];
        $_SESSION['secret'] = $accessToken['oauth_token_secret'];
        header('Location: ' . $callbackUrl);
        exit;
    } else {
        $oauthClient->setToken($_SESSION['token'], $_SESSION['secret']);
        $resourceUrl = "$apiUrl/products";
        $productData = json_encode(array(
            'type_id'           => 'simple',
            'attribute_set_id'  => 4,
            'sku'               => 'simple' . uniqid(),
            'weight'            => 1,
            'status'            => 1,
            'visibility'        => 4,
            'name'              => 'Simple Product',
            'description'       => 'Simple Description',
            'short_description' => 'Simple Short Description',
            'price'             => 99.95,
            'tax_class_id'      => 0,
        ));
        $headers = array('Content-Type' => 'application/json');
        $oauthClient->fetch($resourceUrl, $productData, OAUTH_HTTP_METHOD_POST, $headers);
        print_r($oauthClient->getLastResponseInfo());
    }
} catch (OAuthException $e) {
    print_r($e);
}




