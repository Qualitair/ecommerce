<?php
/**
 * Theme Alien Options Framework
 * Theme options interfect
 *
 * @package Theme Alien Options Framework
 */

/**
 * Class to display admin options interfect
 *
 * @author Theme Alien
 * @version 1.0
 */
class TA_Theme_Options_Admin {
	/**
	 * Setup the options
	 *
	 * @since  1.0
	 *
	 * @param  array $options Theme options fields
	 *
	 * @return TA_Theme_Options_Admin
	 */
	public function __construct( $options ) {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'wp_ajax_theme_alien_options_save', array( $this, 'save' ) );
		add_action( 'wp_ajax_theme_alien_options_reset', array( $this, 'reset' ) );
		add_action( 'wp_ajax_theme_alien_options_import', array( $this, 'import' ) );
        add_action( 'wp_ajax_theme_alien_generate_custom_css', array( $this, 'custom_css' ) );
    }

	/**
	 * Add admin menu
	 *
	 * @since  1.0
	 *
	 * @return  void
	 */
	public function admin_menu() {
		$hook = add_theme_page( __( 'Theme Options', 'bigboom' ), __( 'Theme Options', 'bigboom' ), 'edit_theme_options', 'theme-options', array( $this, 'page' ) );

		add_action( "admin_print_styles-$hook", array( $this, 'enqueue' ) );
	}

	/**
	 * Enqueue scripts and styles for options page
	 *
	 * @since  1.0
	 *
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_media();
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_style( 'font-roboto', '//fonts.googleapis.com/css?family=Roboto:400,300&subset=latin,vietnamese,cyrillic,greek-ext,greek,cyrillic-ext,latin-ext' );
		wp_enqueue_script( 'ace', '//cdnjs.cloudflare.com/ajax/libs/ace/1.1.9/ace.js', array(), '1.1.9', true );
		?>
		<script>window.ace || document.write( '<script src="<?php echo esc_url( TA_OPTIONS_URL ) ?>js/ace.js" type="text/javascript"><\/script>' )</script>
		<?php
		wp_enqueue_style( 'alien-options', TA_OPTIONS_URL . 'css/admin.css' );
		wp_enqueue_script( 'alien-options', TA_OPTIONS_URL . 'js/admin.js', array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable' ), '', true );
		wp_localize_script( 'alien-options', 'alienOptions', array(
			'nonce_save'        => wp_create_nonce( 'theme_alien_options_save' ),
			'nonce_reset'       => wp_create_nonce( 'theme_alien_options_reset' ),
			'nonce_import'      => wp_create_nonce( 'theme_alien_options_import' ),
			'nonce_css'         => wp_create_nonce( 'theme_alien_generate_custom_css' ),
			'reset_notice'      => __( 'This action can\'t be undo. Are you sure want to reset?', 'bigboom' ),
			'import_notice'     => __( 'All previous data will be overwritten and can\'t be undo. Are you sure want to import?', 'bigboom' ),
			'media_title'       => __( 'Select Image', 'bigboom' ),
			'media_title_multi' => __( 'Select Images', 'bigboom' ),
		) );
	}

	/**
	 * Render admin page
	 *
	 * @since  1.0
	 *
	 * @return void
	 */
	public function page() {
		$generator = new TA_Theme_Options_Fields;
		$theme     = wp_get_theme();
		?>

		<div class="wrap">

			<div id="options-header" class="options-header">
				<a href="<?php echo esc_url( TA_Theme_Options::$options['help']['support'] ); ?>" class="help" target="_blank"><i class="entypo-lifebuoy"></i><?php _e( 'Support', 'bigboom' ) ?>
				</a>
				<a href="<?php echo esc_url( TA_Theme_Options::$options['help']['document'] ); ?>" class="help" target="_blank"><i class="entypo-book"></i><?php _e( 'Documentation', 'bigboom' ) ?>
				</a>

				<h1>
					<?php echo esc_attr( $theme->name ) ?> <?php _e( 'Theme Options', 'bigboom' ) ?>
					<span class="theme-version"><?php echo esc_html( $theme->version ) ?></span>
					<span class="theme-author">
						<?php _e( ' by ', 'bigboom' ) ?>
						<a href="<?php echo esc_url( $theme->{'Author URI'} ); ?>" class="theme-author" target="_blank"><?php echo esc_html( $theme->{'Author Name'} ) ?></a>
					</span>
				</h1>
			</div>

			<form method="POST" action="" id="theme-options" class="clearfix">
				<!-- Theme optons menu -->
				<div id="options-menu" class="options-menu">
					<ul class="menu">
						<?php
						foreach ( TA_Theme_Options::$options['sections'] as $id => $menu ) {
							printf( '<li><span class="changes"></span><a href="#%s"><i class="entypo-%s"></i><span>%s</span></a></li>', esc_attr( $id ), esc_attr( $menu['icon'] ), $menu['title'] );
						}
						?>
					</ul>
				</div>

				<!-- Theme optons content -->
				<div id="options-content" class="options-content clearfix">
					<div class="toolbar">
						<span class="toggle-menu entypo-menu"></span>
						<span class="quick-search">
							<input type="text" id="quick-search" placeholder="<?php esc_attr_e( 'Quick Search', 'bigboom' ) ?>">
						</span>

						<button type="button" class="button reset-options"><?php _e( 'Reset Options', 'bigboom' ) ?></button>
						<button type="button" class="button button-primary save-options"><?php _e( 'Save Changes', 'bigboom' ) ?></button>
						<span class="spinner"></span>
					</div>

					<div id="options-sections" class="options-sections clearfix">
						<?php foreach ( TA_Theme_Options::$options['fields'] as $section => $fields ): ?>
							<div id="<?php echo esc_attr( $section ); ?>" class="section">
								<?php echo $generator->generate( $fields ); ?>
							</div>
						<?php endforeach ?>
						<div class="options-back-content"></div>
					</div>
				</div>
			</form>
		</div>

		<div class="clear"></div>

	<?php
	}

	/**
	 * Ajax function to save theme options
	 *
	 * @since  1.0
	 *
	 * @return void
	 */
	public function save() {
		check_ajax_referer( 'theme_alien_options_save' );

		$_POST['data'] = stripslashes_deep( $_POST['data'] );
		parse_str( $_POST['data'], $data );

		foreach ( TA_Theme_Options::$defaults as $name => $default ) {
			$value = isset( $data[$name] ) ? $data[$name] : '';
			set_theme_mod( $name, $value );
		}
		die;
	}

	/**
	 * Ajax function to reset theme options
	 *
	 * @since  1.0
	 *
	 * @return void
	 */
	public function reset() {
		check_ajax_referer( 'theme_alien_options_reset' );
		foreach ( TA_Theme_Options::$defaults as $name => $default ) {
			remove_theme_mod( $name );
		}
		die;
	}

	/**
	 * Ajax function to import theme options
	 *
	 * @since  1.0
	 *
	 * @return void
	 */
	public function import() {
		check_ajax_referer( 'theme_alien_options_import' );
		$data = maybe_unserialize( base64_decode( $_POST['data'] ) );

		foreach ( $data as $name => $value ) {
			set_theme_mod( $name, $value );
		}
		die;
	}

    /**
     * Ajax function to generate custom css when options are saved
     * By default, it does nothing, just define a hook called 'theme_alien_generate_custom_css'.
     *
     * @since 1.0
     *
     * @return void
     */
    public function custom_css() {
        check_ajax_referer( 'theme_alien_generate_custom_css' );

        do_action( 'theme_alien_generate_custom_css' );
        die;
    }
}
