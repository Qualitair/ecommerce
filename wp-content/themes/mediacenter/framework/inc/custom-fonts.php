<?php

function media_center_fonts() {		
	
	global $media_center_theme_options;

	$use_predefined_fonts = isset( $media_center_theme_options['use_default_font'] ) ? $media_center_theme_options['use_default_font'] : true ;

	if( ! $use_predefined_fonts ){
		$media_center_customfont = '';
		$google_fonts_counter = 0;
		
		$googlefonts = array(
			$media_center_theme_options['default_font'],
			$media_center_theme_options['title_font']
		);
					
		foreach($googlefonts as $googlefont) {
		
			if (!isset($googlefont['google'])) {
				$media_center_customfont = str_replace(' ', '+', $googlefont['font-family']). ':100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic|subset=latin,' . $googlefont['subsets'] . '|' . $media_center_customfont;
				$google_fonts_counter++;
			} else {
				if ($googlefont['google'] == "true") {
					$media_center_customfont = str_replace(' ', '+', $googlefont['font-family']). ':100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic|subset=latin,' . $googlefont['subsets'] . '|' . $media_center_customfont;
					$google_fonts_counter++;
				}
			}
			
		}
				
		if ($google_fonts_counter > 0) wp_enqueue_style( 'media_center-googlefonts', "//fonts.googleapis.com/css?family=". substr_replace($media_center_customfont ,"",-1), NULL, NULL, 'all' );
	}
}
add_action( 'wp_enqueue_scripts', 'media_center_fonts' );