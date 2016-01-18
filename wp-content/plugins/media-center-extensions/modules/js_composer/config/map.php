<?php
/**
 * WPBakery Visual Composer Shortcodes settings
 *
 * @package MediaCenterVCExtensions
 *
 */

if ( function_exists( 'vc_map' ) ):

#-----------------------------------------------------------------
# Media Center Banner Element
#-----------------------------------------------------------------

vc_map(	
	array(
		'name' => __( 'Banner', 'media_center' ),
		'base' => 'mc_banner',
		'description' => __( 'Add a banner to your page.', 'media_center' ),
		'class'		=> '',
		'controls' => 'full', 
		'icon' => 'icon-media-center',
		'category' => __( 'Media Center Elements', 'media_center' ),
	   	'params' => array(
      		array(
				'type' => 'attach_image',
	         	'heading' => __( 'Banner Image', 'media_center' ),
	         	'param_name' => 'banner_image',	
	      	),
	      	array(
				 'type' => 'textfield',
		         'heading' => __( 'Title', 'media_center' ),
		         'param_name' => 'title',
		         'description' => __( 'Enter banner title', 'media_center' ),
		         'holder' => 'div'
	      	),
	      	array(
				 'type' => 'textfield',
		         'heading' => __( 'Subtitle Text', 'media_center' ),
		         'param_name' => 'subtitle',
		         'description' => __( 'Enter banner subtitle', 'media_center')
	      	),
	      	array(
				 'type' => 'textfield',
		         'heading' => __( 'Banner Link', 'media_center' ),
		         'param_name' => 'banner_link',
		         'description' => __( 'Link to banner. Default #', 'media_center' ),
		         'value' => '#'
	      	),
	      	array(
	      		'type' => 'dropdown',
	      		'heading' => __( 'On Click', 'media_center' ),
	      		'param_name' => 'banner_link_target',
	      		'value' => array(
					__( 'Open in same page', 'media_center' ) => '_self',
					__( 'Open in new page', 'media_center' )   => '_blank'
				),
      		),
      		array(
	      		'type' => 'dropdown',
	      		'heading' => __( 'Animation on banner hover', 'media_center' ),
	      		'param_name' => 'banner_hover_animation',
	      		'value' => array(
					__( 'bounce', 'media_center' ) => 'bounce',
					__( 'flash', 'media_center' ) => 'flash',
					__( 'pulse', 'media_center' ) => 'pulse',
					__( 'rubberBand', 'media_center' ) => 'rubberBand',
					__( 'shake', 'media_center' ) => 'shake',
					__( 'swing', 'media_center' ) => 'swing',
					__( 'tada', 'media_center' ) => 'tada',
					__( 'wobble', 'media_center' ) => 'wobble',
					__( 'bounceIn', 'media_center' ) => 'bounceIn',
					__( 'bounceInDown', 'media_center' ) => 'bounceInDown',
					__( 'bounceInLeft', 'media_center' ) => 'bounceInLeft',
					__( 'bounceInRight', 'media_center' ) => 'bounceInRight',
					__( 'bounceInUp', 'media_center' ) => 'bounceInUp',
					__( 'bounceOut', 'media_center' ) => 'bounceOut',
					__( 'bounceOutDown', 'media_center' ) => 'bounceOutDown',
					__( 'bounceOutLeft', 'media_center' ) => 'bounceOutLeft',
					__( 'bounceOutRight', 'media_center' ) => 'bounceOutRight',
					__( 'bounceOutUp', 'media_center' ) => 'bounceOutUp',
					__( 'fadeIn', 'media_center' ) => 'fadeIn',
					__( 'fadeInDown', 'media_center' ) => 'fadeInDown',
					__( 'fadeInDownBig', 'media_center' ) => 'fadeInDownBig',
					__( 'fadeInLeft', 'media_center' ) => 'fadeInLeft',
					__( 'fadeInLeftBig', 'media_center' ) => 'fadeInLeftBig',
					__( 'fadeInRight', 'media_center' ) => 'fadeInRight',
					__( 'fadeInRightBig', 'media_center' ) => 'fadeInRightBig',
					__( 'fadeInUp', 'media_center' ) => 'fadeInUp',
					__( 'fadeInUpBig', 'media_center' ) => 'fadeInUpBig',
					__( 'fadeOut', 'media_center' ) => 'fadeOut',
					__( 'fadeOutDown', 'media_center' ) => 'fadeOutDown',
					__( 'fadeOutDownBig', 'media_center' ) => 'fadeOutDownBig',
					__( 'fadeOutLeft', 'media_center' ) => 'fadeOutLeft',
					__( 'fadeOutLeftBig', 'media_center' ) => 'fadeOutLeftBig',
					__( 'fadeOutRight', 'media_center' ) => 'fadeOutRight',
					__( 'fadeOutRightBig', 'media_center' ) => 'fadeOutRightBig',
					__( 'fadeOutUp', 'media_center' ) => 'fadeOutUp',
					__( 'fadeOutUpBig', 'media_center' ) => 'fadeOutUpBig',
					__( 'flip', 'media_center' ) => 'flip',
					__( 'flipInX', 'media_center' ) => 'flipInX',
					__( 'flipInY', 'media_center' ) => 'flipInY',
					__( 'flipOutX', 'media_center' ) => 'flipOutX',
					__( 'flipOutY', 'media_center' ) => 'flipOutY',
					__( 'lightSpeedIn', 'media_center' ) => 'lightSpeedIn',
					__( 'lightSpeedOut', 'media_center' ) => 'lightSpeedOut',
					__( 'rotateIn', 'media_center' ) => 'rotateIn',
					__( 'rotateInDownLeft', 'media_center' ) => 'rotateInDownLeft',
					__( 'rotateInDownRight', 'media_center' ) => 'rotateInDownRight',
					__( 'rotateInUpLeft', 'media_center' ) => 'rotateInUpLeft',
					__( 'rotateInUpRight', 'media_center' ) => 'rotateInUpRight',
					__( 'rotateOut', 'media_center' ) => 'rotateOut',
					__( 'rotateOutDownLeft', 'media_center' ) => 'rotateOutDownLeft',
					__( 'rotateOutDownRight', 'media_center' ) => 'rotateOutDownRight',
					__( 'rotateOutUpLeft', 'media_center' ) => 'rotateOutUpLeft',
					__( 'rotateOutUpRight', 'media_center' ) => 'rotateOutUpRight',
					__( 'hinge', 'media_center' ) => 'hinge',
					__( 'rollIn', 'media_center' ) => 'rollIn',
					__( 'rollOut', 'media_center' ) => 'rollOut',
					__( 'zoomIn', 'media_center' ) => 'zoomIn',
					__( 'zoomInDown', 'media_center' ) => 'zoomInDown',
					__( 'zoomInLeft', 'media_center' ) => 'zoomInLeft',
					__( 'zoomInRight', 'media_center' ) => 'zoomInRight',
					__( 'zoomInUp', 'media_center' ) => 'zoomInUp',
					__( 'zoomOut', 'media_center' ) => 'zoomOut',
					__( 'zoomOutDown', 'media_center' ) => 'zoomOutDown',
					__( 'zoomOutLeft', 'media_center' ) => 'zoomOutLeft',
					__( 'zoomOutRight', 'media_center' ) => 'zoomOutRight',
					__( 'zoomOutUp', 'media_center' ) => 'zoomOutUp',
				),
      		),

			array(
	      		'type' => 'dropdown',
	      		'heading' => __( 'Banner Text Position', 'media_center' ),
	      		'param_name' => 'banner_text_position',
	      		'value' => array(
					__( 'Left', 'media_center' ) => 'text-left',
					__( 'Right', 'media_center' )   => 'text-right',
					__( 'Center', 'media_center' )   => 'text-center',
				),
      		),

	      	array(
				'type' => 'textfield',
	         	'class' => '',
	         	'heading' => __( 'Extra Class', 'media_center' ),
	         	'param_name' => 'el_class',
	         	'description' => __( 'Add your extra classes here.', 'media_center' )
	      	)
	   	)
	) 
);

