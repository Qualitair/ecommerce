<?php
/**
 * Lost password form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="row">
    <div class="col-xs-11 col-sm-10 col-md-6 center-block">

        <?php wc_print_notices(); ?>
        
        <div class="section lost-reset-password m-t-0">

            <form method="post" class="lost_reset_password">

                <?php if( 'lost_password' == $args['form'] ) : ?>

                    <p><?php echo apply_filters( 'woocommerce_lost_password_message', __( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'media_center' ) ); ?></p>

                    <div class="row field-row form-row form-row-first">
                        <div class="col-xs-12">
                            <label for="user_login"><?php _e( 'Username or email', 'media_center' ); ?></label> 
                            <input class="le-input input-text" type="text" name="user_login" id="user_login" />
                        </div>
                    </div>

                <?php else : ?>

                    <p><?php echo apply_filters( 'woocommerce_reset_password_message', __( 'Enter a new password below.', 'media_center') ); ?></p>

                    <div class="row field-row form-row form-row-first">
                        <div class="col-xs-12">
                            <label for="password_1"><?php _e( 'New password', 'media_center' ); ?> <span class="required">*</span></label>
                            <input type="password" class="le-input input-text" name="password_1" id="password_1" />
                        </div>
                    </div>
                    <div class="row field-row form-row form-row-first">
                        <div class="col-xs-12">
                            <label for="password_2"><?php _e( 'Re-enter new password', 'media_center' ); ?> <span class="required">*</span></label>
                            <input type="password" class="le-input input-text" name="password_2" id="password_2" />
                        </div>
                    </div>

                    <input type="hidden" name="reset_key" value="<?php echo isset( $args['key'] ) ? $args['key'] : ''; ?>" />
                    <input type="hidden" name="reset_login" value="<?php echo isset( $args['login'] ) ? $args['login'] : ''; ?>" />

                <?php endif; ?>

                <div class="form-row buttons-holder">
                    <input type="hidden" name="wc_reset_password" value="true" />
                    <input type="submit" class="le-button button" value="<?php echo 'lost_password' == $args['form'] ? __( 'Reset Password', 'media_center' ) : __( 'Save', 'media_center' ); ?>" />
                </div>
                <?php wp_nonce_field( $args['form'] ); ?>

                <hr/>
                <a href="<?php echo get_permalink( get_option('woocommerce_login_page_id') ); ?>" title="<?php _e('Login','media_center'); ?>"><i class="fa fa-long-arrow-left"></i> <?php _e('Go to Login Page','media_center'); ?></a>

            </form>  
        </div><!-- /.lost-reset-password -->

    </div><!-- /.centered-block -->
</div><!-- /.row -->