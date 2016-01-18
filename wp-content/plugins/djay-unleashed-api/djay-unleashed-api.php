<?php

/*
	Plugin Name: DJay Unleashed API
	Plugin URI: http://www.qualitair.com.au/
	Description: Unleashed software api for products
	Version: 1.0
	Author: Denmark Jay Mago
	Author URI: 
	Text Domain: djay-unleashed-api
 */

/**  
* Copyright 2015-2016	Denmark Jay Mago (email : denmarkjay.mago@gmail.com)	
*/

/***********************
* Global variables
***********************/
$duap_prefix = 'duap_';
$duap_plugin_name = "Unleashed Software API";

/*********************************
* Retrieve our plugin options
**********************************/
$duap_options = get_option('duap_settings');

/*********************
* Inludes
**********************/
require_once ('includes/assets.php');
require_once ('includes/unleashedAPI.php');
require_once ('includes/process.php');
require_once ('includes/admin-page.php');


/*********************
* Create database
**********************/
global $duap_db_version;
$duap_db_version = '1.0';

function duap_install() {
	global $wpdb;
	global $duap_db_version;

	$table_name = $wpdb->prefix . 'djay_unleashed';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		duap_id    mediumint(9) NOT NULL AUTO_INCREMENT,
		duap_time  datetime NOT NULL,
		duap_type  varchar(100) NOT NULL,
		duap_json   longtext NOT NULL,
		duap_user  mediumint(9) NOT NULL,
		UNIQUE KEY id (duap_id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'duap_db_version', $duap_db_version );
}

function duap_install_data() {
	global $wpdb;
	
	$welcome_name = 'Mr. WordPress';
	$welcome_text = 'Congratulations, you just completed the installation!';
	
	$table_name = $wpdb->prefix . 'djay_unleashed';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'name' => $welcome_name, 
			'text' => $welcome_text, 
		) 
	);
}

register_activation_hook( __FILE__, 'duap_install' );
register_activation_hook( __FILE__, 'duap_install_data' );