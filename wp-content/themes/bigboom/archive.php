<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Bigboom
 */

get_header(); ?>

<div id="primary" class="content-area <?php bigboom_content_columns(); ?>">
	<main id="main" class="site-main" role="main">

	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'parts/content', get_post_format() ); ?>

		<?php endwhile; ?>

		<?php bigboom_numeric_pagination(); ?>

	<?php else : ?>

		<?php get_template_part( 'parts/content', 'none' ); ?>

	<?php endif; ?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
