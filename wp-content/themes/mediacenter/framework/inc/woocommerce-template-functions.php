<?php

/**
 * WooCommerce Number of products displayed per page
 *
 */
if( ! function_exists( 'media_center_wc_products_per_page') ) :
function media_center_wc_products_per_page(){
	global $media_center_theme_options;
	$products_per_page = isset( $media_center_theme_options['products_per_page'] ) ? $media_center_theme_options['products_per_page'] : 12;
	return $products_per_page;
}
endif;

add_filter( 'loop_shop_per_page', 'media_center_wc_products_per_page' , 20 );

/**
 * Determine col width based on product item size and width of the screen
 *
 */

function media_center_product_item_col_width( $product_item_size = 'size-medium', $screen_width = 75 ){
	$col_width = 3;
	$columns = 4;
	
	if( $product_item_size == 'size-big' ) {

		switch( $screen_width ){
			case 100 :
				$col_width = array( 'col-lg-4', 'col-md-6', 'col-sm-6', 'col-xs-12' );
			break;
			case 75  :
			default  :
				$col_width = array( 'col-lg-6', 'col-md-6', 'col-sm-6', 'col-xs-12' );
		}

	} elseif( $product_item_size == 'size-small' ) {

		switch( $screen_width ){
			case 100 :
				$col_width = array( 'col-lg-2', 'col-md-3', 'col-sm-4', 'col-xs-12' );
			break;
			case 75  :
			default  :
				$col_width = array( 'col-lg-3', 'col-md-3', 'col-sm-4', 'col-xs-12' );
		}

	} else{

		switch( $screen_width ){
			case 100 :
				$col_width = array( 'col-lg-3', 'col-md-4', 'col-sm-6', 'col-xs-12' );
				$columns = 4;
			break;
			case 75  :
			default  :
				$col_width = array( 'col-lg-4', 'col-md-4', 'col-sm-6', 'col-xs-12' );
		}
	}

	return array( 'columns' => $columns,  'classes' => $col_width );
}


/**
 * Get the product thumbnail for the loop.
 *
 * @access public
 * @subpackage  Loop
 * @return void
 */
if( ! function_exists( 'woocommerce_template_loop_product_thumbnail') ) :
function woocommerce_template_loop_product_thumbnail( $view = 'grid-view' ) {
	global $woocommerce_loop, $post, $media_center_theme_options, $woocommerce_loop;

	$product_item_size = isset( $woocommerce_loop['product_item_size'] ) ? $woocommerce_loop['product_item_size'] : $media_center_theme_options['product_item_size'];

	switch( $product_item_size ){
		case 'size-medium':
		$image_size = 'product-medium';
		break;
		case 'size-small' : 
		$image_size = 'product-small';
		break;
		case 'size-big' :
		$image_size = 'product-large';
		break;
		default:
		$image_size = 'product-medium';
	}

	if( $view == 'list-view' ){
		$image_size = 'product-medium';
	}

	echo '<div class="product-image">';
	echo '<a href="' . get_permalink( $post->ID ) . '">';

	if( $media_center_theme_options['lazy_loading'] ){
		if ( has_post_thumbnail( $post->ID ) ) {
			$post_thumbnail_ID = get_post_thumbnail_id( $post->ID );
			$post_thumbnail_src = wp_get_attachment_image_src( $post_thumbnail_ID, $image_size );
		}else{
			$dimensions = wc_get_image_size( $image_size );
			$post_thumbnail_src = array(
				wc_placeholder_img_src(),
				esc_attr( $dimensions['width'] ),
				esc_attr( $dimensions['height'] )
			);
		}
		echo '<img class="post-image echo-lazy-loading" src="' . get_template_directory_uri() . '/assets/images/blank.gif" alt="" data-echo="' . $post_thumbnail_src[0]. '" width="' .$post_thumbnail_src[1]. '" height="' .$post_thumbnail_src[2]. '">';
	}else{
		if ( has_post_thumbnail( $post->ID ) ) {
			echo get_the_post_thumbnail( $post->ID, $image_size);
		}else{
			echo wc_placeholder_img( $image_size );
		}
	}
	echo '</a>';
	echo '</div>';
}
endif;
/*
 * Availability Class
 *
 */