#-----------------------------------------------------------------
# Media Center Brands Carousel
#-----------------------------------------------------------------

vc_map( 
	array(
		'name' => __( 'Brands Carousel', 'media_center' ),
		'base' => 'mc_brands_carousel',
		'description' => __( 'Add a brands carousel to your page', 'media_center' ),
		'class'		=> '',
		'controls' => 'full', 
		'icon' => 'icon-media-center',
		'category' => __( 'Media Center Elements', 'media_center' ),
	   	'params' => array(
	      	array(
				'type' => 'textfield',
				'heading' => __( 'Title', 'media_center' ),
				'param_name' => 'title',
				'description' => __( 'Enter Carousel title', 'media_center' ),
				'holder' => 'div',
	      	),
	      	
	      	array(
				'type' => 'textfield',
		        'heading' => __( 'Order by', 'media_center' ),
		        'param_name' => 'orderby',
		        'description' => __( ' Sort retrieved posts by parameter. Defaults to \'date\'. One or more options can be passed', 'media_center' ),
		        'value' => 'date',

	      	),

	      	array(
		 	   	'type' => 'textfield',
		        'heading' => __( 'Order', 'media_center' ),
		        'param_name' => 'order',
		        'description' => __( 'Designates the ascending or descending order of the \'orderby\' parameter. Defaults to \'DESC\'.', 'media_center' ),
		        'value' => 'DESC',
	      	),

	      	array(
			    'type' => 'textfield',
		        'heading' => __( 'Number of Brands to display', 'media_center' ),
		        'param_name' => 'per_page',
		        'value' => '12'
	      	),
	      	array(
	      		'type'			=> 'dropdown',
	      		'heading'		=> __( 'Container Class', 'media_center' ),
	      		'param_name'	=> 'container_class',
	      		'value'			=> array(
	      			__( 'Container', 'media_center' ) 		=> 'container',
	      			__( 'Container Fluid', 'media_center' ) 	=> 'container-fluid',
	      			__( 'No Container', 'media_center' )      => 'no-container',
      			)
      		),
	      	array(
				'type' => 'textfield',
	         	'class' => '',
	         	'heading' => __( 'Extra Class', 'media_center' ),
	         	'param_name' => 'el_class',
	         	'description' => __( 'Add your extra classes here.', 'media_center' )
	      	)
	   	)
	) 
);

