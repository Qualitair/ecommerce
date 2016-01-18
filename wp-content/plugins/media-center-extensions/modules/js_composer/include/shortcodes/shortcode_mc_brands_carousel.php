<?php

if ( !function_exists( 'shortcode_mc_brands_carousel' ) ):

function shortcode_mc_brands_carousel( $atts, $content = null ){

	extract(shortcode_atts(array(
		'title' 			=> '',
  		'orderby' 			=> 'date',
  		'order' 			=> 'desc',
     	'per_page' 			=> '12',
     	'container_class' 	=> 'container',
     	'el_class' 			=> '',
    ), $atts));

    $element 			= 'mc_products_carousel';
    $css_class 			= trim( $element . ' ' . $el_class );
	$brands_shortcode 	= '[brands orderby="' . $orderby . '" order="' . $order . '" per_page="' . $per_page . '"]' ;
	$carouselID 		= uniqid();
	ob_start();
?>
<section id="section-<?php echo $carouselID; ?>" class="inner-top-xs inner-bottom-sm <?php echo $css_class;?>">
	<div class="<?php echo $container_class; ?>">
		<div class="carousel-holder">
			<div class="title-nav">
				<h2 class="h1"><?php echo $title; ?></h2>
				<div class="nav-holder">
					<a href="#prev" data-target="#owl-<?php echo $carouselID; ?>" class="slider-prev btn-prev fa fa-angle-left"></a>
					<a href="#next" data-target="#owl-<?php echo $carouselID; ?>" class="slider-next btn-next fa fa-angle-right"></a>
				</div>
			</div><!-- /.title-nav -->
			<div id="owl-<?php echo $carouselID; ?>" class="owl-carousel brands-carousel">
				<?php echo wpb_js_remove_wpautop( $brands_shortcode ); ?>
			</div><!-- /.brands-carousel -->
		</div><!-- /.carousel-holder -->
	</div><!-- /.container -->
</section>
<?php

	$brands_carousel = ob_get_clean();

    return $brands_carousel;
}

add_shortcode( 'mc_brands_carousel' , 'shortcode_mc_brands_carousel' );
endif;