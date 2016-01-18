<?php
/**
 * Register meta boxes
 *
 * @since 1.0
 *
 * @param array $meta_boxes
 *
 * @return array
 */
function bigboom_register_meta_boxes( $meta_boxes ) {
	// Post format
	$meta_boxes[] = array(
		'id'       => 'format_detail',
		'title'    => __( 'Format Details', 'bigboom' ),
		'pages'    => array( 'post' ),
		'context'  => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields'   => array(
			array(
				'name'             => __( 'Image', 'bigboom' ),
				'id'               => 'image',
				'type'             => 'image_advanced',
				'class'            => 'image',
				'max_file_uploads' => 1,
			),
			array(
				'name'  => __( 'Gallery', 'bigboom' ),
				'id'    => 'images',
				'type'  => 'image_advanced',
				'class' => 'gallery',
			),
			array(
				'name'  => __( 'Audio', 'bigboom' ),
				'id'    => 'audio',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 2,
				'class' => 'audio',
			),
			array(
				'name'  => __( 'Video', 'bigboom' ),
				'id'    => 'video',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 2,
				'class' => 'video',
			),
			array(
				'name'  => __( 'Link', 'bigboom' ),
				'id'    => 'url',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'link',
			),
			array(
				'name'  => __( 'Text', 'bigboom' ),
				'id'    => 'url_text',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'link',
			),
			array(
				'name'  => __( 'Quote', 'bigboom' ),
				'id'    => 'quote',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 2,
				'class' => 'quote',
			),
			array(
				'name'  => __( 'Author', 'bigboom' ),
				'id'    => 'quote_author',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'quote',
			),
			array(
				'name'  => __( 'URL', 'bigboom' ),
				'id'    => 'author_url',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'quote',
			),
			array(
				'name'  => __( 'Status', 'bigboom' ),
				'id'    => 'status',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'status',
			),
		),
	);

	// Page/Post Settings
	$meta_boxes[] = array(
		'id'       => 'page_settings',
		'title'    => __( 'Display Settings', 'bigboom' ),
		'pages'    => array( 'post', 'page' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'name' => __( 'Title', 'bigboom' ),
				'id'   => 'heading_title',
				'type' => 'heading',
			),
			array(
				'name'  => __( 'Hide the Title', 'bigboom' ),
				'id'    => 'hide_singular_title',
				'type'  => 'checkbox',
				'std'   => false,
			),
			array(
				'name' => __( 'Layout & Styles', 'bigboom' ),
				'id'   => 'heading_layout',
				'type' => 'heading',
			),
			array(
				'name'  => __( 'Custom Layout', 'bigboom' ),
				'id'    => 'custom_layout',
				'type'  => 'checkbox',
				'std'   => false,
			),
			array(
				'name'            => __( 'Layout', 'bigboom' ),
				'id'              => 'layout',
				'type'            => 'image_select',
				'class'           => 'custom-layout',
				'options'         => array(
					'full-content'    => THEME_URL . '/inc/libs/theme-options/img/sidebars/empty.png',
					'sidebar-content' => THEME_URL . '/inc/libs/theme-options/img/sidebars/single-left.png',
					'content-sidebar' => THEME_URL . '/inc/libs/theme-options/img/sidebars/single-right.png',
				),
			),
			array(
				'name'  => __( 'Custom Css', 'bigboom' ),
				'id'    => 'custom_css',
				'type'  => 'textarea',
				'std'   => false,
			),
			array(
				'name'  => __( 'Custom JavaScript', 'bigboom' ),
				'id'    => 'custom_js',
				'type'  => 'textarea',
				'std'   => false,
			),
		),
	);

	$meta_boxes[] = array(
		'id'       => 'testimonial_general',
		'title'    => __( 'General', 'bigboom' ),
		'pages'    => array( 'testimonial' ),
		'context'  => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields'   => array(
			array(
				'name' => __( 'Star Rating', 'bigboom' ),
				'id'   => 'star',
				'type' => 'slider',
				'js_options' => array(
					'min'  => 0,
					'max'  => 5,
					'step' => 0.5,
				),
			),
		)
	);

	return $meta_boxes;
}
add_filter( 'rwmb_meta_boxes', 'bigboom_register_meta_boxes' );

/**
 * Enqueue scripts for admin
 *
 * @since  1.0
 */
function bigboom_admin_enqueue_scripts( $hook ) {
	// Detect to load un-minify scripts when WP_DEBUG is enable
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
		wp_enqueue_script( 'bigboom-backend-js', THEME_URL . "/js/backend/admin$min.js", array( 'jquery' ), THEME_VERSION, true );
	}
}

add_action( 'admin_enqueue_scripts', 'bigboom_admin_enqueue_scripts' );

/**
 * Generate custom color scheme css
 *
 * @since 1.0
 */
function bigboom_generate_custom_color_scheme() {
	parse_str( $_POST['data'], $data );

	if ( ! isset( $data['custom_color_scheme'] ) ) {
		return;
	}

	if ( ! $data['custom_color_scheme'] ) {
		return;
	}

	$color_1 = $data['custom_color_1'];
	if ( ! $color_1 ) {
		return;
	}

	// Prepare LESS to compile
	$less = file_get_contents( THEME_DIR . '/css/color-schemes/mixin.less' );
	$less .= ".custom-color-scheme { .color-scheme($color_1); }";

	// Compile
	require THEME_DIR . '/inc/libs/lessc.inc.php';
	$compiler = new lessc;
	$compiler->setFormatter( 'compressed' );
	$css = $compiler->compile( $less );

	// Get file path
	$upload_dir = wp_upload_dir();
	$dir        = path_join( $upload_dir['basedir'], 'custom-css' );
	$file       = $dir . '/color-scheme.css';

	// Create directory if it doesn't exists
	wp_mkdir_p( $dir );
	@file_put_contents( $file, $css );

	wp_send_json_success();
}

add_action( 'theme_alien_generate_custom_css', 'bigboom_generate_custom_color_scheme' );