#-----------------------------------------------------------------
# Media Center Products Carousel
#-----------------------------------------------------------------

vc_map( 
	array(
		'name' => __( 'Products Carousel', 'media_center' ),
		'base' => 'mc_products_carousel',
		'description' => __( 'Add products carousel to your page', 'media_center' ),
		'class'		=> '',
		'controls' => 'full', 
		'icon' => 'icon-media-center',
		'category' => __( 'Media Center Elements', 'media_center' ),
	   	'params' => array(
	   		array(
	   			'type' => 'textfield',
				'heading' => __( 'Title', 'media_center' ),
				'param_name' => 'title',
				'description' => __( 'Enter Carousel title', 'media_center' ),
				'holder' => 'div'
   			),
			array(
	      		'type' => 'dropdown',
	      		'heading' => __( 'Show', 'media_center' ),
	      		'param_name' => 'shortcode_name',
	      		'value' => array(
					__( 'Recent Products', 'media_center' ) => 'recent_products',
					__( 'Featured Products', 'media_center' )   => 'featured_products',
					__( 'Top Rated Products', 'media_center' )   => 'top_rated_products',
					__( 'On Sale Products', 'media_center' ) => 'sale_products',
					__( 'Best Selling Products', 'media_center' ) => 'best_selling_products',
					__( 'Select Products by Category', 'media_center' ) => 'product_category',
					__( 'Select Products by IDs', 'media_center' ) => 'products_ids',
					__( 'Select Products by SKUs', 'media_center' ) => 'products_skus',
				),
				'description' => __( 'Choose what type of products you want to show in the carousel', 'media_center' )
      		),
      		array(
      			'type' => 'textfield',
				'heading' => __( 'IDs', 'media_center' ),
				'param_name' => 'ids',
				'description' => __( 'Note : This option is applicable only for Select Products by IDs. Specify IDs of products you want to show separated by comma. Example : 1, 2, 3, 4, 5', 'media_center' ),
  			),
  			array(
      			'type' => 'textfield',
				'heading' => __( 'SKUs', 'media_center' ),
				'param_name' => 'skus',
				'description' => __( 'Note : This option is applicable only for Select Products by SKUs. Specify SKUs of products you want to show separated by comma. Example : foo, bar, baz', 'media_center' ),
  			),
  			array(
      			'type' => 'textfield',
				'heading' => __( 'Category', 'media_center' ),
				'param_name' => 'category',
				'description' => __( 'Note : This option is applicable only for Select Products by Category. Specify the category slug of the category of products you want to show', 'media_center' ),
  			),
      		array(
	      		'type' => 'dropdown',
	      		'heading' => __( 'Order By', 'media_center' ),
	      		'param_name' => 'orderby',
	      		'value' => array(
					__( 'Menu Order', 'media_center' ) => 'menu_order',
					__( 'Title', 'media_center' )   => 'title',
					__( 'Date', 'media_center' )   => 'date',
					__( 'Random', 'media_center' )   => 'rand',
					__( 'ID', 'media_center' )   => 'id',
				),
				'description' => __( 'Does not apply for Best Selling Products', 'media_center')
      		),
      		array(
	      		'type' => 'dropdown',
	      		'heading' => __( 'Order Direction', 'media_center' ),
	      		'param_name' => 'order',
	      		'value' => array(
					__( 'Descending', 'media_center' ) => 'desc',
					__( 'Ascending', 'media_center' )   => 'asc',
				),
				'description' => __( 'Does not apply for Best Selling Products', 'media_center')
      		),
      		array(
				'type' => 'textfield',
	         	'heading' => __( 'No of Products', 'media_center' ),
	         	'param_name' => 'per_page',
	         	'value' => '12'
	      	),
	      	array(
	      		'type'			=> 'dropdown',
	      		'heading'		=> __( 'Container Class', 'media_center' ),
	      		'param_name'	=> 'container_class',
	      		'value'			=> array(
	      			__( 'Container', 'media_center' ) 		=> 'container',
	      			__( 'Container Fluid', 'media_center' ) 	=> 'container-fluid',
	      			__( 'No Container', 'media_center' )      => 'no-container',
      			)
      		),
	      	array(
				'type' => 'dropdown',
	         	'heading' => __( 'No of Columns', 'media_center' ),
	         	'param_name' => 'columns',
	         	'value' => array(
					__( '4 - Best suited for pages with sidebar', 'media_center' ) => '4',
					__( '6 - Best suited for Full-width pages', 'media_center' )   => '6',
				),
	      	),
	      	array(
				'type' => 'textfield',
	         	'heading' => __( 'Extra Class', 'media_center' ),
	         	'param_name' => 'el_class',
	         	'description' => __( 'Add your extra classes here.', 'media_center' )
	      	)
	   	)
	) 
);

