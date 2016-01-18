<?php
/**
 * Functions and Hooks for customize menu item
 *
 * @package BigBoom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Walker_Nav_Menu_Edit' ) ) {
	require_once ABSPATH . 'wp-admin/includes/nav-menu.php';
}

/**
 * Class to add new custom fields to menu item
 *
 * @link https://github.com/kucrut/wp-menu-item-custom-fields
 *
 * @since 1.0
 */
class Bigboom_Walker_Nav_Menu_Custom_Fields {
	/**
	 * Holds our custom fields
	 *
	 * @var    array
	 * @access protected
	 * @since  1.0.0
	 */
	protected $fields = array();

	/**
	 * Initialize
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'menu_walker' ) );
		add_filter( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'bigboom_nav_menu_item_custom_fields', array( $this, 'fields' ), 10, 4 );
		add_action( 'wp_update_nav_menu_item', array( $this, 'save' ), 10, 3 );
		add_filter( 'manage_nav-menus_columns', array( $this, 'columns' ), 99 );

		$this->fields = array(
			'icon'            => __( 'Icon', 'bigboom' ),
			'mega-menu'       => __( 'Mega Menu', 'bigboom' ),
			'mega-menu-width' => __( 'Mega Menu Width', 'bigboom' ),
			'column'          => __( 'Column Width', 'bigboom' ),
			'content'         => __( 'Content', 'bigboom' ),
		);
	}

	/**
	 * Enqueue scripts and styles
	 *
	 * @since  1.0.0
	 * @param  string $hook
	 */
	public function enqueue_scripts( $hook ) {
		if ( 'nav-menus.php' != $hook ) {
			return;
		}

		wp_enqueue_style( 'bb-nav-menus', THEME_URL . '/css/backend/nav-menus.css', array(), THEME_VERSION );
		wp_enqueue_script( 'bb-nav-menus', THEME_URL . '/js/backend/nav-menus.js', array(), THEME_VERSION );
	}

	/**
	 * Save custom field value
	 *
	 * @wp_hook action wp_update_nav_menu_item
	 *
	 * @param int   $menu_id         Nav menu ID
	 * @param int   $menu_item_db_id Menu item ID
	 * @param array $menu_item_args  Menu item data
	 */
	public function save( $menu_id, $menu_item_db_id, $menu_item_args ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		foreach ( $this->fields as $name => $label ) {
			$key = sprintf( 'menu-item-%s', $name );
			// Sanitize
			if ( ! empty( $_REQUEST[$key][$menu_item_db_id] ) ) {
				$value = $_REQUEST[$key][$menu_item_db_id];
			} else {
				$value = null;
			}
			// Update
			if ( ! is_null( $value ) ) {
				update_post_meta( $menu_item_db_id, $key, $value );
			} else {
				delete_post_meta( $menu_item_db_id, $key );
			}
		}
	}

	/**
	 * Print fields
	 *
	 * @param object $item  Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args  Menu item args.
	 * @param int    $id    Nav menu ID.
	 *
	 * @return string Form fields
	 */
	public function fields( $id, $item, $depth, $args ) {
		$mega    = get_post_meta( $item->ID, 'menu-item-mega-menu', true );
		$width   = get_post_meta( $item->ID, 'menu-item-mega-menu-width', true );
		$icon    = get_post_meta( $item->ID, 'menu-item-icon', true );
		$column  = get_post_meta( $item->ID, 'menu-item-column', true );
		$content = get_post_meta( $item->ID, 'menu-item-content', true );
		?>
		<p class="description description-thin field-mega-menu-width">
			<label for="<?php echo esc_attr( $this->get_field_id( 'mega-menu-width', $item->ID ) ) ?>">
				<?php echo $this->fields['mega-menu-width'] ?> (<?php _e( 'optional', 'bigboom' ) ?>)<br>
				<input
					type="text"
					id="<?php echo esc_attr( $this->get_field_id( 'mega-menu-width', $item->ID ) ) ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'mega-menu-width', $item->ID ) ) ?>"
					value="<?php echo esc_attr( $width ) ?>"
					placeholder="100%"
				/>
			</label>
		</p>

		<p class="description description-wide field-mega-menu">
			<label for="<?php echo esc_attr( $this->get_field_id( 'mega-menu', $item->ID ) ) ?>">
				<input
					type="checkbox"
					id="<?php echo esc_attr( $this->get_field_id( 'mega-menu', $item->ID ) ) ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'mega-menu', $item->ID ) ) ?>"
					value="1"
					<?php checked( 1, $mega ) ?>
				/>
				<?php echo $this->fields['mega-menu'] ?>
			</label>
		</p>

		<p class="description description-thin field-type-icon field-icon">
			<span><?php echo $this->fields['icon'] ?></span><br />
			<a href="#" class="button-secondary pick-icon"><i class="<?php echo esc_attr( $icon ) ?>"></i> <?php _e( 'Select Icon', 'bigboom' ) ?></a>
			<span class="icons-block">
				<input type="text" class="search-icon" placeholder="<?php esc_attr_e( 'Quick search', 'bigboom' ) ?>">
				<span class="icon-selector">
					<i data-icon="">&nbsp;</i>
					<?php echo implode( "\n", $this->get_icons( $icon ) ); ?>
				</span>
			</span>
			<input type="hidden" name="<?php echo esc_attr( $this->get_field_name( 'icon', $item->ID ) ) ?>" value="<?php echo esc_attr( $icon ) ?>">
		</p>

		<p class="description description-thin field-column">
			<label for="<?php echo esc_attr( $this->get_field_id( 'column', $item->ID ) ) ?>">
				<?php echo $this->fields['column'] ?><br />
				<select
					id="<?php echo esc_attr( $this->get_field_id( 'column', $item->ID ) ) ?>"
					class="widefat edit-menu-item-column"
					name="<?php echo esc_attr( $this->get_field_name( 'column', $item->ID ) ) ?>"
				>
					<option value="12" <?php selected( 12, $column ); ?>>1/1</option>
					<option value="6" <?php selected( 6, $column ); ?>>1/2</option>
					<option value="4" <?php selected( 4, $column ); ?>>1/3</option>
					<option value="3" <?php selected( 3, $column ); ?>>1/4</option>
					<option value="8" <?php selected( 8, $column ); ?>>2/3</option>
					<option value="9" <?php selected( 9, $column ); ?>>3/4</option>
				</select>
			</label>
		</p>

		<p class="description description-wide field-content">
			<label for="<?php echo esc_attr( $this->get_field_id( 'content', $item->ID ) ) ?>">
				<?php echo $this->fields['content'] ?> (<?php _e( 'optional', 'bigboom' ) ?>)<br>
				<textarea
					id="<?php echo esc_attr( $this->get_field_id( 'content', $item->ID ) ) ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'content', $item->ID ) ) ?>"
					class="widefat edit-menu-item-content"
				><?php echo esc_html( $content ); ?></textarea>
				<span class="description"><?php _e( 'HTML and Shortcodes are allowed', 'bigboom' ); ?></span>
			</label>
		</p>
		<?php
	}

