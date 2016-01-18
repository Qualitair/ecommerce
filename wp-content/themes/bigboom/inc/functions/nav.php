<?php
/**
 * Custom functions for nav menu
 *
 * @package BigBoom
 */

/**
 * Display numeric pagination
 *
 * @since 1.0
 * @return void
 */
function bigboom_numeric_pagination() {
	global $wp_query;

	if( $wp_query->max_num_pages < 2 ) {
        return;
	}

	?>
	<nav class="navigation paging-navigation numeric-navigation" role="navigation">
		<?php
		$big = 999999999;
		$args = array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'total'     => $wp_query->max_num_pages,
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'prev_text' => __( 'Previous', 'bigboom' ),
			'next_text' => __( 'Next', 'bigboom' ),
			'type'      => 'plain',
		);

		echo paginate_links( $args );
		?>
	</nav>
<?php
}

/**
 * Display results pagination
 *
 * @since 1.0
 * @return void
 */
function bigboom_results_pagination() {
	$posts_per_page = get_option( 'posts_per_page' );
	$paged = get_query_var( 'paged', 1 );
	$paged = ( $paged == 0 ) ? 1 : $paged;
	$start = $posts_per_page * ( $paged - 1 );
	$results_string = sprintf( __( 'Results: %s - %s of %s', 'bigboom' ),
		$start + 1,
		$start + $posts_per_page,
		wp_count_posts()->publish
	);

	echo '<div class="results-navigation">' . $results_string . '</div>';
?>
<?php
}
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since 1.0
 * @return void
 */
function bigboom_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
				<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'bigboom' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'bigboom' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
<?php
}


/**
 * Display navigation to next/previous post when applicable.
 *
 * @since 1.0
 * @return void
 */
function bigboom_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<div class="nav-links">
			<?php
			previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'bigboom' ) );
			next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link',     'bigboom' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
<?php
}
