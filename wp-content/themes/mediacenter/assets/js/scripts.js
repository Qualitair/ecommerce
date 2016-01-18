/*===================================================================================*/
/*  GO TO TOP / SCROLL UP
/*===================================================================================*/

! function (a, b, c) {
    a.fn.scrollUp = function (b) {
        a.data(c.body, "scrollUp") || (a.data(c.body, "scrollUp", !0), a.fn.scrollUp.init(b))
    }, a.fn.scrollUp.init = function (d) {
        var e = a.fn.scrollUp.settings = a.extend({}, a.fn.scrollUp.defaults, d),
            f = e.scrollTitle ? e.scrollTitle : e.scrollText,
            g = a("<a/>", {
                id: e.scrollName,
                href: "#top"/*,
                title: f*/
            }).appendTo("body");
        e.scrollImg || g.html(e.scrollText), g.css({
            display: "none",
            position: "fixed",
            zIndex: e.zIndex
        }), e.activeOverlay && a("<div/>", {
            id: e.scrollName + "-active"
        }).css({
            position: "absolute",
            top: e.scrollDistance + "px",
            width: "100%",
            borderTop: "1px dotted" + e.activeOverlay,
            zIndex: e.zIndex
        }).appendTo("body"), scrollEvent = a(b).scroll(function () {
            switch (scrollDis = "top" === e.scrollFrom ? e.scrollDistance : a(c).height() - a(b).height() - e.scrollDistance, e.animation) {
            case "fade":
                a(a(b).scrollTop() > scrollDis ? g.fadeIn(e.animationInSpeed) : g.fadeOut(e.animationOutSpeed));
                break;
            case "slide":
                a(a(b).scrollTop() > scrollDis ? g.slideDown(e.animationInSpeed) : g.slideUp(e.animationOutSpeed));
                break;
            default:
                a(a(b).scrollTop() > scrollDis ? g.show(0) : g.hide(0))
            }
        }), g.click(function (b) {
            b.preventDefault(), a("html, body").animate({
                scrollTop: 0
            }, e.scrollSpeed, e.easingType)
        })
    }, a.fn.scrollUp.defaults = {
        scrollName: "scrollUp",
        scrollDistance: 300,
        scrollFrom: "top",
        scrollSpeed: 300,
        easingType: "linear",
        animation: "fade",
        animationInSpeed: 200,
        animationOutSpeed: 200,
        scrollText: "Scroll to top",
        scrollTitle: !1,
        scrollImg: !1,
        activeOverlay: !1,
        zIndex: 2147483647
    }, a.fn.scrollUp.destroy = function (d) {
        a.removeData(c.body, "scrollUp"), a("#" + a.fn.scrollUp.settings.scrollName).remove(), a("#" + a.fn.scrollUp.settings.scrollName + "-active").remove(), a.fn.jquery.split(".")[1] >= 7 ? a(b).off("scroll", d) : a(b).unbind("scroll", d)
    }, a.scrollUp = a.fn.scrollUp
}(jQuery, window, document);

