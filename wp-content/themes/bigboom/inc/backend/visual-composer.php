<?php
/**
 * Custom functions for Visual Composer
 *
 * @package BigBoom
 * @subpackage Visual Composer
 */

class BigBoom_VC {
	public $icons;
	/**
	 * Construction
	 */
	function __construct() {
		// Stop if VC is not installed
		if ( ! in_array( 'js_composer/js_composer.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return false;
		}

		$this->icons = $this->get_icons();

		vc_add_shortcode_param( 'icon', array( $this, 'icon_param' ), THEME_URL . '/js/vc/icon-field.js' );

		add_action( 'vc_before_init', array( $this, 'map_shortcodes' ) );
	}

	/**
	 * Define icon classes
	 *
	 * @return array
	 */
	public static function get_icons() {
		return array('fa fa-adjust', 'fa fa-adn', 'fa fa-align-center', 'fa fa-align-justify', 'fa fa-align-left', 'fa fa-align-right', 'fa fa-ambulance', 'fa fa-anchor', 'fa fa-android', 'fa fa-angellist', 'fa fa-angle-double-down', 'fa fa-angle-double-left', 'fa fa-angle-double-right', 'fa fa-angle-double-up', 'fa fa-angle-down', 'fa fa-angle-left', 'fa fa-angle-right', 'fa fa-angle-up', 'fa fa-apple', 'fa fa-archive', 'fa fa-area-chart', 'fa fa-arrow-circle-down', 'fa fa-arrow-circle-left', 'fa fa-arrow-circle-o-down', 'fa fa-arrow-circle-o-left', 'fa fa-arrow-circle-o-right', 'fa fa-arrow-circle-o-up', 'fa fa-arrow-circle-right', 'fa fa-arrow-circle-up', 'fa fa-arrow-down', 'fa fa-arrow-left', 'fa fa-arrow-right', 'fa fa-arrow-up', 'fa fa-arrows', 'fa fa-arrows-alt', 'fa fa-arrows-h', 'fa fa-arrows-v', 'fa fa-asterisk', 'fa fa-at', 'fa fa-automobile', 'fa fa-backward', 'fa fa-ban', 'fa fa-bank', 'fa fa-bar-chart', 'fa fa-bar-chart-o', 'fa fa-barcode', 'fa fa-bars', 'fa fa-beer', 'fa fa-behance', 'fa fa-behance-square', 'fa fa-bell', 'fa fa-bell-o', 'fa fa-bell-slash', 'fa fa-bell-slash-o', 'fa fa-bicycle', 'fa fa-binoculars', 'fa fa-birthday-cake', 'fa fa-bitbucket', 'fa fa-bitbucket-square', 'fa fa-bitcoin', 'fa fa-bold', 'fa fa-bolt', 'fa fa-bomb', 'fa fa-book', 'fa fa-bookmark', 'fa fa-bookmark-o', 'fa fa-briefcase', 'fa fa-btc', 'fa fa-bug', 'fa fa-building', 'fa fa-building-o', 'fa fa-bullhorn', 'fa fa-bullseye', 'fa fa-bus', 'fa fa-cab', 'fa fa-calculator', 'fa fa-calendar', 'fa fa-calendar-o', 'fa fa-camera', 'fa fa-camera-retro', 'fa fa-car', 'fa fa-caret-down', 'fa fa-caret-left', 'fa fa-caret-right', 'fa fa-caret-square-o-down', 'fa fa-caret-square-o-left', 'fa fa-caret-square-o-right', 'fa fa-caret-square-o-up', 'fa fa-caret-up', 'fa fa-cc', 'fa fa-cc-amex', 'fa fa-cc-discover', 'fa fa-cc-mastercard', 'fa fa-cc-paypal', 'fa fa-cc-stripe', 'fa fa-cc-visa', 'fa fa-certificate', 'fa fa-chain', 'fa fa-chain-broken', 'fa fa-check', 'fa fa-check-circle', 'fa fa-check-circle-o', 'fa fa-check-square', 'fa fa-check-square-o', 'fa fa-chevron-circle-down', 'fa fa-chevron-circle-left', 'fa fa-chevron-circle-right', 'fa fa-chevron-circle-up', 'fa fa-chevron-down', 'fa fa-chevron-left', 'fa fa-chevron-right', 'fa fa-chevron-up', 'fa fa-child', 'fa fa-circle', 'fa fa-circle-o', 'fa fa-circle-o-notch', 'fa fa-circle-thin', 'fa fa-clipboard', 'fa fa-clock-o', 'fa fa-close', 'fa fa-cloud', 'fa fa-cloud-download', 'fa fa-cloud-upload', 'fa fa-cny', 'fa fa-code', 'fa fa-code-fork', 'fa fa-codepen', 'fa fa-coffee', 'fa fa-cog', 'fa fa-cogs', 'fa fa-columns', 'fa fa-comment', 'fa fa-comment-o', 'fa fa-comments', 'fa fa-comments-o', 'fa fa-compass', 'fa fa-compress', 'fa fa-copy', 'fa fa-copyright', 'fa fa-credit-card', 'fa fa-crop', 'fa fa-crosshairs', 'fa fa-css3', 'fa fa-cube', 'fa fa-cubes', 'fa fa-cut', 'fa fa-cutlery', 'fa fa-dashboard', 'fa fa-database', 'fa fa-dedent', 'fa fa-delicious', 'fa fa-desktop', 'fa fa-deviantart', 'fa fa-digg', 'fa fa-dollar', 'fa fa-dot-circle-o', 'fa fa-download', 'fa fa-dribbble', 'fa fa-dropbox', 'fa fa-drupal', 'fa fa-edit', 'fa fa-eject', 'fa fa-ellipsis-h', 'fa fa-ellipsis-v', 'fa fa-empire', 'fa fa-envelope', 'fa fa-envelope-o', 'fa fa-envelope-square', 'fa fa-eraser', 'fa fa-eur', 'fa fa-euro', 'fa fa-exchange', 'fa fa-exclamation', 'fa fa-exclamation-circle', 'fa fa-exclamation-triangle', 'fa fa-expand', 'fa fa-external-link', 'fa fa-external-link-square', 'fa fa-eye', 'fa fa-eye-slash', 'fa fa-eyedropper', 'fa fa-facebook', 'fa fa-facebook-square', 'fa fa-fast-backward', 'fa fa-fast-forward', 'fa fa-fax', 'fa fa-female', 'fa fa-fighter-jet', 'fa fa-file', 'fa fa-file-archive-o', 'fa fa-file-audio-o', 'fa fa-file-code-o', 'fa fa-file-excel-o', 'fa fa-file-image-o', 'fa fa-file-movie-o', 'fa fa-file-o', 'fa fa-file-pdf-o', 'fa fa-file-photo-o', 'fa fa-file-picture-o', 'fa fa-file-powerpoint-o', 'fa fa-file-sound-o', 'fa fa-file-text', 'fa fa-file-text-o', 'fa fa-file-video-o', 'fa fa-file-word-o', 'fa fa-file-zip-o', 'fa fa-files-o', 'fa fa-film', 'fa fa-filter', 'fa fa-fire', 'fa fa-fire-extinguisher', 'fa fa-flag', 'fa fa-flag-checkered', 'fa fa-flag-o', 'fa fa-flash', 'fa fa-flask', 'fa fa-flickr', 'fa fa-floppy-o', 'fa fa-folder', 'fa fa-folder-o', 'fa fa-folder-open', 'fa fa-folder-open-o', 'fa fa-font', 'fa fa-forward', 'fa fa-foursquare', 'fa fa-frown-o', 'fa fa-futbol-o', 'fa fa-gamepad', 'fa fa-gavel', 'fa fa-gbp', 'fa fa-ge', 'fa fa-gear', 'fa fa-gears', 'fa fa-gift', 'fa fa-git', 'fa fa-git-square', 'fa fa-github', 'fa fa-github-alt', 'fa fa-github-square', 'fa fa-gittip', 'fa fa-glass', 'fa fa-globe', 'fa fa-google', 'fa fa-google-plus', 'fa fa-google-plus-square', 'fa fa-google-wallet', 'fa fa-graduation-cap', 'fa fa-group', 'fa fa-h-square', 'fa fa-hacker-news', 'fa fa-hand-o-down', 'fa fa-hand-o-left', 'fa fa-hand-o-right', 'fa fa-hand-o-up', 'fa fa-hdd-o', 'fa fa-header', 'fa fa-headphones', 'fa fa-heart', 'fa fa-heart-o', 'fa fa-history', 'fa fa-home', 'fa fa-hospital-o', 'fa fa-html5', 'fa fa-ils', 'fa fa-image', 'fa fa-inbox', 'fa fa-indent', 'fa fa-info', 'fa fa-info-circle', 'fa fa-inr', 'fa fa-instagram', 'fa fa-institution', 'fa fa-ioxhost', 'fa fa-italic', 'fa fa-joomla', 'fa fa-jpy', 'fa fa-jsfiddle', 'fa fa-key', 'fa fa-keyboard-o', 'fa fa-krw', 'fa fa-language', 'fa fa-laptop', 'fa fa-lastfm', 'fa fa-lastfm-square', 'fa fa-leaf', 'fa fa-legal', 'fa fa-lemon-o', 'fa fa-level-down', 'fa fa-level-up', 'fa fa-life-bouy', 'fa fa-life-buoy', 'fa fa-life-ring', 'fa fa-life-saver', 'fa fa-lightbulb-o', 'fa fa-line-chart', 'fa fa-link', 'fa fa-linkedin', 'fa fa-linkedin-square', 'fa fa-linux', 'fa fa-list', 'fa fa-list-alt', 'fa fa-list-ol', 'fa fa-list-ul', 'fa fa-location-arrow', 'fa fa-lock', 'fa fa-long-arrow-down', 'fa fa-long-arrow-left', 'fa fa-long-arrow-right', 'fa fa-long-arrow-up', 'fa fa-magic', 'fa fa-magnet', 'fa fa-mail-forward', 'fa fa-mail-reply', 'fa fa-mail-reply-all', 'fa fa-male', 'fa fa-map-marker', 'fa fa-maxcdn', 'fa fa-meanpath', 'fa fa-medkit', 'fa fa-meh-o', 'fa fa-microphone', 'fa fa-microphone-slash', 'fa fa-minus', 'fa fa-minus-circle', 'fa fa-minus-square', 'fa fa-minus-square-o', 'fa fa-mobile', 'fa fa-mobile-phone', 'fa fa-money', 'fa fa-moon-o', 'fa fa-mortar-board', 'fa fa-music', 'fa fa-navicon', 'fa fa-newspaper-o', 'fa fa-openid', 'fa fa-outdent', 'fa fa-pagelines', 'fa fa-paint-brush', 'fa fa-paper-plane', 'fa fa-paper-plane-o', 'fa fa-paperclip', 'fa fa-paragraph', 'fa fa-paste', 'fa fa-pause', 'fa fa-paw', 'fa fa-paypal', 'fa fa-pencil', 'fa fa-pencil-square', 'fa fa-pencil-square-o', 'fa fa-phone', 'fa fa-phone-square', 'fa fa-photo', 'fa fa-picture-o', 'fa fa-pie-chart', 'fa fa-pied-piper', 'fa fa-pied-piper-alt', 'fa fa-pinterest', 'fa fa-pinterest-square', 'fa fa-plane', 'fa fa-play', 'fa fa-play-circle', 'fa fa-play-circle-o', 'fa fa-plug', 'fa fa-plus', 'fa fa-plus-circle', 'fa fa-plus-square', 'fa fa-plus-square-o', 'fa fa-power-off', 'fa fa-print', 'fa fa-puzzle-piece', 'fa fa-qq', 'fa fa-qrcode', 'fa fa-question', 'fa fa-question-circle', 'fa fa-quote-left', 'fa fa-quote-right', 'fa fa-ra', 'fa fa-random', 'fa fa-rebel', 'fa fa-recycle', 'fa fa-reddit', 'fa fa-reddit-square', 'fa fa-refresh', 'fa fa-remove', 'fa fa-renren', 'fa fa-reorder', 'fa fa-repeat', 'fa fa-reply', 'fa fa-reply-all', 'fa fa-retweet', 'fa fa-rmb', 'fa fa-road', 'fa fa-rocket', 'fa fa-rotate-left', 'fa fa-rotate-right', 'fa fa-rouble', 'fa fa-rss', 'fa fa-rss-square', 'fa fa-rub', 'fa fa-ruble', 'fa fa-rupee', 'fa fa-save', 'fa fa-scissors', 'fa fa-search', 'fa fa-search-minus', 'fa fa-search-plus', 'fa fa-send', 'fa fa-send-o', 'fa fa-share', 'fa fa-share-alt', 'fa fa-share-alt-square', 'fa fa-share-square', 'fa fa-share-square-o', 'fa fa-shekel', 'fa fa-sheqel', 'fa fa-shield', 'fa fa-shopping-cart', 'fa fa-sign-in', 'fa fa-sign-out', 'fa fa-signal', 'fa fa-sitemap', 'fa fa-skype', 'fa fa-slack', 'fa fa-sliders', 'fa fa-slideshare', 'fa fa-smile-o', 'fa fa-soccer-ball-o', 'fa fa-sort', 'fa fa-sort-alpha-asc', 'fa fa-sort-alpha-desc', 'fa fa-sort-amount-asc', 'fa fa-sort-amount-desc', 'fa fa-sort-asc', 'fa fa-sort-desc', 'fa fa-sort-down', 'fa fa-sort-numeric-asc', 'fa fa-sort-numeric-desc', 'fa fa-sort-up', 'fa fa-soundcloud', 'fa fa-space-shuttle', 'fa fa-spinner', 'fa fa-spoon', 'fa fa-spotify', 'fa fa-square', 'fa fa-square-o', 'fa fa-stack-exchange', 'fa fa-stack-overflow', 'fa fa-star', 'fa fa-star-half', 'fa fa-star-half-empty', 'fa fa-star-half-full', 'fa fa-star-half-o', 'fa fa-star-o', 'fa fa-steam', 'fa fa-steam-square', 'fa fa-step-backward', 'fa fa-step-forward', 'fa fa-stethoscope', 'fa fa-stop', 'fa fa-strikethrough', 'fa fa-stumbleupon', 'fa fa-stumbleupon-circle', 'fa fa-subscript', 'fa fa-suitcase', 'fa fa-sun-o', 'fa fa-superscript', 'fa fa-support', 'fa fa-table', 'fa fa-tablet', 'fa fa-tachometer', 'fa fa-tag', 'fa fa-tags', 'fa fa-tasks', 'fa fa-taxi', 'fa fa-tencent-weibo', 'fa fa-terminal', 'fa fa-text-height', 'fa fa-text-width', 'fa fa-th', 'fa fa-th-large', 'fa fa-th-list', 'fa fa-thumb-tack', 'fa fa-thumbs-down', 'fa fa-thumbs-o-down', 'fa fa-thumbs-o-up', 'fa fa-thumbs-up', 'fa fa-ticket', 'fa fa-times', 'fa fa-times-circle', 'fa fa-times-circle-o', 'fa fa-tint', 'fa fa-toggle-down', 'fa fa-toggle-left', 'fa fa-toggle-off', 'fa fa-toggle-on', 'fa fa-toggle-right', 'fa fa-toggle-up', 'fa fa-trash', 'fa fa-trash-o', 'fa fa-tree', 'fa fa-trello', 'fa fa-trophy', 'fa fa-truck', 'fa fa-try', 'fa fa-tty', 'fa fa-tumblr', 'fa fa-tumblr-square', 'fa fa-turkish-lira', 'fa fa-twitch', 'fa fa-twitter', 'fa fa-twitter-square', 'fa fa-umbrella', 'fa fa-underline', 'fa fa-undo', 'fa fa-university', 'fa fa-unlink', 'fa fa-unlock', 'fa fa-unlock-alt', 'fa fa-unsorted', 'fa fa-upload', 'fa fa-usd', 'fa fa-user', 'fa fa-user-md', 'fa fa-users', 'fa fa-video-camera', 'fa fa-vimeo-square', 'fa fa-vine', 'fa fa-vk', 'fa fa-volume-down', 'fa fa-volume-off', 'fa fa-volume-up', 'fa fa-warning', 'fa fa-wechat', 'fa fa-weibo', 'fa fa-weixin', 'fa fa-wheelchair', 'fa fa-wifi', 'fa fa-windows', 'fa fa-won', 'fa fa-wordpress', 'fa fa-wrench', 'fa fa-xing', 'fa fa-xing-square', 'fa fa-yahoo', 'fa fa-yelp', 'fa fa-yen', 'fa fa-youtube', 'fa fa-youtube-play', 'fa fa-youtube-square','li_heart',
'li_cloud',
'li_star',
'li_tv',
'li_sound',
'li_video',
'li_trash',
'li_user',
'li_key',
'li_search',
'li_settings',
'li_camera',
'li_tag',
'li_lock',
'li_bulb',
'li_pen',
'li_diamond',
'li_display',
'li_location',
'li_eye',
'li_bubble',
'li_stack',
'li_cup',
'li_phone',
'li_news',
'li_mail',
'li_like',
'li_photo',
'li_note',
'li_clock',
'li_paperplane',
'li_params',
'li_banknote',
'li_data',
'li_music',
'li_megaphone',
'li_study',
'li_lab',
'li_food',
'li_t-shirt',
'li_fire',
'li_clip',
'li_shop',
'li_calendar',
'li_vallet',
'li_vynil',
'li_truck',
'li_world',);
	}

	/**
	 * Add new params or add new shortcode to VC
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	function map_shortcodes() {

		// Add attribues to vc_row
		$attributes = array(
			array(
				'type'        => 'textfield',
				'heading'     => 'ID',
				'param_name'  => 'css_id',
				'value'       => '',
				'description' => __( 'Set CSS id for this row', 'bigboom' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Full width content', '' ),
				'param_name'  => 'full_content',
				'value'       => array( __( 'Enable', 'bigboom' ) => 'yes' ),
				'description' => __( 'Select it if you want your content to be displayed in full width of page', 'bigboom' ),
			),
		);
		vc_add_params( 'vc_row', $attributes );
		vc_remove_param( 'vc_row', 'el_id' );

		// Add attribues to vc_column_inner
		$attributes = array(
			array(
				'type' => 'column_offset',
				'heading' => __('Responsiveness', 'bigboom'),
				'param_name' => 'offset',
				'group' => __( 'Width & Responsiveness', 'bigboom' ),
				'description' => __('Adjust column for different screen sizes. Control width, offset and visibility settings.', 'bigboom')
			)
		);
		vc_add_params( 'vc_column_inner', $attributes );

		// Add products carousel shortcode
		vc_map( array(
			'name'     => __( 'Products Carousel', 'bigboom' ),
			'base'     => 'products_carousel',
			'class'    => '',
			'category' => __( 'Content', 'bigboom' ),
			'params'   => array(
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Products', 'bigboom' ),
					'param_name'  => 'products',
					'value'       => array(
						__( 'Recent', 'bigboom' )       => 'recent',
						__( 'Featured', 'bigboom' )     => 'featured',
						__( 'Best Selling', 'bigboom' ) => 'best_selling',
						__( 'Top Rated', 'bigboom' )    => 'top_rated',
						__( 'Sale', 'bigboom' )         => 'sale',
					)
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Title', 'bigboom' ),
					'param_name'  => 'title',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Total Products', 'bigboom' ),
					'param_name'  => 'per_page',
					'value'       => '12',
					'description' => __( 'Set numbers of products to show.', 'bigboom' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Products per view', 'bigboom' ),
					'param_name'  => 'views',
					'value'       => '4',
					'description' => __( "Set numbers of products you want to display at the same time.", 'bigboom' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Columns', 'bigboom' ),
					'param_name'  => 'columns',
					'value'       => '4',
					'description' => __( "Set numbers of columns you want to display.", 'bigboom' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Spacing', 'bigboom' ),
					'param_name'  => 'spacing',
					'value'       => '30',
					'description' => __( "Set spacing between columns.", 'bigboom' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Order By', 'bigboom' ),
					'param_name'  => 'orderby',
					'value'       => array(
						__( '', 'bigboom' )            => '',
						__( 'Date', 'bigboom' )       => 'date',
						__( 'Title', 'bigboom' )      => 'title',
						__( 'Menu Order', 'bigboom' ) => 'menu_order',
						__( 'Random', 'bigboom' )     => 'rand',
					),
					'dependency'  => array( 'element' => 'products', 'value' => array( 'top_rated', 'sale', 'featured' ) ),
					'description' => __( 'Select to order products. Leave empty to use the default order by of theme.', 'bigboom' ),
				),

				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Order', 'bigboom' ),
					'param_name'  => 'order',
					'value'       => array(
						__( '', 'bigboom' )            => '',
						__( 'Ascending ', 'bigboom' )  => 'asc',
						__( 'Descending ', 'bigboom' ) => 'desc',
					),
					'dependency'  => array( 'element' => 'products', 'value' => array( 'top_rated', 'sale', 'featured' ) ),
					'description' => __( 'Select to sort products. Leave empty to use the default sort of theme', 'bigboom' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => __( 'Slider autoplay', 'bigboom' ),
					'param_name'  => 'auto_play',
					'value'       => array( __( 'Yes', 'bigboom' ) => 'true' ),
					'description' => __( 'Enables autoplay mode.', 'bigboom' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => __( 'Hide prev/next buttons', 'bigboom' ),
					'param_name'  => 'hide_navigation',
					'value'       => array( __( 'Yes', 'bigboom' ) => 'false' ),
					'description' => __( 'If "YES" prev/next control will be removed.', 'bigboom' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Extra class name', 'bigboom' ),
					'param_name'  => 'class_name',
					'value'       => '',
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'bigboom' ),
				),
			),
		) );

		// Add products carousel shortcode
		vc_map( array(
			'name'     => __( 'Products Carousel 2', 'bigboom' ),
			'base'     => 'products_carousel_2',
			'class'    => '',
			'category' => __( 'Content', 'bigboom' ),
			'params'   => array(
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Title', 'bigboom' ),
					'param_name'  => 'title',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Total Products', 'bigboom' ),
					'param_name'  => 'per_page',
					'value'       => '12',
					'description' => __( 'Set numbers of products to show.', 'bigboom' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Categories', 'bigboom' ),
					'param_name'  => 'taxonomies',
					'value'       => $this->get_taxonomies(),
					'description' => __( 'Select taxonomies categories.', 'bigboom' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Products per view', 'bigboom' ),
					'param_name'  => 'number',
					'value'       => '2',
					'description' => __( "Set numbers of products you want to display at the same time.", 'bigboom' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Order By', 'bigboom' ),
					'param_name'  => 'orderby',
					'value'       => array(
						__( 'Random', 'bigboom' )     => 'rand',
						__( 'Date', 'bigboom' )       => 'date',
						__( 'Title', 'bigboom' )      => 'title',
						__( 'Menu Order', 'bigboom' ) => 'menu_order',
					),
					'description' => __( 'Select to order products. Leave empty to use the default order by of theme.', 'bigboom' ),
				),

				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Order', 'bigboom' ),
					'param_name'  => 'order',
					'value'       => array(
						__( '', 'bigboom' )            => '',
						__( 'Ascending ', 'bigboom' )  => 'asc',
						__( 'Descending ', 'bigboom' ) => 'desc',
					),
					'description' => __( 'Select to sort products. Leave empty to use the default sort of theme', 'bigboom' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => __( 'Slider autoplay', 'bigboom' ),
					'param_name'  => 'auto_play',
					'value'       => array( __( 'Yes', 'bigboom' ) => 'true' ),
					'description' => __( 'Enables autoplay mode.', 'bigboom' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => __( 'Hide prev/next buttons', 'bigboom' ),
					'param_name'  => 'hide_navigation',
					'value'       => array( __( 'Yes', 'bigboom' ) => 'false' ),
					'description' => __( 'If "YES" prev/next control will be removed.', 'bigboom' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Extra class name', 'bigboom' ),
					'param_name'  => 'class_name',
					'value'       => '',
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'bigboom' ),
				),
			),
		) );

		// Add products carousel shortcode
		vc_map( array(
			'name'     => __( 'Products Carousel 3', 'bigboom' ),
			'base'     => 'products_carousel_3',
			'class'    => '',
			'category' => __( 'Content', 'bigboom' ),
			'params'   => array(
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Products', 'bigboom' ),
					'param_name'  => 'products',
					'value'       => array(
						__( 'Recent', 'bigboom' )       => 'recent',
						__( 'Featured', 'bigboom' )     => 'featured',
						__( 'Best Selling', 'bigboom' ) => 'best_selling',
						__( 'Top Rated', 'bigboom' )    => 'top_rated',
						__( 'Sale', 'bigboom' )         => 'sale',
					)
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Title', 'bigboom' ),
					'param_name'  => 'title',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Total Products', 'bigboom' ),
					'param_name'  => 'per_page',
					'value'       => '12',
					'description' => __( 'Set numbers of products to show.', 'bigboom' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Products per view', 'bigboom' ),
					'param_name'  => 'views',
					'value'       => '4',
					'description' => __( "Set numbers of products you want to display at the same time.", 'bigboom' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Columns', 'bigboom' ),
					'param_name'  => 'columns',
					'value'       => '1',
					'description' => __( "Set numbers of columns you want to display.", 'bigboom' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Order By', 'bigboom' ),
					'param_name'  => 'orderby',
					'value'       => array(
						__( '', 'bigboom' )            => '',
						__( 'Date', 'bigboom' )       => 'date',
						__( 'Title', 'bigboom' )      => 'title',
						__( 'Menu Order', 'bigboom' ) => 'menu_order',
						__( 'Random', 'bigboom' )     => 'rand',
					),
					'dependency'  => array( 'element' => 'products', 'value' => array( 'top_rated', 'sale', 'featured' ) ),
					'description' => __( 'Select to order products. Leave empty to use the default order by of theme.', 'bigboom' ),
				),

				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Order', 'bigboom' ),
					'param_name'  => 'order',
					'value'       => array(
						__( '', 'bigboom' )            => '',
						__( 'Ascending ', 'bigboom' )  => 'asc',
						__( 'Descending ', 'bigboom' ) => 'desc',
					),
					'dependency'  => array( 'element' => 'products', 'value' => array( 'top_rated', 'sale', 'featured' ) ),
					'description' => __( 'Select to sort products. Leave empty to use the default sort of theme', 'bigboom' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => __( 'Slider autoplay', 'bigboom' ),
					'param_name'  => 'auto_play',
					'value'       => array( __( 'Yes', 'bigboom' ) => 'true' ),
					'description' => __( 'Enables autoplay mode.', 'bigboom' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => __( 'Hide prev/next buttons', 'bigboom' ),
					'param_name'  => 'hide_navigation',
					'value'       => array( __( 'Yes', 'bigboom' ) => 'false' ),
					'description' => __( 'If "YES" prev/next control will be removed.', 'bigboom' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Extra class name', 'bigboom' ),
					'param_name'  => 'class_name',
					'value'       => '',
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'bigboom' ),
				),
			),
		) );

		// Add hot deal shortcode
		vc_map( array(
			'name'     => __( 'Hot Deal Products', 'bigboom' ),
			'base'     => 'hot_deal_products',
			'class'    => '',
			'category' => __( 'Content', 'bigboom' ),
			'params'   => array(
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Title', 'bigboom' ),
					'param_name'  => 'title',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Products per view', 'bigboom' ),
					'param_name'  => 'per_page',
					'value'       => '12',
					'description' => __( 'Set numbers of products you want to display at the same time.', 'bigboom' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Columns', 'bigboom' ),
					'param_name'  => 'columns',
					'value'       => '4',
					'description' => __( "Set numbers of columns you want to display.", 'bigboom' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Order By', 'bigboom' ),
					'param_name'  => 'order_by',
					'value'       => array(
						__( 'Date', 'bigboom' )       => 'date',
						__( 'Title', 'bigboom' )      => 'title',
						__( 'Menu Order', 'bigboom' ) => 'menu_order',
						__( 'Random', 'bigboom' )     => 'rand',
					),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Order', 'bigboom' ),
					'param_name'  => 'order',
					'value'       => array(
						__( 'Descending ', 'bigboom' ) => 'desc',
						__( 'Ascending ', 'bigboom' )  => 'asc',
					),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Extra class name', 'bigboom' ),
					'param_name'  => 'class_name',
					'value'       => '',
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'bigboom' ),
				),
			),
		) );

		// Add images carousel shortcode
		vc_map( array(
			'name'     => __( 'BigBoom Images Carousel', 'bigboom' ),
			'base'     => 'images_carousel',
			'class'    => '',
			'category' => __( 'Content', 'bigboom' ),
			'params'   => array(
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Title', 'bigboom' ),
					'param_name'  => 'title',
					'value'       => '',
				),
				array(
					'type'        => 'attach_images',
					'holder'      => 'div',
					'heading'     => __( 'Images', 'bigboom' ),
					'param_name'  => 'images',
					'value'       => '',
					'description' => __( 'Select images from media library', 'bigboom' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Image size', 'bigboom' ),
					'param_name'  => 'image_size',
					'value'       => $this->image_sizes(),
					'description' => __( 'Select image size. Leave empty to use "thumbnail" size.', 'bigboom' ),
				),
				array(
					'type'        => 'textarea',
					'holder'      => 'div',
					'heading'     => __( 'Custom links', 'bigboom' ),
					'param_name'  => 'custom_links',
					'description' => __( 'Enter links for each slide here. Divide links with linebreaks (Enter).', 'bigboom' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Custom link target', 'bigboom' ),
					'param_name'  => 'custom_links_target',
					'value'       => array(
						__( 'Same window', 'bigboom' ) => '_self',
						__( 'New window', 'bigboom' )  => '_blank',
					),
					'description' => __( 'Select where to open custom links.', 'bigboom' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Slides per view', 'bigboom' ),
					'param_name'  => 'number',
					'value'       => 4,
					'description' => __( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode.', 'bigboom' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => __( 'Slider autoplay', 'bigboom' ),
					'param_name'  => 'auto_play',
					'value'       => array( __( 'Yes', 'bigboom' ) => 'true' ),
					'description' => __( 'Enables autoplay mode.', 'bigboom' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => __( 'Hide prev/next buttons', 'bigboom' ),
					'param_name'  => 'hide_navigation',
					'value'       => array( __( 'Yes', 'bigboom' ) => 'false' ),
					'description' => __( 'If "YES" prev/next control will be removed.', 'bigboom' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Extra class name', 'bigboom' ),
					'param_name'  => 'class_name',
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'bigboom' ),
				),
			),
		) );

		// Add s carousel shortcode
		vc_map( array(
			'name'     => __( 'BigBoom Posts Carousel', 'bigboom' ),
			'base'     => 'posts_carousel',
			'class'    => '',
			'category' => __( 'Content', 'bigboom' ),
			'params'   => array(
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Title', 'bigboom' ),
					'param_name'  => 'title',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Total Posts', 'bigboom' ),
					'param_name'  => 'total',
					'value'       => '12',
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Categories', 'bigboom' ),
					'param_name'  => 'categories',
					'value'       => $this->get_categories(),
					'description' => __( 'Select posts categories.', 'bigboom' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Order By', 'bigboom' ),
					'param_name'  => 'order_by',
					'value'       => array(
						__( 'Date', 'bigboom' )       => 'date',
						__( 'Title', 'bigboom' )      => 'title',
						__( 'Modified', 'bigboom' )   => 'modified',
						__( 'Menu Order', 'bigboom' ) => 'menu_order',
						__( 'Random', 'bigboom' )     => 'rand',
					),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Order', 'bigboom' ),
					'param_name'  => 'order',
					'value'       => array(
						__( 'Descending ', 'bigboom' ) => 'desc',
						__( 'Ascending ', 'bigboom' )  => 'asc',
					),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Excerpt Length (words)', 'bigboom' ),
					'param_name'  => 'excerpt_length',
					'value'       => 10,
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => __( 'Hide Readmore Text', 'bigboom' ),
					'param_name'  => 'hide_read_more',
					'value'       => array( __( 'Yes', 'bigboom' ) => 'true' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Readmore Text', 'bigboom' ),
					'param_name'  => 'read_more_text',
					'value'       => __( 'Read More', 'bigboom' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Slides per view', 'bigboom' ),
					'param_name'  => 'number',
					'value'       => 3,
					'description' => __( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode.', 'bigboom' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => __( 'Slider autoplay', 'bigboom' ),
					'param_name'  => 'auto_play',
					'value'       => array( __( 'Yes', 'bigboom' ) => 'true' ),
					'description' => __( 'Enables autoplay mode.', 'bigboom' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => __( 'Hide prev/next buttons', 'bigboom' ),
					'param_name'  => 'hide_navigation',
					'value'       => array( __( 'Yes', 'bigboom' ) => 'false' ),
					'description' => __( 'If "YES" prev/next control will be removed.', 'bigboom' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Extra class name', 'bigboom' ),
					'param_name'  => 'class_name',
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'bigboom' ),
				),
			),
		) );

		// Add Icon Box shortcode
		vc_map( array(
			'name'     => __( 'Icon Box', 'bigboom' ),
			'base'     => 'icon_box',
			'class'    => '',
			'category' => __( 'Content', 'bigboom' ),
			'admin_enqueue_css' => THEME_URL . '/css/vc/icon-field.css',
			'params'   => array(
				array(
					'type'        => 'icon',
					'holder'      => 'div',
					'heading'     => __( 'Icon', 'bigboom' ),
					'param_name'  => 'icon',
					'value'       => '',
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Icon Position', 'bigboom' ),
					'param_name'  => 'icon_position',
					'value'       => array(
						__( 'Left', 'bigboom' ) => 'Left',
						__( 'Top', 'bigboom' )  => 'top',
					),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Title', 'bigboom' ),
					'param_name'  => 'title',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'SubTitle', 'bigboom' ),
					'param_name'  => 'subtitle',
					'value'       => '',
				),
				array(
					'type'        => 'textarea_html',
					'holder'      => 'div',
					'heading'     => __( 'Content', 'bigboom' ),
					'param_name'  => 'content',
					'value'       => '',
					'description' => __( 'Enter the content of this box', 'bigboom' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Readmore Text', 'bigboom' ),
					'param_name'  => 'read_more_text',
					'value'       => __( '', 'bigboom' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Link', 'bigboom' ),
					'param_name'  => 'link',
					'value'       => '',
					'description' => __( 'Enter URL if you want this title to have a link.', 'bigboom' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Extra class name', 'bigboom' ),
					'param_name'  => 'icon_class',
					'value'       => '',
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'bigboom' ),
				)
			),
		) );
	}

	/**
	 * Get available image sizes
	 *
	 * @return string
	 */
	function image_sizes() {
		$output = array();

		foreach ( $this->get_image_sizes() as $name => $size ) {
			$output[ucfirst( $name ) . ' (' . $size['width'] . 'x' . $size['height'] . ')'] = $name;
		}
		$output[__( 'Full Size', 'bigboom' )] = 'full';
		return $output;
	}

	/**
	 * Get available image sizes with width and height following
	 *
	 * @return array|bool
	 */
	function get_image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes       = array();
		$image_sizes = get_intermediate_image_sizes();

		// Create the full array with sizes and crop info
		foreach ( $image_sizes as $size ) {
			if ( in_array( $size, array( 'thumbnail', 'medium', 'large' ) ) ) {
				$sizes[$size]['width']  = get_option( $size . '_size_w' );
				$sizes[$size]['height'] = get_option( $size . '_size_h' );
			} elseif ( isset( $_wp_additional_image_sizes[$size] ) ) {
				$sizes[$size] = array(
					'width'  => $_wp_additional_image_sizes[$size]['width'],
					'height' => $_wp_additional_image_sizes[$size]['height'],
				);
			}
		}

		return $sizes;
	}

