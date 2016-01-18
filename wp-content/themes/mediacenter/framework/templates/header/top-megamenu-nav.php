<?php
/**
 * Top Megamenu Nav
 *
 * @author      Ibrahim Ibn Dawood
 * @package     Framework/Templates
 * @version     1.0.6
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $media_center_theme_options;
?>

<!-- ========================================= NAVIGATION ========================================= -->
<nav id="top-megamenu-nav" class="megamenu-vertical <?php if( $media_center_theme_options['shop_by_departments_menu_dropdown_animation'] != 'none' ) { echo 'animate-dropdown'; } ?>">
    <div class="container">
        <div class="yamm navbar header-1-primary-navbar">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mc-horizontal-menu-collapse">
                    <span class="sr-only"><?php echo __( 'Toggle navigation', 'media_center' ); ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div><!-- /.navbar-header -->
            <div class="collapse navbar-collapse" id="mc-horizontal-menu-collapse">
                <?php echo media_center_primary_nav_menu(); ?>          
            </div><!-- /.navbar-collapse -->
        </div><!-- /.navbar -->
    </div><!-- /.container -->
</nav>
<!-- ========================================= NAVIGATION : END ========================================= -->