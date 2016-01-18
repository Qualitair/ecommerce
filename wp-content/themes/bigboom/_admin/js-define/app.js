( function ( $ ) {
	'use strict';

	function extractDomain(url) {
	    var domain, protocol;
	    //find & remove protocol (http, ftp, etc.) and get domain
	    if (url.indexOf("://") > -1) {
	        domain = url.split('/')[2];
	        protocol = url.split('/')[0];
	    }
	    else {
	        domain = url.split('/')[0];
	        protocol = location.protocol;
	    }

	    //find & remove port number
	    domain = protocol+'//'+domain.split(':')[0];

	    return domain;
	}
	
	$( function() {

		/** 
		 * Navigation site header
		 *
		 */
		var nav = $('.site-header-fixed');
		var navmain = $('.site-header-main');
		var h = nav.outerHeight() + 80 + $('#wpadminbar').outerHeight();
		console.log($('#wpadminbar').outerHeight())
		nav.css({'top': -h+'px'});

		var scrolled = false;

		$(window).scroll(function () {
		    
		    testScroll(h);

		    if (h < $(window).scrollTop() && !scrolled) {
		    	$('.ui-autocomplete').hide();
		        nav.show().animate({ top: 0+$('#wpadminbar').outerHeight() +'px' }); 		        
		        if($('.cs-options').eq(1).is(':visible')) $('.cs-select').click();
		        scrolled = true;
		    }

		   if (h > $(window).scrollTop() && scrolled) {
		       //animates it out of view
		       //nav.animate({ top: -h+'px' }, 'fast');  
		       nav.css({ top: -h+'px' });
		       $('.ui-autocomplete').hide();  
		       if($('.cs-options').eq(1).is(':visible')) $('.cs-select').eq(1).click();
		       //sets it back to default style
		       scrolled = false;   
		    }
		});

		function testScroll(h) {
			console.log('h:'+h+'/w:'+$(window).scrollTop())
			if(h > $(window).scrollTop()) nav.hide();
		}


		/**
		 * Fixing the result on search
		 */
		 nav.find('.ui-autocomplete-input').on('keyup', function() {

		 	$('.ui-autocomplete').addClass('src-top fixed-autosearch');
		 	var val = $(this).val();
		 	setTimeout( function() { navmain.find('.ui-autocomplete-input').val(val) }, 100);
		 });

		 navmain.find('.ui-autocomplete-input').on('keyup', function() {
		 	$('.ui-autocomplete').removeClass('fixed-autosearch');
		 	var val = $(this).val();
		 	setTimeout( function() { nav.find('.ui-autocomplete-input').val(val) }, 100);
		 });


		/**
		 * Adding style on dropdown
		 */
		var exemption = ".wcml_currency_switcher, .cs-select, #rating, #billing_country, #billing_state";
		$("select:not("+exemption+")").each( function() {
			var el = $(this);
			$(this).heapbox({
				'onChange':function(){el.change();}
			});
		});


		/**
		 * Product single view option
		 **/

		 $('select.product-single-view-option').change( function() {
		 	if($(this).val() != 0) window.open(extractDomain(location.href) + '/product/'+ $(this).val(), "_blank"); 
		 })

	});

} )( jQuery );