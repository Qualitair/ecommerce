<?php

function do_sync_product_script() {
	wp_localize_script('do-product-sync', 'ajax_object', admin_url('admin-ajax.php'));	
}


function do_sync_product() {
	do_sync_product_script();
}

add_action( 'loop_start', 'do_sync_product' );

add_action('wp_ajax_do_sync_product', 'do_sync_product_callback');
add_action('wp_ajax_nopriv_do_sync_product', 'do_sync_product_callback');

function do_sync_product_callback() {
	$call = isset($_POST['call']) ? $_POST['call'] : 'price';

	$call = 'duap_update_' . $call;
	if(function_exists($call)) {
		$call();
	} else {
		die('Call to undefined function...');
	}
}

function duap_update_price() {

	try {
		//script goes here
		global $duap_options, $wpdb;
		$totUpdate = 0;
		$totalUP = 0;
		
		//first get all product on unleashed
		$json = getProducts("json");
		$jsonStr = "";
		$productUpdated = array();
		$productUpdatedJson = array();

		$matchTotal = 0;
		$matchProducts = array();

		//now lets add the $sql
		if(isset($json->Items)) $totalUP = count($json->Items);

		if($totalUP) {
			foreach ($json->Items as $k => $product) {
				//print_r($product); 
				$result = $wpdb->get_results( 'SELECT * FROM '. $wpdb->prefix .'posts WHERE post_type = "product" AND post_title = "'.esc_sql($product->ProductDescription).'" LIMIT 1', ARRAY_A);
				//echo $wpdb->num_rows;
				if($wpdb->num_rows) {

					$matchTotal++;
					$matchProducts[] = $product->ProductDescription;

					$prodId = 0;
					foreach ($result as $k => $v) {
						$prodId = $v['ID'];
					}

					if($prodId) {

						$updatePriceJson = '{"individual": "'.$product->SellPriceTier2->Value.'", "wholesale": "'.$product->SellPriceTier3->Value.'", "tender": "'.$product->SellPriceTier1->Value.'"}';

						$isUpdate = 0;

						$isUpdate = $wpdb->replace(
							$wpdb->prefix . 'postmeta', //table
							array(
								//data
								'meta_value' => $updatePriceJson // integer (number) 
							), 
							array(
								//where
								'post_id' => $prodId,
								'meta_key' => 'festiUserRolePrices'
							)
						);						

						//--- if updated successfully
						if($isUpdate) {
							$productUpdated[] = "(".$prodId . ")  <u>" . $product->ProductDescription ."</u>";
							$productUpdatedJson[] = "{<strong>individual:</strong> " . $product->SellPriceTier2->Value . ", 
									<strong>wholesale:</strong> " . $product->SellPriceTier3->Value . ", 
									<strong>tender:</strong> " . $product->SellPriceTier1->Value . "}";
							$totUpdate++;

						//--- else add new
						} else {

							$wpdb->get_results( 'SELECT * FROM '. $wpdb->prefix .'postmeta WHERE post_id = '.$prodId.' AND meta_key = "festiUserRolePrices" LIMIT 1', ARRAY_A);

							//now lets insert the data
							if(! $wpdb->num_rows) {
								$isInsert = $wpdb->insert( 
									$wpdb->prefix . 'postmeta', 
									array(
										'post_id' => $prodId,
										'meta_key' => 'festiUserRolePrices',
										'meta_value' => $updatePriceJson
									), 
									array( 
										'%d', 
										'%s',
										'%s'
									)
								);
								//--- if added
								if($isInsert) {
									$productUpdated[] = "(".$prodId . ") <u>" . $product->ProductDescription ."</u>";
									$productUpdatedJson[] = "{<strong>individual:</strong> " . $product->SellPriceTier2->Value . ", 
										<strong>wholesale:</strong> " . $product->SellPriceTier3->Value . ", 
										<strong>tender:</strong> " . $product->SellPriceTier1->Value . "}";
									$totUpdate++;
								}
							}
						}
					}
				}

				//creating json data
				$jsonStr .= '{"name":"'.esc_sql($product->ProductDescription).'", "individual": "'.$product->SellPriceTier2->Value.'", "wholesale": "'.$product->SellPriceTier3->Value.'", "tender": "'.$product->SellPriceTier1->Value.'"}';
				
				if($totalUP > ($k+1)) $jsonStr .= ",";
			}
		}

		$newJson = "[".$jsonStr."]";

		//now lets insert the data
		$wpdb->insert( 
			$wpdb->prefix . 'djay_unleashed', 
			array(
				'duap_time' => current_time( 'mysql' ), 
				'duap_json' => $newJson,
				'duap_type' => 'price',
				'duap_user' => get_current_user_id()
			), 
			array( 
				'%s', 
				'%s',
				'%d'
			)
		);
		
		$newJson = json_decode($newJson, true);
		echo "<div class='duap-ajax-result-section'>";
		echo "<p><b>Total no. of matched product(s): </b><span class='counter'>" . $matchTotal . "</span><p>";
		echo "<p><b>Total no. of <font color='green'>updated</font> product(s): </b><span class='counter'>" . $totUpdate . "</span><p>";
		echo "<p><b>Product List</b></p><ul>";
		if($totUpdate) foreach ($productUpdated as $k => $prod) echo "<li>" . $prod . " - " . $productUpdatedJson[$k] . "</li>";
		else echo "<p class='empty'>No product has been updated! Please click again...</p>";
		echo "</ul>";
		echo "<hr>";
		echo "<p><b>Matched Product List</b></p><ul>";
		if($matchTotal) foreach ($matchProducts as $prod) echo "<li>" . $prod . "</li>";
		else echo "<p class='empty'>No product matches! Please click again...</p>";
		echo "</ul></div>";

		die();

	} catch(Exception $e) {
		echo $e->getMessage();
		die();
	}
	
}


