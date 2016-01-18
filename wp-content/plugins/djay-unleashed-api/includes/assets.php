<?php

function duap_load_assets() {
	wp_enqueue_style( 'duap-style', plugin_dir_url(__FILE__) . '../assets/css/duap-style.css');
}
add_action('admin_enqueue_scripts', 'duap_load_assets');