function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = escape(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return unescape(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

(function($) {
    "use strict";

    /*===================================================================================*/
    /*  Products LIVE Search
    /*===================================================================================*/

    $(document).ready(function(){

        if( mc_options.enable_live_search == '1' ) {
            var searchProducts = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: mc_options.ajaxurl + '?action=products_live_search&fn=get_ajax_search',
                remote: mc_options.ajaxurl + '?action=products_live_search&fn=get_ajax_search&terms=%QUERY',
            });

            searchProducts.initialize();

            $( '.search-control-group .search-field' ).typeahead(
                {
                    hint: true,
                    highlight: true
                },
                {
                    name: 'search',
                    source: searchProducts.ttAdapter(),
                    displayKey: 'value',
                    templates: {
                        empty : [
                            '<div class="empty-message">',
                            'unable to find any products that match the current query',
                            '</div>'
                        ].join('\n'),
                        suggestion: Handlebars.compile( mc_options.live_search_template )
                    }
                }
            );
        }
    });

    /*===================================================================================*/
    /*  WOW 
    /*===================================================================================*/

    $(document).ready(function () {
        new WOW().init();
    });

    /*===================================================================================*/
    /*  YAMM 
    /*===================================================================================*/
 
    $(document).on('click', '.yamm .dropdown-menu', function(e) {
        e.stopPropagation();
    });

    
    /*===================================================================================*/
    /*  REMEMBER USER SHOP VIEW
    /*===================================================================================*/

    $(document).ready(function() {
        
        $('.grid-list-button-item > a').on('click', function(){
            var href = $(this).attr('href');
            eraseCookie( 'user_shop_view' );
            if( href == '#grid-view' ) {
                createCookie( 'user_shop_view', 'grid-view', 300 );
            } else {
                createCookie( 'user_shop_view', 'list-view', 300 );
            }
        });

    });


    /*===================================================================================*/
    /*  STICKY NAVIGATION
    /*===================================================================================*/

    $(document).ready(function() {
        if( mc_options.should_stick == '1' ) {
            $('#top-megamenu-nav').waypoint('sticky');
        }
    });

    /*===================================================================================*/
    /*  Product Comparison Clear All
    /*===================================================================================*/

    $(document).ready(function(){
        $('#product-comparison-page-clear-all').click(function(){
            var button = $(this),
            data = {
                _yitnonce_ajax: yith_woocompare.nonceremove,
                action: yith_woocompare.actionremove,
                id: button.data('product_id'),
                context: 'frontend'
            };

            // add ajax loader
            $('#main-content').block({message: null, overlayCSS: {background: '#fff url(' + mc_options.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6}});

            $.ajax({
                type: 'post',
                url: yith_woocompare.ajaxurl,
                data: data,
                dataType:'html',
                success: function(response){
                    $('#main-content').unblock();
                    $('#main-content').html(response);

                    // removed trigger
                    $(window).trigger('yith_woocompare_product_removed');
                }
            });

            return false;
        });
    });

    /*===================================================================================*/
    /*  OWL CAROUSEL
    /*===================================================================================*/

    $(document).ready(function () {

        var is_rtl;

        if( mc_options.rtl == '1' ) {
            is_rtl = true;
        } else {
            is_rtl = false;
        }

        $(".products-carousel-6").owlCarousel({
            autoplayHoverPause: true,
            navRewind: true,
            items: 6,
            dots: false,
            margin: -1,
            stagePadding: 1,
            rtl: is_rtl,
            responsive : {
                0 : {
                    items : 1,
                },
                480: {
                    items : 2,
                },
                768 : {
                    items : 3,
                },
                992 : {
                    items : 4,
                },
                1199 : {
                    items : 6,
                },
            },
            onTranslate : function(){
                echo.render();
            }
        });

        $('.products-carousel-4').owlCarousel({
            autoplayHoverPause: true,
            navRewind: true,
            dots: false,
            margin: -1,
            stagePadding: 1,
            rtl: is_rtl,
            responsive: {
                0: {
                    items: 1,
                },
                480 : {
                    items: 2,
                },
                768 : {
                    items : 3,
                },
                1199 : {
                    items : 4,
                },
            },
            onTranslate : function(){
                echo.render();
            }
        });

        $(".brands-carousel").owlCarousel({
            autoplayHoverPause: true,
            navRewind: true,
            items: 6,
            nav: false,
            dots: false,
            rtl: is_rtl,
            responsive : {
                0: {
                    items: 1,
                },
                480 : {
                    items: 2,
                    margin: 10,
                },
                768 : {
                    items : 4,
                },
                992 : {
                    items : 5,
                },
                1199 : {
                    items : 6,
                }
            },
        });

        $('#owl-single-product').owlCarousel({
            items: 1,
            nav: false,
            dots: false,
            rtl: is_rtl,
        });

        $('.single-product-gallery-thumbs').owlCarousel({
            items: 6,
            dots: true,
            navRewind: true,
            nav: false,
            rtl: is_rtl,
            margin: -1,
            responsive : {
                0 : {
                    items : 5,
                },
                479 : {
                    items : 6,
                },
                768 : {
                    items : 6,
                },
                1199 : {
                    items : 6,
                }
            },
        });

        $('.single-product-slider').owlCarousel({
            autoplayHoverPause: true,
            navRewind: true,
            items: 1,
            dots: false,
            nav: false,
            rtl: is_rtl,
            onTranslate : function(){
                echo.render();
            }
        });
        
        $(".slider-next").click(function () {
            var owl = $($(this).data('target'));
            owl.trigger('next.owl.carousel');
            return false;
        });
        
        $(".slider-prev").click(function () {
            var owl = $($(this).data('target'));
            owl.trigger('prev.owl.carousel');
            return false;
        });

        $('.single-product-gallery .horizontal-thumb').click(function(){
            var $this = $(this), owl = $($this.data('target')), slideTo = $this.data('slide');
            owl.trigger('to.owl.carousel', [slideTo, 300, true]);
            $this.addClass('active').parent().siblings().find('.active').removeClass('active');
            return false;
        });

        $(".owl-blog-post-gallery").owlCarousel({
            autoplaySpeed: 5000,
            navSpeed: 200,
            dotsSpeed: 600,
            dragEndSpeed: 800,
            autoplayHoverPause: true,
            dots: true,
            navRewind: true,
            items: 1,
            nav: true,
            autoHeight: true,
            rtl: is_rtl,
            navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
        });
        
    });


    /*===================================================================================*/
    /*  CUSTOM CONTROLS
    /*===================================================================================*/

    $(document).ready(function () {
        
        // Select Dropdown
        if($('.le-select').length > 0){
            $('.le-select select').customSelect({customClass:'le-select-in'});
        }

        // Checkbox
        if($('.le-checkbox').length>0){
            $('.le-checkbox').after('<i class="fake-box"></i>');
        }

        //Radio Button
        if($('.le-radio').length>0){
            $('.le-radio').after('<i class="fake-box"></i>');
        }

        // Buttons
        $('.le-button.disabled').click(function(e){
            e.preventDefault();
        });

        // Quantity Spinner
        $('.le-quantity a').click(function(e){
            e.preventDefault();
            var currentQty= $(this).parent().parent().find('input').val();
            if( $(this).hasClass('minus') && currentQty>0){
                $(this).parent().parent().find('input').val(parseInt(currentQty, 10) - 1);
            }else{
                if( $(this).hasClass('plus')){
                    $(this).parent().parent().find('input').val(parseInt(currentQty, 10) + 1);
                }
            }
        });

        // Price Slider
        if ($('.price-slider').length > 0) {
            $('.price-slider').slider({
                min: 100,
                max: 700,
                step: 10,
                value: [100, 400],
                handle: "square"

            });
        }

        // Data Placeholder for custom controls

        $('[data-placeholder]').focus(function() {
            var input = $(this);
            if (input.val() == input.attr('data-placeholder')) {
                input.val('');

            }
        }).blur(function() {
            var input = $(this);
            if (input.val() === '' || input.val() == input.attr('data-placeholder')) {
                input.addClass('placeholder');
                input.val(input.attr('data-placeholder'));
            }
        }).blur();

        $('[data-placeholder]').parents('form').submit(function() {
            $(this).find('[data-placeholder]').each(function() {
                var input = $(this);
                if (input.val() == input.attr('data-placeholder')) {
                    input.val('');
                }
            });
        });

    });


    /*===================================================================================*/
    /*  SELECT TOP DROP MENU
    /*===================================================================================*/
    $(document).ready(function() {
        $('.top-drop-menu').change(function() {
            var loc = ($(this).find('option:selected').val());
            window.location = loc;
        });
    });

    /*===================================================================================*/
    /*  LAZY LOAD IMAGES USING ECHO
    /*===================================================================================*/
    $(document).ready(function(){
        echo.init({
            offset: 100,
            throttle: 250,
            unload: false,
            callback: function (element, op) {
                $( element ).removeClass( 'echo-lazy-loading');
            }
        });
    });

    /*===================================================================================*/
    /*  DATA HOVER ANIMATION
    /*===================================================================================*/

    $(document).ready(function(){
        $('[data-hover="animate"]').on('mouseenter', function(){
            var $this = $(this), animation = $this.data('animation');
            $this.addClass('animated ' + animation);
        });
        $('[data-hover="animate"]').on('mouseleave', function(){
            var $this = $(this), animation = $this.data('animation');
            $this.removeClass('animated ' + animation);
        });
    });

    /*===================================================================================*/
    /*  LINKING TO ACTIVE TAB
    /*===================================================================================*/

    /*$(document).ready(function(){
        var hash = location.hash, hashPieces = hash.split('?'), activeTab = $('[href=' + hashPieces[0] + ']');
        activeTab && activeTab.tab('show');
    });*/

    /*===================================================================================*/
    /*  SEARCH FORM
    /*===================================================================================*/

    $(document).ready(function(){
        var $dropdownCategory = $('.category-dropdown'), $searchParam = $('#search-param');
        $dropdownCategory.on('click', function(){
            var $this = $(this);
            if( '' === $this.data('value') ){
                $searchParam.attr('name', 'post_type');
                $searchParam.val('product');
            }else{
                $searchParam.attr('name', 'product_cat');
                $searchParam.val( $this.data('value') );
            }
            $('#category-filter-value').text( $this.text() );
            $('#category-filter-value').dropdown('toggle');
            adjustSearchFieldWidth();
            return false;
        });

        adjustSearchFieldWidth();
    });

    $(window).resize(function() {
        adjustSearchFieldWidth();
    });

    function adjustSearchFieldWidth(){
        var widthSearchControlGroup = $('.search-control-group').width();
        var widthSearchBarControls = $('.search-bar-controls').width();
        var widthSearchField = widthSearchControlGroup - (widthSearchBarControls + 30 );
        $('.search-field').width(widthSearchField);
    }

    /*===================================================================================*/
    /*  ADDED TO CART ANIMATION
    /*===================================================================================*/

    $('body').on('added_to_cart', function(){
        
        $('.product-item').unblock(); // Unblock the product item
        $('.cart-item').unblock();
        $('.compare-list').unblock();

        $('html,body').animate({
            scrollTop: $('.top-cart-holder').offset().top - 20
        }, 1000, function(){
            $('.top-cart-holder .dropdown-toggle').dropdown('toggle');
        });
        return false;
    });

    /*===================================================================================*/
    /*  GO TO TOP / SCROLL UP
    /*===================================================================================*/

    $(document).ready(function () {

        if( mc_options.should_scroll == '1' ) {
            $.scrollUp({
                scrollName: "scrollUp", // Element ID
                scrollDistance: 300, // Distance from top/bottom before showing element (px)
                scrollFrom: "top", // "top" or "bottom"
                scrollSpeed: 1000, // Speed back to top (ms)
                easingType: "easeInOutCubic", // Scroll to top easing (see http://easings.net/)
                animation: "fade", // Fade, slide, none
                animationInSpeed: 200, // Animation in speed (ms)
                animationOutSpeed: 200, // Animation out speed (ms)
                scrollText: "<i class='fa fa-angle-up'></i>", // Text for element, can contain HTML
                scrollTitle: " ", // Set a custom <a> title if required. Defaults to scrollText
                scrollImg: 0, // Set true to use image
                activeOverlay: 0, // Set CSS color to display scrollUp active point, e.g "#00FFFF"
                zIndex: 1001 // Z-Index for the overlay
            });
        }
    });


    /*===================================================================================*/
    /*  ANIMATED / SMOOTH SCROLL TO ANCHOR
    /*===================================================================================*/

    $(document).ready(function() {
        
        $("a.scrollTo").click(function() {
            $("html, body").animate({
                scrollTop: $($(this).attr("href")).offset().top + "px"
            }, {
                duration: 1000,
                easing: "easeInOutCubic"
            });
            return false;
        });
        
    });

    /*===================================================================================*/
    /*  Adding to Cart animation
    /*===================================================================================*/
    $(document).ready(function(){
        $('body').on('adding_to_cart', function( e, $btn, data){
            $btn.parents('.product-item').block({message: null, overlayCSS: {background: '#fff url(' + mc_options.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6}});
            $btn.parents('.cart-item').block({message: null, overlayCSS: {background: '#fff url(' + mc_options.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6}});
            $btn.parents('.compare-list').block({message: null, overlayCSS: {background: '#fff url(' + mc_options.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6}});
        });
    });

    /*===================================================================================*/
    /*  PRODUCT CATEGORIES TOGGLE
    /*===================================================================================*/

    $(document).ready(function(){
        $('.cat-parent > a').each(function(){
            var $childIndicator = $('<span class="child-indicator"></span>');
            
            if($(this).siblings('.children').is(':visible')){
                $childIndicator.addClass( 'open' );
            }
            
            $childIndicator.click(function(){
                $(this).parent().siblings('.children').toggle( 'fast', function(){
                    if($(this).is(':visible')){
                        $childIndicator.addClass( 'open' );
                    }else{
                        $childIndicator.removeClass( 'open' );
                    }
                });
                return false;
            });
            $(this).append($childIndicator);
        });
    });

    

    /*===================================================================================*/
    /*  WooCompare
    /*===================================================================================*/

    $(document).ready(function(){

        // add into table
        $(document).on( 'click', '.product a.compare', function(e){
            e.preventDefault();

            var button = $(this),
                data = {
                    _yitnonce_ajax: yith_woocompare.nonceadd,
                    action: yith_woocompare.actionadd,
                    id: button.data('product_id'),
                    context: 'frontend'
                },
                widget_list = $('.yith-woocompare-widget ul.products-list'),
                product_item = button.parents('.product-item'),
                single_product_row = button.parents('.single-product-row');

            // add ajax loader
            product_item.block({message: null, overlayCSS: {background: '#fff url(' + mc_options.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6}});
            widget_list.block({message: null, overlayCSS: {background: '#fff url(' + mc_options.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6}});
            single_product_row.block({message: null, overlayCSS: {background: '#fff url(' + mc_options.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6}});

            $.ajax({
                type: 'post',
                url: yith_woocompare.ajaxurl,
                data: data,
                dataType: 'json',
                success: function(response){
                    product_item.unblock();
                    button.addClass('added').html( '<i class="fa fa-check"></i>' + yith_woocompare.added_label );

                    $('#top-cart-compare-count').text( '(' + $('<ul>' + response.widget_table + '</ul>').find('li').length + ')' );

                    // add the product in the widget
                    widget_list.unblock().html( response.widget_table );

                    single_product_row.unblock();

                    if (yith_woocompare.auto_open == 'yes'){
                        $('body').trigger( 'yith_woocompare_open_popup', { response: response.table_url, button: button } );
                    }
                }
            });
        });

        // open popup
        $('body').on( 'yith_woocompare_open_popup', function( e, data ) {
            var response = data.response, woocompare_url = $('#yith-woocompare-link').attr('href');
            window.location.href = woocompare_url;
        });

        // remove from table
        $(document).on( 'click', '.remove a', function(e){
            e.preventDefault();

            var button = $(this),
                data = {
                    _yitnonce_ajax: yith_woocompare.nonceremove,
                    action: yith_woocompare.actionremove,
                    id: button.data('product_id'),
                    context: 'frontend'
                },
                product_cell = $( 'td.product_' + data.id + ', th.product_' + data.id );

            // add ajax loader
            button.block({message: null, overlayCSS: {background: '#fff url(' + mc_options.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6}});

            $.ajax({
                type: 'post',
                url: yith_woocompare.ajaxurl,
                data: data,
                dataType:'html',
                success: function(response){
                    button.unblock();

                    // in compare table
                    var table = $(response).filter('table.compare-list');
                    $('body > table.compare-list').replaceWith( table );

                    // removed trigger
                    $(window).trigger('yith_woocompare_product_removed');
                }
            });
        });


        // General link to open the compare table
        $('.yith-woocompare-open a, a.yith-woocompare-open').on('click', function(e){
            e.preventDefault();
            $('body').trigger('yith_woocompare_open_popup', { response: yith_add_query_arg('action', yith_woocompare.actionview) + '&iframe=true' });
        });

        // WooCompare Widget

        $('.yith-woocompare-widget')

        // view table (click on compare
        .on('click', 'a.compare', function (e) {
            e.preventDefault();
            $('body').trigger('yith_woocompare_open_popup', { response: $(this).attr('href') });
        })

        // remove product & clear all
        .on('click', 'li a.remove, a.clear-all', function (e) {
            e.preventDefault();

            var lang = $( '.yith-woocompare-widget .products-list').data('lang');

            var button = $(this),
                data = {
                    _yitnonce_ajax: yith_woocompare.nonceremove,
                    action: yith_woocompare.actionremove,
                    id: button.data('product_id'),
                    context: 'frontend',
                    responseType: 'product_list',
                    lang: lang
                },
                product_list = button.parents('.yith-woocompare-widget').find('ul.products-list');

            // add ajax loader
            product_list.block({message: null, overlayCSS: {background: '#fff url(' + mc_options.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6}});

            $.ajax({
                type: 'post',
                url: yith_woocompare.ajaxurl,
                data: data,
                dataType: 'html',
                success: function (response) {
                    product_list.html(response);
                    product_list.unblock();
                    $('#top-cart-compare-count').text( '(' + $('<ul>' + response + '</ul>').find('li > a').length + ')' );
                }
            });
        });


        function yith_add_query_arg(key, value){
            key = escape(key); value = escape(value);

            var s = document.location.search;
            var kvp = key+"="+value;

            var r = new RegExp("(&|\\?)"+key+"=[^\&]*");

            s = s.replace(r,"$1"+kvp);

            if(!RegExp.$1) {s += (s.length>0 ? '&' : '?') + kvp;}

            //again, do what you will here
            return s;
        }

    });

    /*===================================================================================*/
    /*  YITH Wishlist
    /*===================================================================================*/

    $(document).ready(function(){

        var cart_redirect_after_add = typeof( wc_add_to_cart_params ) !== 'undefined' ? wc_add_to_cart_params.cart_redirect_after_add : '',
        this_page = window.location.toString();

        $(document).on( 'click', '.add_to_wishlist', function( ev ){
            var t = $( this);

            ev.preventDefault();

            t.parents('.product-item').block({message: null, overlayCSS: {background: '#fff url(' + mc_options.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6}});
            t.parents('.single-product-row').block({message: null, overlayCSS: {background: '#fff url(' + mc_options.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6}});

            call_ajax_add_to_wishlist( t );

            return false;
        } );

        $(document).on( 'click', '.remove_from_wishlist', function( ev ){
            var t = $( this );

            ev.preventDefault();

            remove_item_from_wishlist( t );

            return false;
        } );

        $(document).on( 'adding_to_cart', 'body', function( ev, button, data ){
            if( button.closest( '.wishlist_table' ).length != 0 ){
                data.remove_from_wishlist_after_add_to_cart = button.closest( 'tr' ).data( 'row-id' );
                data.wishlist_id = button.closest( 'table' ).data( 'id' );
                wc_add_to_cart_params.cart_redirect_after_add = yith_wcwl_l10n.redirect_to_cart;
            }
        } );

        $(document).on( 'added_to_cart', 'body', function( ev ){
            wc_add_to_cart_params.cart_redirect_after_add = cart_redirect_after_add;

            var wishlist = $( '.wishlist_table');

            wishlist.find( '.added' ).removeClass( 'added' );
            wishlist.find( '.added_to_cart' ).remove();
        } );

        $(document).on( 'change', '.yith-wcwl-popup-content .wishlist-select', function( ev ){
            var t = $(this);

            if( t.val() == 'new' ){
                t.parents( '.yith-wcwl-first-row' ).next( '.yith-wcwl-second-row' ).css( 'display', 'table-row' );
            }
            else{
                t.parents( '.yith-wcwl-first-row' ).next( '.yith-wcwl-second-row' ).hide();
            }
        } );

    });

    /**
     * Add a product in the wishlist.
     *
     * @param string url
     * @param string prod_type
     * @return void
     * @since 1.0.0
     */
    function call_ajax_add_to_wishlist( el ) {
        var product_id = el.data( 'product-id' ),
            el_wrap = el.parents('.yith-wcwl-add-to-wishlist'),
            data = {
                add_to_wishlist: product_id,
                product_type: el.data( 'product-type' ),
                action: yith_wcwl_l10n.actions.add_to_wishlist_action
            };

        if( yith_wcwl_l10n.multi_wishlist && yith_wcwl_l10n.is_user_logged_in ){
            var wishlist_popup_container = el.parents( '.yith-wcwl-popup-footer' ).prev( '.yith-wcwl-popup-content' ),
                wishlist_popup_select = wishlist_popup_container.find( '.wishlist-select' ),
                wishlist_popup_name = wishlist_popup_container.find( '.wishlist-name' ),
                wishlist_popup_visibility = wishlist_popup_container.find( '.wishlist-visibility' );

            data.wishlist_id = wishlist_popup_select.val();
            data.wishlist_name = wishlist_popup_name.val();
            data.wishlist_visibility = wishlist_popup_visibility.val();
        }

        if( ! is_cookie_enabled() ){
            alert( yith_wcwl_l10n.labels.cookie_disabled );
            return;
        }

        $.ajax({
            type: 'POST',
            url: yith_wcwl_l10n.ajax_url,
            data: data,
            dataType: 'json',
            beforeSend: function(){
                el.siblings( '.ajax-loading' ).css( 'visibility', 'visible' );
            },
            complete: function(){
                el.siblings( '.ajax-loading' ).css( 'visibility', 'hidden' );
            },
            success: function( response ) {
                var msg = $( '#yith-wcwl-popup-message' ),
                    response_result = response.result,
                    response_message = response.message;

                if( yith_wcwl_l10n.multi_wishlist && yith_wcwl_l10n.is_user_logged_in ) {
                    var wishlist_select = $( 'select.wishlist-select' );
                    $.prettyPhoto.close();

                    wishlist_select.each( function( index ){
                        var t = $(this),
                            wishlist_options = t.find( 'option' );

                        wishlist_options = wishlist_options.slice( 1, wishlist_options.length - 1 );
                        wishlist_options.remove();

                        if( typeof( response.user_wishlists ) != 'undefined' ){
                            var i = 0;
                            for( i in response.user_wishlists ) {
                                if ( response.user_wishlists[i].is_default != "1" ) {
                                    $('<option>')
                                        .val(response.user_wishlists[i].ID)
                                        .html(response.user_wishlists[i].wishlist_name)
                                        .insertBefore(t.find('option:last-child'))
                                }
                            }
                        }
                    } );
                }

                $( '#yith-wcwl-message' ).html( response_message );
                msg.css( 'margin-left', '-' + $( msg ).width() + 'px' ).fadeIn();
                window.setTimeout( function() {
                    msg.fadeOut();
                }, 2000 );

                if( response_result == "true" ) {
                    if( ! yith_wcwl_l10n.multi_wishlist || ! yith_wcwl_l10n.is_user_logged_in || ( yith_wcwl_l10n.multi_wishlist && yith_wcwl_l10n.is_user_logged_in && yith_wcwl_l10n.hide_add_button ) ) {
                        el_wrap.find('.yith-wcwl-add-button').hide().removeClass('show').addClass('hide');
                    }

                    el_wrap.find( '.yith-wcwl-wishlistexistsbrowse').hide().removeClass('show').addClass('hide').find('a').attr('href', response.wishlist_url);
                    el_wrap.find( '.yith-wcwl-wishlistaddedbrowse' ).show().removeClass('hide').addClass('show').find('a').attr('href', response.wishlist_url);
                    increment_wishlist_counter();
                } else if( response_result == "exists" ) {
                    if( ! yith_wcwl_l10n.multi_wishlist || ! yith_wcwl_l10n.is_user_logged_in || ( yith_wcwl_l10n.multi_wishlist && yith_wcwl_l10n.is_user_logged_in && yith_wcwl_l10n.hide_add_button ) ) {
                        el_wrap.find('.yith-wcwl-add-button').hide().removeClass('show').addClass('hide');
                    }

                    el_wrap.find( '.yith-wcwl-wishlistexistsbrowse' ).show().removeClass('hide').addClass('show').find('a').attr('href', response.wishlist_url);
                    el_wrap.find( '.yith-wcwl-wishlistaddedbrowse' ).hide().removeClass('show').addClass('hide').find('a').attr('href', response.wishlist_url);
                } else {
                    el_wrap.find( '.yith-wcwl-add-button' ).show().removeClass('hide').addClass('show');
                    el_wrap.find( '.yith-wcwl-wishlistexistsbrowse' ).hide().removeClass('show').addClass('hide');
                    el_wrap.find( '.yith-wcwl-wishlistaddedbrowse' ).hide().removeClass('show').addClass('hide');
                }

                $('body').trigger('added_to_wishlist');

                el.parents('.product-item').unblock();
                el.parents('.single-product-row').unblock();
            }

        });
    }

    /**
     * Remove a product from the wishlist.
     *
     * @param string url
     * @param int rowid
     * @return void
     * @since 1.0.0
     */
    function remove_item_from_wishlist( el ) {
        var table = el.parents( '.cart.wishlist_table' ),
            pagination = table.data( 'pagination' ),
            per_page = table.data( 'per-page' ),
            current_page = table.data( 'page' ),
            row = el.parents( '.row' ),
            pagination_row = table.find( '.pagination-row'),
            data_row_id = row.data( 'row-id'),
            data = {
                action: yith_wcwl_l10n.actions.remove_from_wishlist_action,
                remove_from_wishlist: data_row_id,
                pagination: pagination,
                per_page: per_page,
                current_page: current_page
            };

        $( '#yith-wcwl-message' ).html( '&nbsp;' );
        $( '.wishlist_table' ).block({message: null, overlayCSS: {background: '#fff url(' + mc_options.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6}});

        $( '#wishlist-page' ).load( yith_wcwl_l10n.ajax_url, data, function(){
            decrement_wishlist_counter();
            $( '.wishlist_table' ).unblock();
        } );
    }

     /**
     * Check if cookies are enabled
     *
     * @return bool
     * @since 2.0.0
     */
    function is_cookie_enabled() {
        if (navigator.cookieEnabled) return true;

        // set and read cookie
        document.cookie = "cookietest=1";
        var ret = document.cookie.indexOf("cookietest=") != -1;

        // delete cookie
        document.cookie = "cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT";

        return ret;
    }

    function get_wishlist_count() {
        return parseInt( $('#top-cart-wishlist-count').text().replace('(', ''), 10 );
    }

    function increment_wishlist_counter(){
        var wishlist_count = get_wishlist_count();
        wishlist_count = wishlist_count + 1;
        return set_wishlist_count( wishlist_count );
    }

    function decrement_wishlist_counter(){
        var wishlist_count = get_wishlist_count();
        wishlist_count = wishlist_count - 1;
        return set_wishlist_count( wishlist_count );
    }

    function set_wishlist_count( value ) {
        return $('#top-cart-wishlist-count').text('(' + value + ')');
    }

    /**
     * Add a product to the cart from the wishlist.
     *
     * @param string url
     * @return void
     * @since 1.0.0
     */
    function add_tocart_from_wishlist( url ) {
        $( '#yith-wcwl-message' ).html( '&nbsp;' );

        $.ajax({
            type: 'GET',
            url: url,
            success: function( response ) {
                $( '#yith-wcwl-message' ).html( response );
            }
        });
    }

    /**
     * Check if a product is in stock.
     *
     * @param string url
     * @param string stock_status
     * @param bool redirect_to_cart
     * @return void
     * @since 1.0.0
     */
    function check_for_stock( url, stock_status, redirect_to_cart ) {
        if( stock_status == 'out-of-stock' ) {
            alert( yith_wcwl_l10n.out_of_stock );
            return false;
        }

        if( redirect_to_cart == 'true' )
        { location.href = url + '&redirect_to_cart=true'; }
        else
        { location.href = url; }
    }

    /*===================================================================================*/
    /*  CURRENCY SWITCHER
    /*===================================================================================*/

    $(document).ready(function(){
        $('.mc_wcml_currency_switcher > li > a').on('click', function(){
            var $this = $(this),
            currency = $this.data('currency'),
            data = { action : 'wcml_switch_currency', currency : currency },
            mc_wcml_currency_switcher = $('.mc_wcml_currency_switcher');
            mc_wcml_currency_switcher.block({message: null, overlayCSS: {background: '#fff url(' + mc_options.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6}});
            $.post( woocommerce_params.ajax_url, data, function(){
                mc_wcml_currency_switcher.unblock();
                location.reload();
            });
            return false;
        });
    });

    /*===================================================================================*/
    /*  GMAP ACTIVATOR
    /*===================================================================================*/

    var directionsDisplay;
    var directionsService;
    var map, destination;

    $(document).ready(function(){
        
        if( typeof gmapParams !== 'undefined'){
            var zoom = parseInt(gmapParams.zoom, 10);
            var latitude = parseFloat(gmapParams.latitude, 10);
            var longitude = parseFloat(gmapParams.longitude, 10);
            var mapIsNotActive = true;
            setupCustomMap( zoom, latitude, longitude, mapIsNotActive );
        }

        //$('')
    });

    function setupCustomMap( zoom, latitude, longitude, mapIsNotActive ) {
        if ($('.map-holder').length > 0 && mapIsNotActive) {

            var styles = [
                {
                    "featureType": "landscape",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "visibility": "simplified"
                        },
                        {
                            "color": "#E6E6E6"
                        }
                    ]
                }, {
                    "featureType": "administrative",
                    "stylers": [
                        {
                            "visibility": "simplified"
                        }
                    ]
                }, {
                    "featureType": "road",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "saturation": -100
                        }
                    ]
                }, {
                    "featureType": "road.highway",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#808080"
                        },
                        {
                            "visibility": "on"
                        }
                    ]
                }, {
                    "featureType": "water",
                    "stylers": [
                        {
                            "color": "#CECECE"
                        },
                        {
                            "visibility": "on"
                        }
                    ]
                }, {
                    "featureType": "poi",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                }, {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#E5E5E5"
                        },
                        {
                            "visibility": "on"
                        }
                    ]
                }, {
                    "featureType": "road.local",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        },
                        {
                            "visibility": "on"
                        }
                    ]
                }, {}
            ];
            
            var lt, ld;

            directionsService = new google.maps.DirectionsService();
            
            if ($('.map').hasClass('center')) {
                lt = (latitude);
                ld = (longitude);
            } else {
                lt = (latitude + 0.0027);
                ld = (longitude - 0.010);
            }

            destination = new google.maps.LatLng(lt, ld);

            var options = {
                mapTypeControlOptions: {
                    mapTypeIds: ['Styled']
                },
                center: destination,
                zoom: zoom,
                disableDefaultUI: true,
                scrollwheel: false,
                mapTypeId: 'Styled'
            };
            
            var div = document.getElementById(gmapParams.gmapID);
            directionsDisplay = new google.maps.DirectionsRenderer();

            map = new google.maps.Map(div, options);
            directionsDisplay.setMap(map);

            var styledMapType = new google.maps.StyledMapType(styles, {
                name: 'Styled'
            });

            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(latitude, longitude),
                map: map
            });
            
            map.mapTypes.set('Styled', styledMapType);

            mapIsNotActive = false;
        }

    }

    $('#get-direction').on('click', function(){
        var start = $('#starting-point').val();
        calcRoute(start);
    });

    function calcRoute( start ) {
        var request = {
            origin: start,
            destination: destination,
            travelMode: google.maps.TravelMode.DRIVING
        };
        directionsService.route(request, function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
            }
        });
    }


})(jQuery);