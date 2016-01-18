<?php

if ( !function_exists( 'shortcode_mc_products_carousel' ) ):


function shortcode_mc_products_carousel( $atts, $content = null ){

	global $woocommerce_loop;

	$woocommerce_loop['is_carousel'] = true;

	extract(shortcode_atts(array(
		'title' 			=> '',
  		'shortcode_name' 	=> '',
		'ids' 				=> '',
		'skus' 				=> '',
		'category' 			=> '',
  		'orderby' 			=> '',
  		'order' 			=> 'desc',
     	'per_page' 			=> '12',
     	'columns' 			=> '6',
     	'el_class' 			=> '',
 		'container_class' 	=> 'container'
    ), $atts));

    $element = 'mc_products_carousel';

    $css_class = trim( $element . ' ' . $el_class );

	$output = '';
	
	$output .= "\n\t" . '<div class="' . $css_class . '">';

	$shortcode_attributes = '';

	switch( $shortcode_name ){
		case 'products_ids':
			$shortcode_attributes .= ' ids="'. $ids . '"  columns="' . $columns . '" ';
			$shortcode_name = 'products';
		break;
		case 'products_skus':
			$shortcode_attributes .= ' skus="'. $skus . '"  columns="' . $columns . '" ';
			$shortcode_name = 'products';
		break;
		case 'product_category':
			$shortcode_attributes .= ' category="' . $category . '" per_page="' . $per_page . '" columns="' . $columns . '" ';
		break;
		case 'recent_products':
		case 'featured_products':
		case 'best_selling_products':
		case 'sale_products':
		case 'top_rated_products':
			$shortcode_attributes .= ' per_page="' . $per_page . '" columns="' . $columns . '" ';
		break;
	}

	if( $shortcode_name != 'best_selling_products'){
		$shortcode_attributes .= ' orderby="' . $orderby .'" order="' . $order . '" ';
	}

	$shortcode_attributes .= ' carousel="true" ';

	$new_shortcode = '[' . $shortcode_name . ' ' . $shortcode_attributes . ']';

	$carouselID = uniqid();

	$woocommerce_loop['carousel_id'] = $carouselID;

	$output .= "\n\t\t" . '<section id="section-' . $carouselID . '" class="inner-top-xs">';
	$output .= '<div class="' . $container_class . '">';
	$output .= "\n\t\t\t" . '<div class="carousel-holder hover">';
	$output .= "\n\t\t\t\t" . '<div class="title-nav">';
	$output .= "\n\t\t\t\t\t" . '<h2 class="h1">' . $title . '</h2>';
	$output .= "\n\t\t\t\t\t" . '<div class="nav-holder">';
	$output .= "\n\t\t\t\t\t\t" . '<a href="#prev" data-target="#' . $carouselID . '" class="slider-prev btn-prev fa fa-angle-left"></a>';
	$output .= "\n\t\t\t\t\t\t" . '<a href="#next" data-target="#' . $carouselID . '" class="slider-next btn-next fa fa-angle-right"></a>';
	$output .= "\n\t\t\t\t\t" . '</div>';
	$output .= "\n\t\t\t\t" . '</div>';
	$output .= "\n\t\t\t" . wpb_js_remove_wpautop( $new_shortcode );
	$output .= '</div>';
	$output .= "\n\t\t\t" . '</div>';
	$output .= "\n\t\t" . '</section>';
	
	$output .= "\n\t" . '</div>';

	return $output;
}

add_shortcode( 'mc_products_carousel' , 'shortcode_mc_products_carousel' );
endif;