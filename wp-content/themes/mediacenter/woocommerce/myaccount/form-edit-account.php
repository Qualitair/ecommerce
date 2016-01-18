<?php
/**
 * Edit account form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.7
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="row">
	<div class="col-lg-8 center-block">
		
		<?php wc_print_notices(); ?>

		<section class="section edit-account">
			<h3><?php _e( 'Edit Account', 'media_center' );?></h3>

			<form action="" method="post" class="cf-style-1">
				
				<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

				<div class="row field-row">
					<div class="col-xs-12 col-sm-6">
						<p class="form-row form-row-first">
							<label for="account_first_name"><?php _e( 'First name', 'media_center' ); ?> <span class="required">*</span></label>
							<input type="text" class="le-input input-text" name="account_first_name" id="account_first_name" value="<?php echo esc_attr( $user->first_name ); ?>" />
						</p>		
					</div>
					<div class="col-xs-12 col-sm-6">
						<p class="form-row form-row-last">
							<label for="account_last_name"><?php _e( 'Last name', 'media_center' ); ?> <span class="required">*</span></label>
							<input type="text" class="le-input input-text" name="account_last_name" id="account_last_name" value="<?php echo esc_attr( $user->last_name ); ?>" />
						</p>		
					</div>
				</div><!-- /.field-row -->

				<div class="row field-row">
					<div class="col-xs-12">
						<p class="form-row form-row-wide">
							<label for="account_email"><?php _e( 'Email address', 'media_center' ); ?> <span class="required">*</span></label>
							<input type="email" class="le-input input-text" name="account_email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />
						</p>		
					</div>
				</div><!-- /.field-row -->

				<h3><?php _e( 'Password Change', 'media_center' ); ?></h3>

				<div class="row field-row">
					<div class="col-xs-12 col-sm-12">
						<p class="form-row form-row-wide">
							<label for="password_current"><?php _e( 'Current Password (leave blank to leave unchanged)', 'media_center' ); ?></label>
							<input type="password" class="le-input input-text" name="password_current" id="password_current" />
						</p>
					</div>
				</div><!-- /.field-row -->
				<div class="row field-row">
					<div class="col-xs-12 col-sm-6">
						<p class="form-row form-row-first">
							<label for="password_1"><?php _e( 'New Password (leave blank to leave unchanged)', 'media_center' ); ?></label>
							<input type="password" class="le-input input-text" name="password_1" id="password_1" />
						</p>		
					</div>
					<div class="col-xs-12 col-sm-6">
						<p class="form-row form-row-last">
							<label for="password_2"><?php _e( 'Confirm new password', 'media_center' ); ?></label>
							<input type="password" class="le-input input-text" name="password_2" id="password_2" />
						</p>
					</div>
				</div><!-- /.field-row -->
				
				<div class="clear"></div>

				<?php do_action( 'woocommerce_edit_account_form' ); ?>

				<p>
					<?php wp_nonce_field( 'save_account_details' ); ?>
					<input type="submit" class="le-button huge button" name="save_account_details" value="<?php _e( 'Save changes', 'media_center' ); ?>" />
					<input type="hidden" name="action" value="save_account_details" />
				</p>

				<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
			</form>

			<?php wc_get_template( 'myaccount/btn-goback-myaccount.php' ); ?>

		</section><!-- /.edit-account -->
	</div><!-- /.col-lg-8 -->
</div>