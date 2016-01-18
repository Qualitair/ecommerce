<?php
/**
 * Search Bar
 *
 * @author      Ibrahim Ibn Dawood
 * @package     Framework/Templates
 * @version     1.0.6
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $media_center_theme_options;

$display_search_categories_filter = isset( $media_center_theme_options['display_search_categories_filter'] ) ? $media_center_theme_options['display_search_categories_filter'] : true;
?>

<!-- ============================================================= SEARCH AREA ============================================================= -->
<div class="search-area animate-dropdown <?php if( !$display_search_categories_filter ) : ?>no-search-categories-filter<?php endif; ?>">
    <form role="search" id="search-header-form" method="get" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
        <div class="search-control-group control-group">
            <label class="sr-only screen-reader-text" for="s"><?php  echo __( 'Search for:', 'media_center' );?></label>
            <input type="text" class="search-field" value="<?php echo get_search_query();?>" name="s" placeholder="<?php echo __( 'Search for products', 'media_center' );?>" />
            <div class="search-bar-controls">
                <?php
                    $selected_cat = isset($_GET['product_cat']) ? $_GET['product_cat'] : '';
                    if( isset( $_GET['product_cat'] ) ) {
                        $selected_cat = $_GET['product_cat'];
                        $search_param = array(
                            'name'  => 'product_cat',
                            'value' => $selected_cat
                        );
                    } else {
                        $search_param = array(
                            'name'  => 'post_type',
                            'value' => 'product'
                        );
                    }

                    $parent_arg = $media_center_theme_options['header_search_dropdown'] == 'hsd0' ? 0 : '';
                    $categories = get_categories( array( 'parent' => $parent_arg , 'hide_empty' => 1, 'taxonomy' => 'product_cat' ) ); 
                    $category_filter_value = '';
                    $categories_filter = '';
                    foreach ($categories as $category){
                        if($selected_cat == $category->category_nicename){
                            $category_filter_value = $category->cat_name;
                            $categories_filter .= '<li class="active" role="presentation"><a class="category-dropdown" role="menuitem" data-value="' . $category->category_nicename . '" tabindex="-1" href="#">' . $category->cat_name . '</a></li>';
                        }else{
                            $categories_filter .= '<li role="presentation"><a class="category-dropdown" role="menuitem" data-value="' . $category->category_nicename . '" tabindex="-1" href="#">' . $category->cat_name . '</a></li>';
                        }                                                                      
                    }
                    if($category_filter_value === ''){
                        $category_filter_value = __('All Categories', 'media_center');
                    }
                ?>
                <?php if( $display_search_categories_filter ) : ?>
                <ul class="list-unstyled categories-filter animate-dropdown">
                    <li class="dropdown">
                        <a id="category-filter-value" class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $category_filter_value; ?></a>
                        <ul class="dropdown-menu" role="menu">
                            <li role="presentation"><a class="category-dropdown" role="menuitem" data-value="" tabindex="-1" href="#"><?php echo __('All Categories', 'media_center'); ?></a>
                            <?php echo $categories_filter; ?>
                        </ul>
                    </li>
                </ul>
                <?php endif; ?>
                <button type="submit" class="btn search-button" value="<?php echo esc_attr__( 'Search', 'media_center' );?>"></button>
                <input type="hidden" id="search-param" name="<?php echo $search_param['name'];?>" value="<?php echo $search_param['value'];?>" />
            </div>
        </div>
    </form>
</div><!-- /.search-area -->
<!-- ============================================================= SEARCH AREA : END ============================================================= -->		