if( ! function_exists( 'media_center_availability_label_class') ) :
function media_center_availability_label_class( $availability ){
	$class = '';
	switch( $availability['class'] ) {
		case 'out-of-stock':
			$class = 'label-danger';
		break;
		case 'available-on-backorder':
			$class = 'label-warning';
		break;
		default :
			$class = 'label-success';
	}
	return $class;
}
endif;
/* Price HTML */
if( ! function_exists( 'media_center_wrap_price_html') ) :
function media_center_wrap_price_html( $price ) {
	//return '<div class="media-center-price">' . $price . '</div>';
	return $price;
}
endif;
add_filter( 'woocommerce_get_price_html', 'media_center_wrap_price_html' );

if( ! function_exists( 'woocommerce_order_again_button') ) :
function woocommerce_order_again_button( $order ) {
	if ( ! $order || $order->status != 'completed' )
		return;

	?>
	<p class="order-again">
		<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'order_again', $order->id ) , 'woocommerce-order_again' ) ); ?>" class="le-button button"><?php _e( 'Order Again', 'media_center' ); ?></a>
	</p>
	<?php
}
endif;

if( ! function_exists( 'get_widget_product_lazy_load_image') ) :
function get_widget_product_lazy_load_image( $product, $image_size = 'product-thumbnail'){
	$image = '';
 
	if ( has_post_thumbnail( $product->id ) ) {
		
		$product_thumbnail_ID = get_post_thumbnail_id( $product->id );
		$product_thumbnail_src = wp_get_attachment_image_src( $product_thumbnail_ID, $image_size );

	} elseif ( ( $parent_id = wp_get_post_parent_id( $product->id ) ) && has_post_thumbnail( $parent_id ) ) {
	 	
	 	$product_thumbnail_ID = get_post_thumbnail_id( $parent_id );
		$product_thumbnail_src = wp_get_attachment_image_src( $product_thumbnail_ID, $image_size );

	} else {
	 	$dimensions = wc_get_image_size( $image_size );
		$product_thumbnail_src = array(
			wc_placeholder_img_src(),
			esc_attr( $dimensions['width'] ),
			esc_attr( $dimensions['height'] )
		);
	}

	$image = '<img class="post-image '. $image_size . ' echo-lazy-loading" src="' . get_template_directory_uri() . '/assets/images/blank.gif" alt="" data-echo="' . $product_thumbnail_src[0]. '" width="' .$product_thumbnail_src[1]. '" height="' .$product_thumbnail_src[2]. '">';

	return $image;
}
endif;

if( ! function_exists( 'woocommerce_product_large_image_size') ) :
function woocommerce_product_large_image_size( $size ){
 
    $size['width'] = '433';
    $size['height'] = '325';
 
    return $size;
} 
endif;
add_filter( 'woocommerce_get_image_size_product-large', 'woocommerce_product_large_image_size' );

if( ! function_exists( 'woocommerce_product_medium_image_size') ) :
function woocommerce_product_medium_image_size( $size ){
 
    $size['width'] = '246';
    $size['height'] = '186';
 
    return $size;
} 
endif;
add_filter( 'woocommerce_get_image_size_product-medium', 'woocommerce_product_medium_image_size' );

if( ! function_exists( 'woocommerce_product_small_image_size') ) :
function woocommerce_product_small_image_size( $size ){
 
    $size['width'] = '194';
    $size['height'] = '143';
 
    return $size;
} 
endif;
add_filter( 'woocommerce_get_image_size_product-small', 'woocommerce_product_small_image_size' );

