<?php ini_set('display_errors','1');
ini_set('max_execution_time', 60000); 
require_once "../app/Mage.php";
Mage::app();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
//$product = Mage::getModel('catalog/product')->load('68');
//foreach ($products as $product) {

//$productId = 68;
$products = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*');
$i=1;
foreach ($products as $product) 
{
	$proid=$product->getId();
	$product = Mage::getModel('catalog/product')->load($product->getId());
	//load the product
	//$product = Mage::getModel('catalog/product')->load($productId);
	//get all images
	$mediaGallery = $product->getMediaGalleryImages();
	//if there are images
	if (isset($mediaGallery))
	{
	    //loop through the images
	    foreach ($mediaGallery as $image)
	    {
			//echo '<br/>';
		//set the first image as the base image
		    $imageposition=$image->getposition();
		   // echo '<br/>';
		    $baseImage=$image->getFile();
		    //echo '<br/>';
		    $product->getImage();
		    //echo '<br/>';
		    $product->getSmallImage();
		    //echo '<br/>';
	       	if ($product->getImage()=='no_selection' && $imageposition=='1')  
	       	{ 
	       		$product->setImage($baseImage)->save();
				$logtxt='Product-Id= '.$proid.'| Set Base Image from Default Image | '.$baseImage;
				Mage::log($logtxt, null, 'syncimage.log');
	       		
	       	 }
	       	if (!$product->getSmallImage() || $product->getSmallImage()=='no_selection')
	       	{ 
	       		$product->setSmallImage($product->getImage())->save();
	       		$logtxt='Product-Id= '.$proid.'| Set Small Image from Base Image | '.$product->getImage();
				Mage::log($logtxt, null, 'syncimage.log');
	       	}
	    	if (!$product->getThumbnail() || $product->getThumbnail()=='no_selection')
	    	{ 
	    		$product->setThumbnail($product->getImage())->save();
	    		$logtxt='Product-Id= '.$proid.'| Set Thumb Image from Base Image | '.$product->getImage();
				Mage::log($logtxt, null, 'syncimage.log');
	    	}
	    	$i++;
	    	//$product->save();
	    }
	}
}

?>
