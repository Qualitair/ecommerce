<?php
/**
 * Hooks for template footer
 *
 * @package BigBoom
 */

/**
 * Display before footer
 */
function bigboom_footer_ads() {
	if ( ! intval( bigboom_theme_option( 'footer_ads' ) ) ) {
		return;
	}
	?>
	<div class="footer-ads">
		<div class="container">
			<?php echo do_shortcode( wp_kses( bigboom_theme_option( 'footer_ads_content' ), wp_kses_allowed_html( 'post' ) ) ); ?>
		</div>
	</div>
	<?php
}
add_action( 'bigboom_before_footer', 'bigboom_footer_ads' );

/**
 * Display widgets on site footer
 */
function bigboom_footer_sidebars() {
	if ( ! intval( bigboom_theme_option( 'footer_widgets' ) ) ) {
		return;
	}

	$columns = max( 1, absint( bigboom_theme_option( 'footer_widget_columns' ) ) );

	if ( 5 == $columns ) {
		$class = 'col-xs-6 col-sm-2 col-sms-12';
	} else {
		$class = 'col-xs-6 col-sms-12 col-sm-' . floor( 12 / $columns );
	}

	?>
	<div class="footer-widgets widgets-area">
		<div class="container">
			<div class="row">
				<?php for( $i = 1; $i <= $columns; $i++ ) : ?>
					<?php
					$class_sidebar = $class;
					if( $columns == 5 && $i == 1 ) {
						$class_sidebar = 'col-xs-6 col-sm-3 col-sms-12';
					} elseif( $columns == 5 && $i == 5 ) {
						$class_sidebar = 'col-xs-12 col-sm-3 col-sms-12';
					}
					?>
					<div class="footer-sidebar-<?php echo esc_attr( $i ) ?> footer-sidebar <?php echo esc_attr( $class_sidebar ) ?>">
						<?php dynamic_sidebar( __( 'Footer', 'bigboom' ) . " $i" ); ?>
					</div>
				<?php endfor; ?>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'bigboom_footer', 'bigboom_footer_sidebars', 5 );

/**
 * Display footer content
 */
function bigboom_footer() {
	?>
	<div class="site-info">
		<div class="container">
			<hr>
			<div class="row">
				<div class="col-xs-12 col-sm-7">
					<?php
					if ( has_nav_menu( 'footer' ) ) {
						wp_nav_menu( array(
							'theme_location'  => 'footer',
							'container'       => 'nav',
							'container_id'    => 'footer-menu',
							'container_class' => 'footer-menu',
						) );
					}
					?>

					<div class="copyright"><?php echo do_shortcode( wp_kses( bigboom_theme_option( 'footer_copyright' ), wp_kses_allowed_html( 'post' ) ) ) ?></div>
				</div>

				<div class="text-right col-xs-12 col-sm-5">
					<?php echo do_shortcode( wp_kses( bigboom_theme_option( 'footer_info' ) , wp_kses_allowed_html( 'post' ) ) ) ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'bigboom_footer', 'bigboom_footer' );

/**
 * Get socials value
 *
 * @since  1.0
 *
 * @return string
 */
function bigboom_socials() {
	$output  = array();
	$socials = array_filter( (array) bigboom_theme_option( 'social' ) );

	if ( empty( $socials ) ) {
		return;
	}

	foreach ( (array) $socials as $name => $value ) {
		$display = $name;

		if ( $name == 'google' ) {
			$display = 'google+';
			$name    = 'google-plus';
		} elseif( $name == 'vimeo' ) {
			$name = 'vimeo-square';
		} elseif ( $name == 'mail' ) {
			$name    = 'envelope';
		}

		if ( $name == 'mail' ) {
			$display = __( 'Mail to us', 'bigboom' );
			$value   = 'mailto:' . esc_attr( $value );
		} else {
			$display = sprintf( __( 'Follow via %s', 'bigboom' ), ucfirst( $display ) );
			$value = esc_url( $value );
		}

		$output[] = sprintf(
			'<a href="%s" class="bb-%s" target="_blank"><i class="fa fa-%s"></i><span>%s</span></a>',
			$value,
			esc_attr( $name ),
			esc_attr( $name ),
			$display
		);
	}

	if ( $output ) {
		echo '<div id="social-icons" class="social-icons hidden-xs hidden-sm">' . implode( "\n\t", $output ) . '</div>';
	}
}
add_action( 'bigboom_after_footer', 'bigboom_socials' );

/**
 * Display go to top button
 *
 * @since 1.0
 */
function bigboom_go_top_button() {
	?>
	<a id="scroll-top" class="backtotop" href="#page-top">
		<i class="fa fa-angle-up"></i>
	</a>
	<?php
}
add_action( 'bigboom_after_footer', 'bigboom_go_top_button' );

/**
 * Add custom scripts to site footer
 *
 * @since 1.0
 */
function bigboom_footer_custom_scripts() {
	if ( $script = bigboom_theme_option( 'footer_script' ) ) {
		echo $script;
	}
}
add_action( 'wp_footer', 'bigboom_footer_custom_scripts' );
