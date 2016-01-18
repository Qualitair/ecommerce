 <?php
/* /
Plugin Name: WooCommerce Hide Shop Products
Plugin URI: http://codecanyon.net/user/codewoogeek
Description:  WooCommerce Toggle/Hide Shop is the WooCommerce Extension which will Show/Hide Shop Page Products based on User Role
Version: 2.2
Author: codewoogeek
Author URI: http://codecanyon.net/user/codewoogeek
/ */

class WooToggler
{
    
    public static function hidemyproduct_in_shop($mainquery)
    {
        global $current_user;
        get_currentuserinfo();
        $user_roles = $current_user->roles;
        $user_role  = array_shift($user_roles);
        //echo"hhh";
        if (!$mainquery->is_main_query())
            return;
        if (!$mainquery->is_post_type_archive('product') && !$mainquery->is_tax(get_object_taxonomies('product')) && !$mainquery->is_search)
            return;
        
        
        $checkoptionenable = get_option('woo_enable_hide_products');
        if ($checkoptionenable == 'yes') {
            
            if (!is_admin() && is_shop() || is_product_category() || is_search()) {
               
                global $wp_roles;
                if (!isset($wp_roles)) {
                    $wp_roles = new WP_Roles();
                }
                $getdata = $wp_roles->get_names();
                
                foreach ($getdata as $data => $key) {
                    if (isset($user_role)) {
                        if ($user_role == $data) {

                            $listenableid        = get_option('woo_select_products_' . $data);
                            $listenable_category = get_option('woo_toggle_type_category_' . $data);
                            /* Product Category Support */
                            $getslugcategories   = array();
                            if (is_array($listenableid)) {
                                foreach ($listenableid as $eachproductid) {
                                    $getproductcategories = wp_get_post_terms($eachproductid, 'product_cat', array(
                                        'fields' => 'all'
                                    ));
                                    foreach ($getproductcategories as $eachcategory) {
                                        $getslugcategories[] = $eachcategory->slug;
                                    }
                                }
                            }
                            
                    
                            if (get_option('woo_include_exclude_products_' . $data) == '2') {
				
                                /*                                         * *** Hide the Product as well as Category ****** */
                                if (get_option('woo_hide_products_in_category_' . $data) == 'yes') {
                                    if ($mainquery->is_tax(get_object_taxonomies('product'))) {
                                        if (get_option('woo_toggle_products_by_type_' . $data) != '2') {
                                            $mainquery->set('post__not_in', $listenableid);
                                            $mainquery->set('tax_query', array(
                                                array(
                                                    'taxonomy' => 'product_cat',
                                                    'field' => 'slug',
                                                    'terms' => $getslugcategories,
                                                    'operator' => 'NOT IN'
                                                )
                                            ));
                                            
                                        } else {
                                           $mainquery->set( 'tax_query', array(array(
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'terms' => (array) $listenable_category, 
						'operator' => 'NOT IN'
					  )));
                                        }
                                    }
                                }
                                
                                if (get_option('woo_hide_products_in_search_' . $data) == 'yes') {
                                    if ($mainquery->is_search) {
                                        if (get_option('woo_toggle_products_by_type_' . $data) != '2') {
                                            $mainquery->set('post__not_in', $listenableid);
                                        } else {
                                            $mainquery->set( 'tax_query', array(array(
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'terms' => (array) $listenable_category, 
						'operator' => 'NOT IN'
					  )));
                                        }
                                    }
                                }
                                if (!$mainquery->is_tax(get_object_taxonomies('product')) && !$mainquery->is_search) {
                                    if (get_option('woo_toggle_products_by_type_' . $data) != '2') {
                                        $mainquery->set('post__not_in', $listenableid);
                                    } else {
                                         $mainquery->set( 'tax_query', array(array(
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'terms' => (array) $listenable_category, 
						'operator' => 'NOT IN'
					  )));
                                    }
                                }
                                
                                
                                //                                        if ($mainquery->is_search) {
                                //                                          
                                //                                            $mainquery->set('post', implode(',',$listenableid));
                                //                                        }
                            } else {
    
                                if (get_option('woo_hide_products_in_search_' . $data) == 'yes') {
                                    if ($mainquery->is_search) {
                                        if (get_option('woo_toggle_products_by_type_' . $data) != '2') {
                                            $mainquery->set('post__in', $listenableid);
                                        } else {
				           $mainquery->set( 'tax_query', array(array(
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'terms' => (array) $listenable_category, 
						'operator' => 'IN'
					  )));
                                        }
                                    }
                                }
                                
                                if (get_option('woo_hide_products_in_category_' . $data) == 'yes') {
                                    if ($mainquery->is_tax(get_object_taxonomies('product'))) {
                                        if (get_option('woo_toggle_products_by_type_' . $data) != '2') {
                                            $mainquery->set('post__in', $listenableid);
                                        } else {
                                           $mainquery->set( 'tax_query', array(array(
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'terms' => (array) $listenable_category, 
						'operator' => 'IN'
					  )));
                                        }
                                    }
                                }
                                
                                if (!$mainquery->is_tax(get_object_taxonomies('product')) && !$mainquery->is_search) {
                                    if (get_option('woo_toggle_products_by_type_' . $data) != '2') {
                                        $mainquery->set('post__in', $listenableid);
                                    } else {
                                       $mainquery->set( 'tax_query', array(array(
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'terms' => (array) $listenable_category, 
						'operator' => 'IN'
					  )));
                                    }
                                }
                            }
                        }
                        
                    } else {
                        
                        if (!is_user_logged_in()) {
                            
                            $listenableid = get_option('woo_select_products_guest');
                            $listenable_category = get_option('woo_toggle_type_category_guest');
                            /* Product Category Support */
                            $getslugcategories = array();
                            if (is_array($listenableid)) {
                                foreach ($listenableid as $eachproductid) {
                                    $getproductcategories = wp_get_post_terms($eachproductid, 'product_cat', array(
                                        'fields' => 'all'
                                    ));
                                    foreach ($getproductcategories as $eachcategory) {
                                        $getslugcategories[] = $eachcategory->slug;
                                    }
                                }
                            }
                            
                            
                            if (get_option('woo_include_exclude_products_guest') == '2') {
                                if (get_option('woo_hide_products_in_category_guest') == 'yes') {
                                    if ($mainquery->is_tax(get_object_taxonomies('product'))) {
                                        if (get_option('woo_toggle_products_by_type_guest') != '2') {
                                            $mainquery->set('post__not_in', $listenableid);
                                            /*   $mainquery->set('tax_query', array(array(
                                            'taxonomy' => 'product_cat',
                                            'field' => 'slug',
                                            'terms' => $getslugcategories,
                                            'operator' => 'NOT IN'
                                            )));*/
                                        } else {
                                          $mainquery->set( 'tax_query', array(array(
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'terms' => (array) $listenable_category, 
						'operator' => 'NOT IN'
					  )));
                                            
                                        }
                                    }
                                    //var_dump($getslugcategories);
                                    
                                }
                                if (get_option('woo_hide_products_in_search_guest') == 'yes') {
                                    if ($mainquery->is_search) {
                                        if (get_option('woo_toggle_products_by_type_guest') != '2') {
                                            $mainquery->set('post__not_in', $listenableid);
                                        } else {
                                           $mainquery->set( 'tax_query', array(array(
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'terms' => (array) $listenable_category, 
						'operator' => 'NOT IN'
					  )));
                                        }
                                    }
                                }
                                if (!$mainquery->is_tax(get_object_taxonomies('product')) && !$mainquery->is_search) {
                                    
                                    if (get_option('woo_toggle_products_by_type_guest') != '2') {
                                        $mainquery->set('post__not_in', $listenableid);
                                    } else {
                                        $mainquery->set( 'tax_query', array(array(
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'terms' => (array) $listenable_category, 
						'operator' => 'NOT IN'
					  )));
}
                                }
                            } else {
                                if (get_option('woo_hide_products_in_search_guest') == 'yes') {
                                    if ($mainquery->is_search) {
                                        if (get_option('woo_toggle_products_by_type_guest') != '2') {
                                            $mainquery->set('post__in', $listenableid);
                                        } else {
                                            $mainquery->set( 'tax_query', array(array(
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'terms' => (array) $listenable_category, 
						'operator' => 'IN'
					  )));
                                        }
                                    }
                                }
                                
                                if (get_option('woo_hide_products_in_category_guest') == 'yes') {
                                    if ($mainquery->is_tax(get_object_taxonomies('product'))) {
                                        if (get_option('woo_toggle_products_by_type_guest') != '2') {
                                            $mainquery->set('post__in', $listenableid);
                                        } else {
                                           $mainquery->set( 'tax_query', array(array(
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'terms' => (array) $listenable_category, 
						'operator' => 'IN'
					  )));
                                        }
                                    }
                                }
                                
                                if (!$mainquery->is_tax(get_object_taxonomies('product')) && !$mainquery->is_search) {
                                    if (get_option('woo_toggle_products_by_type_guest') != '2') {
                                        $mainquery->set('post__in', $listenableid);
                                    } else {
                                        $mainquery->set( 'tax_query', array(array(
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'terms' => (array) $listenable_category, 
						'operator' => 'IN'
					  )));
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            //remove_action( 'pre_get_posts', 'custom_pre_get_posts_query' );
        }
    }
    
    public static function allow_scripts()
    {
        global $my_admin_page;
        
        $newscreenids = get_current_screen();
        
        if (isset($_GET['tab'])) {
            if (($_GET['tab'] == 'woocommerce_toggle_shop')) {
                $array[] = $newscreenids->id;
                return $array;
            } else {
                $array[] = '';
                return $array;
            }
        }
    }
    
    public static function common_function_to_show_hide($data)
    {
        ob_start();
        
?>
   
    
 var alter_data = jQuery('#woo_toggle_products_by_type_<?php
        echo $data;
?>').val();
                if(alter_data==='1') {
                    jQuery('#woo_toggle_type_category_<?php
        echo $data;
?>').parent().parent().hide();
                    jQuery('#woo_select_products_<?php
        echo $data;
?>').parent().parent().show();
                    
                }else {

                    jQuery('#woo_select_products_<?php
        echo $data;
?>').parent().parent().hide();
                    jQuery('#woo_toggle_type_category_<?php
        echo $data;
?>').parent().parent().show();
                                    } 
                jQuery('#woo_toggle_products_by_type_<?php
        echo $data;
?>').change(function(){
                var current_data = jQuery(this).val();
                if(current_data==='1') {
                    // Hide the Category alone here
                    jQuery('#woo_toggle_type_category_<?php
        echo $data;
?>').parent().parent().hide();
                    jQuery('#woo_select_products_<?php
        echo $data;
?>').parent().parent().show();
                    
                }else {
                    // Hide the Products and Category Checkbox  (for category it is useless)
                    jQuery('#woo_select_products_<?php
        echo $data;
?>').parent().parent().hide();
                    jQuery('#woo_toggle_type_category_<?php
        echo $data;
?>').parent().parent().show();
                  
                }
                });    
    <?php
        
        return ob_get_clean();
        
    }
    
    
    
    
    public static function load_script_to_admin()
    {
        global $woocommerce;
        //var_dump($woocommerce->version);
        if (isset($_GET['tab'])) {
            if ($_GET['tab'] == 'woocommerce_toggle_shop') {
?>
               <script type="text/javascript">
                    jQuery(function () {
                <?php
                global $wp_roles;
                if (!isset($wp_roles)) {
                    $wp_roles = new WP_Roles();
                }
                $getdata = $wp_roles->get_names();
                if ((float) $woocommerce->version > "2.2.0") {
                    
                    $k = 0;
                    foreach ($getdata as $data => $key) {
                        
                        if ($k == 0) {
                            
                            
?>
           jQuery('#woo_select_products_guest').select2();
	   jQuery('#woo_toggle_type_category_guest').select2();
            
            <?php
 echo self::common_function_to_show_hide('guest');
                        }
                        
                        echo self::common_function_to_show_hide($data);
?>
               

                            jQuery('#woo_select_products_<?php
                        echo $data;
?>').select2();
                jQuery('#woo_toggle_type_category_<?php
                        echo $data;
?>').select2();
        <?php
                        $k++;
                    }
                } else {
                    $k = 0;
                    foreach ($getdata as $data => $key) {
                        if ($k == 0) {
                          
                            
?>
                       jQuery('#woo_select_products_guest').chosen();
		       jQuery('#woo_toggle_type_category_guest').chosen();
		
            <?php
  echo self::common_function_to_show_hide('guest');
                        }
                        echo self::common_function_to_show_hide($data);
?>
                           jQuery('#woo_select_products_<?php
                        echo $data;
?>').chosen();
                jQuery('#woo_toggle_type_category_<?php
                        echo $data;
?>').chosen();
                        <?php
                        $k++;
                    }
                }
?>                    
        });
                </script>
                <?php
            }
        }
    }
    
    
    
}

include_once('include/class-admin-settings.php');
add_action('pre_get_posts', array(
    'WooToggler',
    'hidemyproduct_in_shop'
));
add_action('admin_head', array(
    'WooToggler',
    'load_script_to_admin'
));
//add_filter('woocommerce_screen_ids', array('WooToggler', 'allow_scripts'), 1); 
