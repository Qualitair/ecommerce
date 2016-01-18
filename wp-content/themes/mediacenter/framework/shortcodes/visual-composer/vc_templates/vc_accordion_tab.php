<?php
$output = $title = '';

extract(shortcode_atts(array(
	'title' => __("Section", "media_center"),
	'parent' => '',
	'active' => false,
	'enable_arrow' => true,
	'headline_alignment' => 'text-left'
), $atts));

if($active == 'true'){
	$panelCollapseClass = 'panel-collapse collapse in';
	$panelToggleClass = 'panel-toggle';
}else{
	$panelCollapseClass = 'panel-collapse collapse';
	$panelToggleClass = 'panel-toggle collapsed';
}

if($enable_arrow !== 'false'){
	$title = '<span>'.$title.'</span>';
}

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_accordion_section group', $this->settings['base'], $atts );
$panel_heading_class = 'panel-heading '.$headline_alignment;

if(!empty($parent)){
	$data_attributes = ' data-toggle="collapse" data-parent="#'.$parent.'" ';
}else{
	$data_attributes = ' data-toggle="collapse" ';
}

$output .= "\n\t\t\t" . '<div class="panel panel-faq">';
	$output .= "\n\t\t\t\t" . '<div class="'. $panel_heading_class. '">';
    	$output .= "\n\t\t\t\t\t" . '<h4 class="panel-title"><a class="'.$panelToggleClass.'" ' .$data_attributes. ' href="#panel-collapse-'.sanitize_title($title).'">'.$title.'</a></h4>';
	$output .= "\n\t\t\t\t" . '</div>' . $this->endBlockComment('.panel-heading') . "\n";
	$output .= "\n\t\t\t\t" . '<div id="panel-collapse-'.sanitize_title($title).'" class="'.$panelCollapseClass.'">';
    	$output .= "\n\t\t\t\t\t" . '<div class="panel-body">';
        	$output .= ($content=='' || $content==' ') ? __("Empty section. Edit page to add content here.", "media_center") : "\n\t\t\t\t" . wpb_js_remove_wpautop($content);
    	$output .= "\n\t\t\t\t\t" . '</div>';
	$output .= "\n\t\t\t\t" . '</div>' . $this->endBlockComment('.panel-collapse') . "\n";
$output .= "\n\t\t\t" . '</div> ' . $this->endBlockComment('.panel') . "\n";

echo $output;