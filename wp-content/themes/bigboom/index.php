<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package BigBoom
 */
get_header(); ?>
<div id="primary" class="content-area <?php bigboom_content_columns() ?>">
	<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();
				get_template_part( 'parts/content', get_post_format() );
			endwhile;

			echo '<div class="pagination">';
			bigboom_results_pagination();
			bigboom_numeric_pagination();
			echo '</div>';
		else :
			get_template_part( 'parts/content', 'none' );
		endif;
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
