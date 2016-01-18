<?php
/**
 * Hooks for template archive
 *
 * @package BigBoom
 */


/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @since 1.0
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function bigboom_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'bigboom_setup_author' );

/**
 * Template Comment
 *
 * @since  1.0
 *
 * @param  array  $comment
 * @param  array  $args
 * @param  int    $depth
 *
 * @return mixed
 */
function bigboom_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
	?>

	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
		<article id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>

	<div class="comment-author vcard">
		<?php
		if ( $args['avatar_size'] != 0 ) {
			echo get_avatar( $comment, $args['avatar_size'] );
		}
		?>
	</div>
	<div class="comment-meta commentmetadata">
		<?php printf( '<cite class="author-name">%s</cite>', get_comment_author_link() ); ?>

		<a class="author-posted" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php printf( ', %1$s, %2$s', get_comment_date( 'd F Y' ),  get_comment_time() ); ?>
		</a>

		<?php
		comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => '<i class="fa fa-mail-reply"></i>' ) ) );
		edit_comment_link( __( 'Edit', 'bigboom' ), '  ', '' );
		?>

		<?php if ( $comment->comment_approved == '0' ) : ?>
		<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'bigboom' ); ?></em>
		<?php endif; ?>

		<div class="comment-content">
			<?php comment_text(); ?>
		</div>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
		</article>
	<?php endif; ?>
	<?php
}

/**
 * Change the excerpt length
 *
 * @since  1.0.0
 *
 * @param  int $length
 * @return int
 */
function bigboom_excerpt_length( $length ) {
	$custom = intval( bigboom_theme_option( 'excerpt_length' ) );
	if ( $custom ) {
		$length = $custom;
	}

	return $length;
}
add_filter( 'excerpt_length', 'bigboom_excerpt_length' );

/**
 * Change more string at the end of the excerpt
 *
 * @since  1.0
 *
 * @param string $more
 *
 * @return string
 */
function bigboom_excerpt_more( $more ) {
    $more = '&hellip;';

	return $more;
}
add_filter( 'excerpt_more', 'bigboom_excerpt_more' );