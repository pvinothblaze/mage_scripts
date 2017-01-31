<?php ini_set('display_errors','1');
ini_set('max_execution_time', 60000); 
require_once "app/Mage.php";
Mage::app();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$customer_groups=array(0,1,2,3);
$name='sample';
$coupon_code='Test';
$discount='10';
$rule = Mage::getModel('salesrule/rule');
 $rule->setName($name)
    ->setDescription($name)
    ->setFromDate('')
    ->setCouponType(2)
    ->setCouponCode($coupon_code)
    ->setUsesPerCustomer(1)
    ->setCustomerGroupIds($customer_groups) //an array of customer grou pids
    ->setIsActive(1)
    ->setConditionsSerialized('')
    ->setActionsSerialized('')
    ->setStopRulesProcessing(0)
    ->setIsAdvanced(1)
    ->setProductIds('')
    ->setSortOrder(0)
    ->setSimpleAction('cart_fixed')
    ->setDiscountAmount($discount)
    ->setDiscountQty(null)
    ->setDiscountStep(0)
    ->setSimpleFreeShipping('0')
    ->setApplyToShipping('0')
    ->setIsRss(0)
    ->setWebsiteIds(array(1))->save();
    ?>
