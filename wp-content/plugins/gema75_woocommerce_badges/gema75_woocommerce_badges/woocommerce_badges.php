<?php
/*
Plugin Name: Woocommerce Badges Managment - by Gema75 (shared on wplocker.com)
Plugin URI: http://codecanyon.net/user/Gema75?ref=Gema75
Description: A woocommerce plugin for management of product badges.
Version: 3.75.81
Author:  Gema75
Author URI: http://codecanyon.net/user/Gema75?ref=Gema75
*/


/*
	FIX2 => fixes problems with "own badge dissapears when QUICK EDIT a woocommerce product"  ... dt 11 March 2015
*/


add_action( 'init', 'gema75_wc_badge_register_cpt' );

function gema75_wc_badge_register_cpt() {

    $labels = array( 
        'name' => _x( 'WC Badges', 'gema75_wc_badge' ),
        'singular_name' => _x( 'WC Badge', 'gema75_wc_badge' ),
        'add_new' => _x( 'Add New', 'gema75_wc_badge' ),
        'add_new_item' => _x( 'Add New badge', 'gema75_wc_badge' ),
        'edit_item' => _x( 'Edit badge', 'gema75_wc_badge' ),
        'new_item' => _x( 'New badge', 'gema75_wc_badge' ),
        'view_item' => _x( 'View badge', 'gema75_wc_badge' ),
        'search_items' => _x( 'Search Badges', 'gema75_wc_badge' ),
        'not_found' => _x( 'No badges found', 'gema75_wc_badge' ),
        'not_found_in_trash' => _x( 'No badges found in Trash', 'gema75_wc_badge' ),
        
        'menu_name' => _x( 'WC Badges', 'gema75_wc_badge' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'Add options to every product so you can add badges',
         
		'supports' => array( 'title' ),

        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 65,
        
        'show_in_nav_menus' => false,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'has_archive' => false,
        'query_var' => false,
        'can_export' => true,
        
         'register_meta_box_cb' => 'gema75_wc_badge_meta_box', // Callback function for custom metaboxes
    );

    register_post_type( 'gema75_wc_badge', $args );
}


//add our metaboxes to BADGE CPT and Woocommerce products
add_action( 'add_meta_boxes', 'gema75_wc_badge_meta_box');
function gema75_wc_badge_meta_box() {
   
    //add color/text/padding/margin metabox to the BADGE CPT
    add_meta_box('badge-cpt-meta-box', 'Badge Information', 'gema75_add_metabox_to_badges', 'gema75_wc_badge','normal','high');


    //remove "slug" metabox from BADGE CPT
    remove_meta_box( 'slugdiv', 'gema75_wc_badge', 'normal' );

    //add metabox to woocommerce product 
    add_meta_box('badge-products-meta-box', 'Badge Information', 'gema75_add_metabox_to_wc_products', 'product','side');
}



//APPEND "DOCS" to the menu of "WC BADGES"
add_action('admin_menu', 'gema75_badges_register_submenu');

function gema75_badges_register_submenu() {
		add_submenu_page( 'edit.php?post_type=gema75_wc_badge', 'Display Settings', 'Display Settings', 'manage_options', 'gema75-wc-badges-displaysettings', 'gema75_badges_display_submenu_callback' ); 
}
 

//Save "display settings" 
if(isset($_POST['gema75_badge_change_display_settings'])) {

		//save if to show the badge on single product page or not ..set default to NO
		$gema75_wc_badge_display['displaysingleproduct']= ( empty( $_POST['gema75_badge_display_displaysingleproduct'] ) ) ? 'no' :  $_POST['gema75_badge_display_displaysingleproduct'] ;
		
		//save if to show the badge on single product page or not ..set default to NO
		$gema75_wc_badge_display['display_displaycatalog']= ( empty( $_POST['gema75_badge_display_displaycatalog'] ) ) ? 'no' :  $_POST['gema75_badge_display_displaycatalog'] ;
		
		//save option
		update_option('gema75_wc_badge_displaysettings',$gema75_wc_badge_display);

}
 
function gema75_badges_display_submenu_callback(){ 


	//get saved display settings or create default value 
	$display_settings_array = get_option('gema75_wc_badge_displaysettings');
	
	if(!isset($display_settings_array['displaysingleproduct'])){
		$display_settings_array['displaysingleproduct'] = 'yes';
	}	
	
	if(!isset($display_settings_array['display_displaycatalog'])){
		$display_settings_array['display_displaycatalog'] = 'yes';
	}
	

?><style>
	#gema75_help_accordion h3{
		background-color: #fff;
		padding:10px;
		cursor:pointer;
		border-left: 4px solid #7AD03A;
		-webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
		box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
	}
	#gema75_help_accordion div{
		background-color: #fff;
		padding:10px;
	}
  </style>

  <h3> Display Settings  </h3>
 <form  action="" method="post">
 <input type="hidden" name="gema75_badge_change_display_settings" value="yes">
    <table class="form-table" >
  <tbody>
     <tr valign="top" >
            <th scope="row" class="titledesc"><label for="gema75_badges_text_color">Display to Catalog View</label></th>
            <td class="forminp" style="vertical-align:top"  colspan="2">
				<div>
					<select  name="gema75_badge_display_displaycatalog"  > 
						<option value="yes" <?php if ($display_settings_array['display_displaycatalog']=='yes'){ echo 'selected="selected"';} ?>> Yes </option>
						<option value="no"  <?php if ($display_settings_array['display_displaycatalog']=='no'){ echo 'selected="selected"';} ?>> No </option>
					</select>
				</div>
				<p class="description">Select Yes or No to display or don't display the Badge to the Catalog View</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc"><label for="gema75_badges_text_color">Display to Single Product</label></th>
            <td class="forminp" style="vertical-align:top"  colspan="2">
				<div>
					<select  name="gema75_badge_display_displaysingleproduct"  > 
						<option value="yes" <?php if ($display_settings_array['displaysingleproduct']=='yes'){ echo 'selected="selected"';} ?> >Yes</option>
						<option value="no"  <?php if ($display_settings_array['displaysingleproduct']=='no'){ echo 'selected="selected"';} ?> >No</option>
					</select>
				</div>
				<p class="description">Select Yes or No  to display or don't display the Badge to the Single Product View</p>
            </td>
        </tr>
		 <tr valign="top">
			<td>
				<p>
					<input type="submit" value="Save changes"> 
				</p>
			</td>
		</tr>
    </tbody>
    </table>        
    </form>
  <br>

<?php 
}