#-----------------------------------------------------------------
# Media Center 6-1 Products Grid
#-----------------------------------------------------------------

vc_map( 
	array(
		'name' => __( '6-1 Products Grid', 'media_center' ),
		'base' => 'mc_6_1_products_grid',
		'description' => __( 'Add 6-1 Products Grid to your page', 'media_center' ),
		'class'		=> '',
		'controls' => 'full', 
		'icon' => 'icon-media-center',
		'category' => __( 'Media Center Elements', 'media_center' ),
	   	'params' => array(
	   		array(
	   			'type' => 'textfield',
				'heading' => __( 'Title', 'media_center' ),
				'param_name' => 'title',
				'description' => __( 'Enter Carousel title', 'media_center' ),
				'holder' => 'div'
   			),
			array(
	      		'type' => 'dropdown',
	      		'heading' => __( 'Show', 'media_center' ),
	      		'param_name' => 'shortcode_name',
	      		'value' => array(
					__( 'Recent Products', 'media_center' ) => 'recent_products',
					__( 'Featured Products', 'media_center' )   => 'featured_products',
					__( 'Top Rated Products', 'media_center' )   => 'top_rated_products',
					__( 'On Sale Products', 'media_center' ) => 'sale_products',
					__( 'Best Selling Products', 'media_center' ) => 'best_selling_products',
					__( 'Select Products by Category', 'media_center' ) => 'product_category',
					__( 'Select Products by IDs', 'media_center' ) => 'products_ids',
					__( 'Select Products by SKUs', 'media_center' ) => 'products_skus',
				),
				'description' => __( 'Choose what type of products you want to show in the carousel', 'media_center' )
      		),
      		array(
      			'type' => 'textfield',
				'heading' => __( 'IDs', 'media_center' ),
				'param_name' => 'ids',
				'description' => __( 'Note : This option is applicable only for Select Products by IDs. Specify IDs of products you want to show separated by comma. Example : 1, 2, 3, 4, 5', 'media_center' ),
  			),
  			array(
      			'type' => 'textfield',
				'heading' => __( 'SKUs', 'media_center' ),
				'param_name' => 'skus',
				'description' => __( 'Note : This option is applicable only for Select Products by SKUs. Specify SKUs of products you want to show separated by comma. Example : foo, bar, baz', 'media_center' ),
  			),
  			array(
      			'type' => 'textfield',
				'heading' => __( 'Category', 'media_center' ),
				'param_name' => 'category',
				'description' => __( 'Note : This option is applicable only for Select Products by Category. Specify the category slug of the category of products you want to show', 'media_center' ),
  			),
      		array(
	      		'type' => 'dropdown',
	      		'heading' => __( 'Order By', 'media_center' ),
	      		'param_name' => 'orderby',
	      		'value' => array(
					__( 'Menu Order', 'media_center' ) => 'menu_order',
					__( 'Title', 'media_center' )   => 'title',
					__( 'Date', 'media_center' )   => 'date',
					__( 'Random', 'media_center' )   => 'rand',
					__( 'ID', 'media_center' )   => 'id',
				),
				'description' => __( 'Does not apply for Best Selling Products', 'media_center')
      		),
      		array(
	      		'type' => 'dropdown',
	      		'heading' => __( 'Order Direction', 'media_center' ),
	      		'param_name' => 'order',
	      		'value' => array(
					__( 'Descending', 'media_center' ) => 'desc',
					__( 'Ascending', 'media_center' )   => 'asc',
				),
				'description' => __( 'Does not apply for Best Selling Products', 'media_center')
      		),
	      	array(
				'type' => 'textfield',
	         	'heading' => __( 'Extra Class', 'media_center' ),
	         	'param_name' => 'el_class',
	         	'description' => __( 'Add your extra classes here.', 'media_center' )
	      	)
	   	)
	) 
);

