<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package BigBoom
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<section class="error-404 not-found">
			<header class="page-header">
				<h1 class="page-title"><?php _e( '404', 'bigboom' ); ?></h1>
			</header><!-- .page-header -->

			<div class="page-content col-sm-6 col-sm-offset-3">				
				<h2 class="error-title"><?php _e( '<center><div class="error-404-logo"><img src="/wp-content/uploads/2015/11/warning-message-icon.png" /></div>Oops! Page can&rsquo;t be found.</center>', 'bigboom' ); ?></h2>
				<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'bigboom' ); ?></p>

				<?php get_search_form(); ?>

			</div><!-- .page-content -->
		</section><!-- .error-404 -->

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
