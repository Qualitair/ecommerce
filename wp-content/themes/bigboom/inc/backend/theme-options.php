<?php
add_filter( 'ta_theme_options', 'bigboom_theme_options' );

/**
 * Register theme options fields
 *
 * @since  1.0
 *
 * @return array Theme options fields
 */
function bigboom_theme_options() {
	$options = array();

	// Help information
	$options['help'] = array(
		'document' => 'http://tiny.cc/pyo3yx',
		'support'  => 'http://themealien.com/support/bigboom',
	);


	// Sections
	$options['sections'] = array(
		'general'    => array(
			'icon'  => 'cog',
			'title' => __( 'General', 'bigboom' ),
		),
		'promo'    => array(
			'icon'  => 'megaphone',
			'title' => __( 'Promotion', 'bigboom' ),
		),
		'layout'     => array(
			'icon'  => 'grid',
			'title' => __( 'Layout', 'bigboom' ),
		),
		'style'      => array(
			'icon'  => 'palette',
			'title' => __( 'Style', 'bigboom' ),
		),
		'header'     => array(
			'icon'  => 'browser',
			'title' => __( 'Header', 'bigboom' ),
		),
		'blog'    => array(
			'icon'  => 'archive',
			'title' => __( 'Blog', 'bigboom' ),
		),
		'shop'    => array(
			'icon'  => 'shopping-cart',
			'title' => __( 'Shop', 'bigboom' ),
		),
		'footer'     => array(
			'icon'  => 'rss',
			'title' => __( 'Footer', 'bigboom' ),
		),
		'export'     => array(
			'icon'  => 'upload-to-cloud',
			'title' => __( 'Backup - Restore', 'bigboom' ),
		),
	);

	// Fields
	$options['fields']            = array();
	$options['fields']['general'] = array(
		array(
			'name'  => 'favicon',
			'label' => __( 'Favicon', 'bigboom' ),
			'type'  => 'icon',
		),
		array(
			'name'     => 'home_screen_icons',
			'label'    => __( 'Home Screen Icons', 'bigboom' ),
			'desc'     => __( 'Select image file that will be displayed on home screen of handheld devices.', 'bigboom' ),
			'type'     => 'group',
			'children' => array(
				array(
					'name'    => 'icon_ipad_retina',
					'type'    => 'icon',
					'subdesc' => __( 'IPad Retina (144x144px)', 'bigboom' ),
				),
				array(
					'name'    => 'icon_ipad',
					'type'    => 'icon',
					'subdesc' => __( 'IPad (72x72px)', 'bigboom' ),
				),

				array(
					'name'    => 'icon_iphone_retina',
					'type'    => 'icon',
					'subdesc' => __( 'IPhone Retina (114x114px)', 'bigboom' ),
				),

				array(
					'name'    => 'icon_iphone',
					'type'    => 'icon',
					'subdesc' => __( 'IPhone (57x57px)', 'bigboom' ),
				)
			)
		),
		array(
			'name'    => 'addthis_profile_id',
			'label'   => __( 'AddThis Profile ID', 'bigboom' ),
			'subdesc' => __( 'Please go <a href="https://www.addthis.com/settings/publisher" target="_blank">here</a> to get your Addthis Profile Id', 'bigboom' ),
			'type'    => 'textarea',
		),
		array(
			'name'    => 'social',
			'label'   => __( 'Socials', 'bigboom' ),
			'type'    => 'social',
			'subdesc' => __( 'Click to social icon to add link', 'bigboom' ),
		),
	);

	$options['fields']['promo'] = array(
		array(
			'name'    => 'promotion',
			'label'   => __( 'Promotion', 'bigboom' ),
			'desc'    => __( 'Display a promotion section at the top of site', 'bigboom' ),
			'type'    => 'switcher',
			'default' => false,
		),
		array(
			'name'    => 'promotion_home_only',
			'label'   => __( 'Display On Homepage Only', 'bigboom' ),
			'desc'    => __( 'Display the promotion section on the homepage only', 'bigboom' ),
			'type'    => 'switcher',
			'default' => false,
		),
		array(
			'name'     => 'promo_bg',
			'type'     => 'background',
			'label'    => __( 'Background', 'bigboom' ),
			'desc'     => __( 'Setup background for promotion section', 'bigboom' ),
			'patterns' => array(
				THEME_URL . '/img/patterns/p1.png',
				THEME_URL . '/img/patterns/p2.png',
				THEME_URL . '/img/patterns/p3.png',
				THEME_URL . '/img/patterns/p4.png',
				THEME_URL . '/img/patterns/p5.png',
			),
		),
		array(
			'name'     => 'promo_content',
			'label'    => __( 'Content', 'bigboom' ),
			'desc'     => __( 'Enter promotion content', 'bigboom' ),
			'type'     => 'editor',
			'settings' => array(
				'media_buttons' => true,
				'teeny'         => false,
				'quicktags'     => true,
			),
		),
	);

	$options['fields']['layout'] = array(
		array(
			'name'    => 'default_layout',
			'label'   => __( 'Default Layout', 'bigboom' ),
			'desc'    => __( 'Default sidebar position for whole site', 'bigboom' ),
			'type'    => 'image_toggle',
			'default' => 'sidebar-content',
			'options' => array(
				'full-content'    => TA_OPTIONS_URL . 'img/sidebars/empty.png',
				'sidebar-content' => TA_OPTIONS_URL . 'img/sidebars/single-left.png',
				'content-sidebar' => TA_OPTIONS_URL . 'img/sidebars/single-right.png',
			)
		),
		array(
			'name'    => 'shop_layout',
			'label'   => __( 'Shop Layout', 'bigboom' ),
			'desc'    => __( 'Default sidebar position for page', 'bigboom' ),
			'type'    => 'image_toggle',
			'default' => 'sidebar-content',
			'options' => array(
				'full-content'    => TA_OPTIONS_URL . 'img/sidebars/empty.png',
				'sidebar-content' => TA_OPTIONS_URL . 'img/sidebars/single-left.png',
				'content-sidebar' => TA_OPTIONS_URL . 'img/sidebars/single-right.png',
			)
		),
		array(
			'name'    => 'page_layout',
			'label'   => __( 'Page Layout', 'bigboom' ),
			'desc'    => __( 'Default sidebar position for page', 'bigboom' ),
			'type'    => 'image_toggle',
			'default' => 'full-content',
			'options' => array(
				'full-content'    => TA_OPTIONS_URL . 'img/sidebars/empty.png',
				'sidebar-content' => TA_OPTIONS_URL . 'img/sidebars/single-left.png',
				'content-sidebar' => TA_OPTIONS_URL . 'img/sidebars/single-right.png',
			)
		),
	);

	$options['fields']['style'] = array(
		array(
			'name'    => 'color_scheme',
			'label'   => __( 'Color Scheme', 'bigboom' ),
			'desc'    => __( 'Select color scheme for website', 'bigboom' ),
			'type'    => 'color_scheme',
			'default' => '',
			'options' => array(
				''        => '#da4b52',
				'blue'    => '#428bca',
				'green'   => '#b0d95e',
				'orange'  => '#f08a47',
				'purple'  =>'#c74a73',
				'yellow'  => '#ff9c00',
				'brown'   => '#987654',
				'cyan'    => '#1ABC9C',
				'skyblue' => '#00cdcd',
				'gray'    => '#656565',
			)
		),
		array(
			'name'     => 'custom_color_scheme',
			'label'    => __( 'Custom Color Scheme', 'bigboom' ),
			'desc'     => __( 'Enable custom color scheme to pick your own color scheme', 'bigboom' ),
			'type'     => 'group',
			'layout'   => 'vertical',
			'children' => array(
				array(
					'name'    => 'custom_color_scheme',
					'type'    => 'switcher',
					'default' => false,
				),
				array(
					'name'    => 'custom_color_1',
					'type'    => 'color',
					'subdesc' => __( 'Custom Color', 'bigboom' ),
				),
			)
		),
		array(
			'type'  => 'divider',
		),
		array(
			'name'     => 'custom_css',
			'label'    => __( 'Custom CSS', 'bigboom' ),
			'type'     => 'code_editor',
			'language' => 'css',
			'subdesc'  => __( 'Enter your custom style rulers here', 'bigboom' )
		),
	);

	$options['fields']['header'] = array(
		array(
			'name'    => 'topbar',
			'label'   => __( 'Topbar', 'bigboom' ),
			'desc'    => __( 'Display topbar before site header', 'bigboom' ),
			'type'    => 'switcher',
			'default' => true,
		),
		array(
			'type'    => 'divider',
		),
		array(
			'name'  => 'logo',
			'label' => __( 'Logo', 'bigboom' ),
			'desc'  => __( 'Select logo from media library or upload new one', 'bigboom' ),
			'type'  => 'image',
		),
		array(
			'name'     => 'logo_size',
			'label'    => __( 'Logo Size (Optional)', 'bigboom' ),
			'desc'     => __( 'If the Retina Logo uploaded, please enter the size of the Standard Logo just upload above (not the Retina Logo)', 'bigboom' ),
			'type'     => 'group',
			'children' => array(
				array(
					'name'    => 'logo_size_width',
					'type'    => 'number',
					'subdesc' => __( '(Width)', 'bigboom' ),
					'suffix'  => 'px'
				),
				array(
					'name'    => 'logo_size_height',
					'type'    => 'number',
					'subdesc' => __( '(Height)', 'bigboom' ),
					'suffix'  => 'px'
				),
			)
		),
		array(
			'name'     => 'logo_margin',
			'label'    => __( 'Logo Margin', 'bigboom' ),
			'type'     => 'group',
			'children' => array(
				array(
					'name'    => 'logo_margin_top',
					'type'    => 'number',
					'size'    => 'mini',
					'subdesc' => __( 'top', 'bigboom' ),
					'suffix'  => 'px'
				),
				array(
					'name'    => 'logo_margin_right',
					'type'    => 'number',
					'size'    => 'mini',
					'subdesc' => __( 'right', 'bigboom' ),
					'suffix'  => 'px'
				),
				array(
					'name'    => 'logo_margin_bottom',
					'type'    => 'number',
					'size'    => 'mini',
					'subdesc' => __( 'bottom', 'bigboom' ),
					'suffix'  => 'px'
				),
				array(
					'name'    => 'logo_margin_left',
					'type'    => 'number',
					'size'    => 'mini',
					'subdesc' => __( 'left', 'bigboom' ),
					'suffix'  => 'px'
				),
			)
		),
		array(
			'name'  => 'header_scripts',
			'label' => __( 'Header Script', 'bigboom' ),
			'desc'  => __( 'Enter your custom scripts here like Google Analytics script', 'bigboom' ),
			'type'  => 'code_editor',
			'language' => 'html',
		),
	);

	$options['fields']['blog'] = array(
		array(
			'name'    => 'show_page_comment',
			'label'   => __( 'Show page comment', 'bigboom' ),
			'type'    => 'switcher',
			'default' => false,
		),
		array(
			'type' => 'divider'
		),
		array(
			'name'    => 'excerpt_length',
			'label'   => __( 'Excerpt Length', 'bigboom' ),
			'type'    => 'number',
			'size'    => 'small',
			'default' => 30,
		),
	);

	$options['fields']['shop'] = array(
		array(
			'name'    => 'product_newness',
			'label'   => __( 'Product Newness', 'bigboom' ),
			'type'    => 'number',
			'size'    => 'small',
			'desc'    => __( 'Display the "New" badge for how many days?', 'bigboom' ),
			'default' => 3,
		),
	);

	$options['fields']['footer'] = array(
		array(
			'name'    => 'footer_ads',
			'label'   => __( 'Enable Footer Ads', 'bigboom' ),
			'desc'    => __( 'Display an ads or any content you want before site footer', 'bigboom' ),
			'type'    => 'switcher',
			'default' => 1,
		),
		array(
			'name'    => 'footer_ads_content',
			'label'   => __( 'Footer Ads', 'bigboom' ),
			'subdesc' => __( 'Shortcodes are allowed', 'bigboom' ),
			'type'    => 'textarea',
		),
		array(
			'type' => 'divider'
		),
		array(
			'name'    => 'footer_widgets',
			'label'   => __( 'Enable Footer Widget', 'bigboom' ),
			'type'    => 'switcher',
			'default' => 1,
		),
		array(
			'name'    => 'footer_widget_columns',
			'label'   => __( 'Footer Widget Columns', 'bigboom' ),
			'desc'    => __( 'How many sidebar you want to show on footer', 'bigboom' ),
			'type'    => 'image_toggle',
			'default' => 5,
			'options' => array(
				1 => TA_OPTIONS_URL . 'img/footer/one-column.png',
				2 => TA_OPTIONS_URL . 'img/footer/two-columns.png',
				3 => TA_OPTIONS_URL . 'img/footer/three-columns.png',
				4 => TA_OPTIONS_URL . 'img/footer/four-columns.png',
				5 => TA_OPTIONS_URL . 'img/footer/five-columns.png',
			)
		),
		array(
			'type' => 'divider'
		),
		array(
			'name'    => 'footer_copyright',
			'label'   => __( 'Footer Copyright', 'bigboom' ),
			'desc'    => __( 'Copyright text to display on left side of footer', 'bigboom' ),
			'subdesc' => __( 'Shortcodes are allowed', 'bigboom' ),
			'type'    => 'textarea',
			'default' => __( 'Copyright &copy; 2015', 'bigboom' ),
		),
		array(
			'name'    => 'footer_info',
			'label'   => __( 'Extra Information', 'bigboom' ),
			'desc'    => __( 'Provide more information to display on right side of footer', 'bigboom' ),
			'subdesc' => __( 'Shortcodes are allowed', 'bigboom' ),
			'type'    => 'textarea',
		),
		array(
			'name'  => 'footer_script',
			'label' => __( 'Footer Scripts', 'bigboom' ),
			'type'  => 'code_editor',
			'language' => 'html',
		),
	);

	$options['fields']['export'] = array(
		array(
			'name'    => 'backup',
			'label'   => __( 'Backup Settings', 'bigboom' ),
			'subdesc' => __( 'You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options" button above<br><img src="http://www.ten28.com/qa.jpg">', 'bigboom' ),
			'type'    => 'backup',
		),
	);

	return $options;
}
