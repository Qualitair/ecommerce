<?php
/**
 * Login form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( is_user_logged_in() ) 
	return;
?>
<form method="post" class="login login-form inner-bottom-xs" <?php if ( $hidden ) echo 'style="display:none;"'; ?>>

	<?php do_action( 'woocommerce_login_form_start' ); ?>

	<?php if ( $message ) echo wpautop( wptexturize( $message ) ); ?>

	<div class="field-row row">
		<div class="col-md-6">
			<p class="form-row form-row-first">
				<label for="username"><?php _e( 'Username or email', 'media_center' ); ?> <span class="required">*</span></label>
				<input type="text" class="le-input input-text" name="username" id="username" />
			</p>		
		</div>
		<div class="col-md-6">
			<p class="form-row form-row-last">
				<label for="password"><?php _e( 'Password', 'media_center' ); ?> <span class="required">*</span></label>
				<input class="le-input input-text" type="password" name="password" id="password" />
			</p>
		</div>
	</div>

	<div class="field-row clearfix">
		<span class="pull-left">
    		<label for="rememberme" class="inline content-color">
				<input name="rememberme" class="le-checbox auto-width inline" type="checkbox" id="rememberme" value="forever" /> <span class="bold"><?php _e( 'Remember me', 'media_center' ); ?></span>
			</label>
    	</span>
    	<span class="pull-right">
    		<a class="content-color bold" href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php _e( 'Lost your password?', 'media_center' ); ?></a>
    	</span>
	</div>

	<?php do_action( 'woocommerce_login_form' ); ?>
	<div class="buttons-holder">
		<?php wp_nonce_field( 'woocommerce-login' ); ?>
		<input type="submit" class="le-button button outer-right-xs" name="login" value="<?php _e( 'Login', 'media_center' ); ?>" />
		<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ) ?>" />
	</div>

	<?php do_action( 'woocommerce_login_form_end' ); ?>

</form>