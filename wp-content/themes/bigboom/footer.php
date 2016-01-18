<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package BigBoom
 */
?>		<?php if ( ! is_page_template( 'template-full-width.php' ) ) : ?>
				</div> <!-- .row -->
			</div><!-- .container -->
		<?php endif; ?>
	</div><!-- #content -->

	<?php do_action( 'bigboom_before_footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php do_action( 'bigboom_footer' ); ?>
	</footer><!-- #colophon -->

	<?php do_action( 'bigboom_after_footer' ); ?>
</div><!-- #page -->
<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-id="<?php echo esc_attr( bigboom_theme_option( 'addthis_profile_id' ) ); ?>">
	<div class="item-detail">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i><span class="sr-only"><?php _e( 'Close', 'bigboom' ) ?></span></button>
				</div>
				<div class="woocommerce">
					<div class="modal-body product"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php wp_footer(); ?>

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/_admin/plugins/dropdown/js/classie.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/_admin/plugins/dropdown/js/selectFx.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/_admin/js-define/app.js"></script>

<script>
	(function() {

		[].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	
			new SelectFx(el);

		} );
	})();
	
</script>

</body>
</html>
