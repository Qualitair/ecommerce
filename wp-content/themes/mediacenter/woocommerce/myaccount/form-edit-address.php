<?php
/**
 * Edit address form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $current_user;

$page_title = ( $load_address === 'billing' ) ? __( 'Billing Address', 'media_center' ) : __( 'Shipping Address', 'media_center' );

get_currentuserinfo();
?>

<?php wc_print_notices(); ?>

<div class="row">
	<div class="col-lg-8 center-block">
		
		<?php if ( ! $load_address ) : ?>

			<?php wc_get_template( 'myaccount/my-address.php' ); ?>

		<?php else : ?>

		<section class="section edit-address">
			<form method="post">

				<h3><?php echo apply_filters( 'woocommerce_my_account_edit_address_title', $page_title ); ?></h3>

				<?php foreach ( $address as $key => $field ) : ?>

				<?php
					$field['input_class'] = array( 'le-input' );
					$field['start_row'] = isset( $field['start_row'] ) ? $field['start_row'] : true;
					$field['end_row'] = isset( $field['end_row'] ) ? $field['end_row'] : true;
					$field['container_class'] = isset( $field['container_class'] ) ? $field['container_class'] : 'col-xs-12';
				?>

					<?php if( $field['start_row'] ) : ?>
					<div class="row field-row">
					<?php endif; ?>

						<div class="<?php echo $field['container_class'];?>">
							
							<?php woocommerce_form_field( $key, $field, ! empty( $_POST[ $key ] ) ? wc_clean( $_POST[ $key ] ) : $field['value'] ); ?>

						</div><!-- /.col -->

					<?php if( $field['end_row'] ) : ?>
					</div><!-- /.row .field-row -->
					<?php endif; ?>

				<?php endforeach; ?>

				<p>
					<input type="submit" class="le-button huge button" name="save_address" value="<?php _e( 'Save Address', 'media_center' ); ?>" />
					&nbsp;<?php wc_get_template( 'myaccount/btn-goback-myaccount.php' ); ?>
					<?php wp_nonce_field( 'woocommerce-edit_address' ); ?>
					<input type="hidden" name="action" value="edit_address" />
				</p>

			</form>	
		</section><!-- /.edit-address -->

	</div><!-- /.col-lg-8 -->
</div><!-- /.row -->

<?php endif; ?>