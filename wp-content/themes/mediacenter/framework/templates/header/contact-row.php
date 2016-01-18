<?php
/**
 * Contact Row
 *
 * @author      Ibrahim Ibn Dawood
 * @package     Framework/Templates
 * @version     1.0.6
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $media_center_theme_options;
?>

<div class="contact-row">
    <?php if( !empty( $media_center_theme_options['header_support_phone'] ) ) : ?>
    <div class="phone inline">
        <i class="fa fa-phone"></i> <?php echo $media_center_theme_options['header_support_phone']; ?>
    </div>
    <?php endif; ?>
    <?php if( !empty( $media_center_theme_options['header_support_email'] ) ) : ?>
    <div class="contact inline">
        <i class="fa fa-envelope"></i> <?php echo $media_center_theme_options['header_support_email']; ?>
    </div>
    <?php endif; ?>
</div><!-- /.contact-row -->