#-----------------------------------------------------------------
# Media Center Vertical Class
#-----------------------------------------------------------------

vc_map( 
	array(
		'name' 			=> __( 'Vertical Menu', 'media_center' ),
		'base' 			=> 'mc_vertical_menu',
		'description' 	=> __( 'Add a vertical menu to your home page', 'media_center' ),
		'class'			=> '',
		'controls' 		=> 'full', 
		'icon' 			=> 'icon-media-center',
		'category' 		=> __( 'Media Center Elements', 'media_center' ),
	   	'params' 		=> array(
	      	array(
				'type' 		 	=> 'textfield',
				'heading' 	 	=> __( 'Title', 'media_center' ),
				'param_name' 	=> 'title',
				'holder' 	 	=> 'div',
	      	),

	      	array(
				'type' 			=> 'textfield',
				'heading' 		=> __( 'Title Icon Class', 'media_center' ),
				'param_name' 	=> 'icon_class',
				'description' 	=> sprintf( __('Fontawesome Icon Class. Default icon is <em>fa-list</em>. For complete list of icon classes %s', 'media_center' ), '<a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">' . __( 'Click here', 'media_center' ) . '</a>' ),
	      	),
	      	
	      	array(
				'type' 			=> 'textfield',
		        'heading' 		=> __( 'Menu', 'media_center' ),
		        'param_name' 	=> 'menu',
		        'description' 	=> __( 'Menu ID, slug, or name. Leave it empty to pull all categories', 'media_center')
	      	),

	      	array(
	      		'type'			=> 'dropdown',
	      		'heading'		=> __( 'Dropdown Trigger', 'media_center' ),
	      		'param_name'	=> 'dropdown_trigger',
	      		'value'			=> array(
	      			__( 'Click', 'media_center' ) => 'click',
	      			__( 'Hover', 'media_center' ) => 'hover',
      			)
      		),

      		array(
	      		'type'			=> 'dropdown',
	      		'heading'		=> __( 'Dropdown Animation', 'media_center' ),
	      		'param_name'	=> 'dropdown_animation',
	      		'value'			=> array(
	      			__( 'No Animation', 'media_center' ) 			=> 	'none',
		        	__( 'BounceIn', 'media_center' ) 				=> 	'bounceIn',
		        	__( 'BounceInDown', 'media_center' ) 			=> 	'bounceInDown',
		        	__( 'BounceInLeft', 'media_center' ) 			=> 	'bounceInLeft',
		        	__( 'BounceInRight', 'media_center' ) 			=> 	'bounceInRight',
		        	__( 'BounceInUp', 'media_center' ) 				=> 	'bounceInUp',
					__( 'FadeIn', 'media_center' ) 					=> 	'fadeIn',
					__( 'FadeInDown', 'media_center' ) 				=> 	'fadeInDown',
					__( 'FadeInDown Big', 'media_center' ) 			=> 	'fadeInDownBig',
					__( 'FadeInLeft', 'media_center' ) 				=> 	'fadeInLeft',
					__( 'FadeInLeft Big', 'media_center' ) 			=> 	'fadeInLeftBig',
					__( 'FadeInRight', 'media_center' ) 			=> 	'fadeInRight',
					__( 'FadeInRight Big', 'media_center' ) 		=> 	'fadeInRightBig',
					__( 'FadeInUp', 'media_center' ) 				=> 	'fadeInUp',
					__( 'FadeInUp Big', 'media_center' ) 			=> 	'fadeInUpBig',
					__( 'FlipInX', 'media_center' ) 				=> 	'flipInX',
					__( 'FlipInY', 'media_center' ) 				=> 	'flipInY',
					__( 'Light SpeedIn', 'media_center' ) 			=> 	'lightSpeedIn',
					__( 'RotateIn', 'media_center' ) 				=> 	'rotateIn',
					__( 'RotateInDown Left', 'media_center' ) 		=> 	'rotateInDownLeft',
					__( 'RotateInDown Right', 'media_center' ) 		=> 	'rotateInDownRight',
					__( 'RotateInUp Left', 'media_center' ) 		=> 	'rotateInUpLeft',
					__( 'RotateInUp Right', 'media_center' ) 		=> 	'rotateInUpRight',
					__( 'RoleIn', 'media_center' ) 					=> 	'roleIn',
		        	__( 'ZoomIn', 'media_center' ) 					=> 	'zoomIn',
					__( 'ZoomInDown', 'media_center' ) 				=> 	'zoomInDown',
					__( 'ZoomInLeft', 'media_center' ) 				=> 	'zoomInLeft',
					__( 'ZoomInRight', 'media_center' ) 			=> 	'zoomInRight',
					__( 'ZoomInUp', 'media_center' ) 				=> 	'zoomInUp',
      			)
      		),

	      	array(
				'type' 			=> 'textfield',
	         	'class' 		=> '',
	         	'heading' 		=> __( 'Extra Class', 'media_center' ),
	         	'param_name' 	=> 'el_class',
	         	'description' 	=> __( 'Add your extra classes here.', 'media_center' )
	      	)
	   	)
	) 
);

