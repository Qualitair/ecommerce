<?php
/**
 * Pagination - Show numbered pagination for catalog pages.
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wp_query;
?>
<div class="pagination-holder">
    <div class="row">
    	<?php if ( $wp_query->max_num_pages > 1 ) : ?>
    	<div class="col-xs-12 col-sm-8 text-left">
            <nav class="woocommerce-pagination">
				<?php
					echo paginate_links( apply_filters( 'woocommerce_pagination_args', array(
						'base'         => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
						'format'       => '',
						'current'      => max( 1, get_query_var( 'paged' ) ),
						'total'        => $wp_query->max_num_pages,
						'prev_text'    => '&larr;',
						'next_text'    => '&rarr;',
						'type'         => 'list',
						'end_size'     => 3,
						'mid_size'     => 3
					) ) );
				?>
			</nav>
		</div>
		<?php endif; ?>

        <div class="col-xs-12 col-sm-4 <?php if ( $wp_query->max_num_pages > 1 ){ echo 'text-right';}?>">
            <?php wc_get_template( 'loop/result-count.php' ); ?>
        </div>

    </div><!-- /.row -->
</div>