	/**
	 * Add our fields to the screen options toggle
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns Menu item columns
	 *
	 * @return array
	 */
	public function columns( $columns ) {
		$columns = array_merge( $columns, $this->fields );
		return $columns;
	}

	/**
	* Replace default menu editor walker with theme's
	*
	* We don't actually replace the default walker. We're still using it and
	* only injecting some HTMLs.
	*
	* @since   1.0.0
	*
	* @param   string $walker Walker class name
	*
	* @return  string Walker class name
	*/
	public function menu_walker( $walker ) {
		$walker = 'Bigboom_Walker_Nav_Menu_Edit';

		return $walker;
	}

	/**
	 * Get field name
	 *
	 * @since  1.0.0
	 *
	 * @param  string  $name The field name
	 * @param  integer $id   The ID of menu item
	 *
	 * @return string        The name attribute
	 */
	protected function get_field_name( $name, $id = 0 ) {
		return sprintf( 'menu-item-%s[%s]', $name, $id );
	}

	/**
	 * Get field id
	 *
	 * @since  1.0.0
	 *
	 * @param  string  $name The field name
	 * @param  integer $id   The ID of menu item
	 *
	 * @return string        The name attribute
	 */
	protected function get_field_id( $name, $id = 0 ) {
		return "edit-menu-item-$name-$id";
	}

	/**
	 * Display field type icon
	 *
	 * @since  1.0.0
	 *
	 * @param  string $selected The selected icon
	 */
	public function get_icons( $selected = '' ) {
		$icons = BigBoom_VC::get_icons();
		$list = array();

		foreach( $icons as $icon ) {
			$list[] = sprintf(
				'<i class="%1$s" data-icon="%1$s %2$s"></i>',
				esc_attr( $icon ),
				$icon == $selected ? 'selected' : ''
			);
		}

		return $list;
	}
}

/**
 * Menu item custom fields walker
 *
 * @link https://github.com/kucrut/wp-menu-item-custom-fields
 *
 * @since 1.0
 */
class Bigboom_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {
	/**
	 * Start the element output.
	 *
	 * @see Walker_Nav_Menu::start_el()
	 * @since 1.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   Not used.
	 * @param int    $id     Not used.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$item_output = '';
		parent::start_el( $item_output, $item, $depth, $args );

		$output .= preg_replace(
			// NOTE: Check this regex from time to time!
			'/(?=<p[^>]+class="[^"]*field-move)/',
			$this->get_fields( $item, $depth, $args ),
			$item_output
		);
	}

	/**
	 * Get custom fields
	 *
	 * @since 1.0.0
	 * @uses add_action() Calls 'menu_item_custom_fields' hook
	 *
	 * @param object $item  Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args  Menu item args.
	 * @param int    $id    Nav menu ID.
	 *
	 * @return string Form fields
	 */
	protected function get_fields( $item, $depth, $args = array(), $id = 0 ) {
		ob_start();
		/**
		 * Get menu item custom fields from plugins/themes
		 *
		 * @since 1.0.0
		 *
		 * @param object $item  Menu item data object.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args  Menu item args.
		 * @param int    $id    Nav menu ID.
		 *
		 * @return string Custom fields
		 */
		do_action( 'bigboom_nav_menu_item_custom_fields', $id, $item, $depth, $args );

		/**
		 * Add new hooks for Nav Menu Roles plugin
		 *
		 * @since 1.0.4
		 * @link https://wordpress.org/plugins/nav-menu-roles/faq/
		 *
		 * @param object $item  Menu item data object.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args  Menu item args.
		 * @param int    $id    Nav menu ID.
		 *
		 * @return string Custom fields
		 */
		do_action( 'wp_nav_menu_item_custom_fields', $id, $item, $depth, $args );
		return ob_get_clean();
	}
}
