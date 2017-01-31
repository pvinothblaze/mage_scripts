<?php
ini_set('display_errors','1');
require_once "../app/Mage.php";
Mage::app();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
/**
		 * Get the resource model
		 */
		$resource = Mage::getSingleton('core/resource');
		
		/**
		 * Retrieve the read connection
		 */
		$readConnection = $resource->getConnection('core_read');
		/**
		* Retrieve the write connection
		*/
		$writeConnection = $resource->getConnection('core_write');
		/**
		 * Retrieve our table name
		 */
		$table = 'pf_orders';
		$writeConnection->insert($table, 
				array(
						'private_order_notes' => 'sample'
					)
			);
			
			
			$query = "UPDATE {$table} SET pf_order_status = 'shipped' WHERE order_id = 1377241";
			
			/**
			 * Execute the query
			 */
			$writeConnection->query($query);
			?>
