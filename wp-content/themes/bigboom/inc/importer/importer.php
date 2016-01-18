<?php
/**
 * Main file for importing theme demo data
 *
 * Create new menu in the admin area, under Appearance menu
 * Allows to import demo content with 1 click only
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Importer classes
if( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
	define( 'WP_LOAD_IMPORTERS', true );
}

if( ! class_exists( 'TA_Content_Importer' ) ) {
	require_once THEME_DIR . '/inc/importer/wordpress-importer.php';
}

if ( ! class_exists( 'TA_Widgets_Importer') ) {
	require_once THEME_DIR . '/inc/importer/widgets-importer.php';
}

/**
 * Main class for importing demo content
 *
 * @version 1.0.0
 */
class TA_Demo_Import {
	/**
	 * Demo data configuration
	 *
	 * @var array
	 */
	public $data;

	/**
	 * Construction function
	 * Add new admin menu under Appearance menu
	 */
	public function __construct( $data = array() ) {
		$this->data = $data;

		add_action( 'admin_menu', array( $this, 'menu' ) );
		add_action( 'admin_notices', array( $this, 'notice' ) );
	}

	/**
	 * Add new menu under Appearance menu
	 */
	public function menu() {
		add_theme_page(
			__( 'Import Theme Demo Data', 'bigboom' ),
			__( 'Theme Demo Data', 'bigboom' ),
			'edit_theme_options',
			'import-demo-content',
			array( $this, 'page' )
		);
	}

	/**
	 * Display notice
	 */
	public function notice() {
		global $pagenow;

		// Only display on themes page
		if ( 'themes.php' != $pagenow ) {
			return;
		}

		// Only display on import demo page
		if ( ! isset( $_GET['page'] ) || 'import-demo-content' != $_GET['page'] ) {
			return;
		}

		if ( isset( $_GET['import'] ) && 'success' == $_GET['import'] ) {
			return;
		}
		?>

		<div class="updated notice is-dismissible">
			<p><?php _e( 'Before starting the import, you have to install all required plugins and other plugins that you want to use.', 'bigboom' ) ?></p>
			<button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'bigboom' ) ?></span></button>
		</div>

