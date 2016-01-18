<?php
/**
 * Brands Filter Widget
 *
 * @author 		Ibrahim Ibn Dawood
 * @category 	Widgets
 * @package 	MediaCenter/Framework/Widgets
 * @version 	1.0.6
 * @extends 	WC_Widget
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class MC_Widget_Brands_Filter extends WC_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'woocommerce widget_brands_filter';
		$this->widget_description = __( 'Shows brands in a widget which lets you narrow down the list of products when viewing product categories.', 'media_center' );
		$this->widget_id          = 'mc_brands_filter';
		$this->widget_name        = __( 'MediaCenter Brands Filter', 'media_center' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => __( 'Filter by', 'media_center' ),
				'label' => __( 'Title', 'media_center' )
			),
			'display_type' => array(
				'type'  => 'select',
				'std'   => 'list',
				'label' => __( 'Display type', 'media_center' ),
				'options' => array(
					'list'      => __( 'List', 'media_center' ),
					'dropdown'  => __( 'Dropdown', 'media_center' )
				)
			),
			'query_type' => array(
				'type'  => 'select',
				'std'   => 'and',
				'label' => __( 'Query type', 'media_center' ),
				'options' => array(
					'and' => __( 'AND', 'media_center' ),
					'or'  => __( 'OR', 'media_center' )
				)
			),
		);

		parent::__construct();
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	public function widget( $args, $instance ) {
		
		global $_chosen_brands;

		extract( $args );

		//if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) )
			//return;

		$current_term 	= is_tax() ? get_queried_object()->term_id : '';
		$current_tax 	= is_tax() ? get_queried_object()->taxonomy : '';
		$title 			= apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		$taxonomy 		= 'product_brand';
		$query_type 	= isset( $instance['query_type'] ) ? $instance['query_type'] : 'and';
		$display_type 	= isset( $instance['display_type'] ) ? $instance['display_type'] : 'list';

		//echo '<pre>' . print_r( $taxonomy )

		if ( ! taxonomy_exists( $taxonomy ) )
			return;

	    $get_terms_args = array( 'hide_empty' => '1' );

		$orderby = wc_attribute_orderby( $taxonomy );

		switch ( $orderby ) {
			case 'name' :
				$get_terms_args['orderby']    = 'name';
				$get_terms_args['menu_order'] = false;
			break;
			case 'id' :
				$get_terms_args['orderby']    = 'id';
				$get_terms_args['order']      = 'ASC';
				$get_terms_args['menu_order'] = false;
			break;
			case 'menu_order' :
				$get_terms_args['menu_order'] = 'ASC';
			break;
		}

		$terms = get_terms( $taxonomy, $get_terms_args );

		if ( count( $terms ) > 0 ) {

			ob_start();

			$found = false;

			echo $before_widget . $before_title . $title . $after_title;

			// Force found when option is selected - do not force found on taxonomy attributes
			if ( ! is_tax() && is_array( $_chosen_brands ) && array_key_exists( $taxonomy, $_chosen_brands ) )
				$found = true;

			if ( $display_type == 'dropdown' ) {

				// skip when viewing the taxonomy
				if ( $current_tax && $taxonomy == $current_tax ) {

					$found = false;

				} else {

					$taxonomy_filter = str_replace( 'pa_', '', $taxonomy );

					$found = false;

					echo '<select class="dropdown_layered_nav_' . $taxonomy_filter . '">';

					echo '<option value="">' . sprintf( __( 'Any %s', 'media_center' ), wc_attribute_label( $taxonomy ) ) .'</option>';

					foreach ( $terms as $term ) {

						// If on a term page, skip that term in widget list
						if ( $term->term_id == $current_term )
							continue;

						// Get count based on current view - uses transients
						$transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_taxonomy_id ) );

						if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {

							$_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );

							set_transient( $transient_name, $_products_in_term, YEAR_IN_SECONDS );
						}

						$option_is_set = ( isset( $_chosen_brands[ $taxonomy ] ) && in_array( $term->term_id, $_chosen_brands[ $taxonomy ]['terms'] ) );

						// If this is an AND query, only show options with count > 0
						if ( $query_type == 'and' ) {

							$count = sizeof( array_intersect( $_products_in_term, WC()->query->filtered_product_ids ) );

							if ( $count > 0 )
								$found = true;

							if ( $count == 0 && ! $option_is_set )
								continue;

						// If this is an OR query, show all options so search can be expanded
						} else {

							$count = sizeof( array_intersect( $_products_in_term, WC()->query->unfiltered_product_ids ) );

							if ( $count > 0 )
								$found = true;

						}

						echo '<option value="' . esc_attr( $term->term_id ) . '" '.selected( isset( $_GET[ 'filter_' . $taxonomy_filter ] ) ? $_GET[ 'filter_' .$taxonomy_filter ] : '' , $term->term_id, false ) . '>' . $term->name . '</option>';
					}

					echo '</select>';

					wc_enqueue_js("

						jQuery('.dropdown_layered_nav_$taxonomy_filter').change(function(){

							location.href = '" . esc_url_raw( preg_replace( '%\/page/[0-9]+%', '', add_query_arg('filtering', '1', remove_query_arg( array( 'page', 'filter_' . $taxonomy_filter ) ) ) ) ) . "&filter_$taxonomy_filter=' + jQuery(this).val();

						});

					");

				}

			} else {

				// List display
				echo "<ul>";

				foreach ( $terms as $term ) {

					// Get count based on current view - uses transients
					$transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_taxonomy_id ) );

					if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {

						$_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );

						set_transient( $transient_name, $_products_in_term );
					}

					$option_is_set = ( isset( $_chosen_brands[ $taxonomy ] ) && in_array( $term->term_id, $_chosen_brands[ $taxonomy ]['terms'] ) );

					// skip the term for the current archive
					if ( $current_term == $term->term_id )
						continue;

					// If this is an AND query, only show options with count > 0
					if ( $query_type == 'and' ) {

						$count = sizeof( array_intersect( $_products_in_term, WC()->query->filtered_product_ids ) );

						if ( $count > 0 && $current_term !== $term->term_id )
							$found = true;

						if ( $count == 0 && ! $option_is_set )
							continue;

					// If this is an OR query, show all options so search can be expanded
					} else {

							$count = sizeof( array_intersect( $_products_in_term, WC()->query->unfiltered_product_ids ) );

							if ( $count > 0 )
								$found = true;

					}

					$arg = 'filter_' . sanitize_title( 'product_brand' );

					$current_filter = ( isset( $_GET[ $arg ] ) ) ? explode( ',', $_GET[ $arg ] ) : array();

					if ( ! is_array( $current_filter ) )
						$current_filter = array();

					$current_filter = array_map( 'esc_attr', $current_filter );

					if ( ! in_array( $term->term_id, $current_filter ) )
						$current_filter[] = $term->term_id;

					// Base Link decided by current page
					if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
						$link = home_url();
					} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id('shop') ) ) {
						$link = get_post_type_archive_link( 'product' );
					} else {
						$link = get_term_link( get_query_var('term'), get_query_var('taxonomy') );
					}

					// All current filters
					if ( $_chosen_brands ) {
						foreach ( $_chosen_brands as $name => $data ) {
							if ( $name !== $taxonomy ) {

								// Exclude query arg for current term archive term
								while ( in_array( $current_term, $data['terms'] ) ) {
									$key = array_search( $current_term, $data );
									unset( $data['terms'][$key] );
								}

								// Remove pa_ and sanitize
								$filter_name = sanitize_title( str_replace( 'pa_', '', $name ) );

								if ( ! empty( $data['terms'] ) )
									$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );

								if ( $data['query_type'] == 'or' )
									$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
							}
						}
					}

					// Min/Max
					if ( isset( $_GET['min_price'] ) )
						$link = add_query_arg( 'min_price', $_GET['min_price'], $link );

					if ( isset( $_GET['max_price'] ) )
						$link = add_query_arg( 'max_price', $_GET['max_price'], $link );

					// Orderby
					if ( isset( $_GET['orderby'] ) )
						$link = add_query_arg( 'orderby', $_GET['orderby'], $link );

					// Current Filter = this widget
					if ( isset( $_chosen_brands[ $taxonomy ] ) && is_array( $_chosen_brands[ $taxonomy ]['terms'] ) && in_array( $term->term_id, $_chosen_brands[ $taxonomy ]['terms'] ) ) {

						$class = 'class="chosen"';

						// Remove this term is $current_filter has more than 1 term filtered
						if ( sizeof( $current_filter ) > 1 ) {
							$current_filter_without_this = array_diff( $current_filter, array( $term->term_id ) );
							$link = add_query_arg( $arg, implode( ',', $current_filter_without_this ), $link );
						}

					} else {

						$class = '';
						$link = add_query_arg( $arg, implode( ',', $current_filter ), $link );

					}

					// Search Arg
					if ( get_search_query() )
						$link = add_query_arg( 's', get_search_query(), $link );

					// Post Type Arg
					if ( isset( $_GET['post_type'] ) )
						$link = add_query_arg( 'post_type', $_GET['post_type'], $link );

					// Query type Arg
					if ( $query_type == 'or' && ! ( sizeof( $current_filter ) == 1 && isset( $_chosen_brands[ $taxonomy ]['terms'] ) && is_array( $_chosen_brands[ $taxonomy ]['terms'] ) && in_array( $term->term_id, $_chosen_brands[ $taxonomy ]['terms'] ) ) )
						$link = add_query_arg( 'query_type_' . sanitize_title( 'product_brand' ), 'or', $link );

					echo '<li ' . $class . '>';

					echo ( $count > 0 || $option_is_set ) ? '<a href="' . esc_url( apply_filters( 'woocommerce_layered_nav_link', $link ) ) . '">' : '<span>';

					echo $term->name;

					echo ( $count > 0 || $option_is_set ) ? '</a>' : '</span>';

					echo ' <small class="count">' . $count . '</small></li>';

				}

				echo "</ul>";

			} // End display type conditional

			echo $after_widget;

			if ( ! $found )
				ob_end_clean();
			else
				echo ob_get_clean();
		}
	}
}

register_widget( 'MC_Widget_Brands_Filter' );

function product_brands_filter_query( $filtered_posts ) {
	
	if ( is_active_widget( false, false, 'mc_brands_filter', true ) && ! is_admin() ) {

		global $_chosen_brands;

		$_chosen_brands = array();

		if ( ! empty( $_GET[ 'filter_product_brand' ] ) && taxonomy_exists( 'product_brand' ) ) {

    		$_chosen_brands[ 'product_brand' ]['terms'] = explode( ',', $_GET[ 'filter_product_brand' ] );

    		if ( empty( $_GET[ 'query_type_product_brand' ] ) || ! in_array( strtolower( $_GET[ 'query_type_product_brand' ] ), array( 'and', 'or' ) ) )
    			$_chosen_brands[ 'product_brand' ]['query_type'] = 'and';
    		else
    			$_chosen_brands[ 'product_brand' ]['query_type'] = strtolower( $_GET[ 'query_type_product_brand' ] );

		}

		if ( sizeof( $_chosen_brands ) > 0 ) {

			$matched_products   = array(
				'and' => array(),
				'or'  => array()
			);
			$filtered_attribute = array(
				'and' => false,
				'or'  => false
			);

			foreach ( $_chosen_brands as $brand => $data ) {
				$matched_products_for_brand = array();
				$filtered = false;

				if ( sizeof( $data['terms'] ) > 0 ) {
					foreach ( $data['terms'] as $value ) {

						$posts = get_posts(
							array(
								'post_type' 	=> 'product',
								'numberposts' 	=> -1,
								'post_status' 	=> 'publish',
								'fields' 		=> 'ids',
								'no_found_rows' => true,
								'tax_query' => array(
									array(
										'taxonomy' 	=> $brand,
										'terms' 	=> $value,
										'field' 	=> 'term_id'
									)
								)
							)
						);

						if ( ! is_wp_error( $posts ) ) {

							if ( sizeof( $matched_products_for_brand ) > 0 || $filtered )
								$matched_products_for_brand = $data['query_type'] == 'or' ? array_merge( $posts, $matched_products_for_brand ) : array_intersect( $posts, $matched_products_for_brand );
							else
								$matched_products_for_brand = $posts;

							$filtered = true;
						}
					}
				}

				if ( sizeof( $matched_products[ $data['query_type'] ] ) > 0 || $filtered_attribute[ $data['query_type'] ] === true ) {
					$matched_products[ $data['query_type'] ] = ( $data['query_type'] == 'or' ) ? array_merge( $matched_products_for_brand, $matched_products[ $data['query_type'] ] ) : array_intersect( $matched_products_for_brand, $matched_products[ $data['query_type'] ] );
				} else {
					$matched_products[ $data['query_type'] ] = $matched_products_for_brand;
				}

				$filtered_attribute[ $data['query_type'] ] = true;
			}

			// Combine our AND and OR result sets
			if ( $filtered_attribute['and'] && $filtered_attribute['or'] )
				$results = array_intersect( $matched_products[ 'and' ], $matched_products[ 'or' ] );
			else
				$results = array_merge( $matched_products[ 'and' ], $matched_products[ 'or' ] );

			if ( $filtered ) {

				WC()->query->layered_nav_post__in   = $results;
				WC()->query->layered_nav_post__in[] = 0;

				if ( sizeof( $filtered_posts ) == 0 ) {
					$filtered_posts   = $results;
					$filtered_posts[] = 0;
				} else {
					$filtered_posts   = array_intersect( $filtered_posts, $results );
					$filtered_posts[] = 0;
				}

			}
		}
	}
	
	return (array) $filtered_posts;
}

add_filter('loop_shop_post_in', 'product_brands_filter_query');