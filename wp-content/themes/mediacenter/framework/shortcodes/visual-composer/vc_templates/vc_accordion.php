<?php
$output = $title = $interval = $el_class = $collapsible = $active_tab = '';
//
extract(shortcode_atts(array(
    'title' 			=> '',
    'subtitle' 			=> '',
    'interval' 			=> 0,
    'el_class' 			=> '',
    'collapsible' 		=> 'no',
    'active_tab' 		=> '1',
    'group_panels' 		=> true,
    'accordion_style' 	=> ''
), $atts));

if(!empty($subtitle)){
	$subtitle = '<p class="single-line">'.$subtitle.'</p>';
}

$count = 1;
$accordionID = uniqid('accordion-');

while(($letter_pos = strpos($content, '[vc_accordion_tab')) !== false) {
	
	if($group_panels !== 'false'){
		if( $active_tab == $count){
			$replaceText = '[panel parent="'.$accordionID.'" active="true"';
		}else{
			$replaceText = '[panel parent="'.$accordionID.'"'; 
		}
	}else{
		if( $active_tab == $count){
			$replaceText = '[panel active="true"';
		}else{
			$replaceText = '[panel'; 
		}
	}
	
	$replaceTextCount = strlen('[vc_accordion_tab');
	$count++;
    $content = substr_replace($content, $replaceText, $letter_pos, $replaceTextCount);
}
$content = str_replace('[panel', '[vc_accordion_tab', $content);

$el_class = $this->getExtraClass($el_class);


$panel_class = !empty( $accordion_style ) ? $accordion_style : 'style-1';

$output .= wpb_widget_title( array( 'title' => $title, 'extraclass' => 'accordion-title-' . $accordion_style ) );
$output .= "\n\t\t".$subtitle;
$output .= "\n\t\t\t".'<div id="'.$accordionID.'" class="panel-group-faq ' . $panel_class . ' panel-group">';
	$output .= "\n\t\t\t\t".wpb_js_remove_wpautop($content);
$output .= "\n\t\t\t".'</div> '.$this->endBlockComment('.panel-group');

echo $output;