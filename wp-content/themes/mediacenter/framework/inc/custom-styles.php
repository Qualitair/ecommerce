<?php
if ( !function_exists ('media_center_custom_styles') ) :
	
function media_center_custom_styles() {
	global $media_center_theme_options;
	$default_font_family = isset( $media_center_theme_options['default_font'] ) ? $media_center_theme_options[ 'default_font' ] : '\'Open Sans\', sans-serif;';
	$title_font_family = isset( $media_center_theme_options['title_font'] ) ? $media_center_theme_options[ 'title_font' ] : '\'Open Sans\', sans-serif;';
?>

<!-- ===================== Theme Options Styles ===================== -->
		
<style type="text/css">

/* Typography */

h1, .h1,
h2, .h2,
h3, .h3,
h4, .h4,
h5, .h5,
h6, .h6{
	font-family: <?php echo $title_font_family['font-family']; ?>;
}

body{
	font-family: <?php echo $default_font_family['font-family']; ?>;
}

/* Animation */

<?php if ( $media_center_theme_options['top_bar_left_menu_dropdown_animation'] != 'none' ): ?>
.top-left .open > .dropdown-menu,
.top-left .open > .dropdown-menu > .dropdown-submenu > .dropdown-menu {
  animation-name: <?php echo $media_center_theme_options['top_bar_left_menu_dropdown_animation']; ?>;
}
<?php endif; ?>

<?php if ( $media_center_theme_options['top_bar_right_menu_dropdown_animation'] != 'none' ): ?>
.top-right .open > .dropdown-menu,
.top-right .open > .dropdown-menu > .dropdown-submenu > .dropdown-menu {
  animation-name: <?php echo $media_center_theme_options['top_bar_right_menu_dropdown_animation']; ?>;
}
<?php endif; ?>

<?php if ( $media_center_theme_options['main_navigation_menu_dropdown_animation'] != 'none' ): ?>
#top-megamenu-nav .open > .dropdown-menu,
#top-megamenu-nav .open > .dropdown-menu > .dropdown-submenu > .dropdown-menu {
  animation-name: <?php echo $media_center_theme_options['main_navigation_menu_dropdown_animation']; ?>;
}
<?php endif; ?>

<?php if ( $media_center_theme_options['shop_by_departments_menu_dropdown_animation'] != 'none' ): ?>
#top-mega-nav .open > .dropdown-menu,
#top-mega-nav .open > .dropdown-menu > .dropdown-submenu > .dropdown-menu {
  animation-name: <?php echo $media_center_theme_options['shop_by_departments_menu_dropdown_animation']; ?>;
}
<?php endif; ?>

/* Product Labels */

<?php
	$product_labels = get_categories( array( 'taxonomy' => 'product_label') );
	$product_label_css = '';
	if ( $product_labels && ! is_wp_error( $product_labels ) && empty( $product_labels['errors'] ) ){
		foreach( $product_labels as $product_label ){
			if( isset( $product_label->term_id ) ){
				$background_color = get_woocommerce_term_meta( $product_label->term_id , 'background_color', true );
				$text_color = get_woocommerce_term_meta( $product_label->term_id , 'text_color', true );

				$product_label_css .= '.label-' . $product_label->term_id . ' > span {';
				$product_label_css .= 'color: '. $text_color . ';';
				$product_label_css .= '}';

				$product_label_css .= '.label-' .$product_label->term_id . '.ribbon:after {';
				$product_label_css .= 'border-top-color: '. $background_color . ';';
				$product_label_css .= '}';
			}
		}
	}
	echo $product_label_css;
?>
/* Custom CSS */
<?php 
if ( ( isset( $media_center_theme_options['custom_css'] ) ) && ( trim( $media_center_theme_options['custom_css'] ) != "" ) ) {
	echo $media_center_theme_options['custom_css'];
}
?>

</style>
<!-- ===================== Theme Options Styles : END ===================== -->

<?php
}

add_action( 'wp_head', 'media_center_custom_styles', 100 );
endif;