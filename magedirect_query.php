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
						'order_id' => $data['0'], 
						'line_item_id' => $data['1'],
						'product_id' => $data['2'],
						'transaction_time' => $data['3'],
						'item_title' => $data['4'],
						'brand' => $data['5'],
						'condition' => $data['6'],
						'upc' => $data['7'],
						'mpn' => $data['8'],
						'sku' => $data['9'],
						'isbn' => $data['10'],
						'merchant_id' => $data['11'],
						'quantity_bought' => $data['12'],
						'unit_price' => $data['13'],
						'item_subtotal' => $data['14'],
						'order_tax' => $data['15'],
						'order_shipping_cost' => $data['16'],
						'promotional_savings' => $data['17'],
						'order_total' => $data['18'],
						'order_selling_fee' => $data['19'],
						'order_earnings' => $data['20'],
						'shipping_carrier_service' => $data['21'],
						'ship_by_date' => $data['22'],
						'deliver_by_date' => $data['23'],
						'shipped_on_date' => $data['24'],
						'tracking_id' => $data['25'],
						'shipment_tracking_submission_date' => $data['26'],
						'shipment_tracking_submission_on_time' => $data['27'],
						'coupon_code' => $data['28'],
						'shipping_address_first_name' => $data['29'],
						'shipping_address_last_name' => $data['30'],
						'shipping_address_1' => $data['31'],
						'shipping_address_2' => $data['32'],
						'shipping_address_city' => $data['33'],
						'shipping_address_state' => $data['34'],
						'shipping_address_zip' => $data['35'],
						'shipping_phone_number' => $data['36'],
						'shipping_phone_number' => $data['36'],
						'billing_address_first_name' => $data['37'],
						'billing_address_last_name' => $data['38'],
						'billing_address_1' => $data['39'],
						'billing_address_2' => $data['40'],
						'billing_address_city' => $data['41'],
						'billing_address_state' => $data['42'],
						'billing_address_zip' => $data['43'],
						'billing_phone_number' => $data['44'],
						'buyer_email_address' => $data['45'],
						'buyer_phone_number' => $data['46'],
						'payment_gateway' => $data['47'],
						'transaction_id' => $data['48'],
						'payPal_protection_eligibility' => $data['49'],
						'payPal_protection_eligibility_type' => $data['50'],
						'payPal_pending_reason' => $data['51'],
						'payPal_reason_code' => $data['52'],
						'payPal_payer_id' => $data['53'],
						'payPal_payer_status' => $data['54'],
						'payPal_payer_country_code' => $data['55'],
						'payPal_checkout_status' => $data['56'],
						'credit_card_last_four' => $data['57'],
						'order_status' => $data['58'],
						'note_to_seller' => $data['59'],
						'private_order_notes' => $data['60'],
						'buyer_feedback_score' => $data['61'],
						'buyer_feedback_message' => $data['62'],
						'order_arrived_on_time' => $data['63'],
						'item_was_as_described' => $data['64'],
						'good_customer_service_experience' => $data['65'],
						'order_was_refunded' => $data['66'],
						'refunded_on' => $data['67'],
						'reason_for_refund' => $data['68'],
						'refund_type' => $data['69'],
						'refund_amount' => $data['70'],
						'refund_transaction_id' => $data['71'],
						'buyer_ip_address' => $data['72'],
						'pf_order_status'=>$status,
						'pf_order_id'=>$pf_order_id
					)
			);
			
			
			$query = "UPDATE {$table} SET pf_order_status = 'shipped' WHERE order_id = 1377241";
			
			/**
			 * Execute the query
			 */
			$writeConnection->query($query);
			?>
