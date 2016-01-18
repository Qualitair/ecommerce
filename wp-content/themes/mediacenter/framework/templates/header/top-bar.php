<?php
/**
 * Top Bar
 *
 * @author      Ibrahim Ibn Dawood
 * @package     Framework/Templates
 * @version     1.0.6
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $media_center_theme_options;

$top_bar_left_switch = isset( $media_center_theme_options['top_bar_left_switch'] ) ? $media_center_theme_options['top_bar_left_switch'] : true;
$top_bar_right_switch = isset( $media_center_theme_options['top_bar_right_switch'] ) ? $media_center_theme_options['top_bar_right_switch'] : true;
$hide_top_bar_on_mobile = isset( $media_center_theme_options['hide_top_bar_on_mobile'] ) && $media_center_theme_options['hide_top_bar_on_mobile'] === '1' ? true : false;
$top_bar_class = 'top-bar';
if( $hide_top_bar_on_mobile ) {
    $top_bar_class .= ' hidden-xs';
}
?>

<nav class="<?php echo esc_attr( $top_bar_class ); ?>">
    <div class="container">
        <div class="col-xs-12 col-sm-6 no-margin <?php if( $media_center_theme_options['top_bar_left_menu_dropdown_animation'] != 'none' ) { echo 'animate-dropdown'; } ?>">
        <?php 
            if( $top_bar_left_switch ) {
                echo media_center_top_left_nav_menu();
            }
        ?>
        </div><!-- /.col -->
        
        
        <div class="col-xs-12 col-sm-6 no-margin <?php if( $media_center_theme_options['top_bar_right_menu_dropdown_animation'] != 'none' ) { echo 'animate-dropdown'; } ?>">
        <?php 
            if( $top_bar_right_switch ) {
                echo media_center_top_right_nav_menu();
            }
        ?>
        </div><!-- /.col -->
    </div><!-- /.container -->
</nav><!-- /.top-bar -->