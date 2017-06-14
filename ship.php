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

$product_id='116';
$_product = $objectManager->get('Magento\Catalog\Model\Product')->load($product_id);

$quote=$objectManager->create('Magento\Quote\Model\Quote');
$data=$quote->getShippingAddress();

$quote->getShippingAddress()->setCountryId('US');
$quote->getShippingAddress()->setRegion('FL');
$quote->getShippingAddress()->setPostcode('33076');

$quote->addProduct($_product); 
//$quote->getShippingAddress()->collectTotals();
$quote->getShippingAddress()->setCollectShippingRates(true);
$quote->getShippingAddress()->collectShippingRates();
$rates = $quote->getShippingAddress()->getShippingRatesCollection();

foreach ($rates as $rate)
{
echo $rate->getPrice();
}

?>
