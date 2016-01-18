<?php
/**
 * Control Bar
 *
 * @author 		Ibrahim Ibn Dawood
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="control-bar clearfix">

	<?php woocommerce_catalog_ordering(); ?>

	<?php extract( media_center_get_grid_list_tab_pane_classes() ); ?>
	
	<div class="grid-list-buttons">
        <ul>
            <li class="grid-list-button-item <?php echo $grid_tab_pane_class; ?>"><a href="#grid-view" data-toggle="tab"><i class="fa fa-th-large"></i> <?php echo _x( 'Grid', 'Grid as in list view/grid view', 'media_center' );?></a></li>
            <li class="grid-list-button-item <?php echo $list_tab_pane_class; ?>"><a href="#list-view" data-toggle="tab"><i class="fa fa-th-list"></i> <?php echo _x( 'List', 'List as in list view/grid view', 'media_center' ); ?></a></li>
        </ul>
    </div>
</div>