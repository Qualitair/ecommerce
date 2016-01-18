<?php
$output = $el_class = $bg_image = $bg_color = $bg_image_repeat = $font_color = $padding = $margin_bottom = $css = '';
extract(shortcode_atts(array(
    'el_class'        => '',
    'bg_image'        => '',
    'bg_color'        => '',
    'bg_image_repeat' => '',
    'font_color'      => '',
    'padding'         => '',
    'margin_bottom'   => '',
    'css'             => '',
    'has_container'   => '',
    'container_class' => '',
    'row_animation'   => '',
), $atts));

$el_class = $this->getExtraClass($el_class);

wp_enqueue_script( 'wpb_composer_front_js' );

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, get_row_css_class() . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

if( $row_animation != '' && $row_animation != 'none' ) {
    $css_class .= ' wow '. $row_animation ;
}

$style = $this->buildStyle($bg_image, $bg_color, $bg_image_repeat, $font_color, $padding, $margin_bottom);

if( $has_container ){
    $output = '<div class="row-wrapper ' . $css_class . '" ' . $style . '>';
    $output .= '<div class="' . $container_class . '">';
    $output .= '<div class="row">';
    $output .= wpb_js_remove_wpautop($content);
    $output .= '</div>' . $this->endBlockComment('row');
    $output .= '</div>' . $this->endBlockComment( $container_class );
    $output .= '</div>' . $this->endBlockComment( 'row-wrapper' );
}else{
    $output .= '<div class="row ' . $css_class . '" ' . $style . '>';
    $output .= wpb_js_remove_wpautop($content);
    $output .= '</div>' . $this->endBlockComment('row');
}

echo $output;