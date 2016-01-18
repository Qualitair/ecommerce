<?php
/**
 * @package BigBoom
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if(  ! bigboom_get_meta( 'hide_singular_title' ) ) : ?>
		<header class="entry-header clearfix">
			<?php bigboom_entry_thumbnail(); ?>

			<h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title() ?></a></h2>

			<span class="entry-author">
				<i class="fa fa-pencil"></i>
				<?php _e( 'By:', 'bigboom' ); ?>
				<a class="url fn n" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ) ?>" title="<?php echo esc_attr( sprintf( __( 'View all posts by %s', 'bigboom' ), get_the_author() ) ) ?>" rel="author">
					<?php the_author(); ?>
				</a>
			</span>
			<?php
			$time_string = '<time class="entry-date published" datetime="%s"><i class="fa fa-calendar"></i><span>%s</span></time>';

			printf(
				$time_string,
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date( 'd F Y' ) )
			);
			?>

			<span class="entry-comments">
				<i class="fa fa-comments-o"></i>
				<?php comments_popup_link( __( '0 comment', 'bigboom' ), __( '1 comments', 'bigboom' ), __( '% comments', 'bigboom' ), 'comments-link' ); ?>
			</span>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'bigboom' ) );
				if ( $tags_list ) :
			?>
			<span class="tags-links">
				<?php printf( __( 'Tags: %1$s', 'bigboom' ), $tags_list ); ?>
			</span>
			<?php endif; ?>
		</header>
	<?php endif; ?>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'bigboom' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
