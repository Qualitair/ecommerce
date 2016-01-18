<?php

class WooCommerce_Hide_Products_Admin {

    public static function woocommerce_hide_product_settings_tabs($settings_tabs) {
        $settings_tabs['woocommerce_toggle_shop'] = __('WooCommerce Hide Shop Products', 'woocommerce');
        return $settings_tabs;
    }

    public static function woocommerce_hide_product_admin_options() {

        return apply_filters('woocommerce_toggle_product_settings', array(
            array(
                'name' => __('WooCommerce Hide Shop Products', 'woocommerce'),
                'type' => 'title',
                'desc' => '',
                'id' => '_woo_hide_products'
            ),
            array('name' => __('Enable/Disable WooCommerce Toggle Shop Products', 'woohideproducts'),
                'desc' => __('Turn Checked If you want to Toggle Shop Products'),
                'tip' => '',
                'id' => 'woo_enable_hide_products',
                'css' => '',
                'std' => 'yes',
                'type' => 'checkbox',
                'defaultvalue' => 'woo_enable_hide_products',
                'desc_tip' => '',
            ),
            array('type' => 'sectionend', 'id' => '_woo_hide_products'),
            array('type' => 'sectionend', 'id' => '_woo_hide_products_maincontent'),
        ));
    }

    public static function woocommerce_hide_product_admin_default() {
        global $woocommerce;
        foreach (WooCommerce_Hide_Products_Admin::woocommerce_hide_product_admin_options() as $settings) {
            if (isset($settings['defaultvalue']) && ($settings['std'])) {
                add_option($settings['defaultvalue'], $settings['std']);
            }
        }
    }

    public static function woocommerce_hide_product_admin_fields() {
        woocommerce_admin_fields(WooCommerce_Hide_Products_Admin::woocommerce_hide_product_admin_options());
    }

    public static function woocommerce_hide_product_update_options() {
        woocommerce_update_options(WooCommerce_Hide_Products_Admin::woocommerce_hide_product_admin_options());
    }