#-----------------------------------------------------------------
# Media Center Service Icon Element
#-----------------------------------------------------------------

vc_map( 
	array(
		'name' => __( 'Service Icon', 'media_center' ),
		'base' => 'mc_service_icon',
		'description' => __( 'Add a service icon to your page.', 'media_center' ),
		'class'		=> '',
		'controls' => 'full', 
		'icon' => 'icon-media-center',
		'category' => __( 'Media Center Elements', 'media_center' ),
	   	'params' => array(
	      	array(
				 'type' => 'textfield',
		         'heading' => __( 'Title', 'media_center' ),
		         'param_name' => 'title',
		         'description' => __( 'Enter service title', 'media_center' ),
		         'holder' => 'div'
	      	),
	      	array(
				 'type' => 'textfield',
		         'heading' => __( 'Service Icon Link', 'media_center' ),
		         'param_name' => 'link',
		         'description' => __( 'Enter service icon link (optional)', 'media_center' ),
		         'holder' => 'div'
	      	),
	      	array(
				 'type' => 'textarea',
		         'heading' => __( 'Service Description', 'media_center' ),
		         'param_name' => 'description',
		         'description' => __( 'Enter service description', 'media_center'),
		         'holder' => 'div'
	      	),
	      	array(
				 'type' => 'textfield',
		         'heading' => __( 'Service Icon Class', 'media_center' ),
		         'param_name' => 'icon_class',
				 'description' => sprintf( __('Fontawesome Icon Class. Default icon is <em>fa-list</em>. For complete list of icon classes %s', 'media_center' ), '<a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">' . __( 'Click here', 'media_center' ) . '</a>' ),
	      	),
	      	array(
				'type' => 'textfield',
	         	'class' => '',
	         	'heading' => __( 'Extra Class', 'media_center' ),
	         	'param_name' => 'el_class',
	         	'description' => __( 'Add your extra classes here.', 'media_center' )
	      	)
	   	)
	) 
);

