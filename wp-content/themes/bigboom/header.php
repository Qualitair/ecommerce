<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package BigBoom
 */
?>
<!--
 -- @authors: denmark jay mago (back-end), albert babon abilar(front-end)
 -- @url: www.qualitair.com.au
-->
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<!--[if lt IE 9]>
	<script src="//cdn.jsdelivr.net/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="//cdn.jsdelivr.net/respond/1.3.0/respond.min.js"></script>
	<![endif]-->

	<?php wp_head(); ?>
	
<!-- added by djay -->
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/_admin/plugins/dropdown/css/cs-select.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/_admin/plugins/dropdown/css/cs-skin-elastic.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/_admin/css-define/css-overide.css" type="text/css" media="screen" />

<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/_admin/plugins/product/css/product-list-carousel-hover.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/_admin/plugins/product/js/product-list-carousel-hover.js"></script>

<?php 
	function removeWhitespace($content) {
	    $content = preg_replace('~>\s*\n\s*<~', '><', $content);
	    return preg_replace('!\s+!smi', ' ', $content);
	}
	?>
<script type="text/javascript">
	<?php ob_start('removeWhitespace'); ?>
	( function ( $ ) {
	'use strict';
		

		$( function() {
			
			/** Version 1.0 */
			$('a.product-hidden-link').bind('click mouseover', function(e) {
                e.preventDefault();

                var popup = $('#admin-custom-popup'),
                	btnLogin = $('.btn-show-login');

                popup.bPopup({
                    zIndex: 99991,
                    escClose: true,
                    modalColor: 'rgba(3,3,3,.2)'
                });

                /** show login button */
                var formLogin 	  = $('.popup-form-login'),
                	formRegister  = $('.popup-form-signup');

                var loginHeight = (formLogin.find('p').length) * formLogin.find('p').eq(0).height() - 10;
                var regHeight	= (formRegister.find('p').length - 1) * formRegister.find('p').eq(0).height() + 20;
                var timer = null;

                btnLogin.unbind('click mouseover').bind('click mouseover', function() {
                	if(timer) return;
                	timer = 1;
                	/**  if closed */
                	if(! parseInt($(this).data('status'))) {                		
                		formLogin.animate({height: loginHeight}, '100');
                		formRegister.animate({height: 0}, 'medium');
                		$(this).data('status', '1').
                			find('span').
                			html('LOGIN<i class="fa fa-times" style="float:right; position: absolute; margin-top: 5px; right: 60px;"></i>');

                	/** open */
                	} else {
                		formLogin.animate({height: 0}, 'medium');
                		formRegister.animate({height: regHeight}, '100');
                		$(this).data('status', '0').
                			find('span').
                			html('LOGIN');
                	}

                	setTimeout( function() { timer = null; }, 110);
                });

                if(parseInt(btnLogin.data('status'))) btnLogin.click();

                popup.find('form.popup-form-signup').on('submit', function() {
                	window.location = '/register/?customer='+ $('#frm-customer-type').val()+ '&e='+ $('#frm-customer-email').val();
                	return false;
                }).find('#frm-customer-type').unbind('change').bind('change', function() {
                	var type = $(this).val();
                	var types = ['Individual', 'Wholesale', 'Tender'];
                	var desc  = ['Purchase less than two (2) items.', 'Purchase more than two (2) items.', 'Customer for contracting purhcase.'];

                	for(var i=0; i<types.length; i++) {
                		if(types[i].toLowerCase()  == type) {
                			$('.customer-desc-label i').text(types[i].charAt(0).toUpperCase() + types[i].slice(1));
                			$('.customer-desc-label span').html(desc[i]);
                			break;
                		}
                	}
                });

                popup.find('#frm-customer-type').find('option').eq(0).attr('selected', 'selected');
                popup.find('#frm-customer-type').trigger('change');
                
                popup.find('.heapBox').find('.holder').
                	attr('rel', popup.find('#frm-customer-type').children('option').eq(0).html()).
                	html(popup.find('#frm-customer-type').children('option').eq(0).html()).
                	parents('.heapBox').find('.heapOptions').find('.heapOption').find('.selected').removeClass('selected').
                	parents('.heapBox').find('.heapOptions').find('.heapOption').eq(0).find('a').addClass('selected');

                popup.find('.heapBox').show();
                
            });

            function hideSlideModal(e) {
			    var popup = $('#admin-custom-popup');
			    popup.slideFadeToggle();
			    e.removeClass('selected');
			}

			/** Version 2.0 */
            $('a.product-hidden-link-none').on('click', function() {
            	var popup = $('#admin-custom-popup');
            	popup.find('.heapBox').show();

			    if($(this).hasClass('selected')) {			      
			      	hideSlideModal($(this));               
			    } else {
			      	$(this).addClass('selected');
			      	var el = this;
			      	var style = {
						top: $(el).offset().top + $(el).height(),
						left: $(el).offset().left,
						position: 'absolute',
						zIndex: 99993
				  	};
			      	
			      	popup.css(style).slideFadeToggle();
			    }

			    return false;
			});

			$('.close').on('click', function() {
			    deselect($('#contact'));
			    return false;
			});

		});

		$.fn.slideFadeToggle = function(easing, callback) {
			return this.animate({ opacity: 'toggle', height: 'toggle' }, 'fast', easing, callback);
		};

	} )( jQuery );
	<?php ob_get_flush(); ?>
</script>

<!-- ./!end -->
</head>

