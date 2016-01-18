<?php
/**
 * Walker mega menu
 *
 * @since 1.0.0
 */
class BigBoom_Walker_Nav_Menu extends Walker_Nav_Menu {
	/**
	 * Store state of top level item
	 *
	 * @since 1.0.0
	 * @var boolean
	 */
	protected $in_mega = false;

	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker::start_lvl()
	 *
	 * @since 1.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);

		if ( ! $depth && $this->in_mega ) {
			$output .= $indent . '<div class="mega-menu-columns">' . "\n";
		} else {
			$output .= "\n$indent<ul class=\"sub-menu\">\n";
		}
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @see Walker::end_lvl()
	 *
	 * @since 1.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);

		if ( ! $depth && $this->in_mega ) {
			$output .= "$indent</div>\n";
		} else {
			$output .= "$indent</ul>\n";
		}
	}

	/**
	 * Start the element output.
	 * Display item description text and classes
	 *
	 * @see   Walker::start_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 * @param int    $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$item_icon       = get_post_meta( $item->ID, 'menu-item-icon', true );
		$item_is_mega    = get_post_meta( $item->ID, 'menu-item-mega-menu', true );
		$item_column     = get_post_meta( $item->ID, 'menu-item-column', true );
		$item_mega_width = get_post_meta( $item->ID, 'menu-item-mega-menu-width', true );
		$item_content    = get_post_meta( $item->ID, 'menu-item-content', true );

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		if ( $item_icon ) {
			$classes[] = 'menu-item-has-icon';
		}
		if ( $item_is_mega && ! $depth ) {
			$classes[] = 'menu-item-mega';
		}
		if ( $item->image && ! $depth ) {
			$classes[] = 'menu-item-has-background';
		}
		if ( 1 == $depth && $this->in_mega ) {
			$classes[] = 'mega-sub-menu col-md-'. esc_attr( $item_column );
		}

		/**
		 * Check if this is top level and is mega menu
		 */
		if ( ! $depth ) {
			$this->in_mega = $item_is_mega;
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$item_id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$item_id = $item_id ? ' id="' . esc_attr( $item_id ) . '"' : '';

		if ( 1 == $depth && $this->in_mega ) {
			$output .= $indent . '<div' . $item_id . $class_names . '>';
		} else {
			$output .= $indent . '<li' . $item_id . $class_names . '>';
		}

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';

		/** Add icon */
		if ( $item_icon ) {
			$item_output .= '<i class="' . esc_attr( $item_icon ) . '"></i>';
		}

		/** This filter is documented in wp-includes/post-template.php */
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;

		/** Add custom badge */
		if ( in_array( 'hot', $classes ) && ! $depth ) {
			$item_output .= '<span class="hot-badge">' . __( 'Hot', 'bigboom' ) . '</span>';
		} elseif ( in_array( 'new', $classes ) && ! $depth ) {
			$item_output .= '<span class="new-badge">' . __( 'New', 'bigboom' ) . '</span>';
		}

		$item_output .= '</a>';
		$item_output .= $args->after;

		if ( 1 == $depth && $this->in_mega && in_array( 'sep', $classes ) ) {
			$item_output = '<hr>';
		} elseif ( in_array( 'hide-text', $classes ) ) {
			$item_output = '';
		}

		if ( 1 <= $depth && ! empty( $item_content ) ) {
			$item_output .= '<div class="menu-item-content">' . do_shortcode( $item_content ) . '</div>';
		}

		if ( $item_is_mega && ! $depth ) {
			$style  = $item_mega_width ? 'style="width:' . esc_attr( $item_mega_width ) . '"' : '';
			$item_output .= '<div class="mega-menu-container container" ' . $style . '>' . "\n";
		}

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @see Walker::end_el()
	 *
	 * @since 1.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Page data object. Not used.
	 * @param int    $depth  Depth of page. Not Used.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {

		if ( ! $depth && $this->in_mega ) {
			$output .= '</div>';
		} elseif ( 1 == $depth && $this->in_mega ) {
			$output .= '</div>';
		} else {
			$output .= "</li>\n";
		}
	}
}
