var bigboom = bigboom || {},
	bigboomShortCode = bigboomShortCode || {},
	bigboomSearch = bigboomSearch || {};
( function ( $ ) {
	'use strict';

	$( function() {
		var $window = $( window ),
			$body   = $( 'body' ),
			$socials = $( '#social-icons' );

		// Flex slider for gallery
		if( $( '.flexslider .slides' ).find( 'li' ).length > 1 ) {
			$( '.flexslider .slides' ).owlCarousel({
				direction: bigboom.direction,
				items: 1,
				slideSpeed : 800,
				navigation: true,
				pagination: false,
				autoPlay: true,
				paginationSpeed : 1000,
				navigationText: ['<span class="fa fa-arrow-left"></span>', '<span class="fa fa-arrow-right"></span>']

			});
		}

		// Init countdown js
		$( '#hot-deal-products' ).find( '.sale-price-date' ).each( function() {
			$( this ).countdown( $( this ).attr( 'data-date' ) , function( event ) {
				var day = event.strftime('%D'),
					output = '',
					i = 0;
				for( i = 0; i < day.length; i ++ ) {
					output += '<span>' + day[i] + '</span>';
				}
				$( this ).find( '.day' ).html( output );

				var hour = event.strftime('%H');
				output = '';
				for( i = 0; i < hour.length; i ++ ) {
					output += '<span>' + hour[i] + '</span>';
				}
	    		$( this ).find( '.hour' ).html( output );

	    		var minu = event.strftime('%M');
				output = '';
				for( i = 0; i < minu.length; i ++ ) {
					output += '<span>' + minu[i] + '</span>';
				}
	    		$( this ).find( '.minu' ).html( output );
	 		});
		});

		// Toggle promotion
		$( '#top-promotion' ).on( 'click', '.close', function( e ) {
			e.preventDefault();

			$( this ).parents( '#top-promotion' ).slideUp();
		} );

		// Shop view
		var $shopView = $( '.shop-view' );
		$shopView.on( 'click', 'a', function( e ) {
			e.preventDefault();
			var $el = $( this ),
				view = $el.data( 'view' );

			if ( $el.hasClass( 'current' ) ) {
				return;
			}

			$shopView.children( 'a' ).removeClass( 'current' ).filter( '[data-view=' + view + ']' ).addClass( 'current' );
			$body.removeClass( 'shop-view-grid shop-view-list' ).addClass( 'shop-view-' + view );

			document.cookie = 'shop_view=' + view + ';domain=' + window.location.host + ';path=/';
		} );

		// Change number of products to be viewed on shop page
		$( '.shop-products-number' ).change( function() {
			$( this ).closest( 'form' ).submit();
		} );

		// Socials
		var heightSocials = $socials.height(),
			heightWindow = $window.height();

		$socials.css( {
			top: ( heightWindow - heightSocials ) / 2
		} );

		// Scroll top
		$window.scroll( function () {
			if ( $( this ).scrollTop() > 100) {
				$( '#scroll-top' ).fadeIn();
			} else {
				$( '#scroll-top' ).fadeOut();
			}
		});

		$( '#scroll-top' ).on( 'click', function ( e ) {
			e.preventDefault();

			$( 'body,html' ).animate({
				scrollTop: 0
			}, 800);
		});


		/* ToolTip */
		$( '.yith-wcwl-add-to-wishlist .add_to_wishlist' ).attr( 'data-original-title', $( '.yith-wcwl-add-to-wishlist .add_to_wishlist' ).html() ).attr( 'rel', 'tooltip' );
		$( '.yith-wcwl-wishlistaddedbrowse a' ).attr( 'data-original-title', $( '.yith-wcwl-wishlistaddedbrowse a' ).html() ).attr( 'rel', 'tooltip' );
		$( '.yith-wcwl-wishlistexistsbrowse a' ).attr( 'data-original-title', $( '.yith-wcwl-wishlistexistsbrowse a' ).html() ).attr( 'rel', 'tooltip' );
		$( '.woocommerce .compare.button' ).attr( 'data-original-title', $( '.woocommerce .compare.button' ).html() ).attr( 'rel', 'tooltip' );

		$( '[rel=tooltip]' ).tooltip({ offsetTop: -20 });

		productsCarousel();

		imagesCarousel();

		postsCarousel();

		singleThumbCarousel();

		$window.resize( function() {
			if ( $window.width() <= 768 ) {
				if( ! $( 'table.shop_table' ).hasClass( 'wrapped' ) ) {
					$( 'table.shop_table' ).addClass( 'wrapped' ).wrapAll( '<div class="table-responsive"/>' );
				}
			} else {
				$( '.mobile-menu' ).removeAttr( 'style' );
			}
		} ).trigger( 'resize' );

		// Click the icon navbar-toggle show/hide menu mobile
		$( '.site-header' ).on( 'click', '.navbar-toggle', function( e ) {
			e.preventDefault();
			$( '.mobile-menu' ).slideToggle();
		});

		// Instance search
		if ( $().autocomplete ) {
			var searchCache = {}; // Cache the search results

			$.ui.autocomplete.prototype._renderItem = function( ul, item ) {
				return $( '<li class="woocommerce"></li>' )
					.append( '<a href="' + item.value + '">' + item.thumb + '<span class="product-title">' + item.label + '</span>' + item.rate + '<span class="product-price">' + item.price + '</span></a>' )
					.appendTo( ul );
			};

			$( '.instance-search .search-field' ).autocomplete( {
				minLength: 2,
				source: function( request, response ) {
					var term = request.term,
						cat  = $( this.element ).prev( '.product-cat' ).children( 'select' ).val(),
						key  = term + '|' + cat;

					if ( key in searchCache ) {
						response( searchCache[key] );
						return;
					}

					window.console.log(request, response);

					$.ajax( {
						url: bigboom.ajax_url,
						dataType: 'json',
						method: 'post',
						data: {
							action: 'search_products',
							bbnonce: bigboom.nonce,
							term: term,
							cat: cat
						},
						success: function( data ) {
							searchCache[key] = data.data;
							response( data.data );
						}
					} );
				},
				select: function( event, ui ) {
					event.preventDefault();
					if ( ui.item.value != '#' ) {
						location.href = ui.item.value;
					}
				}
			} );
		}

		/**
		 * Product quick view popup
		 */
		var $modal = $( '#modal' ),
			$modalBody = $modal.find( '.modal-body' );

		// Open product single modal
		$( '.product' ).on( 'click', '.bb-quick-view', function( e ) {
			e.preventDefault();

			$modal.fadeIn().addClass( 'in' );
			$modalBody.html( '<div class="ajax-loading"><i class="fa fa-spin fa-spinner"></i></div>' );
			$.get( $( this ).attr( 'data-href' ), function( response ) {
				if ( ! response ) {
					return;
				}

				var $content = $( response ).find( '#site-content .bb-product-view' );

				$modalBody.html( $content );

				singleThumbCarousel();

				var pubid = $modal.attr( 'data-id' );
				if( pubid !== '' ) {
					var script = '//s7.addthis.com/js/300/addthis_widget.js#pubid=' + pubid;
					if (window.addthis) {
					    window.addthis = null;
					    window._adr = null;
					    window._atc = null;
					    window._atd = null;
					    window._ate = null;
					    window._atr = null;
					    window._atw = null;
					}
					$.getScript(script);
				}

			} );
		} );

		// Close portfolio modal
		$modal.on( 'click', 'button.close', function( e ) {
			e.preventDefault();

			$modal.fadeOut( 500, function() {
				$body.removeClass( 'modal-open' );
				$modal.removeClass( 'in' );

				// Trigger resize event on $window to make isotope mansory works correctly
				$window.trigger( 'resize' );
			} );
		} );

		/**
		 * Init Images Carousel
		 */
		function imagesCarousel() {
			if ( bigboomShortCode.length === 0 || typeof bigboomShortCode.imagesCarousel === 'undefined' ) {
				return;
			}
			$.each( bigboomShortCode.imagesCarousel, function ( id, imagesCarousel ) {
				var autoplay = ( imagesCarousel.autoplay === 'true' ) ? true : false,
					hideNavigation = ( imagesCarousel.navigation === 'true' ) ? true : false;
				$( document.getElementById( id ) ).find( '.bb-owl-list').owlCarousel({
					direction: bigboom.direction,
					items: imagesCarousel.number,
					slideSpeed : 800 ,
					navigation: hideNavigation,
					pagination: false,
					autoPlay: autoplay,
        			paginationSpeed : 1000,
					navigationText: ['<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>']

				});
			} );
		}

		/**
		 * Init posts carousel
		 */
		function postsCarousel() {
			if ( bigboomShortCode.length === 0 || typeof bigboomShortCode.postsCarousel === 'undefined' ) {
				return;
			}
			$.each( bigboomShortCode.postsCarousel, function ( id, postsCarousel ) {
				var autoplay = ( postsCarousel.autoplay === 'true' ) ? true : false,
					hideNavigation = ( postsCarousel.navigation === 'true' ) ? true : false;

				$( document.getElementById( id ) ).find( '.bb-owl-list').owlCarousel({
					direction: bigboom.direction,
					items: postsCarousel.number,
					slideSpeed : 800 ,
					navigation: hideNavigation,
					pagination: false,
					autoPlay: autoplay,
        			paginationSpeed : 1000,
					navigationText: ['<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>'],
					itemsDesktopSmall: [979, 2],
					itemsDesktop: [1199, postsCarousel.number]

				});

			} );
		}

		/**
		 * Init product carousel
		 */
		function productsCarousel() {
			if ( bigboomShortCode.length === 0 || typeof bigboomShortCode.productsCarousel === 'undefined' ) {
				return;
			}
			$.each( bigboomShortCode.productsCarousel, function ( id, productsCarousel ) {

				if( productsCarousel.spacing != null ) {
					var spacing = productsCarousel.spacing;
					spacing = parseInt( spacing, 10 );
					$( document.getElementById( id ) ).find( '.products' ).css( {
						'margin-left': '-' + spacing + 'px',
						'margin-right': '-' + spacing + 'px'
					} );

					$( document.getElementById( id ) ).find( '.col-product' ).css( {
						'padding-left': spacing,
						'padding-right': spacing
					} );

					if( spacing === 0 ) {
						$( document.getElementById( id ) ).find( '.col-product .product' ).css( {
							'margin-bottom': '15px'
						} );
					}
					else {
						$( document.getElementById( id ) ).find( '.col-product .product' ).css( {
							'margin-bottom': spacing * 2
						} );
					}

			 	}

				var autoplay = ( productsCarousel.autoplay === 'true' ) ? true : false,
					hideNavigation = ( productsCarousel.navigation === 'true' ) ? true : false,
					itemsSmall = 1;

				if( productsCarousel.number > 1 ) {
					itemsSmall = productsCarousel.number - 1;
				}

				$( document.getElementById( id ) ).find( '.products').owlCarousel({
					direction: bigboom.direction,
					items: productsCarousel.number,
					slideSpeed : 800 ,
					navigation: hideNavigation,
					pagination: false,
					autoPlay: autoplay,
        			paginationSpeed : 1000,
					navigationText: ['<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>'],
					itemsDesktopSmall: [979, itemsSmall],
					itemsDesktop: [1199, productsCarousel.number]
				});

			} );
		}

		/**
		 * Init product single thumb carousel
		 */
		function singleThumbCarousel() {

			$( '.woocommerce .thumbnails' ).owlCarousel({
				direction: bigboom.direction,
				items: 4,
				slideSpeed : 800 ,
				navigation: true,
				pagination: false,
				paginationSpeed : 1000,
				navigationText: ['<span class="fa fa-chevron-left"></span>', '<span class="fa fa-chevron-right"></span>']
			});

			$( '.woocommerce .thumbnails' ).find( 'a' ).on( 'click', function( e ) {
				e.preventDefault();
				$( '.woocommerce .images' ).find( '.woocommerce-main-image').attr( 'href', $( this ).attr( 'href' ) );
				$( '.woocommerce .images' ).find( '.woocommerce-main-image img' ).attr( 'src', $( this ).attr( 'data-src' ) );
			} );


			$( '.woocommerce-main-image').on( 'click', function(e) {
				e.preventDefault();
				var api_images = [],
					api_titles = [],
					i = 0;

				if( $( '.woocommerce .thumbnails' ).length > 0 ) {
					$( '.woocommerce .thumbnails' ).find( 'a' ).each( function(  ) {
						api_images[i] = $( this ).attr( 'href' );
						api_titles[i] = $( this ).find( 'img' ).attr( 'alt' );
						i++;
					} );
				} else {
					api_images[i] = $( this ).attr( 'href' );
					api_titles[i] = $( this ).find( 'img' ).attr( 'alt' );
				}

				$.fn.prettyPhoto({'theme': 'pp_woocommerce'});
				$.prettyPhoto.open( api_images, api_titles, '' );
			} );
		}

	} );

} )( jQuery );
