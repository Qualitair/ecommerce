<?php global $mc_page_metabox; ?>

<?php

$post_classes = '';
$page_title = $mc_page_metabox->get_the_value( 'page_title' ) ;
$page_subtitle = $mc_page_metabox->get_the_value( 'page_subtitle' ) ;

if( $mc_page_metabox->get_the_value( 'container_unwrap' ) !== '1' ){
	$post_classes = 'container';
}

if( empty( $page_title ) ){
	$page_title = get_the_title();
}

$should_hide_title = media_center_should_hide_title();

?>
<div id="post-<?php the_ID(); ?>" <?php post_class( $post_classes ); ?>>
	
	<?php if( ! $should_hide_title ): ?>
	<div class="section section-page-title inner-xs">
		<div class="page-header">
			<h2 class="page-title"><?php echo $page_title;?></h2>
			<?php if( !empty( $page_subtitle ) ) : ?>
			<p class="page-subtitle"><?php echo $mc_page_metabox->get_the_value( 'page_subtitle' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>
        
    <div class="entry-content inner-bottom-sm">
        <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'media_center' ) ); ?>
        <?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'media_center' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
        <?php
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}

		?>
    </div><!-- .entry-content -->

</div><!-- #post-<?php the_ID(); ?> -->