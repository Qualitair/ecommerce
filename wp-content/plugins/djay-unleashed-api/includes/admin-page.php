<?php

/*************************** 
* Display the page
****************************/
function duap_options_page() {
	
	global $duap_options, $wpdb;

	ob_start(); 

?>
	<form method="post" action="options.php">
		<div class="duap-logo"><img src="/wp-content/uploads/2015/12/logo-unleashed-software.png"></div>
		<?php settings_fields('duap_settings_group');	?>

		<table class="form-table duap-api-form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label class="description" for="duap_settings[duap_api_url]"><?php _e('API', 'duap_domain'); ?></label></th>
				<td><input type="text" id="duap_settings[duap_api_url]" name="duap_settings[duap_api_url]" value="<?php echo $duap_options['duap_api_url']; ?>" data-content="<?php echo $duap_options['duap_api_url']; ?>"> <span class="description">API Url - where API endpoint will be appended.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label class="description" for="duap_settings[duap_api_id]"><?php _e('API ID', 'duap_domain'); ?></label></th>
				<td><input type="text" id="duap_settings[duap_api_id]" name="duap_settings[duap_api_id]" value="<?php echo $duap_options['duap_api_id']; ?>" data-content="<?php echo $duap_options['duap_api_id']; ?>"> <span class="description">API unique ID provided by your software.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label class="description" for="duap_settings[duap_api_key]"><?php _e('API Key', 'duap_domain'); ?></label></th>
				<td><input type="text" id="duap_settings[duap_api_id]" name="duap_settings[duap_api_key]" value="<?php echo $duap_options['duap_api_key']; ?>" data-content="<?php echo $duap_options['duap_api_key']; ?>"> <span class="description">API Key provided by your software.</span>
				</td>
			</tr>
		</tbody>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="Save Changes">
		</p>
	</form>
<?php
	
	$product = $wpdb->get_results( 'SELECT * FROM wp_posts WHERE post_type = "product" AND post_status = "publish"');
?>	
	<div>
	<h2>Product Information: </h2>
	<p><label>Total no. of published (active) product: <strong><big><?php echo $wpdb->num_rows; ?></big></strong></label></p>
	</div>

	<div>		
		<table class="form-table duap-api-form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><button class="button-secondary btn-ajax-sync product" action="product">Update <u>Price</u> Woocommerce<i class="dashicons-before dashicons-arrow-left-alt"></i> <i class="dashicons-before dashicons-arrow-right-alt"></i> Unleashed API</button></th>
					<td> <span class="description">Sync product <u><b>price</b></u> from unleashed to wordpress woocommerce plugin.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><button class="button-secondary btn-ajax-sync stock" action="stock">Update <u>Stock</u> Woocommerce <i class="dashicons-before dashicons-arrow-left-alt"></i> <i class="dashicons-before dashicons-arrow-right-alt"></i> Unleashed API</button></th>
					<td> <span class="description">Sync product <u><b>stock</b></u> from unleashed to wordpress woocommerce plugin.</span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="duap-result-content">
		
	</div>
<?php
	echo ob_get_clean();
}
	

function duap_add_options_link() {
	add_options_page('Unleashed Software Plugin', 'DJay Unleashed API', 'manage_options', 'duap-options', 'duap_options_page');
}
add_action('admin_menu', 'duap_add_options_link');

function duap_register_settings() {
	register_setting('duap_settings_group', 'duap_settings');
}

add_action('admin_init', 'duap_register_settings');

add_action( 'admin_footer', 'my_action_javascript' ); // Write our JS below here
function my_action_javascript() { ?>
	<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__) . '../assets/js/script.js'; ?>" ></script> <?php
}

?>