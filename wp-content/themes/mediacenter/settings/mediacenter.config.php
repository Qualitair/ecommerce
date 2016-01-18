<?php
if ( !class_exists( 'ReduxFramework' ) ) {
	return;
}

if ( !class_exists( "Media_Center_Theme_Options" ) ) {

	class Media_Center_Theme_Options {
		
		public function __construct( ) {
			// Base Config for Media Center
			add_action( 'after_setup_theme', array($this, 'load_config') );
		}

		public function load_config() {

			$entranceAnimations = array(
				'none'				=> __( 'No Animation', 'media_center' ),
		        'bounceIn'			=> __( 'BounceIn', 'media_center' ),
		        'bounceInDown'		=> __( 'BounceInDown', 'media_center' ),
		        'bounceInLeft'		=> __( 'BounceInLeft', 'media_center' ),
		        'bounceInRight'		=> __( 'BounceInRight', 'media_center' ),
		        'bounceInUp'		=> __( 'BounceInUp', 'media_center' ),
				'fadeIn'			=> __( 'FadeIn', 'media_center' ),
				'fadeInDown'		=> __( 'FadeInDown', 'media_center' ),
				'fadeInDownBig'		=> __( 'FadeInDown Big', 'media_center' ),
				'fadeInLeft'		=> __( 'FadeInLeft', 'media_center' ),
				'fadeInLeftBig'		=> __( 'FadeInLeft Big', 'media_center' ),
				'fadeInRight'		=> __( 'FadeInRight', 'media_center' ),
				'fadeInRightBig'	=> __( 'FadeInRight Big', 'media_center' ),
				'fadeInUp'			=> __( 'FadeInUp', 'media_center' ),
				'fadeInUpBig'		=> __( 'FadeInUp Big', 'media_center' ),
				'flipInX'			=> __( 'FlipInX', 'media_center' ),
				'flipInY'			=> __( 'FlipInY', 'media_center' ),
				'lightSpeedIn'		=> __( 'LightSpeedIn', 'media_center' ),
				'rotateIn' 			=> __( 'RotateIn', 'media_center' ),
				'rotateInDownLeft' 	=> __( 'RotateInDown Left', 'media_center' ),
				'rotateInDownRight' => __( 'RotateInDown Right', 'media_center' ),
				'rotateInUpLeft' 	=> __( 'RotateInUp Left', 'media_center' ),
				'rotateInUpRight' 	=> __( 'RotateInUp Right', 'media_center' ),
				'roleIn'			=> __( 'RoleIn', 'media_center' ),
		        'zoomIn'			=> __( 'ZoomIn', 'media_center' ),
				'zoomInDown'		=> __( 'ZoomInDown', 'media_center' ),
				'zoomInLeft'		=> __( 'ZoomInLeft', 'media_center' ),
				'zoomInRight'		=> __( 'ZoomInRight', 'media_center' ),
				'zoomInUp'			=> __( 'ZoomInUp', 'media_center' ),
			);

			$sections = array(

				array(
					'title' => __('General', 'media_center'),
					'icon' 	=> 'fa fa-dot-circle-o',
					'fields' => array(
						array(
							'title' => __('Favicon', 'media_center'),
							'subtitle' => __('<em>Upload your custom Favicon image. <br>.ico or .png file required.</em>', 'media_center'),
							'id' => 'favicon',
							'type' => 'media',
							'default' => array(
								'url' => get_template_directory_uri() . '/favicon.ico',
							),
						),
						
						array(
							'title' => __('Your Logo', 'media_center'),
							'subtitle' => __('<em>Upload your logo image. Recommended dimension : 233x54 pixels</em>', 'media_center'),
							'id' => 'site_logo',
							'type' => 'media',
						),
						
						array(
							'title' => __('Use text instead of logo ?', 'media_center'),
							'id' => 'use_text_logo',
							'type' => 'checkbox',
							'default' => '0',
						),
						
						array(
							'title' => __('Logo Text', 'media_center'),
							'subtitle' => __('<em>Will be displayed only if use text logo is checked.</em>', 'media_center'),
							'id' => 'logo_text',
							'type' => 'text',
							'default' => 'Media Center',
							'required' => array(
								0 => 'use_text_logo',
								1 => '=',
								2 => 1,
							),
						),

						array(
							'title' => __('Scroll to Top', 'media_center'),
							'id' => 'scroll_to_top',
							'on' => __('Enabled', 'media_center'),
							'off' => __('Disabled', 'media_center'),
							'type' => 'switch',
							'default' => 1,
						),

						array(
                            'title' => __('Hide Product Title in Breadcrumb ?', 'media_center'),
                            'subtitle'    => __( 'Click Yes if you have longer product titles and the breadcrumb is overflowing.', 'media_center' ),
                            'id' => 'breadcrumb_ignore_title',
                            'on' => __('Yes', 'media_center'),
                            'off' => __('No', 'media_center'),
                            'type' => 'switch',
                            'default' => 1,
                        ),
					),
				),

				array(
					'title' => __('Header', 'media_center'),
					'icon' 	=> 'fa fa-arrow-circle-o-up',
					'fields' => array(
						array(
							'id'		=> 'header_style',
							'type' 		=> 'radio',
							'title'		=> __( 'Header Style', 'media_center' ),
							'options' => array(
								'header-style-1' => __( 'Header 1', 'media_center' ),
								'header-style-2' => __( 'Header 2', 'media_center' )
							),
							'default' => 'header-style-1',
						),
						array(
							'id' => 'top_bar_info',
							'icon' => true,
							'type' => 'info',
							'raw' => __('<h3 style="margin: 0;">Top Bar</h3>', 'media_center'),
						),
						array(
							'title' => __('Top Bar', 'media_center'),
							'subtitle' => __('<em>Enable / Disable the Top Bar.</em>', 'media_center'),
							'id' => 'top_bar_switch',
							'on' => __('Enabled', 'media_center'),
							'off' => __('Disabled', 'media_center'),
							'type' => 'switch',
							'default' => 1,
						),
						array(
							'title' => __('Top Bar Left', 'media_center'),
							'subtitle' => __('<em>Enable / Disable the Top Bar Left Navigation.</em>', 'media_center'),
							'id' => 'top_bar_left_switch',
							'on' => __('Enabled', 'media_center'),
							'off' => __('Disabled', 'media_center'),
							'type' => 'switch',
							'default' => 1,
						),
						array(
							'title' => __('Top Bar Right', 'media_center'),
							'subtitle' => __('<em>Enable / Disable the Top Bar Right Navigation.</em>', 'media_center'),
							'id' => 'top_bar_right_switch',
							'on' => __('Enabled', 'media_center'),
							'off' => __('Disabled', 'media_center'),
							'type' => 'switch',
							'default' => 1,
						),
						array(
							'title'			=> __( 'Hide Top Bar in mobile', 'media_center' ),
							'id'			=> 'hide_top_bar_on_mobile',
							'on'			=> __( 'Yes', 'media_center' ),
							'off'			=> __( 'No', 'media_center' ),
							'type'			=> 'switch',
							'default'		=> 0
						),

						array(
							'id' => 'main_header_info',
							'icon' => true,
							'type' => 'info',
							'raw' => '<h3 style="margin: 0;">Main Header</h3>',
						),

						array(
							'title' => __('Sticky Header', 'media_center'),
							'subtitle' => __('<em>Enable / Disable the Sticky Header. Available only for Header Style 2</em>', 'media_center'),
							'id' => 'sticky_header',
							'on' => __('Enabled', 'media_center'),
							'off' => __('Disabled', 'media_center'),
							'type' => 'switch',
							'default' => 0,
						),

						array(
							'title' => __('Support Phone Number', 'media_center'),
							'id' => 'header_support_phone',
							'type' => 'text',
							'default' => '(+800) 123 456 7890',
						),

						array(
							'title' => __( 'Support Email Address', 'media_center' ),
							'id' => 'header_support_email',
							'type' => 'text',
							'default' => 'contact@support.com',
						),

						array(
							'id' 	=> 'search_bar_info',
							'icon' 	=> true,
							'type' 	=> 'info',
							'raw' 	=> __('<h3 style="margin: 0;">Search Bar</h3>', 'media_center'),
						),

						array(
							'title' 	=> __( 'Live Search', 'media_center' ),
							'id'		=> 'live_search',
							'type'		=> 'switch',
							'default'	=> 1,
							'on'		=> __( 'Enabled', 'media_center' ),
							'off'		=> __( 'Disabled', 'media_center' )
						),

						array(
							'title' 	=> __( 'Search Result Template', 'media_center' ),
							'id'		=> 'live_search_template',
							'type' 		=> 'ace_editor',
							'mode' 		=> 'html',
							'required' 	=> array( 'live_search', 'equals', 1 ),
							'default'	=> '<a href="{{url}}" class="media live-search-media"><img src="{{image}}" class="pull-left" height="60" width="60"><div class="media-body"><p>{{{value}}}</p></div></a>',
							'desc'		=> __( 'Available parameters : {{value}}, {{url}}, {{image}}, {{brand}} and {{{price}}}', 'media_center')
						),

						array(
							'title' 	=> __( 'Show Categories Filter', 'media_center' ),
							'id'		=> 'display_search_categories_filter',
							'type'		=> 'switch',
							'default'	=> 1,
							'on'		=> __( 'Yes', 'media_center' ),
							'off'		=> __( 'No', 'media_center' )
						),

						array(
							'title' 	=> __( 'Search Category Dropdown', 'media_center' ),
							'id' 		=> 'header_search_dropdown',
							'type' 		=> 'radio',
							'options' 	=> array(
								'hsd0' 	=> __( 'Include only top level categories', 'media_center' ),
								'hsd1' 	=> __( 'Include all categories', 'media_center' )
							),
							'default' 	=> 'hsd0',
							'required' 	=> array( 'display_search_categories_filter', 'equals', 1 )
						),
					),
				),

				array(
					'title'				=> __( 'Navigation', 'media_center' ),
					'icon'				=> 'fa fa-navicon',
					'fields'			=> array(
						array(
							'id' 		=> 'top_bar_left_info',
							'icon' 		=> true,
							'type' 		=> 'info',
							'raw' 		=> '<h3 style="margin: 0;">Top Bar Left Menu</h3>',
						),
						array(
							'title'		=> __( 'Dropdown Trigger', 'media_center' ),
							'id'		=> 'top_bar_left_menu_dropdown_trigger',
							'type'		=> 'select',
							'options'	=> array(
								'click'	=> __( 'Click', 'media_center' ),
								'hover'	=> __( 'Hover', 'media_center' ),
							),
							'default'	=> 'click',
						),
						array(
							'title'		=> __( 'Dropdown Animation', 'media_center' ),
							'id'		=> 'top_bar_left_menu_dropdown_animation',
							'type'		=> 'select',
							'options'	=> $entranceAnimations,
							'default'	=> 'fadeInUp',
						),

						array(
							'id' 		=> 'top_bar_right_info',
							'icon' 		=> true,
							'type' 		=> 'info',
							'raw' 		=> '<h3 style="margin: 0;">Top Bar Right Menu</h3>',
						),
						array(
							'title'		=> __( 'Dropdown Trigger', 'media_center' ),
							'id'		=> 'top_bar_right_menu_dropdown_trigger',
							'type'		=> 'select',
							'options'	=> array(
								'click'	=> __( 'Click', 'media_center' ),
								'hover'	=> __( 'Hover', 'media_center' ),
							),
							'default'	=> 'click',
						),
						array(
							'title'		=> __( 'Dropdown Animation', 'media_center' ),
							'id'		=> 'top_bar_right_menu_dropdown_animation',
							'type'		=> 'select',
							'options'	=> $entranceAnimations,
							'default'	=> 'fadeInUp',
						),

						array(
							'id' 		=> 'main_navigation_info',
							'icon' 		=> true,
							'type' 		=> 'info',
							'raw' 		=> '<h3 style="margin: 0;">Main Navigation Menu</h3>',
						),
						array(
							'title'		=> __( 'Dropdown Trigger', 'media_center' ),
							'id'		=> 'main_navigation_menu_dropdown_trigger',
							'type'		=> 'select',
							'options'	=> array(
								'click'	=> __( 'Click', 'media_center' ),
								'hover'	=> __( 'Hover', 'media_center' ),
							),
							'default'	=> 'click',
						),
						array(
							'title'		=> __( 'Dropdown Animation', 'media_center' ),
							'id'		=> 'main_navigation_menu_dropdown_animation',
							'type'		=> 'select',
							'options'	=> $entranceAnimations,
							'default'	=> 'fadeInUp',
						),

						array(
							'id' 		=> 'shop_by_departments_info',
							'icon' 		=> true,
							'type' 		=> 'info',
							'raw' 		=> '<h3 style="margin: 0;">Shop By Departments Menu</h3>',
						),

						array(
							'title'		=> __( 'Dropdown Trigger', 'media_center' ),
							'id'		=> 'shop_by_departments_menu_dropdown_trigger',
							'type'		=> 'select',
							'options'	=> array(
								'click'	=> __( 'Click', 'media_center' ),
								'hover'	=> __( 'Hover', 'media_center' ),
							),
							'default'	=> 'click',
						),
						array(
							'title'		=> __( 'Dropdown Animation', 'media_center' ),
							'id'		=> 'shop_by_departments_menu_dropdown_animation',
							'type'		=> 'select',
							'options'	=> $entranceAnimations,
							'default'	=> 'fadeInUp',
						),

						array(
							'id' 		=> 'wpml_info',
							'icon' 		=> true,
							'type' 		=> 'info',
							'raw' 		=> '<h3 style="margin: 0;">Language and Currency Switcher</h3>',
						),

						array(
							'title'		=> __( 'Language Switcher', 'media_center' ),
							'id'		=> 'enable_language_switcher',
							'type'		=> 'switch',
							'on'		=> __( 'Enabled', 'media_center' ),
							'off'		=> __( 'Disabled', 'media_center' ),
							'subtitle'	=> __( '<em>Available only if WPML Plugin is enabled</em>', 'media_center' ),
							'default'	=> 1,
						),

						array(
							'title'		=> __( 'Language Switcher Position', 'media_center' ),
							'id'		=> 'language_switcher_position',
							'type'		=> 'select',
							'options'	=>  array(
								'top_bar_left'	=> __( 'Top Bar Left Menu', 'media_center' ),
								'top_bar_right'	=> __( 'Top Bar Right Menu', 'media_center' ),
							),
							'default'	=> 'top_bar_right',
						),
						array(
							'title'		=> __( 'Currency Switcher', 'media_center' ),
							'id'		=> 'enable_currency_switcher',
							'type'		=> 'switch',
							'on'		=> __( 'Enabled', 'media_center' ),
							'off'		=> __( 'Disabled', 'media_center' ),
							'subtitle'	=> __( '<em>Available only if WPML Plugin and WooCommerce Multilingual is enabled</em>', 'media_center' ),
							'default'	=> 1,
						),
						array(
							'title'		=> __( 'Currency Switcher Position', 'media_center' ),
							'id'		=> 'currency_switcher_position',
							'type'		=> 'select',
							'options'	=>  array(
								'top_bar_left'	=> __( 'Top Bar Left Menu', 'media_center' ),
								'top_bar_right'	=> __( 'Top Bar Right Menu', 'media_center' ),
							),
							'default'	=> 'top_bar_right',
						),
					)
				),

				array(
					'title' => __('Footer', 'media_center'),
					'icon' 	=> 'fa fa-arrow-circle-o-down',
					'fields' => array(
						array(
							'title' => __('Footer Contact Info Text', 'media_center'),
							'id' => 'footer_contact_info_text',
							'type' => 'textarea',
							'default' => __('Feel free to contact us via phone,email or just send us mail.', 'media_center'),
						),

						array(
							'title' => __('Footer Contact Info Address', 'media_center'),
							'id' => 'footer_contact_info_address',
							'type' => 'textarea',
							'default' => '17 Princess Road, London, Greater London NW1 8JR, UK 1-888-8MEDIA (1-888-892-9953)',
						),

						array(
							'title' => __('Footer Payment Images', 'media_center'),
							'subtitle' => __('<em>Upload your payment images. Preferred dimension for each image 40x29 px.</em>', 'media_center'),
							'id' => 'credit_card_icons_gallery',
							'type' => 'gallery',
						),

						array(
							'title' => __('Footer Copyright Text', 'media_center'),
							'subtitle' => __('<em>Enter your copyright information here.</em>', 'media_center'),
							'id' => 'footer_copyright_text',
							'type' => 'textarea',
							'default' => '&copy; <a href="#">Media Center</a> - All Rights Reserved',
						),
					),
				),

				array(

					'title' => __('Shop', 'media_center'),
					'icon' 	=> 'fa fa-shopping-cart',
					'fields' => array(
						
						array(
							'id' => 'shop_general_info',
							'icon' => true,
							'type' => 'info',
							'raw' => '<h3 style="margin: 0;">General</h3>',
						),

						array(
							'title' => __('Catalog Mode', 'media_center'),
							'subtitle' => __('<em>Enable / Disable the Catalog Mode.</em>', 'media_center'),
							'desc' => __('<em>When enabled, the feature Turns Off the shopping functionality of WooCommerce.</em>', 'media_center'),
							'id' => 'catalog_mode',
							'on' => __('Enabled', 'media_center'),
							'off' => __('Disabled', 'media_center'),
							'type' => 'switch',
						),

						array(
							'title' 	=> __('Default View', 'media_center'),
							'subtitle' 	=> __('<em>Choose a default view between grid and list views.</em>', 'media_center'),
							'id' 		=> 'shop_default_view',
							'type'		=> 'select',
							'options'	=> array(
								'grid-view'	=> __( 'Grid View', 'media_center' ),
								'list-view' => __( 'List View', 'media_center' ),
							),
							'default'	=> 'grid-view',
						),

						array(
							'title' 	=> __('Remember User View ?', 'media_center' ),
							'desc'		=> __( 'When user switches a view, should we maintain the view in all pages ?', 'media_center' ),
							'id' 		=> 'remember_user_view',
							'type'		=> 'switch',
							'on'		=> __( 'Yes', 'media_center' ),
							'off'		=> __( 'No', 'media_center' ),
							'default'	=> 0
						),

						array(
							'title' => __('Product Item Size', 'media_center'),
							'subtitle' => __('Product item size determines the number of products per row.', 'media_center'),
							'id' => 'product_item_size',
							'type' => 'select',
							'options' => array(
								'size-small' => __( 'Small', 'media_center'),
								'size-medium' => __( 'Medium', 'media_center'),
								'size-big' => __( 'Large', 'media_center'),
							),
							'default' => 'size-medium',
						),

						array(
							'title' 		=> __('Number of Products per Page', 'media_center'),
							'subtitle' 		=> __('<em>Drag the slider to set the number of products per page <br />to be listed on the shop page and catalog pages.</em>', 'media_center'),
							'id' 			=> 'products_per_page',
							'min' 			=> '3',
							'step' 			=> '1',
							'max' 			=> '48',
							'type' 			=> 'slider',
							'default' 		=> '15',
							'display_value' => 'label'
						),

						array(
							'id' => 'shop_product_item',
							'icon' => true,
							'type' => 'info',
							'raw' => '<h3 style="margin: 0;">Product Item Settings</h3>',
						),

						array(
							'title' 	=> __('Product Item Animation', 'media_center'),
							'subtitle' 	=> __('<em>A list of all the product animations.</em>', 'media_center'),
							'id' 		=> 'products_animation',
							'type' 		=> 'select',
							'options' 	=> $entranceAnimations,
							'default' 	=> 'none',
						),

						array(
							'title' => __('Echo Lazy loading', 'media_center'),
							'subtitle' => __( '<em>Lazy load product images. Product images will not be loaded until scrolled into view.</em>', 'media_center' ),
							'id' => 'lazy_loading',
							'on' => __('Enabled', 'media_center'),
							'off' => __('Disabled', 'media_center'),
							'type' => 'switch',
							'default' => 1,
						),

						array(
							'title' 	=> __( 'Hard Crop Images ?', 'media_center' ),
							'subtitle'	=> __( 'If you do not like your images to be cropped, please select "No"', 'media_center' ),
							'type'		=> 'switch',
							'on'		=> __( 'Yes', 'media_center' ),
							'off'		=> __( 'No', 'media_center' ),
							'id'		=> 'hard_crop_images',
							'default'	=> 0,
							'desc'		=> __( 'You will have to regenerate thumbnails after changing this setting', 'media_center' ),
						),

						array(
							'title'		=> __( 'Show rating in Grid View ?', 'media_center' ),
							'type'		=> 'switch',
							'on'		=> __( 'Yes', 'media_center' ),
							'off'		=> __( 'No', 'media_center' ),
							'id'		=> 'show_rating_in_grid',
							'default'	=> 0,
						),

						array(
							'id' => 'shop_page_settings',
							'icon' => true,
							'type' => 'info',
							'raw' => '<h3 style="margin: 0;">Shop Page Settings</h3>',
						),

						array(
							'id'      => 'shop_page_layout',
							'title'   => __( 'Shop Page Layout', 'media_center' ),
							'type'	  => 'select',
							'options' => array(
								'full-width' 	=> __( 'Full-width', 'media_center' ),
								'sidebar-left'  => __( 'Left Sidebar', 'media_center' ),
								'sidebar-right' => __( 'Right Sidebar', 'media_center' ),
							),
							'default' => 'sidebar-left'
						),

						array(
							'id'      => 'single_product_layout',
							'title'   => __( 'Single Product Page Layout', 'media_center' ),
							'type'	  => 'select',
							'options' => array(
								'full-width' 	=> __( 'Full-width', 'media_center' ),
								'sidebar-left'  => __( 'Left Sidebar', 'media_center' ),
								'sidebar-right' => __( 'Right Sidebar', 'media_center' ),
							),
							'default' => 'full-width'
						),

						array(
							'title'		=> __( 'Product Comparision Page', 'media_center' ),
							'subtitle'	=> __( 'This sets the product comparison page for your shop', 'media_center' ),
							'type'		=> 'select',
							'data'		=> 'pages',
							'id'		=> 'product_comparison_page'
						),
					),
				),

				array(
					'title' => __('Blog', 'media_center'),
					'icon' 	=> 'fa fa-list-alt',
					'fields' => array(
						array(
							'title' 	=> __('Blog Layout', 'media_center'),
							'subtitle' 	=> __('<em>Select the layout for the Blog Listing. <br />The second option will enable the Blog Listing Sidebar.</em>', 'media_center'),
							'id' 		=> 'blog_layout',
							'type' 		=> 'image_select',
							'options' 	=> array(
								'sidebar_right' 	=> get_template_directory_uri() . '/framework/images/theme_options/icons/blog_right_sidebar.png',
								'without_sidebar' 	=> get_template_directory_uri() . '/framework/images/theme_options/icons/blog_no_sidebar.png',
								'sidebar_left' 		=> get_template_directory_uri() . '/framework/images/theme_options/icons/blog_left_sidebar.png',
							),
							'default' 	=> 'sidebar_right',
						),
						array(
							'title' 	=> __('Blog Style', 'media_center'),
							'subtitle' 	=> __('<em>Select the layout style for the Blog Listing.</em>', 'media_center'),
							'id' 		=> 'blog_style',
							'type' 		=> 'select',
							'options' 	=> array(
								'normal' 		=> __( 'Normal', 'media_center' ),
								'list-view' 	=> __( 'List View', 'media_center' ),
								'grid-view'		=> __( 'Grid View', 'media_center' )
							),
							'default' 	=> 'normal',
						),
						array(
							'title' 	=> __( 'Full width Density', 'media_center' ),
							'subtitle'  => __( 'Applicable only if you choose <em>without sidebar</em> option for blog layout', 'media_center' ),
							'id' 		=> 'full_width_density',
							'type' 		=> 'radio',
							'options'	=> array(
								'wide' 			=> __( 'Wide', 'media_center' ),
								'narrow' => __( 'Narrow', 'media_center' )
							),
							'default' 	=> 'narrow',
						),
						array(
							'title'		=> __( 'Force Excerpt', 'media_center' ),
							'subtitle'  => __( 'Show only excerpt instead of blog content in archive pages', 'media_center' ),
							'id'		=> 'force_excerpt',
							'on' 		=> __('Yes', 'media_center'),
							'off' 		=> __('No', 'media_center'),
							'type' 		=> 'switch',
							'default' 	=> 0,		
						),
						array(
							'title'		=> __( 'Excerpt Length', 'media_center' ),
							'id'		=> 'excerpt_length',
							'type'		=> 'text',
							'default'	=> 75,
							'required'	=> array( 'force_excerpt', 'equals', 1 )		
						),
					),
				),

				array(
					'title' => __('Styling', 'media_center'),
					'icon' 	=> 'fa fa-pencil-square-o',
					'fields' => array(
						array(
							'title' 	=> __( 'Use a predefined color scheme', 'media_center' ),
							'on' 		=> __('Yes', 'media_center'),
							'off' 		=> __('No', 'media_center'),
							'type' 		=> 'switch',
							'default' 	=> 1,
							'id' 		=> 'use_predefined_color'
						),
						array(
							'title' 	=> __('Main Theme Color', 'media_center'),
							'subtitle' 	=> __('<em>The main color of the site.</em>', 'media_center'),
							'id' 		=> 'main_color',
							'type' 		=> 'select',
							'options' 	=> array(
								'green' 	 => __( 'Green', 'media_center' ) ,
								'blue' 		 => __( 'Blue', 'media_center' ) ,
								'red' 		 => __( 'Red', 'media_center' ) ,
								'orange' 	 => __( 'Orange', 'media_center' ) ,
								'navy' 		 => __( 'Navy', 'media_center' ) ,
								'dark-green' => __( 'Dark-green', 'media_center' ) ,
							),
							'default' 	=> 'green',
							'required' 	=> array( 'use_predefined_color', 'equals', 1 ),
						),
						array(
							'id' 		=> 'main_custom_color',
							'icon' 		=> true,
							'type' 		=> 'info',
							'raw'   	=> '<h3>'. __( 'Using a Custom theme Color', 'media_center' ). '</h3>' .
										   '<p>' . __( 'Using a custom color is simple but it requires a few extra steps.', 'media_center' ) . '</p>' .
										   '<p>' . __( 'Method 1 (Recommended) : Using LESS', 'media_center' ) . '</p>' .
										   '<ol>' .
										   '<li>'. __( 'Navigate to <em>assets/less/custom-color.less</em> file.', 'media_center' ) . '</li>'.
										   '<li>'. __( 'On line 7, set <mark>@primary-color</mark> to the color of your choice.', 'media_center' ) . '</li>'.
										   '<li>'. __( 'Compile <em>assets/less/custom-color.less</em> file to <em>assets/css/custom-color.css</em>', 'media_center' ) . '</li>'.
										   '<li>'. __( 'You can also use <a href="http://less2css.org" target="_blank">less2css.org</a> to compile the LESS file and copy the output to <em>assets/css/custom-color.css</em>', 'media_center' ) . '</li>'.
										   '</ol>'.
										   '<p>' . __( 'Method 2 : Using CSS and Find and Replace', 'media_center' ) . '</p>' .
										   '<ol>' .
										   '<li>'. __( 'Navigate to <em>assets/css/green.css</em> file.', 'media_center' ) . '</li>'.
										   '<li>'. __( 'Copy the entire file content and paste it in <em>assets/css/custom-color.css</em>.', 'media_center' ) . '</li>'.
										   '<li>'. __( 'Open <em>assets/css/custom-color.css</em> file using your favourite code editor.', 'media_center' ) . '</li>'.
										   '<li>'. __( 'Do a find and replace of green color which is #59b210 with your choice of color.', 'media_center' ) . '</li>'.
										   '<li>'. __( 'We have also used darken and lighten version of the primary color. Replace them as well.', 'media_center' ) . '</li>'.
										   '</ol>'.
										   '</ol>',
							'required' 	=> array( 'use_predefined_color', 'equals', 0 )
						),
					),
				),

				array(
					'title' => __('Typography', 'media_center'),
					'icon' => 'fa fa-font',
					'fields' => array(
						array(
							'title' 	=> __( 'Use default font settings ?', 'media_center' ),
							'subtitle'	=> __( '<em>Toggle No if you want to override with your own fonts</em>', 'media_center' ),
							'id'		=> 'use_default_font',
							'type'		=> 'switch',
							'on'		=> __( 'Yes', 'media_center' ),
							'off'		=> __( 'No', 'media_center' ),
							'default'   => 1
						),
						array(
							'title' 		=> __('Default Font Family', 'media_center'),
							'subtitle' 		=> __('<em>Pick the default font family for your site.</em>', 'media_center'),
							'id' 			=> 'default_font',
							'type' 			=> 'typography',
							'line-height' 	=> false,
							'text-align' 	=> false,
							'font-style' 	=> false,
							'font-weight' 	=> false,
							'font-size' 	=> false,
							'color' 		=> false,
							'required'		=> array( 'use_default_font', 'equals', 0 ),
							'default' 		=> array(
								'font-family' 	=> 'Open Sans',
								'subsets' 		=> '',
							),
						),

						array(
							'title' 		=> __('Title Font Family', 'media_center'),
							'subtitle' 		=> __('<em>Pick the font family for titles for your site.</em>', 'media_center'),
							'id' 			=> 'title_font',
							'type' 			=> 'typography',
							'line-height' 	=> false,
							'text-align' 	=> false,
							'font-style' 	=> false,
							'font-weight' 	=> false,
							'font-size' 	=> false,
							'color' 		=> false,
							'default' 		=> array(
								'font-family' 	=> 'Open Sans',
								'subsets' 		=> '',
							),
							'required'		=> array( 'use_default_font', 'equals', 0 ),
						),
					),
				),

				array(
					'title' => __('Social Media', 'media_center'),
					'icon' => 'fa fa-share-square-o',
					'fields' => array(
						array(
							'title' => __('Facebook', 'media_center'),
							'subtitle' => __('<em>Type your Facebook profile URL here.</em>', 'media_center'),
							'id' => 'facebook_link',
							'type' => 'text',
							'default' => 'https://www.facebook.com/',
						),
						array(
							'title' => __('Twitter', 'media_center'),
							'subtitle' => __('<em>Type your Twitter profile URL here.</em>', 'media_center'),
							'id' => 'twitter_link',
							'type' => 'text',
							'default' => 'http://twitter.com/',
						),
						array(
							'title' => __('Pinterest', 'media_center'),
							'subtitle' => __('<em>Type your Pinterest profile URL here.</em>', 'media_center'),
							'id' => 'pinterest_link',
							'type' => 'text',
							'default' => 'http://www.pinterest.com/',
						),
						array(
							'title' => __('LinkedIn', 'media_center'),
							'subtitle' => __('<em>Type your LinkedIn profile URL here.</em>', 'media_center'),
							'id' => 'linkedin_link',
							'type' => 'text',
						),
						array(
							'title' => __('Google+', 'media_center'),
							'subtitle' => __('<em>Type your Google+ profile URL here.</em>', 'media_center'),
							'id' => 'googleplus_link',
							'type' => 'text',
						),
						array(
							'title' => __('RSS', 'media_center'),
							'subtitle' => __('<em>Type your RSS Feed URL here.</em>', 'media_center'),
							'id' => 'rss_link',
							'type' => 'text',
						),

						array(
							'title' => __('Tumblr', 'media_center'),
							'subtitle' => __('<em>Type your Tumblr URL here.</em>', 'media_center'),
							'id' => 'tumblr_link',
							'type' => 'text',
						),

						array(
							'title' => __('Instagram', 'media_center'),
							'subtitle' => __('<em>Type your Instagram profile URL here.</em>', 'media_center'),
							'id' => 'instagram_link',
							'type' => 'text',
						),

						array(
							'title' => __('Youtube', 'media_center'),
							'subtitle' => __('<em>Type your Youtube profile URL here.</em>', 'media_center'),
							'id' => 'youtube_link',
							'type' => 'text',
						),

						array(
							'title' => __('Vimeo', 'media_center'),
							'subtitle' => __('<em>Type your Vimeo profile URL here.</em>', 'media_center'),
							'id' => 'vimeo_link',
							'type' => 'text',
						),

						array(
							'title' => __('Dribbble', 'media_center'),
							'subtitle' => __('<em>Type your Dribble profile URL here.</em>', 'media_center'),
							'id' => 'dribbble_link',
							'type' => 'text',
						),

						array(
							'title' => __('Stumble Upon', 'media_center'),
							'subtitle' => __('<em>Type your Stumble Upon profile URL here.</em>', 'media_center'),
							'id' => 'stumble_upon_link',
							'type' => 'text',
						),
					),
				),

				array(
					'title' => __('Custom Code', 'media_center'),
					'icon' => 'fa fa-code',
					'fields' => array(

						array(
							'title' => __('Custom CSS', 'media_center'),
							'subtitle' => __('<em>Paste your custom CSS code here.</em>', 'media_center'),
							'id' => 'custom_css',
							'type' => 'ace_editor',
							'mode' => 'css',
						),

						array(
							'title' => __('Header JavaScript Code', 'media_center'),
							'subtitle' => __('<em>Paste your custom JS code here. The code will be added to the header of your site.</em>', 'media_center'),
							'id' => 'header_js',
							'type' => 'ace_editor',
							'mode' => 'javascript',
						),

						array(
							'title' => __('Footer JavaScript Code', 'media_center'),
							'subtitle' => __('<em>Here is the place to paste your Google Analytics code or any other JS code you might want to add to be loaded in the footer of your website.</em>', 'media_center'),
							'id' => 'footer_js',
							'type' => 'ace_editor',
							'mode' => 'javascript',
						),
					),
				),
			);

			// Change your opt_name to match where you want the data saved.
			$args = array(
				'opt_name' => 'media_center_theme_options',
				'menu_title' => __( 'MC Options', 'media_center' ),
				'page_priority' => 3,
				'allow_sub_menu' => false,
				'dev_mode'	=> false,
				'intro_text' => '',
				'footer_credit' => '&nbsp;',
				'page_slug' => 'theme_options',
				'google_api_key' => 'AIzaSyDDZJO4F0d17RnFoi1F2qtw4wn6Wcaqxao',
			);

			// Use this section if this is for a theme. Replace with plugin specific data if it is for a plugin.
			$theme = wp_get_theme();
			$args['display_name'] = $theme->get('Name');
			$args['display_version'] = $theme->get('Version');

			$ReduxFramework = new ReduxFramework($sections, $args);
		}	
	}
	new Media_Center_Theme_Options();
}