#-----------------------------------------------------------------
# Media Center Team Member
#-----------------------------------------------------------------

vc_map( 
	array(
		'name' 			=> __( 'Team Member', 'media_center' ),
		'base' 			=> 'mc_team_member',
		'description' 	=> __( 'Add a team member profile to your page.', 'media_center' ),
		'class'			=> '',
		'controls' 		=> 'full', 
		'icon' 			=> 'icon-media-center',
		'category' 		=> __( 'Media Center Elements', 'media_center' ),
	   	'params' => array(
	      	array(
				 'type' 		=> 'textfield',
		         'heading' 		=> __( 'Full Name', 'media_center' ),
		         'param_name' 	=> 'name',
		         'description' 	=> __( 'Enter team member full name', 'media_center' ),
		         'holder' 		=> 'div'
	      	),
	      	array(
				 'type' 		=> 'textfield',
		         'heading' 		=> __( 'Designation', 'media_center' ),
		         'param_name' 	=> 'designation',
		         'description' 	=> __( 'Enter designation of team member', 'media_center'),
	      	),
	      	array(
				'type' 			=> 'attach_image',
	         	'heading' 		=> __( 'Profile Pic', 'media_center' ),
	         	'param_name' 	=> 'profile_pic',	
	      	),
	      	array(
	      		'type' 			=> 'dropdown',
	      		'heading'		=> __( 'Display Style', 'media_center' ),
	      		'value' 		=> array(
	      			__( 'Square', 'media_center' ) => 'square',
	      			__( 'Circle', 'media_center' ) => 'circle'
      			),
      			'param_name'	=> 'display_style',
      		),
      		array(
      			'type' 			=> 'textfield',
	         	'class' 		=> '',
	         	'heading' 		=> __( 'Link', 'media_center' ),
	         	'param_name' 	=> 'link',
	         	'description' 	=> __( 'Add link to the team member. Leave blank if there aren\'t any', 'media_center' )
  			),
	      	array(
				'type' 			=> 'textfield',
	         	'class' 		=> '',
	         	'heading' 		=> __( 'Extra Class', 'media_center' ),
	         	'param_name' 	=> 'el_class',
	         	'description' 	=> __( 'Add your extra classes here.', 'media_center' )
	      	)
	   	)
	) 
);

#-----------------------------------------------------------------
# Media Center GMap
#-----------------------------------------------------------------