	/**
	 * Get categories
	 *
	 * @return array|string
	 */
	function get_categories() {
		$output[__( 'All', 'bigboom' )] = '';
		$categories = get_terms( 'category' );
		if( $categories  ) {
			foreach ( $categories as $category ) {
				$output[$category->name] = $category->term_id;
			}
		}
		return $output;
	}

	/**
	 * Get taxonomies
	 *
	 * @return array|string
	 */
	function get_taxonomies() {
		$output[__( 'All', 'bigboom' )] = '';
		$taxonomies = get_terms( 'product_cat' );
		if( ! is_wp_error( $taxonomies ) && $taxonomies  ) {
			foreach ( $taxonomies as $taxonomy ) {
				$output[$taxonomy->name] = $taxonomy->term_id;
			}
		}
		return $output;
	}

	/**
	 * Return setting UI for icon param type
	 *
	 * @param  array $settings
	 * @param  string $value
	 *
	 * @return string
	 */
	function icon_param( $settings, $value ) {
		// Generate dependencies if there are any
		$dependency = vc_generate_dependencies_attributes( $settings );
		$icons = array();
		foreach( $this->icons as $icon ) {
			$icons[] = sprintf(
				'<i data-icon="%1$s" class="%1$s %2$s"></i>',
				$icon,
				$icon == $value ? 'selected' : ''
			);
		}

		return sprintf(
			'<div class="icon_block">
				<span class="icon-preview"><i class="%s"></i></span>
				<input type="text" class="icon-search" placeholder="%s">
				<input type="hidden" name="%s" value="%s" class="wpb_vc_param_value wpb-textinput %s %s_field" %s>
				<div class="icon-selector">%s</div>
			</div>',
			esc_attr( $value ),
			esc_attr__( 'Quick Search', 'bigboom' ),
			esc_attr( $settings['param_name'] ),
			esc_attr( $value ),
			esc_attr( $settings['param_name'] ),
			esc_attr( $settings['type'] ),
			$dependency,
			implode( '', $icons )
		);
	}
}

// Define classes for Icon box tabs shortcode
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_Icon_Box_Tabs extends WPBakeryShortCodesContainer {
	}
}

if ( class_exists( 'WPBakeryShortCode' ) ) {
	class WPBakeryShortCode_Icon_Box_Tab extends WPBakeryShortCode {
	}
}
