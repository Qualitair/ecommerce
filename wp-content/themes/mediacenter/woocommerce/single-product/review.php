<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
?>
<div itemprop="reviews" itemscope itemtype="http://schema.org/Review" <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

	<div id="comment-<?php comment_ID(); ?>" class="comment_container comment-item review-item">
	    <div class="row no-margin">
	        <div class="col-lg-1 col-xs-12 col-sm-2 no-margin">
	            <div class="avatar">
	                <?php echo get_avatar( $comment, apply_filters( 'woocommerce_review_gravatar_size', '70' ), '', get_comment_author() ); ?>
	            </div><!-- /.avatar -->
	        </div><!-- /.col -->

	        <div class="col-xs-12 col-lg-11 col-sm-10 no-margin">
	            <div class="comment-body">
	                <div class="meta-info">
	                    <div class="author inline">
	                    	<strong itemprop="author"><?php comment_author(); ?></strong>
	                    	<?php
		                    	if ( get_option( 'woocommerce_review_rating_verification_label' ) === 'yes' )
									if ( wc_customer_bought_product( $comment->comment_author_email, $comment->user_id, $comment->comment_post_ID ) )
										echo '<em class="verified">(' . __( 'verified owner', 'media_center' ) . ')</em> ';
							?>
	                    </div>
	                    <div class="star-holder inline">
                        	<?php if ( $rating && get_option( 'woocommerce_enable_review_rating' ) == 'yes' ) : ?>
								<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf( __( 'Rated %d out of 5', 'media_center' ), $rating ) ?>">
									<span style="width:<?php echo ( $rating / 5 ) * 100; ?>%"><strong itemprop="ratingValue"><?php echo $rating; ?></strong> <?php _e( 'out of 5', 'media_center' ); ?></span>
								</div>

							<?php endif; ?>
	                    </div>
	                    <div class="date inline pull-right">
	                        <time itemprop="datePublished" datetime="<?php echo get_comment_date( 'c' ); ?>"><?php echo get_comment_date( __( get_option( 'date_format' ), 'media_center' ) ); ?></time>
	                    </div>
	                </div><!-- /.meta-info -->
	                
	                <div itemprop="description" class="description comment-text">
	                	<?php comment_text(); ?>
	                </div>
	                <!-- /.comment-text -->
	            </div><!-- /.comment-body -->

	        </div><!-- /.col -->

	    </div><!-- /.row -->
	</div><!-- /.comment-item -->
</div>