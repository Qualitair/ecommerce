<?php
$output = $title = $el_class = $open = $css_animation = '';
extract(shortcode_atts(array(
    'title' => __("Click to toggle", "media_center"),
    'el_class' => '',
    'open' => 'false',
    'css_animation' => ''
), $atts));

$el_class = $this->getExtraClass($el_class);

$is_active = ( $open == 'true' );

$panel_heading_css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_toggle' . $open, $this->settings['base'], $atts );
$panel_heading_css_class .= $this->getCSSAnimation($css_animation);

$panel_body_id = uniqid();
$panel_body_css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_toggle_content' . $el_class, $this->settings['base'], $atts );

if( $is_active ){
	$panel_body_css_class .= 'in';
}

?>

<div class="panel panel-faq">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a href="#collapse-<?php echo $panel_body_id;?>" data-toggle="collapse" class="collapse-toggle <?php if( !$is_active ){ echo 'collapsed'; } ?>">
				<?php echo $title;?>
			</a>
		</h4><!-- /.panel-title -->
	</div><!-- /.panel-heading -->
	<div class="panel-collapse collapse" id="collapse-<?php echo $panel_body_id;?>">
		<div class="panel-body">
			<?php echo wpb_js_remove_wpautop($content, true); ?>
		</div><!-- /.panel-body -->
	</div><!-- /.panel-collapse -->
</div><!-- /.panel-faq -->