<?php
/*
 * The template for displaying Blog Posts
 */
	get_header(); 
?>

<?php 
	
	global $media_center_theme_options;

	$blog_layout = isset( $media_center_theme_options['blog_layout'] ) ? $media_center_theme_options['blog_layout'] : 'sidebar_right';

	if ( $blog_layout == 'without_sidebar' ) {

		$full_width_density = isset( $media_center_theme_options['full_width_density'] ) ? $media_center_theme_options['full_width_density'] : 'narrow';

		if ( $full_width_density == 'wide' ) {
			get_template_part( 'templates/blog-fullwidth-wide' );
		} else {
			get_template_part( 'templates/blog-fullwidth-narrow' );
		}

	} else if ( $blog_layout == 'sidebar_left' ) {
		get_template_part( 'templates/blog-sidebar-left' );
	} else{
		get_template_part( 'templates/blog-sidebar-right' );
	}

?>


<?php get_footer();