if( ! function_exists( 'woocommerce_product_thumbnail_image_size') ) :
function woocommerce_product_thumbnail_image_size( $size ){
 
    $size['width'] = '73';
    $size['height'] = '73';
 
    return $size;
} 
endif;
add_filter( 'woocommerce_get_image_size_product-thumbnail', 'woocommerce_product_thumbnail_image_size' );
add_filter( 'woocommerce_get_image_size_shop_thumbnail', 'woocommerce_product_thumbnail_image_size' );

if( ! function_exists( 'woocommerce_show_brand') ) :
function woocommerce_show_brand( $product_id = false, $return = false ){
	
	global $product;
	
	$product_id = ($product_id) ? $product_id : $product->id;

	$terms = get_the_terms( $product_id, 'product_brand' );
	$on_brand = '';
	if ( $terms && ! is_wp_error( $terms ) ) {
		$brand_links = array();
		foreach ( $terms as $term ) {
			$brand_links[] = $term->name;
		}
		$on_brand = join( ", ", $brand_links );
	}

	$output = $on_brand; //TODO : Add code to store brands 

	if( $return ){
		return $output;
	} else {
		echo '<div class="product-brand">' . $output . '</div>';
	}
}
endif;

if( ! function_exists( 'woocommerce_get_labels') ) :
function woocommerce_get_labels( $product_id = false ) {
	global $product;

	$product_id = ( $product_id ) ? $product_id : $product->id;
	$labels = get_the_terms( $product_id, 'product_label' );
	$product_labels = '';

	if ( $labels && ! is_wp_error( $labels ) ){
		foreach( $labels as $label ){
			$product_labels .= '<div class="ribbon label-' . $label->term_id . '"><span>' . $label->name . '</span></div>';
		}
	}

	return $product_labels;
}
endif;


function media_center_control_bar(){
	wc_get_template( 'loop/control-bar.php' );
	return true;
}

function woocommerce_media_center_hover_area(){
	wc_get_template( 'loop/hover-area.php' );
	return true;
}

// Before Main Content
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

// Before Shop Loop
add_action( 'woocommerce_before_shop_loop', 'media_center_control_bar', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

//Before Shop Loop Item
add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 5 );
add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_thumbnail', 10 );

//Before Shop Loop Item Title
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

//After Shop Loop Item Title
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_show_brand', 10 );

//After Shop Loop Item
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_media_center_hover_area', 20 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
global $yith_woocompare;
if( isset( $yith_woocompare->obj) ){
	remove_action( 'woocommerce_single_product_summary', array( $yith_woocompare->obj , 'add_compare_link' ), 35 );
}

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 5 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_show_brand', 15 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 15 );
add_action( 'woocommerce_single_product_summary', 'media_center_single_product_wish_compare', 16 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 30 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 40 );

// Styling Checkout Fields
add_filter( 'woocommerce_checkout_fields' , 'media_center_override_checkout_fields' );

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );

if( ! function_exists( 'media_center_override_checkout_fields') ) :
function media_center_override_checkout_fields( $fields ) {

	$field_classes = array(
		'order_comments' => array( 'col-xs-12', 'col-sm-12' )
	);

	foreach($fields as $key => $field ){
		foreach( $field as $sub_key => $sub_field ){
			if( !isset($field_classes[$sub_key]) ){
				if( isset( $sub_field['class'] ) && !in_array( 'col-xs-12' , $sub_field['class'] ) ){
					$additional_class = array( 'col-xs-12' );
					if( !in_array( 'form-row-wide', $sub_field['class'] ) ){
						$additional_class[] = 'col-sm-6';
					}
					$fields[$key][$sub_key]['class'] = array_merge( $fields[$key][$sub_key]['class'], $additional_class );
				}
			} else {
				$fields[$key][$sub_key]['class'] = array_merge( $fields[$key][$sub_key]['class'], $field_classes[$sub_key] );
			}
		}
	}

	return $fields;
}
endif;