vc_map( 
	array(
		'name'        => __( 'Google Map', 'media_center' ),
		'base'        => 'mc_gmap',
		'description' => __( 'Add a Google Map to your page.', 'media_center' ),
		'class'		  => '',
		'controls'    => 'full', 
		'icon' 		  => 'icon-media-center',
		'category'    => __( 'Media Center Elements', 'media_center' ),
	   	'params'      => array(
	      	array(
				 'type'       => 'textfield',
		         'heading'    => __( 'Latitude', 'media_center' ),
		         'param_name' => 'lat',
		         'holder'     => 'div'
	      	),
	      	array(
				'type'       => 'textfield',
		        'heading'    => __( 'Longitude', 'media_center' ),
		        'param_name' => 'lon',
		        'holder'     => 'div'
	      	),
	      	array(
				'type'       => 'textfield',
		        'heading'    => __( 'Zoom', 'media_center' ),
		        'param_name' => 'zoom',
	      	),
	      	array(
	      		'type'			=> 'textfield',
	      		'class'			=>  '',
	      		'heading'		=> __( 'Minimum Height in px', 'media_center' ),
	      		'param_name'	=> 'map_min_height',
	      		'value'			=> '460px'
      		),
	      	array(
				'type'        => 'textfield',
	         	'class'       => '',
	         	'heading'     => __( 'Extra Class', 'media_center' ),
	         	'param_name'  => 'el_class',
	         	'description' => __( 'Add your extra classes here.', 'media_center' )
	      	),
	      	array(
	      		'type' => 'dropdown',
	      		'heading' => __( 'Display Get Direction', 'media_center' ),
	      		'param_name' => 'add_get_directions',
	      		'value' => array(
					__( 'Yes', 'media_center' ) => 'yes',
					__( 'No', 'media_center' )  => 'no',
				),
				'description' => __( 'Should display "Get Direction" form ?', 'media_center')
      		),
	   	)
	) 
);

#-----------------------------------------------------------------
# Media Center Home Page Tabs
#-----------------------------------------------------------------

vc_map(
	array(
		'name'			=> __( 'Home Page Tabs', 'media_center' ),
		'base'  		=> 'mc_home_page_tabs',
		'description'	=> __( 'Product Tabs for Home', 'media_center' ),
		'category'		=> __( 'Media Center Elements', 'media_center' ),
		'icon' 			=> 'icon-media-center',
		'params' 		=> array(
			array(
				'type'			=> 'textfield',
				'heading'		=> __('Tab #1 title', 'media_center' ),
				'param_name'	=> 'title_tab_1',
			),

			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Tab #1 Content, Show :', 'media_center' ),
				'param_name'	=> 'content_sc_tab_1',
				'value'			=> array(
					__( 'Featured Products', 'media_center' )		=> 'featured_products' ,
					__( 'On Sale Products', 'media_center' )		=> 'sale_products' 	,
					__( 'Top Rated Products', 'media_center' )		=> 'top_rated_products' ,
					__( 'Recent Products', 'media_center' )			=> 'recent_products' 	,
					__( 'Best Selling Products', 'media_center' )	=> 'best_selling_products',
				),
			),

			array(
				'type'			=> 'textfield',
				'heading'		=> __('Tab #2 title', 'media_center' ),
				'param_name'	=> 'title_tab_2',
			),

			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Tab #2 Content, Show :', 'media_center' ),
				'param_name'	=> 'content_sc_tab_2',
				'value'			=> array(
					__( 'Featured Products', 'media_center' )		=> 'featured_products' ,
					__( 'On Sale Products', 'media_center' )		=> 'sale_products' 	,
					__( 'Top Rated Products', 'media_center' )		=> 'top_rated_products' ,
					__( 'Recent Products', 'media_center' )			=> 'recent_products' 	,
					__( 'Best Selling Products', 'media_center' )	=> 'best_selling_products',
				),
			),

			array(
				'type'			=> 'textfield',
				'heading'		=> __('Tab #3 title', 'media_center' ),
				'param_name'	=> 'title_tab_3',
			),

			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Tab #3 Content, Show :', 'media_center' ),
				'param_name'	=> 'content_sc_tab_3',
				'value'			=> array(
					__( 'Featured Products', 'media_center' )		=> 'featured_products' ,
					__( 'On Sale Products', 'media_center' )		=> 'sale_products' 	,
					__( 'Top Rated Products', 'media_center' )		=> 'top_rated_products' ,
					__( 'Recent Products', 'media_center' )			=> 'recent_products' 	,
					__( 'Best Selling Products', 'media_center' )	=> 'best_selling_products',
				),
			),
		),
	)
);

endif;