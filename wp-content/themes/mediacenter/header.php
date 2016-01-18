<?php	
	global $media_center_theme_options;
	global $woocommerce, $yith_wcwl, $yith_woocompare, $header_style;
    $header_style = !empty( $header_style ) ? $header_style : $media_center_theme_options['header_style'];
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>

<head>
    <!-- Meta -->
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

    <title><?php wp_title( '|', true, 'right' ); ?></title>
	
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

    <?php if ( !empty( $media_center_theme_options['favicon'] ) ): ?>
    <!-- Favicon -->
    <?php $media_center_favicon = $media_center_theme_options['favicon']; ?>
    <link rel="shortcut icon" href="<?php echo $media_center_favicon['url'];?>">
    <?php endif; ?>

	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/assets/js/html5.js" type="text/javascript"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/assets/js/respond.min.js" type="text/javascript"></script>
	<![endif]-->

    <!-- Custom Javascript Code -->
    
    <?php if ( (isset($media_center_theme_options['header_js'])) && ($media_center_theme_options['header_js'] != "") ) : ?>
    <script type="text/javascript">
        <?php echo $media_center_theme_options['header_js']; ?>
    </script>
    <?php endif; ?>

	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
<div class="wrapper">
    <?php 
        if ( $media_center_theme_options['top_bar_switch'] ) {
            media_center_display_header_part( 'top-bar' );
        }
    ?>
    <header class="<?php echo $header_style == 'header-style-1' ? 'header-style-1' : 'no-padding-bottom header-alt'; ?>">
            
        <div class="container no-padding">
            <div class="col-xs-12 col-md-3 logo-holder">
                <?php media_center_display_header_part( 'logo' ); ?>
            </div><!-- /.logo-holder -->

    		<div class="col-xs-12 col-md-6 top-search-holder no-margin">
    			
                <?php media_center_display_header_part( 'contact-row' ); ?>

                <?php media_center_display_header_part( 'search-bar' ); ?>

            </div><!-- /.top-search-holder -->

    		<div class="col-xs-12 col-md-3 top-cart-row no-margin">
                <?php media_center_display_header_part( 'top-cart' ); ?>
            </div><!-- /.top-cart-row -->
    	</div><!-- /.container -->

    <?php if( $header_style == 'header-style-2' ) : ?>
    	
        <?php media_center_display_header_part( 'top-megamenu-nav' ) ; ?>
    	
        <?php media_center_display_breadcrumb( $header_style ); ?>
            
    </header><!-- /.header-alt -->

    <?php else : ?>

    </header><!-- /.header-style-1 -->
        
    <?php media_center_display_breadcrumb( $header_style ); ?>

    <?php endif; ?>