// Styling Address fields
add_filter( 'woocommerce_default_address_fields' , 'media_center_override_address_fields' );

if( ! function_exists( 'media_center_override_address_fields') ) :
function media_center_override_address_fields( $fields ) {

	$field_classes = array(
		'country' 	 	=> array( 'col-xs-12' ),
		'first_name' 	=> array( 'col-xs-12', 'col-sm-6' ),
		'last_name'		=> array( 'col-xs-12', 'col-sm-6' ),
		'company'		=> array( 'col-xs-12' ),
		'address_1'		=> array( 'col-xs-12', 'col-sm-6' ),
		'address_2' 	=> array( 'col-xs-12', 'col-sm-6' ),
		'city'			=> array( 'col-xs-12', 'col-sm-4' ),
		'state'			=> array( 'col-xs-12', 'col-sm-4' ),
		'postcode'		=> array( 'col-xs-12', 'col-sm-4' ),
	);

	foreach( $fields as $key => $field ){
		if( isset( $field_classes[$key] ) ) {
			$fields[$key]['class'] = array_merge( $fields[$key]['class'], $field_classes[$key]);
		} else {
			$fields[$key]['class'][] = 'col-xs-12';
		}
	}

	$fields['address_2']['label'] = '&nbsp;';

	return $fields;
}
endif;

// WooCommerce Pages

function media_center_woocommerce_pages( $menu = true, $wrap_before = '<ul>', $wrap_after = '</ul>' ){
	$woocommerce_pages = array(
		get_option( 'woocommerce_shop_page_id' ),
		get_option( 'woocommerce_cart_page_id' ),
		get_option( 'woocommerce_checkout_page_id' ),
		get_option( 'woocommerce_terms_page_id' ),
		get_option( 'woocommerce_myaccount_page_id' ),
	);
	$output = '';

	if( $menu ) {
		$output .= $wrap_before;
		foreach( $woocommerce_pages as $woocommerce_page ){
			if ( !empty( $woocommerce_page ) ){
				$output .= '<li><a href="' . get_permalink( $woocommerce_page ) . '">' . get_the_title( $woocommerce_page ) . '</a></li>';
			}
		}
		$output .= $wrap_after;
	} else {
		$output = $woocommerce_pages ;
	}

	return $output;
}

function media_center_single_product_wish_compare(){
	return wc_get_template( 'single-product/wish-compare.php' );
}

add_filter( 'woocommerce_short_description_list_view', 'media_center_wc_short_description', 10, 1 );

if( ! function_exists( 'media_center_wc_short_description') ) :
function media_center_wc_short_description( $excerpt ){
	$new_excerpt_length = 160;
	$new_excerpt = '';

	if( strlen( $excerpt ) < $new_excerpt_length ){
		$new_excerpt = $excerpt;
	}else{
		$new_excerpt = substr( $excerpt, 0 , $new_excerpt_length ) . '...';
	}

	return $new_excerpt;
}
endif;

if( ! function_exists( 'media_center_get_grid_list_tab_pane_classes') ) :
function media_center_get_grid_list_tab_pane_classes() {
	global $media_center_theme_options;

	if( isset( $media_center_theme_options['remember_user_view'] ) && $media_center_theme_options['remember_user_view'] && isset( $_COOKIE['user_shop_view'] ) ){
		if( $_COOKIE['user_shop_view'] == 'list-view' ){
			$list_tab_pane_class = 'active';
			$grid_tab_pane_class = '';
		} else {
			$list_tab_pane_class = '';
			$grid_tab_pane_class = 'active';
		}
	} else {
		if( isset( $media_center_theme_options['shop_default_view'] ) && $media_center_theme_options['shop_default_view'] == 'list-view' ) {
			$list_tab_pane_class = 'active';
			$grid_tab_pane_class = '';
		} else {
			$list_tab_pane_class = '';
			$grid_tab_pane_class = 'active';
		}
	}

	return array( 'list_tab_pane_class' => $list_tab_pane_class, 'grid_tab_pane_class' => $grid_tab_pane_class );
}
endif;