function duap_update_stock() {

	try {
		//script goes here
		global $duap_options, $wpdb;
		$totUpdate = 0;
		$totalUP = 0;
		
		//first get all product on unleashed
		$json = getStockOnHand("json");
		$jsonStr = "";
		$productUpdated = array();
		$productUpdatedJson = array();

		$matchTotal = 0;
		$matchProducts = array();

		//now lets add the $sql
		if(isset($json->Items)) $totalUP = count($json->Items);

		if($totalUP) {
			foreach ($json->Items as $k => $product) {
				//print_r($product); 
				$result = $wpdb->get_results( 'SELECT * FROM '. $wpdb->prefix .'posts WHERE post_type = "product" AND post_title = "'.esc_sql($product->ProductDescription).'" LIMIT 1', ARRAY_A);
				//echo $wpdb->num_rows;
				if($wpdb->num_rows) {

					$matchTotal++;
					$matchProducts[] = $product->ProductDescription;

					$prodId = 0;
					foreach ($result as $k => $v) {
						$prodId = $v['ID'];
					}

					if($prodId) {

						$updatePriceJson = $product->QtyOnHand;
						$status = intval($updatePriceJson) ? 'instock' : 'outofstock';

						$isUpdate = 0;

						$isUpdate = $wpdb->replace(
							$wpdb->prefix . 'postmeta', //table
							array(
								//data
								'meta_value' => $updatePriceJson // integer (number) 
							), 
							array(
								//where
								'post_id' => $prodId,
								'meta_key' => '_stock'
							)
						);

						$isUpdate = $wpdb->replace(
							$wpdb->prefix . 'postmeta', //table
							array(
								//data
								'meta_value' => $status // integer (number) 
							), 
							array(
								//where
								'post_id' => $prodId,
								'meta_key' => '_stock_status'
							)
						);		

						//--- if updated successfully
						if($isUpdate) {
							$productUpdated[] = "(".$prodId . ")  <u>" . $product->ProductDescription ."</u>";
							$productUpdatedJson[] = "Stock On Hand: " . $product->QtyOnHand . " | status: " . $status;
							$totUpdate++;

						//--- else add new
						} else {
							$wpdb->get_results( 'SELECT * FROM '. $wpdb->prefix .'postmeta WHERE post_id = '.$prodId.' AND meta_key = "_stock" LIMIT 1', ARRAY_A);

							//now lets insert the data
							if(! $wpdb->num_rows) {
								$isInsert = $wpdb->insert( 
									$wpdb->prefix . 'postmeta', 
									array(
										'post_id' => $prodId,
										'meta_key' => '_stock',
										'meta_value' => $updatePriceJson
									), 
									array( 
										'%d', 
										'%s',
										'%s'
									)
								);
								//--- if added
								if($isInsert) {
									$productUpdated[] = "(".$prodId . ") <u>" . $product->ProductDescription ."</u>";
									$productUpdatedJson[] = "Stock On Hand: " . $product->QtyOnHand;
									$totUpdate++;
								}
							}
						}
					}
				}				
			}
		}

		echo "<div class='duap-ajax-result-section'>";
		echo "<p><b>Total no. of matched product(s): </b><span class='counter'>" . $matchTotal . "</span><p>";
		echo "<p><b>Total no. of <font color='green'>updated</font> product(s): </b><span class='counter'>" . $totUpdate . "</span><p>";
		echo "<p><b>Product List</b></p><ul>";
		if($totUpdate) foreach ($productUpdated as $k => $prod) echo "<li>" . $prod . " - " . $productUpdatedJson[$k] . "</li>";
		else echo "<p class='empty'>No product has been updated! Please click again...</p>";
		echo "</ul>";
		echo "<hr>";
		echo "<p><b>Matched Product List</b></p><ul>";
		if($matchTotal) foreach ($matchProducts as $prod) echo "<li>" . $prod . "</li>";
		else echo "<p class='empty'>No product matches! Please click again...</p>";
		echo "</ul></div>";

		die();

	} catch(Exception $e) {
		echo $e->getMessage();
		die();
	}
	
}