<?php

// Custom WP Query
include_once 'class-mc-query-shortcode.php';

// Wishlist
include_once 'wishlist.php';

// Compare
include_once 'compare.php';


#-----------------------------------------------------------------
# Shortcodes using [query] calss
#-----------------------------------------------------------------


// [blog] shortcode
//................................................................
if ( ! function_exists( 'blog_query' ) ) :
	function blog_query($args = null, $content = null) {

		// default template
		if ( !isset($args['template']) || empty($args['template']) ) {
			$args['template'] = 'blog-sidebar-right';
		}

		// default Style
		if ( !isset($args['blog_style']) || empty($args['blog_style']) ) {
			$args['blog_style'] = 'normal';
		}

		return custom_wp_query($args, $content);
	}
	
	add_shortcode('blog', 'blog_query');
endif;

// [shop] shortcode
//................................................................
if ( ! function_exists( 'shop_query' ) ) :
	function shop_query($args = null, $content = null) {
		global $woocommerce_loop;

		if( class_exists( 'WooCommerce' ) ){
			
			$catalog_ordering_args = WC()->query->get_catalog_ordering_args();

			if( $args == null ){
				$args = array();
			}

			if( is_array( $args ) ){
				$args = array_merge( $args, $catalog_ordering_args );
			}
		}


		// default template
		if ( !isset($args['template']) || empty($args['template']) ) {
			$args['template'] = 'shop-sidebar-left';
		}

		// default product item
		if( !isset( $args['product_item_size'] ) || empty( $args['product_item_size'] ) ) {
			$args['product_item_size'] = 'size-medium';
		} 

		// post type
		if ( !isset($args['post_type']) || empty($args['post_type']) ) {
			$args['post_type'] = 'product';
		}

		// orderby
		if ( !isset($args['orderby']) || empty($args['orderby']) ) {
			$args['orderby'] = 'menu_order'; // default by sort order
		}
		
		if ( !isset($args['order']) || empty($args['order']) ) {
			$args['order'] = 'ASC'; // default order
		}
		
		// categories 
		if ( isset($args['category']) ) {
			$args['taxonomy_slug'] = 'product_category';
			//$args['taxonomy_terms'] = ;
			unset($args['category']);
		}

		$woocommerce_loop['product_item_size'] = $args['product_item_size'];

		return custom_wp_query($args, $content);
	}
	
	add_shortcode('shop', 'shop_query');
endif;