if( ! function_exists( 'media_center_get_wishlist_url') ) :
function media_center_get_wishlist_url(){
	$wishlist_page_id = get_option( 'yith_wcwl_wishlist_page_id' );
	
	if(function_exists('icl_object_id')) {
		$wishlist_page_id = icl_object_id( $wishlist_page_id,'page' );
	}
	
	return get_permalink( $wishlist_page_id );
}
endif;


if( ! function_exists( 'media_center_get_compare_url') ) :
function media_center_get_compare_url(){
	global $media_center_theme_options;
	$compare_page_id = $media_center_theme_options['product_comparison_page'];
	
	if(function_exists('icl_object_id')) {
		$compare_page_id = icl_object_id( $compare_page_id,'page' );
	}
	return get_permalink( $compare_page_id );
}
endif;

function woocommerce_button_proceed_to_checkout() {
$checkout_url = WC()->cart->get_checkout_url(); ?>
	<a href="<?php echo $checkout_url; ?>" class="btn-block le-button big checkout-button button alt wc-forward text-center"><?php _e( 'Proceed to Checkout', 'media_center' ); ?></a>
<?php
}

if ( ! function_exists( 'mediacenter_breadcrumb' ) ) {
    function mediacenter_breadcrumb( $args = array() ) {
        $args = wp_parse_args( $args, apply_filters( 'woocommerce_breadcrumb_defaults', array(
            'delimiter'   => ' &#47; ',
            'wrap_before' => '<nav class="woocommerce-breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>',
            'wrap_after'  => '</nav>',
            'before'      => '',
            'after'       => '',
            'home'        => _x( 'Home', 'breadcrumb', 'media_center' )
        ) ) );

        $breadcrumbs = new WC_Breadcrumb();

        if ( $args['home'] ) {
            $breadcrumbs->add_crumb( $args['home'], home_url() );
        }

        $args['breadcrumb'] = $breadcrumbs->generate();

        wc_get_template( 'framework/inc/breadcrumb.php', $args );
    }
}

if( !function_exists( 'media_center_list_signup_benefits' ) ) {
	function media_center_list_signup_benefits() {
		$benefits = apply_filters( 'mc_signup_benefits', array(
			__( 'Speed your way through the checkout', 'media_center' ),
			__( 'Track your orders easily', 'media_center' ),
			__( 'Keep a record of all your purchases', 'media_center' )
		) );
	?>
	<section class="inner-left-xs section why-register inner-top-xs">
		<h2 class="semi-bold bordered"><?php echo __( 'Sign up today and you\'ll be able to :', 'media_center' ); ?></h2>
		<ul class="list-unstyled list-benefits">
			<?php foreach( $benefits as $benefit ) : ?>
			<li><i class="fa fa-check primary-color"></i> <?php echo $benefit; ?></li>
			<?php endforeach; ?>
		</ul>
	</section><!-- /.why-register -->
	<?php		
	}
}

if( !function_exists( 'add_star_rating_to_grid_title' ) ) {
	function add_star_rating_to_grid_title( $title ) {
		global $media_center_theme_options;
		$star_rating = '';
		if( isset( $media_center_theme_options['show_rating_in_grid'] ) && $media_center_theme_options['show_rating_in_grid'] === '1' ) :
			ob_start();
	?>
	        <div class="star-holder clearfix star-holder-grid">
	        <?php wc_get_template( 'loop/rating.php' ) ; ?>
	        </div>
	<?php
			$star_rating = ob_get_contents();
			ob_end_clean();
		endif;
		return $title. $star_rating;
	}
}

add_filter( 'product_title_grid_view', 'add_star_rating_to_grid_title' );