//REMOVE "VIEW BADGE" LINK start

	//Hides the 'view' button in the post edit page
	function gema75_hide_badge_view_button() {
	 
		$current_screen = get_current_screen();
	 
		if( $current_screen->post_type === 'gema75_wc_badge' ) {
			echo '<style>#edit-slug-box{display: none;}</style>';
		}
		
		return;
	 
	}
	add_action( 'admin_head', 'gema75_hide_badge_view_button' );
	 
	//Removes the 'view' link in the admin bar
	function gema75_hide_badge_view_admin_bar() {
	 
		global $wp_admin_bar;
	 
		if( get_post_type() === 'gema75_wc_badge'){
	 
			$wp_admin_bar->remove_menu('view');
	 
		}
	 
	}
	add_action( 'wp_before_admin_bar_render', 'gema75_hide_badge_view_admin_bar' );
	 
	//Removes the 'view' button in the posts list page
	function gema75_remove_view_row_action( $actions ) {
	 
		if( get_post_type() === 'gema75_wc_badge' )
			unset( $actions['view'] );
		return $actions;
	 
	}
	add_filter( 'post_row_actions', 'gema75_remove_view_row_action', 10, 1 );

//REMOVE "VIEW BADGE" LINK ends


//adds text/color/padding/margin metaboxes to the "badge" CPT
function gema75_add_metabox_to_badges(){
   global $post;
        
   $actual_badge_meta = get_post_meta( $post->ID, 'gema75_single_badge_info',true);
   
   //print_r($actual_badge_meta);
   
   if(empty($actual_badge_meta)){
   		//Not an existing badge  ...no values to retrieve ... lets create a default array 
   		$actual_badge_meta = array(
   				'badge_margin_top' => '9',
   				'badge_margin_left' => '8',
   				'badges_text_color' => '#336699',
   				'badges_background_color' => '#000000',
   				'badge_padding_top' => '11',
   				'badge_padding_right' => '12',
   				'badge_padding_bottom' => '13',
   				'badge_padding_left' => '14',
   				'badges_badge_text' => 'Default text',
   				'badge_radius_top_left' => '15',
   				'badge_radius_top_right' => '16',
   				'badge_radius_bottom_right' => '17',
   				'badge_radius_bottom_left' => '18',
   				'badges_opacity' => '0.8',
   				'badges_days_old' => '22',
				'preset_image' =>'noPresetSelected', //set a non existent image as default 
				'badge_image' =>'',
   			);

   }  //ends if empty badge meta
        
   wp_enqueue_script('wp-color-picker');
   wp_enqueue_style( 'wp-color-picker' );
?>
        
        <script type="text/javascript">
            jQuery(document).ready(function($) {   
                
                //WP colorpicker
                $('#gema75_badges_text_color').wpColorPicker({
                	color: true,
                    change: function(event, ui) {           
                        $("span.gema75_badges_quick_preview").css( "color", ui.color.toString());
                    }
                });
                
                //WP color picker for background
                $('#gema75_badges_background_color').wpColorPicker({
                    change: function(event, ui) {           
                        $("span.gema75_badges_quick_preview").css( "background-color", ui.color.toString());
                    }
                });
                
                //update position 
                $(".gema75_badges_quick_preview").draggable({
                    scroll: false,
                    drag: function() {
                        
                        var thisPos = $(this).position();
                        var parentPos = $(this).parent().position();
                        var x = thisPos.left+parentPos.left - parentPos.left;
                        var y = thisPos.top+parentPos.top - parentPos.top;
                        //update input fields ondrag
                        $('#gema75_badge_margin_top').val(y);
                        $('#gema75_badge_margin_left').val(x);
                    }
                });
                
                //live preview update text value
                $('#gema75_badges_badge_text').keyup(function() {
                        var text_of_badge = $(this).val(); // grab badge text input value
                        if ($(this).val().length > 0)
                        {
                            // replace text on live preview 
                            $('span.gema75_badges_quick_preview').text(text_of_badge);  
                        } 
                }); 
                
                //live preview  top-left radius corners
                $('#gema75_badge_radius_top_left').keyup(function() {
                        var radius_top = $(this).val(); // grab badge text input value
                        $('span.gema75_badges_quick_preview').css({
                                'border-top-left-radius' :   radius_top +'px' ,
                                '-webkit-border-top-left-radius' : radius_top +'px' ,
                                '-moz-border-radius-topleft' : radius_top +'px' ,
                        });  
                        
                }); 
                
                //live preview  top-right radius corners
                $('#gema75_badge_radius_top_right').keyup(function() {
                        var radius_top = $(this).val(); // grab badge text input value
                        $('span.gema75_badges_quick_preview').css({
                                'border-top-right-radius' :   radius_top +'px' ,
                                '-webkit-border-top-right-radius' : radius_top +'px' ,
                                '-moz-border-radius-topright' : radius_top +'px' ,
                        });  
                        
                }); 
                
                //live preview  bottom-right radius corners
                $('#gema75_badge_radius_bottom_left').keyup(function() {
                        var radius_top = $(this).val(); // grab badge text input value
                        $('span.gema75_badges_quick_preview').css({
                                'border-bottom-right-radius' :   radius_top +'px' ,
                                '-webkit-border-bottom-right-radius' : radius_top +'px' ,
                                '-moz-border-radius-bottomright' : radius_top +'px' ,
                        });  
                        
                }); 
                
                //live preview  bottom-left radius corners
                $('#gema75_badge_radius_bottom_right').keyup(function() {
                        var radius_top = $(this).val(); // grab badge text input value
                        $('span.gema75_badges_quick_preview').css({
                                'border-bottom-left-radius' :   radius_top +'px' ,
                                '-webkit-border-bottom-left-radius' : radius_top +'px' ,
                                '-moz-border-radius-bottomleft' : radius_top +'px' ,
                        });  
                        
                }); 
				
                //live preview padding top
                $("#gema75_badge_padding_top").change(function() {
                    var badge_padding_top =  $(this).val();
					console.log('padd top' + badge_padding_top);
                    $('span.gema75_badges_quick_preview').css('padding-top', badge_padding_top+'px' );
                });	
				
                //live preview padding bottom
                $("#gema75_badge_padding_bottom").change(function() {
                    var badge_padding_bottom =  $(this).val();
                    $('span.gema75_badges_quick_preview').css('padding-bottom', badge_padding_bottom+'px' );
                });	

                //live preview padding left
                $("#gema75_badge_padding_left").change(function() {
                    var badge_padding_left =  $(this).val();
                    $('span.gema75_badges_quick_preview').css('padding-left', badge_padding_left+'px' );
                });	

                //live preview padding right
                $("#gema75_badge_padding_right").change(function() {
                    var badge_padding_right =  $(this).val();
                    $('span.gema75_badges_quick_preview').css('padding-right', badge_padding_right+'px' );
                });					

                //live preview opacity
                $("#gema75_badges_opacity").change(function() {
                    var badge_opacity =  $(this).val();
                    //console.log(badge_opacity);
                    $('span.gema75_badges_quick_preview').css('opacity', badge_opacity);
                });
            });             
        </script>

		<style  type="text/css">
            span.gema75_badges_quick_preview {
                position: absolute;

                top:     <?php echo $actual_badge_meta['badge_margin_top'];?>px;
                left:    <?php echo $actual_badge_meta['badge_margin_left'];?>px;
				
				<?php if(isset($actual_badge_meta['badges_text_color'])) { ?>
					color:   <?php echo $actual_badge_meta['badges_text_color'];?>;
					background-color: <?php echo $actual_badge_meta['badges_background_color'];?>;
				<?php } ?> 
				
				<?php if(isset($actual_badge_meta['badge_padding_right'])) { ?>
					padding: <?php echo $actual_badge_meta['badge_padding_top'];?>px <?php echo $actual_badge_meta['badge_padding_right'];?>px <?php echo $actual_badge_meta['badge_padding_bottom'];?>px <?php echo $actual_badge_meta['badge_padding_left'];?>px;
					
					-webkit-border-top-left-radius: <?php echo $actual_badge_meta['badge_radius_top_left'];?>px;
					-webkit-border-top-right-radius:  <?php echo $actual_badge_meta['badge_radius_top_right'];?>px;
					-webkit-border-bottom-right-radius: <?php echo $actual_badge_meta['badge_radius_bottom_right'];?>px ;
					-webkit-border-bottom-left-radius: <?php echo $actual_badge_meta['badge_radius_bottom_left'];?>px;
					
					-moz-border-radius-topleft:  <?php echo $actual_badge_meta['badge_radius_top_left'];?>px ;
					-moz-border-radius-topright:  <?php echo $actual_badge_meta['badge_radius_top_right'];?>px;
					-moz-border-radius-bottomright:  <?php echo $actual_badge_meta['badge_radius_bottom_right'];?>px;
					-moz-border-radius-bottomleft: <?php echo $actual_badge_meta['badge_radius_bottom_left'];?>px;
					
					border-top-left-radius:  <?php echo $actual_badge_meta['badge_radius_top_left'];?>px ;
					border-top-right-radius:  <?php echo $actual_badge_meta['badge_radius_top_right'];?>px ;
					border-bottom-right-radius:  <?php echo $actual_badge_meta['badge_radius_bottom_right'];?>px ;
					border-bottom-left-radius: <?php echo $actual_badge_meta['badge_radius_bottom_left'];?>px ;
				
				<?php } ?>
                
                opacity: <?php echo $actual_badge_meta['badges_opacity'];?>;
                cursor:move;
            }

        </style>

    <form  action="" method="post">
    <table class="form-table" >
        <tbody>

        <tr valign="top">
			
            <th scope="row" class="titledesc"><label for="gema75_badges_text_color">Text Color</label></th>
            <td class="forminp" style="vertical-align:top">
			
				<?php if(isset($actual_badge_meta['badges_text_color'])){ ?>
					<input name="gema75_badge[badges_text_color]" type="text" id="gema75_badges_text_color" value="<?php echo  $actual_badge_meta['badges_text_color']; ?>" data-default-color="<?php echo  $actual_badge_meta['badges_text_color']; ?>">
					<p class="description">Select the text color of the badge</p>
				<?php }else{
				
					echo '<p> Not Applicable on a image based badge </p>'; 
				
				} ?>
            </td>
			
            <td class="forminp" rowspan="7" style="vertical-align:top">  
                <span style="font-weight: 600"> Quick preview ( drag the badge ) </span> <Br><Br>
                <div id="gema75_badges_preview_container" style="width: 250px; height: 250px; border: 1px solid black;background-image:url('<?php echo plugin_dir_url(__FILE__);?>assets/250x250.gif');position:relative">
                
					
				
                    <span class="gema75_badges_quick_preview">
					
					<?php
					//check if we have a badge from a preset or a text based badge
					
					if(isset($actual_badge_meta['preset_image']) ){
						
						if($actual_badge_meta['preset_image']!='noPresetSelected'){
							echo '<img src="'.$actual_badge_meta['preset_image'].'">';
						}else{
							echo $actual_badge_meta['badges_badge_text'];
						}
					}else{
					
						echo $actual_badge_meta['badges_badge_text'];
						
					}

					//echo  (( $actual_badge_meta['preset_image'] != '' ) ? '<img src="'.$actual_badge_meta['preset_image'].'">' : $actual_badge_meta['badges_badge_text'] ) ; 
					?>

					</span>
                </div>
            </td>
        </tr>   

        <tr valign="top">
            <th scope="row" class="titledesc"><label for="gema75_badges_background_color">Background Color</label></th>
            <td class="forminp" >
			
				<?php if(isset($actual_badge_meta['badges_background_color'])){ ?>
                <input name="gema75_badge[badges_background_color]" type="text" id="gema75_badges_background_color" value="<?php echo  $actual_badge_meta['badges_background_color']; ?>" data-default-color="<?php echo  $actual_badge_meta['badges_background_color']; ?>">
                <p class="description">Select the background color of the badge</p>
				<?php } else {
					
					echo '<p> Not Applicable on a image based badge </p>'; 
					
				}?>
            </td>
        </tr>   
        
		
		<?php 
		//the badge text might be plain text or a <img src=.... ( when a preset is selected) 
		//we dont show the badge text input at all if we have a preset image selected
		
		//print_r($actual_badge_meta);
		
		if(isset($actual_badge_meta['preset_image'] )){
			if($actual_badge_meta['preset_image']=='noPresetSelected'){
			
			?>
				<tr valign="top">
					<th scope="row" class="titledesc"><label for="gema75_badges_badge_text">Badge text</label></th>
					<td class="forminp">
							<input name="gema75_badge[badges_badge_text]" id="gema75_badges_badge_text" class="text" value="<?php echo   $actual_badge_meta['badges_badge_text']; ?>" type="text" >
							<p class="description">Text of the badge (i.e new , nuovo , neue ) </p>
					</td>
				</tr> 
			<?php 
			
			}
		}else{ ?>
		
				<tr valign="top">
					<th scope="row" class="titledesc"><label for="gema75_badges_badge_text">Badge text</label></th>
					<td class="forminp">
							<input name="gema75_badge[badges_badge_text]" id="gema75_badges_badge_text" class="text" value="<?php echo   $actual_badge_meta['badges_badge_text']; ?>" type="text" >
							<p class="description">Text of the badge (i.e new , nuovo , neue ) </p>
					</td>
				</tr> 			
		
		<?php } ?>	

        <tr valign="top">
            <th scope="row" class="titledesc"><label for="gema75_badges_days_old">Newer than</label></th>
            <td class="forminp" >
                    <input name="gema75_badge[badges_days_old]" id="gema75_badges_days_old" class="text" value="<?php echo  $actual_badge_meta['badges_days_old']; ?>" type="text" >
                    <p class="description">Show the badge for products newer than X days </p>
                    <p class="description">If badge is not time related , leave empty</p>
            </td>
        </tr>   

        <tr valign="top">
            <th scope="row" class="titledesc"><label for="gema75_badge_margins">Position</label></th>
            <td class="forminp" >
                    
                        <ul>
                            <li style="float:left;margin-right:20px"><input name="gema75_badge[badge_margin_top]" id="gema75_badge_margin_top" value="<?php echo  $actual_badge_meta['badge_margin_top']; ?>" class="text" type="text" size="2"><p class="description">Top </p></li>
                            <li style="float:left;margin-right:20px"><input name="gema75_badge[badge_margin_left]"  id="gema75_badge_margin_left" value="<?php echo  $actual_badge_meta['badge_margin_left']; ?>" class="text" type="text" size="2"><p class="description">Left </p></li>

                        </ul>
                    
            </td>
        </tr>   

        <tr valign="top">
            <th scope="row" class="titledesc"><label for="gema75_badge_margins">Paddings (pixels)</label></th>
            <td class="forminp" >
                    
					
					<?php if(isset($actual_badge_meta['badge_padding_top']) && isset( $actual_badge_meta['badge_padding_bottom'])  && isset($actual_badge_meta['badge_padding_left'])  && isset($actual_badge_meta['badge_padding_right']) ) {  ?>
					
                        <ul>
                            <li style="float:left;margin-right:20px"><input name="gema75_badge[badge_padding_top]" id="gema75_badge_padding_top" value="<?php echo  $actual_badge_meta['badge_padding_top']; ?>" class="text" type="text" size="2"><p class="description">Top </p></li>
                            <li style="float:left;margin-right:20px"><input name="gema75_badge[badge_padding_bottom]" id="gema75_badge_padding_bottom" value="<?php echo  $actual_badge_meta['badge_padding_bottom']; ?>" class="text" type="text" size="2"><p class="description">Bottom </p></li>
                            <li style="float:left;margin-right:20px"><input name="gema75_badge[badge_padding_left]" id="gema75_badge_padding_left" value="<?php echo  $actual_badge_meta['badge_padding_left']; ?>" class="text" type="text" size="2"><p class="description">Left </p></li>
                            <li style="float:left;margin-right:20px"><input name="gema75_badge[badge_padding_right]" id="gema75_badge_padding_right" value="<?php echo  $actual_badge_meta['badge_padding_right']; ?>" class="text" type="text" size="2"><p class="description">Right </p></li>
                        </ul>
						
						<?php } else { 
								echo '<p> Not Applicable on a image based badge </p>'; 
							}
						?>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" class="titledesc"><label for="gema75_badge_margins">Radius (pixels)</label></th>
            <td class="forminp" >
                    
					<?php if(isset($actual_badge_meta['badge_radius_top_left'])  && isset($actual_badge_meta['badge_radius_top_right'])  && isset( $actual_badge_meta['badge_radius_bottom_left'])  && isset($actual_badge_meta['badge_radius_bottom_right'])  ) {  ?>
					
                        <ul>
                            <li style="float:left;margin-right:20px"><input name="gema75_badge[badge_radius_top_left]" id="gema75_badge_radius_top_left" value="<?php echo  $actual_badge_meta['badge_radius_top_left']; ?>" class="text" type="text" size="2"><p class="description">Top-Left </p></li>
                            
                            <li style="float:left;margin-right:20px"><input name="gema75_badge[badge_radius_top_right]" id="gema75_badge_radius_top_right" value="<?php echo  $actual_badge_meta['badge_radius_top_right']; ?>" class="text" type="text" size="2"><p class="description">Top-Right </p></li>
                            
                            <li style="float:left;margin-right:20px"><input name="gema75_badge[badge_radius_bottom_left]" id="gema75_badge_radius_bottom_left" value="<?php echo  $actual_badge_meta['badge_radius_bottom_left']; ?>" class="text" type="text" size="2"><p class="description">Bottom-Right </p></li>
                            
                            <li style="float:left;margin-right:20px"><input name="gema75_badge[badge_radius_bottom_right]" id="gema75_badge_radius_bottom_right" value="<?php echo  $actual_badge_meta['badge_radius_bottom_right']; ?>" class="text" type="text" size="2"><p class="description">Bottom-Left </p></li>

                        </ul>
						
					<?php } else { 
							echo '<p> Not Applicable on a image based badge </p>'; 
						 }
					?>	
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" class="titledesc"><label for="gema75_badges_days_old">Badge Opacity</label></th>
            <td class="forminp"  colspan="2">
                    <select name="gema75_badge[badges_opacity]" id="gema75_badges_opacity" class="text"  >
                        <option value="0.0" <?php if ($actual_badge_meta['badges_opacity']=='0.0'){ echo 'selected="selected"';} ?> >0 - transparent </option>
                        <option value="0.1" <?php if ($actual_badge_meta['badges_opacity']=='0.1'){ echo 'selected="selected"';} ?> >1</option>
                        <option value="0.2" <?php if ($actual_badge_meta['badges_opacity']=='0.2'){ echo 'selected="selected"';} ?> >2</option>
                        <option value="0.3" <?php if ($actual_badge_meta['badges_opacity']=='0.3'){ echo 'selected="selected"';} ?> >3</option>
                        <option value="0.4" <?php if ($actual_badge_meta['badges_opacity']=='0.4'){ echo 'selected="selected"';} ?> >4</option>
                        <option value="0.5" <?php if ($actual_badge_meta['badges_opacity']=='0.5'){ echo 'selected="selected"';} ?> >5</option>
                        <option value="0.6" <?php if ($actual_badge_meta['badges_opacity']=='0.5'){ echo 'selected="selected"';} ?> >6</option>
                        <option value="0.7" <?php if ($actual_badge_meta['badges_opacity']=='0.7'){ echo 'selected="selected"';} ?> >7</option>
                        <option value="0.8" <?php if ($actual_badge_meta['badges_opacity']=='0.8'){ echo 'selected="selected"';} ?> >8</option>
                        <option value="0.9" <?php if ($actual_badge_meta['badges_opacity']=='0.9'){ echo 'selected="selected"';} ?> >9</option>
                        <option value="1"   <?php if ($actual_badge_meta['badges_opacity']=='1'){ echo 'selected="selected"';} ?> >10 - opaque</option>
                    </select>
                    <p class="description">Set the transparency ( 10 full opaque , 0 full transparent )  </p>
            </td>
        </tr>       
        
        <tr valign="top">   
             <th scope="row" class="titledesc"><label for="gema75_badges_days_old">Product categories</label></th>
            <td class="forminp"  colspan="2">
   
				<?php
				$badge_belongs_to_wc_category =0;
				
				if(isset($actual_badge_meta['badge_belong_to_wc_category_id'])){
					if(is_numeric($actual_badge_meta['badge_belong_to_wc_category_id'])){
						$badge_belongs_to_wc_category = $actual_badge_meta['badge_belong_to_wc_category_id'];
					}
				}
				//print_r($actual_badge_meta);

				?>
<?php
$args_woocommerce_categories = array(
		  'orderby' => 'id',
		  'order' => 'ASC',
		  'taxonomy' => 'product_cat',
		  'hide_empty' => '0',
		  'hierarchical' => '1'
	);
	$gema75_badges_woocommerce_categories = get_categories($args_woocommerce_categories);
	
	//print_r($gema75_badges_woocommerce_categories);
	
	//print_r($actual_badge_meta['badge_belong_to_wc_category_id']);
	
	foreach($gema75_badges_woocommerce_categories as $gema75_badges_woocommerce_category) { 
	
		$checked=' ';
	
		if(isset($actual_badge_meta['badge_belong_to_wc_category_id'])){
			if(in_array($gema75_badges_woocommerce_category->cat_ID,$actual_badge_meta['badge_belong_to_wc_category_id'])){
					$checked=' checked="checked" ';
			}
		}
		
		echo '<input type="checkbox"  ' . $checked . '  name="gema75_wc_product_categories_list[]" class="gema75_wc_product_categories" value="'.$gema75_badges_woocommerce_category->cat_ID.'"> '. $gema75_badges_woocommerce_category->cat_name .' <br>';
	
	} 
?>
                <p class="description">Select category if you want to show the badge only for products in a given category  </p>
            </td>
        </tr>
    

	<!-- Preset images START -->
	   <tr valign="top" style="background-color:#F6F6F6">
            <th scope="row" class="titledesc"><label>Preset Images</label></th>
            <td class="forminp" style="vertical-align:top" colspan="2">
			
				<select name="gema75_badge[preset_image]" id="presetImagesSelect" class="postform">
					<option value="noPresetSelected">Select Preset</option>
					
					<?php $presetImagePath = plugins_url( 'gema75_woocommerce_badges/img/'); 

						if(isset($actual_badge_meta['preset_image'])){
					?>
					
							<option value="<?php echo plugins_url( 'gema75_woocommerce_badges/img/1.png' );?>"  <?php if($presetImagePath.'1.png' == $actual_badge_meta['preset_image']){echo 'selected="selected"';}  ?>>1</option>
							<option value="<?php echo plugins_url( 'gema75_woocommerce_badges/img/2.png' );?>"  <?php if($presetImagePath.'2.png' == $actual_badge_meta['preset_image']){echo 'selected="selected"';}  ?>>2</option>
							<option value="<?php echo plugins_url( 'gema75_woocommerce_badges/img/3.png' );?>"  <?php if($presetImagePath.'3.png' == $actual_badge_meta['preset_image']){echo 'selected="selected"';}  ?>>3</option>
							<option value="<?php echo plugins_url( 'gema75_woocommerce_badges/img/4.png' );?>"  <?php if($presetImagePath.'4.png' == $actual_badge_meta['preset_image']){echo 'selected="selected"';}  ?>>4</option>
							<option value="<?php echo plugins_url( 'gema75_woocommerce_badges/img/5.png' );?>"  <?php if($presetImagePath.'5.png' == $actual_badge_meta['preset_image']){echo 'selected="selected"';}  ?>>5</option>
							<option value="<?php echo plugins_url( 'gema75_woocommerce_badges/img/6.png' );?>"  <?php if($presetImagePath.'6.png' == $actual_badge_meta['preset_image']){echo 'selected="selected"';}  ?>>6</option>
							
							<option value="<?php echo plugins_url( 'gema75_woocommerce_badges/img/7.png' );?>"  <?php if($presetImagePath.'7.png' == $actual_badge_meta['preset_image']){echo 'selected="selected"';}  ?>>7</option>
							<option value="<?php echo plugins_url( 'gema75_woocommerce_badges/img/8.png' );?>"  <?php if($presetImagePath.'8.png' == $actual_badge_meta['preset_image']){echo 'selected="selected"';}  ?>>8</option>
							<option value="<?php echo plugins_url( 'gema75_woocommerce_badges/img/9.png' );?>"  <?php if($presetImagePath.'9.png' == $actual_badge_meta['preset_image']){echo 'selected="selected"';}  ?>>9</option>
							<option value="<?php echo plugins_url( 'gema75_woocommerce_badges/img/10.png' );?>"  <?php if($presetImagePath.'10.png' == $actual_badge_meta['preset_image']){echo 'selected="selected"';}  ?>>10</option>
					
					<?php 
						}
					?>
					
					</select>
            </td>
        </tr>
		 <tr valign="top" style="background-color:#F6F6F6">
            <th scope="row" class="titledesc"><label>Preset Images</label></th>
            <td class="forminp" style="vertical-align:top" colspan="2">
			
				<ul style="list-style-type: none;clear:both">
					<li style="float:left;text-align:center;padding:5px;"> <div>  no.1  <br><img src="<?php echo plugins_url( 'gema75_woocommerce_badges/img/1.png' );?>" width="50" ></div></li>
					<li style="float:left;text-align:center;padding:5px;"> <div>  no.2  <br> <img src="<?php echo plugins_url( 'gema75_woocommerce_badges/img/2.png' );?>" width="50" > </div></li>
					<li style="float:left;text-align:center;padding:5px;"> <div>  no.3  <br> <img src="<?php echo plugins_url( 'gema75_woocommerce_badges/img/3.png' );?>" width="50" > </div></li>
					<li style="float:left;text-align:center;padding:5px;"> <div>  no.4  <br> <img src="<?php echo plugins_url( 'gema75_woocommerce_badges/img/4.png' );?>" width="50" > </div></li>
					<li style="float:left;text-align:center;padding:5px;"> <div>  no.5  <br> <img src="<?php echo plugins_url( 'gema75_woocommerce_badges/img/5.png' );?>" width="50" > </div></li>
					<li style="float:left;text-align:center;padding:5px;clear:both;"> <div>  no.6  <br> <img src="<?php echo plugins_url( 'gema75_woocommerce_badges/img/6.png' );?>" width="50" > </div><br><br></li>
					<li style="float:left;text-align:center;padding:5px;"> <div>  no.7  <br> <img src="<?php echo plugins_url( 'gema75_woocommerce_badges/img/7.png' );?>" width="50" ></div></li>
					<li style="float:left;text-align:center;padding:5px;"> <div>  no.8  <br> <img src="<?php echo plugins_url( 'gema75_woocommerce_badges/img/8.png' );?>" width="50" > </div></li>
					<li style="float:left;text-align:center;padding:5px;"> <div>  no.9  <br> <img src="<?php echo plugins_url( 'gema75_woocommerce_badges/img/9.png' );?>" width="50" > </div></li>
					<li style="float:left;text-align:center;padding:5px;"> <div>  no.10  <br> <img src="<?php echo plugins_url( 'gema75_woocommerce_badges/img/10.png' );?>" width="50"> </div></li>

				</ul>
			<div style="display:block;width:100%;clear:both"></div>
				 <p class="description"></p>
            </td>
        </tr>
	<!-- Preset images ENDS -->
	
	
       <tr valign="top">
            <th scope="row" class="titledesc"><label for="gema75_badges_text_color">Background image</label></th>
            <td class="forminp" style="vertical-align:top"  colspan="2">
               <input id="gema75_badge_image_input" type="text" name="gema75_badge[image_input]" size="40" value="<?php echo esc_url($actual_badge_meta['badge_image'] ); ?>" />   
            <input id="upload_image_button" type="button" value="From Library" class="button" />
                <p class="description">Enter URL or upload from library</p>
            </td>
           
        </tr> 
        <tr valign="top">
            <th scope="row" class="titledesc"><label for="gema75_badges_text_color">Current background image</label></th>
            <td class="forminp" style="vertical-align:top"  colspan="2">
            <div id="previewImageUploaded"></div>
            </td>
        </tr>

    </tbody>
    </table>        
    </form>             
        
        
    <?php

} // ENDS gema75_add_metabox_to_badges