		<?php
	}

	/**
	 * Admin page for importing demo content
	 */
	public function page() {
		$result = $this->import();
		?>

		<div class="wrap">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

			<?php if ( $result ) : ?>
				<p>
					<?php _e( 'All Done!', 'bigboom' ) ?>
				</p>
			<?php else : ?>

				<p>
					<?php _e( 'Select what content do you want to import. Leave default to import all demo data.', 'bigboom' ) ?>
				</p>

				<form id="ta-import-form" action="" method="post">
					<?php wp_nonce_field( 'ta-import-demo-data', '_ta_import_nonce' ); ?>

					<p>
						<label>
							<input type="checkbox" name="import[]" value="content" checked="checked">
							<?php _e( 'Content', 'bigboom' ) ?>
						</label>
					</p>

					<p>
						<label>
							<input type="checkbox" name="import[]" value="widgets" checked="checked">
							<?php _e( 'Widgets', 'bigboom' ) ?>
						</label>
					</p>

					<p>
						<label>
							<input type="checkbox" name="import[]" value="theme_options" checked="checked">
							<?php _e( 'Theme Options', 'bigboom' ) ?>
						</label>
					</p>

					<?php if ( $this->data['sliders'] ) : ?>
					<p>
						<label>
							<input type="checkbox" name="import[]" value="sliders" checked="checked">
							<?php _e( 'Revolution Sliders (require plugin installed)', 'bigboom' ) ?>
						</label>
					</p>
					<?php endif; ?>

					<input type="submit" class="button button-primary" value="<?php _e( 'Import Demo Content', 'bigboom' ) ?>">

					<p class="description"><?php _e( 'It usally take less than one minute to finish. Please be patient.', 'bigboom' ) ?></p>
				</form>

			<?php endif; ?>
		</div>

		<?php
	}

	/**
	 * Process importing request
	 * Redirect when success to avoid user refresh browser
	 */
	public function import() {
		if ( ! isset( $_POST['_ta_import_nonce'] ) || ! wp_verify_nonce( $_POST['_ta_import_nonce'], 'ta-import-demo-data' ) ) {
			return;
		}

		if ( ! isset( $_POST['import'] ) || empty( $_POST['import'] ) ) {
			return;
		}

		$import = (array) $_POST['import'];

		// Start importing
		if ( in_array( 'content', $import ) ) {
			$this->import_content();
			$this->import_menu_locations();
		}

		if ( in_array( 'theme_options', $import ) ) {
			$this->import_theme_options();
		}

		if ( in_array( 'widgets', $import ) ) {
			$this->import_widgets();
		}

		if ( in_array( 'content', $import ) ) {
			$this->import_menu_locations();
		}

		if ( in_array( 'sliders', $import ) ) {
			$this->import_sliders();
		}
		// End importing

		// Set home & blog page
		if ( isset( $this->data['home_page'] ) ) {
			$home = get_page_by_title( $this->data['home_page'] );

			if ( $home ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $home->ID ); // Front Page
			}
		}

		if ( isset( $this->data['blog_page'] ) ) {
			$blog = get_page_by_title( $this->data['blog_page'] );

			if ( $blog ) {
				update_option( 'page_for_posts', $blog->ID ); // Front Page
			}
		}

		return true;
	}

	/**
	 * Import content
	 * Import posts, pages, menus, custom post types
	 *
	 * @param  string $file The exported file's name
	 */
	public function import_content( $file = 'demo-content.xml' ) {
		if ( ! file_exists( THEME_DIR . '/demo/' . $file ) ) {
			return;
		}

		$import = new TA_Content_Importer();
		$xml    = THEME_DIR . '/demo/'. $file;

		$import->fetch_attachments = true;

		ob_start();
		$import->import( $xml );
		ob_end_clean();
	}

	/**
	 * Import menu locations
	 *
	 * @param  string $file The exported file's name
	 */
	public function import_menu_locations( $file = 'menus.txt' ) {
		if ( ! file_exists( THEME_DIR . '/demo/' . $file ) ) {
			return;
		}

		$file_path 	= THEME_URL . '/demo/' . $file;
		$file_data 	= wp_remote_get( $file_path );
		$data 		= maybe_unserialize( base64_decode( $file_data['body'] ) );
		$menus 		= wp_get_nav_menus();

		foreach( $data as $key => $val ) {
			foreach( $menus as $menu ) {
				if( $val && $menu->slug == $val ) {
					$data[$key] = absint( $menu->term_id );
				}
			}
		}

		set_theme_mod( 'nav_menu_locations', $data );
	}

	/**
	 * Import theme options
	 *
	 * @param  string $file The exported file's name
	 */
	public function import_theme_options( $file = 'theme-options.txt' ) {
		if ( ! file_exists( THEME_DIR . '/demo/' . $file ) ) {
			return;
		}

		$file_path 	= THEME_URL . '/demo/'. $file;
		$file_data 	= wp_remote_get( $file_path );
		$data 		= maybe_unserialize( base64_decode( $file_data['body'] ) );

		// replace exported URL with destination URL
		if( is_array( $data ) && isset( $this->data['base_url'] ) ) {
			$replace = home_url( '/' );

			foreach( $data as $key => $option ) {
				if( is_string( $option ) ) {
					$data[$key] = str_replace( trailingslashit( $this->data['base_url'] ), $replace, $option );
				}
			}
		}

		foreach ( $data as $name => $value ) {
			set_theme_mod( $name, $value );
		}
	}

	/**
	 * Import widgets
	 *
	 * @param  string $file The exported file's name
	 */
	function import_widgets( $file = 'widgets-data.json' ) {
		if ( ! file_exists( THEME_DIR . '/demo/' . $file ) ) {
			return;
		}

		$file_path 	= THEME_URL . '/demo/'. $file;
		$file_data 	= wp_remote_get( $file_path );
		$data 		= json_decode( $file_data['body'] );

		$importer   = new TA_Widgets_Importer();
		$importer->import( $data );
	}

	/**
	 * Import exported revolution sliders
	 *
	 * @param  string $path The exported files' path
	 */
	public function import_sliders( $path = '/demo/sliders/' ) {
		if ( ! class_exists( 'RevSlider' ) ) {
			return;
		}

		$files = scandir( THEME_DIR . $path );

		if ( empty( $files ) ) {
			return;
		}

		$slider = new RevSlider();

		ob_start();
		foreach( $files as $file ) {
			if ( $file == '.' || $file == '..' ) {
				continue;
			}
			$file = THEME_DIR . $path . $file;
			if ( 'zip' != strtolower( pathinfo( $file, PATHINFO_EXTENSION ) ) ) {
				continue;
			}

			$response = $slider->importSliderFromPost( true, true, $file );
		}
		ob_clean();
	}
}