    public static function woocommerce_add_filter_hide_product($settings) {

        $args = array('post_type' => 'product', 'posts_per_page' => '-1');
        $checkproducts = get_posts($args);
        if (is_array($checkproducts)) {
            foreach ($checkproducts as $product) {
                $product_id[] = $product->ID;
                $product_title[] = get_the_title($product->ID);
            }
        }
        if (is_array($checkproducts)) {
            $newcombinedvalues = array_combine((array) $product_id, (array) $product_title);
        }

	
	$get_terms_product = get_terms('product_cat',array('hide_empty'=>'false'));
	//var_dump($get_terms_product);
	$current_category_products = array();
	if(!empty($get_terms_product)&&is_array($get_terms_product)) {
	 	foreach($get_terms_product as $each_term) {		
			$current_category_products[$each_term->term_id]= $each_term->name; 
		}	
	}

	//var_dump($current_category_products);	
        $updated_settings = array();
        $mainvariable = array();
        foreach ($settings as $section) {
            if (isset($section['id']) && '_woo_hide_products_maincontent' == $section['id'] &&
                    isset($section['type']) && 'sectionend' == $section['type']) {
                global $wp_roles;
                if (!isset($wp_roles)) {
                    $wp_roles = new WP_Roles();
                }
                $getdata = $wp_roles->get_names();

                foreach ($getdata as $data => $key) {
                    $updated_settings[] = array(
                        'name' => __('Toggle Shop Products for ' . $key, 'woohideproducts'),
                        'type' => 'title',
                        'id' => '_woo_hide_products_' . $data,
                    );
		    $updated_settings[] = array(
			'name' => __('Toggle Type', 'woohideproducts'),
			'desc' => __('Toggle Products by category or certain products', 'woohideproducts'),
			'tip' => '',
			'id' => 'woo_toggle_products_by_type_'.$data,
			'css' => '',
			'std' => '1',
			'defaultvalue' => 'woo_toggle_products_by_type_'.$data,
			'type' => 'select',
			'options' => array('1' => __('By Products','woohideproducts'),'2'=> __('By Categories','woohideproducts')),
			'desc_tip' => true,
		    );

                    $updated_settings[] = array(
                        'name' => __('Hide Products in Category', 'woohideproducts'),
                        'desc' => __('Hide Products in Category Page of WooCommerce', 'woohideproducts'),
                        'tip' => '',
                        'id' => 'woo_hide_products_in_category_' . $data,
                        'css' => '',
                        'std' => 'yes',
                        'defaultvalue' => 'woo_hide_products_in_category_' . $data,
                        'type' => 'checkbox',
                        'desc_tip' => '',
                    );
                    $updated_settings[] = array(
                        'name' => __('Hide Products in Search Result', 'woohideproducts'),
                        'desc' => __('Hide Products in Search Result of WooCommerce', 'woohideproducts'),
                        'tip' => '',
                        'id' => 'woo_hide_products_in_search_' . $data,
                        'css' => '',
                        'std' => 'yes',
                        'defaultvalue' => 'woo_hide_products_in_search_' . $data,
                        'type' => 'checkbox',
                        'desc_tip' => '',
                    );
                    $updated_settings[] = array(
                        'name' => __('Toggle Include/Exclude Archive Products', 'woohideproducts'),
                        'desc' => __('Toggle Include Products/Exclude Products in WooCommerce Shop Page', 'woohideproducts'),
                        'tip' => '',
                        'id' => 'woo_include_exclude_products_' . $data,
                        'css' => '',
                        'std' => '2',
                        'defaultvalue' => 'woo_include_exclude_products_' . $data,
                        'type' => 'radio',
                        'options' => array('1' => __('Include', 'woohideproducts'), '2' => __('Exclude', 'woohideproducts')),
                        'desc_tip' => true,
                    );


		    $updated_settings[] = array(
			'name' => __('Choose Category','woohideproducts'),
			'desc' => __('Want to hide products in a bulk way then using this option to hide the products','woohideproducts'),
			'tip' => '',
			'id' => 'woo_toggle_type_category_'.$data,
			'css' => '',
			'std' => '',
			'defaultvalue' => 'woo_toggle_type_category_'.$data,
			'type' => 'multiselect',
			'options' => $current_category_products,
	            ); 
		 	
                    $updated_settings[] = array(
                        'name' => __('Select Product to Toggle Shop', 'woohideproducts'),
                        'desc' => __('Select Product which will be Toggle Shop in Shop Page', 'woohideproducts'),
                        'tip' => '',
                        'id' => 'woo_select_products_' . $data,
                        'css' => '',
                        'std' => '',
                        'type' => 'multiselect',
                        'options' => $newcombinedvalues,
                    );


                    $updated_settings[] = array(
                        'type' => 'sectionend', 'id' => '_woo_hide_products_' . $data,
                    );
                }

                $updated_settings[] = array(
                    'name' => __('Toggle Shop Products for Guest', 'woohideproducts'),
                    'type' => 'title',
                    'id' => '_woo_hide_products_guest',
                );
		  $updated_settings[] = array(
			'name' => __('Toggle Type', 'woohideproducts'),
			'desc' => __('Toggle Products by category or certain products', 'woohideproducts'),
			'tip' => '',
			'id' => 'woo_toggle_products_by_type_guest',
			'css' => '',
			'std' => '1',

			'defaultvalue' => 'woo_toggle_products_by_type_guest',
			'type' => 'select',
			'options' => array('1' => __('By Products','woohideproducts'),'2'=> __('By Categories','woohideproducts')),
			'desc_tip' => true,
		    );

		 $updated_settings[] = array(
                        'name' => __('Hide Products in Category', 'woohideproducts'),
                        'desc' => __('Hide Products in Category Page of WooCommerce', 'woohideproducts'),
                        'tip' => '',
                        'id' => 'woo_hide_products_in_category_guest',
                        'css' => '',
                        'std' => 'yes',
                        'defaultvalue' => 'woo_hide_products_in_category_guest',
                        'type' => 'checkbox',
                        'desc_tip' => '',
                    );
                    $updated_settings[] = array(
                        'name' => __('Hide Products in Search Result', 'woohideproducts'),
                        'desc' => __('Hide Products in Search Result of WooCommerce', 'woohideproducts'),
                        'tip' => '',
                        'id' => 'woo_hide_products_in_search_guest',
                        'css' => '',
                        'std' => 'yes',
                        'defaultvalue' => 'woo_hide_products_in_search_guest',
                        'type' => 'checkbox',
                        'desc_tip' => '',
                    );

                $updated_settings[] = array(
                    'name' => __('Toggle Include/Exclude Archive Products', 'woohideproducts'),
                    'desc' => __('Toggle Include Products/Exclude Products in WooCommerce Shop Page', 'woohideproducts'),
                    'tip' => '',
                    'id' => 'woo_include_exclude_products_guest',
                    'css' => '',
                    'std' => '2',
                    'defaultvalue' => 'woo_include_exclude_products_guest',
                    'type' => 'radio',
                    'options' => array('1' => __('Include', 'woohideproducts'), '2' => __('Exclude', 'woohideproducts')),
                    'desc_tip' => true,
                );

		 $updated_settings[] = array(
			'name' => __('Choose Category','woohideproducts'),
			'desc' => __('Want to hide products in a bulk way then using this option to hide the products','woohideproducts'),
			'tip' => '',
			'id' => 'woo_toggle_type_category_guest',
			'css' => '',
			'std' => '',
			'defaultvalue' => 'woo_toggle_type_category_guest',
			'type' => 'multiselect',
			'options' => $current_category_products,
	            ); 

                $updated_settings[] = array(
                    'name' => __('Select Product to Toggle Shop', 'woohideproducts'),
                    'desc' => __('Select Product which will be Toggle Shop in Shop Page', 'woohideproducts'),
                    'tip' => '',
                    'id' => 'woo_select_products_guest',
                    'css' => '',
                    'std' => '',
                    'type' => 'multiselect',
                    'options' => $newcombinedvalues,
                );

                $updated_settings[] = array(
                    'type' => 'sectionend', 'id' => '_woo_hide_products_guest',
                );
            }

            $updated_settings[] = $section;
        }

        return $updated_settings;
    }

}

new WooCommerce_Hide_Products_Admin();

add_action('woocommerce_update_options_woocommerce_toggle_shop', array('WooCommerce_Hide_Products_Admin', 'woocommerce_hide_product_update_options'));
add_action('woocommerce_settings_tabs_woocommerce_toggle_shop', array('WooCommerce_Hide_Products_Admin', 'woocommerce_hide_product_admin_fields'));
add_filter('woocommerce_settings_tabs_array', array('WooCommerce_Hide_Products_Admin', 'woocommerce_hide_product_settings_tabs'), 100);
add_action('admin_init', array('WooCommerce_Hide_Products_Admin', 'woocommerce_hide_product_admin_default'));
add_filter('woocommerce_toggle_product_settings', array('WooCommerce_Hide_Products_Admin', 'woocommerce_add_filter_hide_product'));