function gema75_add_metabox_to_wc_products() {
    $post_id = get_the_ID();

    $gema75_product_badge_data = get_post_meta( $post_id, 'gema75_product_badge',true );
    $gema75_product_badge_id = ( empty( $gema75_product_badge_data['id'] ) ) ? '' : $gema75_product_badge_data['id'];

    ?>
   
    <p>
        <label>Select which badge to apply</label><br />

 <select name="gema75_product_badge[id]" >
	
	<option>Select badge to apply</option>
	
	<?php
	//get all badges and make a dropdown on the product page   
	$args_get_badges = array(
		'post_type' => 'gema75_wc_badge',
		'posts_per_page' => '-1',
	 );
	
	$gema75_badges_lists = get_posts( $args_get_badges );
	
	wp_reset_query() ;
	
	foreach ($gema75_badges_lists as $gema75_badge) {
	//print_r( $gema75_badge);

		if($gema75_badge->ID == $gema75_product_badge_id){
			$selected= 'selected="selected"';
		}else{
			$selected='';
		}
		echo '<option value="'.$gema75_badge->ID.'"   '.$selected.'  >'.$gema75_badge->post_title.'</option>';
	}

	?>
 </select> 

    </p>

    <?php
} // gema75_add_metabox_to_wc_products  ENDS

