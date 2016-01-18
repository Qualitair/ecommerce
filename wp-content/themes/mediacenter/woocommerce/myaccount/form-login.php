<?php
/**
 * Login Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.6
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<?php wc_print_notices(); ?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php 
if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ){
	$registerSectionClass = 'col-md-6';
	$loginSectionClass = 'col-md-6';
	$showRegister = true;
}else{
	$loginSectionClass = 'center-block col-md-6';
	$showRegister = false;
}
?>

<div id="authentication">
	<div class="row">

		<div class="<?php echo $loginSectionClass;?>">
			<section class="section sign-in inner-right-xs">
				<h2 class="bordered"><?php _e( 'Login', 'media_center' ); ?></h2>
				<p><?php _e( 'Hello, Welcome to your account', 'media_center' );?></p>

				<form method="post" class="login login-form cf-style-1" role="form">

					<?php do_action( 'woocommerce_login_form_start' ); ?>

					<div class="field-row form-row form-row-wide">
						<label for="username"><?php _e( 'Username or email address', 'media_center' ); ?> <span class="required">*</span></label>
						<input type="text" class="le-input input-text" name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
					</div>

					<div class="field-row form-row form-row-wide">
						<label for="password"><?php _e( 'Password', 'media_center' ); ?> <span class="required">*</span></label>
						<input class="le-input input-text" type="password" name="password" id="password" />
					</div>

					<?php do_action( 'woocommerce_login_form' ); ?>
					
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


					<div class="buttons-holder">
						<?php wp_nonce_field( 'woocommerce-login' ); ?>
						<input type="submit" class="le-button huge button" name="login" value="<?php _e( 'Login', 'media_center' ); ?>" /> 
					</div>


					<?php do_action( 'woocommerce_login_form_end' ); ?>

				</form>

			</section><!-- /.sign-in -->
		</div><!-- /.col -->

		<?php if($showRegister) : ?>

		<div class="<?php echo $registerSectionClass;?>">
			
			<section class="section register inner-left-xs">
				
				<h2 class="bordered"><?php _e( 'Register', 'media_center' ); ?></h2>
				<p><?php _e( 'Create your own account', 'media_center' ); ?></p>

				<form method="post" class="register cf-style-1" role="form">

					<?php do_action( 'woocommerce_register_form_start' ); ?>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

						<div class="field-row form-row form-row-wide">
							<label for="reg_username"><?php _e( 'Username', 'media_center' ); ?> <span class="required">*</span></label>
							<input type="text" class="le-input input-text" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
						</div>

					<?php endif; ?>

					<div class="field-row form-row form-row-wide">
						<label for="reg_email"><?php _e( 'Email address', 'media_center' ); ?> <span class="required">*</span></label>
						<input type="email" class="le-input input-text" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" />
					</div>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
			
						<div class="field-row form-row form-row-wide">
							<label for="reg_password"><?php _e( 'Password', 'media_center' ); ?> <span class="required">*</span></label>
							<input type="password" class="le-input input-text" name="password" id="reg_password" />
						</div>

					<?php endif; ?>

					<!-- Spam Trap -->
					<div style="left:-999em; position:absolute;"><label for="trap"><?php _e( 'Anti-spam', 'media_center' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

					<?php do_action( 'woocommerce_register_form' ); ?>
					<?php do_action( 'register_form' ); ?>

					<div class="buttons-holder form-row">
						<?php wp_nonce_field( 'woocommerce-register' ); ?>
						<input type="submit" class="le-button huge button" name="register" value="<?php _e( 'Register', 'media_center' ); ?>" />
					</div>

					<?php do_action( 'woocommerce_register_form_end' ); ?>

				</form>

			</section><!-- /.register -->

			<?php echo media_center_list_signup_benefits(); ?>

		</div><!-- /.col -->

		<?php endif ; ?>

	</div><!-- /.row -->
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
