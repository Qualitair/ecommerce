<?php
/**
 * Vertical Menu Widget
 *
 * @author 		Ibrahim Ibn Dawood
 * @category 	Widgets
 * @package 	MediaCenter/Framework/Widgets
 * @version 	1.0.6
 * @extends 	WC_Widget
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class MC_Widget_Vertical_Menu extends WC_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'vertical-menu';
		$this->widget_description = __( 'Home Page like vertical menu.', 'media_center' );
		$this->widget_id          = 'media_center_vertical_menu';
		$this->widget_name        = __( 'Media Center Vertical Menu', 'media_center' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => __( 'Shop By Departments', 'media_center' ),
				'label' => __( 'Title for vertical menu', 'media_center' )
			),
			'icon_class'  => array(
				'type'  => 'text',
				'std'   => 'fa-list',
				'label' => sprintf( __('Fontawesome Icon Class. Default icon is <em>fa-list</em>. For complete list of icon classes %s', 'media_center' ), '<a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">' . __( 'Click here', 'media_center' ) . '</a>' )
			),
			'menu'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Menu ID, slug, or name. Leave it empty to pull all product categories.', 'media_center')
			),
			'dropdown_trigger' => array(
				'type'    => 'select',
				'std'     => 'click',
				'label'   => __( 'Dropdown Trigger', 'media_center' ),
				'options' => array(
					'click' => __( 'Click', 'media_center' ),
	      			'hover' => __( 'Hover', 'media_center' ),
				)
			),
			'dropdown_animation' => array(
				'type'    => 'select',
				'std'     => 'none',
				'label'   => __( 'Dropdown Animation', 'media_center' ),
				'options' => array(
					'none' 				=>	__( 'No Animation', 'media_center' ),
					'bounceIn' 			=>	__( 'BounceIn', 'media_center' ),
					'bounceInDown' 		=>	__( 'BounceInDown', 'media_center' ),
					'bounceInLeft' 		=>	__( 'BounceInLeft', 'media_center' ),
					'bounceInRight' 	=>	__( 'BounceInRight', 'media_center' ),
					'bounceInUp' 		=>	__( 'BounceInUp', 'media_center' ),
					'fadeIn' 			=>	__( 'FadeIn', 'media_center' ),
					'fadeInDown' 		=>	__( 'FadeInDown', 'media_center' ),
					'fadeInDownBig' 	=>	__( 'FadeInDown Big', 'media_center' ),
					'fadeInLeft' 		=>	__( 'FadeInLeft', 'media_center' ),
					'fadeInLeftBig' 	=>	__( 'FadeInLeft Big', 'media_center' ),
					'fadeInRight' 		=>	__( 'FadeInRight', 'media_center' ),
					'fadeInRightBig' 	=>	__( 'FadeInRight Big', 'media_center' ),
					'fadeInUp' 			=>	__( 'FadeInUp', 'media_center' ),
					'fadeInUpBig' 		=>	__( 'FadeInUp Big', 'media_center' ),
					'flipInX' 			=>	__( 'FlipInX', 'media_center' ),
					'flipInY' 			=>	__( 'FlipInY', 'media_center' ),
					'lightSpeedIn' 		=>	__( 'Light SpeedIn', 'media_center' ),
					'rotateIn' 			=>	__( 'RotateIn', 'media_center' ),
					'rotateInDownLeft' 	=>	__( 'RotateInDown Left', 'media_center' ),
					'rotateInDownRight' =>	__( 'RotateInDown Right', 'media_center' ),
					'rotateInUpLeft' 	=>	__( 'RotateInUp Left', 'media_center' ),
					'rotateInUpRight' 	=>	__( 'RotateInUp Right', 'media_center' ),
					'roleIn' 			=>	__( 'RoleIn', 'media_center' ),
					'zoomIn' 			=>	__( 'ZoomIn', 'media_center' ),
					'zoomInDown' 		=>	__( 'ZoomInDown', 'media_center' ),
					'zoomInLeft' 		=>	__( 'ZoomInLeft', 'media_center' ),
					'zoomInRight' 		=>	__( 'ZoomInRight', 'media_center' ),
					'zoomInUp' 			=>	__( 'ZoomInUp', 'media_center' ),
				)
			),
			'el_class'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Extra Class', 'media_center' )
			)
		);
		parent::__construct();
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	public function widget( $args, $instance ) {

		if ( $this->get_cached_widget( $args ) )
			return;

		ob_start();
		extract( $args );		

		echo $before_widget;

		$title = apply_filters( 'widget_title', $instance['title'] );

		echo do_shortcode( '[mc_vertical_menu title="' . $title. '" icon_class="' . $instance['icon_class']. '" menu="' . $instance['menu']. '" dropdown_trigger="'. $instance['dropdown_trigger']. '" dropdown_animation="' . $instance['dropdown_animation']. '" el_class="'. $instance['el_class']. '"]' );

		echo $after_widget;

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget( $args, $content );
	}
}

register_widget( 'MC_Widget_Vertical_Menu' );