//set post meta ( badge id )  to woocommerce product
add_action( 'save_post', 'gema75_product_save_post' );
function gema75_product_save_post( $post_id ) {

	

	//FIX2 ... starts
	
	if(isset($_GET['bulk_edit']) || isset($_POST['bulk_edit'])){
		return;
	}
	
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || defined('DOING_AJAX') && DOING_AJAX ){
		return;
	}
	//FIX2 ... starts

	//BADGE IS FOR SINGLE PRODUCT
    if ( !empty( $_POST['gema75_product_badge'] ) ) {
       
        $gema75_product_badge_data['id'] = ( empty( $_POST['gema75_product_badge']['id'] ) ) ? '' : sanitize_text_field( $_POST['gema75_product_badge']['id'] );
 
        update_post_meta( $post_id, 'gema75_product_badge', $gema75_product_badge_data );
    
	} else {
    
		delete_post_meta( $post_id, 'gema75_product_badge' );

    }

}

//save settings for a single "badge" CPT
add_action( 'save_post', 'gema75_badges_save_post' );
function gema75_badges_save_post( $post_id ) {
 
	//FIX2..start
	
	if(isset($_GET['bulk_edit']) || isset($_POST['bulk_edit'])){
		return;
	}
	
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || defined('DOING_AJAX') && DOING_AJAX ){
		return;
	}
	//FIX2..ends
	
	//get old badge meta
	$old_meta = get_post_meta( $post_id, 'gema75_single_badge_info', true );
 
    if ( !empty($_POST['gema75_badge']['badges_background_color']) || !empty($_POST['gema75_badge']['preset_image']))  {  
      
        //save bg color , text color , badge text
        $gema75_single_badge_info['badges_background_color']= ( empty( $_POST['gema75_badge']['badges_background_color'] ) ) ? '' : sanitize_text_field( $_POST['gema75_badge']['badges_background_color'] );
        $gema75_single_badge_info['badges_text_color']= ( empty( $_POST['gema75_badge']['badges_text_color'] ) ) ? '' : sanitize_text_field( $_POST['gema75_badge']['badges_text_color'] );
        $gema75_single_badge_info['badges_badge_text']= ( empty( $_POST['gema75_badge']['badges_badge_text'] ) ) ? '' : sanitize_text_field( $_POST['gema75_badge']['badges_badge_text'] );
      
        //save "days old"
        $gema75_single_badge_info['badges_days_old']= ( empty( $_POST['gema75_badge']['badges_days_old'] ) ) ? '' : sanitize_text_field( $_POST['gema75_badge']['badges_days_old'] );
        
        $gema75_single_badge_info['badge_margin_top']= ( empty( $_POST['gema75_badge']['badge_margin_top'] ) ) ? '' : sanitize_text_field( $_POST['gema75_badge']['badge_margin_top'] );
        $gema75_single_badge_info['badge_margin_left']= ( empty( $_POST['gema75_badge']['badge_margin_left'] ) ) ? '' : sanitize_text_field( $_POST['gema75_badge']['badge_margin_left'] );

        //save padding
        $gema75_single_badge_info['badge_padding_top']= ( empty( $_POST['gema75_badge']['badge_padding_top'] ) ) ? '' : sanitize_text_field( $_POST['gema75_badge']['badge_padding_top'] );
        $gema75_single_badge_info['badge_padding_bottom']= ( empty( $_POST['gema75_badge']['badge_padding_bottom'] ) ) ? '' : sanitize_text_field( $_POST['gema75_badge']['badge_padding_bottom'] );
        $gema75_single_badge_info['badge_padding_left']= ( empty( $_POST['gema75_badge']['badge_padding_left'] ) ) ? '' : sanitize_text_field( $_POST['gema75_badge']['badge_padding_left'] );
        $gema75_single_badge_info['badge_padding_right']= ( empty( $_POST['gema75_badge']['badge_padding_right'] ) ) ? '' : sanitize_text_field( $_POST['gema75_badge']['badge_padding_right'] );

        //save border radius
        $gema75_single_badge_info['badge_radius_top_left']= ( empty( $_POST['gema75_badge']['badge_radius_top_left'] ) ) ? '' : sanitize_text_field( $_POST['gema75_badge']['badge_radius_top_left'] );
        $gema75_single_badge_info['badge_radius_top_right']= ( empty( $_POST['gema75_badge']['badge_radius_top_right'] ) ) ? '' : sanitize_text_field( $_POST['gema75_badge']['badge_radius_top_right'] );
        $gema75_single_badge_info['badge_radius_bottom_left']= ( empty( $_POST['gema75_badge']['badge_radius_bottom_left'] ) ) ? '' : sanitize_text_field( $_POST['gema75_badge']['badge_radius_bottom_left'] );
        $gema75_single_badge_info['badge_radius_bottom_right']= ( empty( $_POST['gema75_badge']['badge_radius_bottom_right'] ) ) ? '' : sanitize_text_field( $_POST['gema75_badge']['badge_radius_bottom_right'] );

        //save opacity
        $gema75_single_badge_info['badges_opacity']= ( empty( $_POST['gema75_badge']['badges_opacity'] ) ) ? '' :  $_POST['gema75_badge']['badges_opacity'] ;


		
		
        //save image to be used as background-image:url(....)
        if ( isset ($_POST['gema75_badge']['image_input'])) {
            $gema75_single_badge_info['badge_image']= ( empty( $_POST['gema75_badge']['image_input'] ) ) ? '' :  $_POST['gema75_badge']['image_input'] ;
        }
		
		
		
		
		//if user has selected a preset we need to remove some CSS attributes like background color etc
        if ( isset ($_POST['gema75_badge']['preset_image']) && $_POST['gema75_badge']['preset_image']!='noPresetSelected' ) {
		
			//set "badge text to contain the IMG 
            $gema75_single_badge_info['preset_image']=  $_POST['gema75_badge']['preset_image'] ;
			
			unset($gema75_single_badge_info['badges_background_color']);
			unset($gema75_single_badge_info['badges_text_color']);
			
			unset($gema75_single_badge_info['badges_badge_text']);
			
			$gema75_single_badge_info['badge_padding_top']='0';
			$gema75_single_badge_info['badge_padding_bottom']='0';
			
			unset($gema75_single_badge_info['badge_padding_left']);
			unset($gema75_single_badge_info['badge_padding_right']);
			
			unset($gema75_single_badge_info['badge_radius_top_left']);
			unset($gema75_single_badge_info['badge_radius_top_right']);
			unset($gema75_single_badge_info['badge_radius_bottom_left']);
			unset($gema75_single_badge_info['badge_radius_bottom_right']);

        }		
		
		//badge is for one ore more WooCommerce category ... update category meta	
		if(!empty($_POST['gema75_wc_product_categories_list']) && is_array($_POST['gema75_wc_product_categories_list'])){
			
			foreach($_POST['gema75_wc_product_categories_list'] as $gema75_wc_product_categories_list_key => $gema75_wc_product_categories_list_value){
					//echo $gema75_wc_product_categories_list_value;
			
					$category_id_to_assign_badge_to = $gema75_wc_product_categories_list_value;

					//print_r($_POST['gema75_wc_product_categories_list']);
					//die();
					
					
					//delete OLD option
					if(isset($old_meta['badge_belong_to_wc_category_id'])){
					
						foreach($old_meta['badge_belong_to_wc_category_id'] as $single_category_id){
							echo "KATEGORIA VJETER " . $single_category_id . "<br>";
							delete_option('gema75_wc_cat_id_'.$single_category_id.'_badge_id_'.$post_id );
						}
					}
					
					//set option 
					//key : concatenating the WC product ID with badgeID 
					//		example  if product id=23 AND badgeID = 10  THEN  key = gema75_wc_cat_id_23_badge_id_10
					//value : badge ID
					
					update_option('gema75_wc_cat_id_'.$category_id_to_assign_badge_to.'_badge_id_'.$post_id,$post_id);

					//save the category the badge belongs to also on the badge meta itself as an array
					$gema75_single_badge_info['badge_belong_to_wc_category_id'][]= $category_id_to_assign_badge_to ;
			}
		}else{
			//remove  option (gema75_wc_cat_id_23_badge_id_10)
			if(isset($old_meta['badge_belong_to_wc_category_id'])){
				foreach($old_meta['badge_belong_to_wc_category_id'] as $old_meta_badgeBelongsToWcCategory_key =>$old_meta_badgeBelongsToWcCategory_value ){
				
					delete_option('gema75_wc_cat_id_'.$old_meta_badgeBelongsToWcCategory_value.'_badge_id_'.$post_id );

				}
				unset($gema75_single_badge_info['badge_belong_to_wc_category_id']);
			}
		}

		//update BADGE CPT 
		update_post_meta( $post_id, 'gema75_single_badge_info', $gema75_single_badge_info );
        
    } else {
		if(isset($old_meta['badge_belong_to_wc_category_id'])){
		foreach($old_meta['badge_belong_to_wc_category_id'] as $old_meta_badgeBelongsToWcCategory_key =>$old_meta_badgeBelongsToWcCategory_value ){
			//remove  option (gema75_wc_cat_id_23_badge_id_10)
			@delete_option('gema75_wc_cat_id_'.$old_meta_badgeBelongsToWcCategory_value.'_badge_id_'.$post_id );
		}
		}
		//remove BADGE CPT METAs
        delete_post_meta( $post_id, 'gema75_single_badge_info' );
    }
	
	
} //ENDS  gema75_badges_save_post