<body <?php body_class(); ?>>
<div id="admin-custom-popup">
	<span class="button b-close"><span>X</span></span>
    <div>
    	<h2 class="title price-title">Fill up the form <p><small>To <strong>unlock</strong> the price</small></p></h2>
    	<form class="form-custom-admin popup-form-signup">
    		<p class="form-row form-row-wide">
				<label for="frm-customer-email">Email address<span class="required">*</span></label>
				<input type="email" class="input-text" required name="frm-customer-email" id="frm-customer-email" value="" placeholder="Email address" />
			</p>
    		<p class="form-row form-row-wide">
		    	<label for="frm-customer-type">Select customer type:</label>
		    	<select id="frm-customer-type" name="customer-type">
		    		<option value="individual">Individual</option>
		    		<option value="wholesale">Wholesale</option>
		    		<option value="tender">Tender</option>
		    	</select>
		    </p>
		    <p  class="form-row form-row-wide customer-desc-label">
				<labe>Selected: <i>Individual</i></labe>
				<br>
				<span>Purchase less than two (2) items.</span>
			</p>
		    <p class="form-row form-row-wide">
				<button class="button general blue proceed-btn">Proceed</button>
			</p>
			<p  class="form-row form-row-wide customer-or-label">
				<label><span>OR</span></label>
			</p>
    	</form>
    	<div class="form-custom-admin popup-form-login-toggle">
    		<p  class="form-row form-row-wide btn-show-login" data-status="0">
				<a href="Javascript:void(0)"><span>LOGIN</span></a>
			</p>
    	</div>
    	<form class="form-custom-admin popup-form-login" action="/login/?redirectUrl=<?php echo getCurrentUrl(); ?>" method="post">	    		
			<p class="form-row form-row-wide">
				<label for="frm-customer-email">Email address or username<span class="required">*</span></label>
				<input type="usename" class="input-text" required name="username" id="frm-customer-email" value="" placeholder="Email address" />
			</p>
			<p class="form-row form-row-wide">
				<label for="frm-customer-email">Password<span class="required">*</span></label>
				<input type="password" class="input-text" required name="password" id="" value="" placeholder="Password" />
			</p>
			<p class="form-row form-row-wide">
                <label for="rememberme" class="inline remember-login">
				<input name="rememberme" type="checkbox" id="rememberme" value="forever" class="checkbox general"> Remember me</label>				
			</p>
			<p class="form-row form-row-wide" style="margin-top: 40px;">                
				<input type="hidden" id="_wpnonce" name="_wpnonce" value="ec8e2ffc7e">
				<input type="hidden" name="_wp_http_referer" value="<?php echo getCurrentUrl(); ?>">				
				<input type="submit" class="button general blue" name="login" value="Login">				
			</p>
		</form>
    </div>
</div>

<div id="page" class="hfeed site">

	<?php do_action( 'bigboom_before_header' ); ?>

	<header id="masthead" class="site-header site-header-main" role="banner">
		<div class="container">
			<div class="row">
				<div class="site-branding col-xs-12 col-sm-2 col-md-2">
					<?php
					$logo = bigboom_theme_option( 'logo' );
					$logo = $logo ? $logo : THEME_URL . '/img/logo.png';
					$logo2 = THEME_URL . '/img/logo-2.png';
					?>
					<center>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
							<img alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" src="<?php echo esc_url( $logo ); ?>" />
						</a>
					</center>
					<?php
					printf(
						'<%1$s class="site-title"><a href="%2$s" rel="home">%3$s</a></%1$s>',
						is_home() ? 'h1' : 'p',
						esc_url( home_url( '/' ) ),
						get_bloginfo( 'name' )
					);
					?>
				</div>

				<div class="header-sidebar widgets-area col-xs-12 col-sm-10 col-md-10">
					<?php dynamic_sidebar( 'header-sidebar' ); ?>
				</div>
			</div>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<a href="#" class="navbar-toggle">
				<i class="fa fa-bars nav-bars"></i>
				<?php echo __( 'Menu', 'bigboom' ); ?>
			</a>

			<?php
			$mobile_menu_class = has_nav_menu( 'mobile' ) ? '' : 'mobile-menu';

			wp_nav_menu( array(
				'theme_location'  => 'primary',
				'container_id'    => 'primary-menu',
				'container_class' => "primary-menu $mobile_menu_class container",
				'menu_class'      => 'menu clearfix',
			) );

			if ( has_nav_menu( 'mobile' ) ) {
				wp_nav_menu( array(
					'theme_location'  => 'mobile',
					'container_id'    => 'mobile-menu',
					'container_class' => 'primary-menu mobile-menu container',
					'menu_class'      => 'menu clearfix',
				) );
			}
			?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<header id="masthead" class="site-header site-header-fixed" role="banner">
		<div class="container">
			<div class="row">
				<div class="site-branding col-xs-12 col-sm-2 col-md-2">
					<?php
					$logo = bigboom_theme_option( 'logo' );
					$logo = $logo ? $logo : THEME_URL . '/img/logo.png';
					?>
					<center>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
							<img alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" src="<?php echo esc_url( $logo2 ); ?>" />
						</a>
					</center>
					<?php
					printf(
						'<%1$s class="site-title"><a href="%2$s" rel="home">%3$s</a></%1$s>',
						is_home() ? 'h1' : 'p',
						esc_url( home_url( '/' ) ),
						get_bloginfo( 'name' )
					);
					?>
				</div>

				<div class="header-sidebar widgets-area col-xs-12 col-sm-10 col-md-10">
					<?php dynamic_sidebar( 'header-sidebar' ); ?>
				</div>
			</div>
		</div><!-- .site-branding -->
	</header><!-- #masthead -->

	<?php do_action( 'bigboom_after_header' ); ?>

	<div id="site-content" class="site-content">
		<?php if ( ! is_page_template( 'template-full-width.php' ) ) : ?>
				<div class="container">
					<div class="row">
			<?php endif; ?>
