<?php
/**
 * Change password form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;
?>

<div class="row">
	<div class="col-lg-8 center-block">
		
		<?php wc_print_notices(); ?>

		<section class="section change-password">
			<h3><?php _e('Change Password', 'media_center');?></h3>

			<form action="<?php echo esc_url( get_permalink( wc_get_page_id( 'change_password' ) ) ); ?>" method="post">

				<div class="row field-row form-row form-row-first">	
					<div class="col-xs-12">
						<label for="password_current"><?php _e( 'Current Password (leave blank to leave unchanged)', 'media_center' ); ?></label>
						<input type="password" class="input-text" name="password_current" id="password_current" />
					</div>
				</div>

				<div class="row field-row">
					<div class="col-xs-12 col-sm-6 form-row-first">
						<label for="password_1"><?php _e( 'New password', 'media_center' ); ?> <span class="required">*</span></label>
						<input type="password" class="le-input input-text" name="password_1" id="password_1" />
					</div>

					<div class="col-xs-12 col-sm-6 form-row-last">
						<label for="password_2"><?php _e( 'Re-enter new password', 'media_center' ); ?> <span class="required">*</span></label>
						<input type="password" class="le-input input-text" name="password_2" id="password_2" />
					</div>
				</div>

				<div class="row field-row">
					<input type="submit" class="le-button button" name="change_password" value="<?php _e( 'Save', 'media_center' ); ?>" />
				</div>

				<?php wp_nonce_field( 'woocommerce-change_password' ); ?>

				<input type="hidden" name="action" value="change_password" />

				<hr/>
				<a href="<?php echo get_permalink( get_option('woocommerce_login_page_id') ); ?>" title="<?php _e('Login','media_center'); ?>"><i class="fa fa-long-arrow-left"></i> <?php _e('Go to Login Page','media_center'); ?></a>

			</form>
		</section><!-- /.change-password -->

	</div><!-- /.center-block -->
</div><!-- /.row -->