////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// FRONTEND FRONTEND FRONTEND FRONTEND FRONTEND FRONTEND FRONTEND FRONTEND FRONTEND FRONTEND FRONTEND FRONTEND FRONTEND FRONTEND FRONTEND FRONTEND FRONTEND 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


 


//get saved display option 
$get_saved_display_option =get_option('gema75_wc_badge_displaysettings');

if(isset($get_saved_display_option['displaysingleproduct'])){
	if($get_saved_display_option['displaysingleproduct']=='yes'){
		//add our badge to single product page
		add_action( 'woocommerce_single_product_summary', 'gema75_show_product_loop_new_badge' , 30 ); 
	}
}

if(isset($get_saved_display_option['display_displaycatalog'])){
	if($get_saved_display_option['display_displaycatalog']=='yes'){
		//add our badge to the catalog page
		add_action( 'woocommerce_before_shop_loop_item_title', 'gema75_show_product_loop_new_badge' , 30 );
	}
}


//function to get badge settings and options 
function gema75_show_product_loop_new_badge() {
 global $post;

 $product_cat_id['id'] = '';

 //Check if product belongs to a category that has a badge assigned ... START


//if product belongs to many categories the category with the highest ID will be used
$get_product_categories = get_the_terms($post->ID, 'product_cat' );


if($get_product_categories ){
	foreach ($get_product_categories as $single_category) {
		$product_cat_id['id']= $single_category->term_id;
		
	}
}



//get all badges that belongs to this WC product`s category
$args_get_badges_for_category_args = array(
	'post_type' => 'gema75_wc_badge',
	'posts_per_page' => '-1',
   );
$gema75_category_badges_array = get_posts( $args_get_badges_for_category_args );



//wp_reset_query() ;

foreach ($gema75_category_badges_array as $gema75_category_badge_single) {

	$get_badge_options_array = get_post_meta($gema75_category_badge_single->ID , 'gema75_single_badge_info',true);

	if(isset($get_badge_options_array['badge_belong_to_wc_category_id']) ){
		
		//Found a badge assigned to this WC product`s category
		$category_badge[] = $get_badge_options_array;

	}


}


//Check if product belongs to a category that has a badge assigned ... ENDS

 
//check if product has a own badge (meta) assigned  
//and if badge itself exists (try to read the meta by the badge ID)
//...apply the own badge
$has_own_badge_assigned =  get_post_meta( $post->ID, 'gema75_product_badge',true);

if(isset($has_own_badge_assigned['id'] ) &&  is_numeric($has_own_badge_assigned['id'] ) && (get_post_meta( $has_own_badge_assigned['id'] , 'gema75_single_badge_info') ) ){

	echo '<!-- own badge -->' ; 

	$gema75_badge_meta = get_post_meta( $has_own_badge_assigned['id'], 'gema75_single_badge_info', true );

}else{
	//no own badge ... try to apply the product`s category badge 
	if(isset($category_badge)){

		//print_r($category_badge);
	
		foreach($category_badge as $category_badge_single){
		//	$category_badge_single = $category_badge[0];
			if(in_array($product_cat_id['id'],$category_badge_single['badge_belong_to_wc_category_id'])){
				echo '<!-- category badge -->' ; 
				$gema75_badge_meta = $category_badge_single;
			}
		}
	}
}


//check if is a newer-than-x-days  badge 
if(isset($gema75_badge_meta['badges_days_old'])){

	if($gema75_badge_meta['badges_days_old']!=''){
	
		$postdate  = get_the_time( 'Y-m-d' );          		// Post date
		
		$postdatestamp  = strtotime( $postdate );           // post date timestamp
		
		$newness = $gema75_badge_meta['badges_days_old'];   
		
		
		
		//If the product was published within the newness time frame display the new badge 
		if ((time() - ( 60 * 60 * 24 * $newness )) < $postdatestamp){ 
			echo '<!-- timebased badge not expired -->'; 
		}else{
			echo '<!-- timebased badge expired -->'; 
			//dont continue since the badge is expired
			return;
		}
	
	}
	
} //end if isset $gema75_badge_meta['badges_days_old']

            if(isset($gema75_badge_meta['badges_badge_text']) || isset($gema75_badge_meta['preset_image']) || isset($gema75_badge_meta['badge_image'])){    
					
            ?>
                
				
			<span class="gema75_badge_new_<?php echo $post->ID;?>">
					
					<?php
					
					//print_r($gema75_badge_meta);
					
					if(isset($gema75_badge_meta['preset_image'])){
					
						if($gema75_badge_meta['preset_image']!='noPresetSelected'){
							
							echo '<img src="'.$gema75_badge_meta['preset_image'].'" class="preset">';
							
						}
						
					}else{
						
						if(isset($gema75_badge_meta['badge_image'])){
						
							if($gema75_badge_meta['badge_image']!=''){
							
								echo '<img src="'.$gema75_badge_meta['badge_image'].'" class="customImage">';
							
							}else{
							
								echo $gema75_badge_meta['badges_badge_text'];
							
							}
						
						}
					
					}

					
					/*
					//check if we have a badge from a preset or custom image
					if($gema75_badge_meta['preset_image'] != ''  && $gema75_badge_meta['badge_image']==''){
					
						echo '<img src="'.$gema75_badge_meta['preset_image'].'" class="preset">';
					
					}else if( $gema75_badge_meta['preset_image'] == ''  && $gema75_badge_meta['badge_image']!='' ){
					
						echo '<img src="'.$gema75_badge_meta['badge_image'].'" class="customImage">';
						
					}else if($gema75_badge_meta['preset_image'] == ''  && $gema75_badge_meta['badge_image']==''){
						
						echo $gema75_badge_meta['badges_badge_text'];
					
					}
					*/
					
					
					?>

			</span>
 


            <style  type="text/css">
            
            span.gema75_badge_new_<?php echo $post->ID;?> {
                position: absolute;
                z-index: 10;
                top:     <?php echo $gema75_badge_meta['badge_margin_top']; ?>px !important;
                left:    <?php echo $gema75_badge_meta['badge_margin_left']; ?>px !important;
                
				
				<?php 
				//print_r($gema75_badge_meta);
				//if badge doesnt has a preset image then show the CSS settings that are aplicable for text based badge 
				if(!isset($gema75_badge_meta['preset_image']) && (!isset($gema75_badge_meta['badge_image']) || $gema75_badge_meta['badge_image']==''    ) ){ ?>
                        color:   <?php echo $gema75_badge_meta['badges_text_color']; ?> !important;
						background-color: <?php echo $gema75_badge_meta['badges_background_color']; ?> !important;
						padding: <?php echo $gema75_badge_meta['badge_padding_top']; ?>px <?php echo $gema75_badge_meta['badge_padding_right']; ?>px <?php echo $gema75_badge_meta['badge_padding_bottom']; ?>px <?php echo $gema75_badge_meta['badge_padding_left']; ?>px  !important;
						
						 -webkit-border-top-left-radius: <?php echo $gema75_badge_meta['badge_radius_top_left']; ?>px !important;
						-webkit-border-top-right-radius:  <?php echo $gema75_badge_meta['badge_radius_top_right']; ?>px !important;
						-webkit-border-bottom-right-radius: <?php echo $gema75_badge_meta['badge_radius_bottom_right']; ?>px !important;
						-webkit-border-bottom-left-radius: <?php echo $gema75_badge_meta['badge_radius_bottom_left']; ?>px !important;
						
						-moz-border-radius-topleft:  <?php echo $gema75_badge_meta['badge_radius_top_left']; ?>px !important;
						-moz-border-radius-topright:  <?php echo $gema75_badge_meta['badge_radius_top_right']; ?>px !important;
						-moz-border-radius-bottomright:  <?php echo $gema75_badge_meta['badge_radius_bottom_right']; ?>px !important;
						-moz-border-radius-bottomleft: <?php echo $gema75_badge_meta['badge_radius_bottom_left']; ?>px !important;
						
						border-top-left-radius:  <?php echo $gema75_badge_meta['badge_radius_top_left']; ?>px !important;
						border-top-right-radius:  <?php echo $gema75_badge_meta['badge_radius_top_right']; ?>px !important;
						border-bottom-right-radius:  <?php echo $gema75_badge_meta['badge_radius_bottom_right']; ?>px !important;
						border-bottom-left-radius: <?php echo $gema75_badge_meta['badge_radius_bottom_left']; ?>px !important;
				
				<?php } ?>
				
                        opacity: <?php echo $gema75_badge_meta['badges_opacity']; ?>;

            }

			 span.gema75_badge_new_<?php echo $post->ID;?> img {
				box-shadow:0 0 0 0 !important;
			 }

        </style>
                <?php 
				
            } //ENDS if($gema75_badge_meta['badges_badge_text'])

}  //ends gema75_show_product_loop_new_badge()




//
// IMAGE UPLOAD META BOX starts
//


//script actions with page detection


add_action('admin_print_scripts-post.php', 'gema75_badge_image_admin_scripts');

add_action('admin_print_scripts-post-new.php', 'gema75_badge_image_admin_scripts');

function gema75_badge_image_admin_scripts() {
    wp_enqueue_script( 'boj-image-upload',plugins_url( 'gema75_woocommerce_badges/js/gema75_badge_meta_image.js' ),array( 'jquery', 'media-upload', 'thickbox' ) );
}

//style actions with page detection
add_action('admin_print_styles-post.php', 'gema75_badge_image_admin_styles');
add_action('admin_print_styles-post-new.php', 'gema75_badge_image_admin_styles');

function gema75_badge_image_admin_styles() {
    wp_enqueue_style( 'thickbox' );
}


//
// IMAGE UPLOAD META BOX ends
//

