jQuery( document ).ready( function( $ ) {
	/** Field Icon */
	$( 'body' ).on( 'click', '.pick-icon', function ( e ) {
		e.preventDefault();

		$( this ).next( '.icons-block' ).slideToggle();
	} );

	$( '.icon-selector' ).on( 'click', 'i', function(e) {
		e.preventDefault();
		var $el = $( this ),
			icon = $el.data( 'icon' );

		$el.closest( '.icons-block' ).next( 'input' ).val( icon ).siblings( '.pick-icon' ).children( 'i' ).attr( 'class', icon );
		$el.addClass( 'selected' ).siblings( '.selected' ).removeClass( 'selected' );
	} );

	$( '.search-icon' ).on( 'keyup', function() {
		var search = $( this ).val(),
			$icons = $( this ).siblings( '.icon-selector' ).children();

			if ( !search ) {
				$icons.show();
				return;
			}

			$icons.hide().filter( function() {
				return $( this ).data( 'icon' ).indexOf( search ) >= 0;
			} ).show();
	} );

	/** Field image */
	var file;

	$( '.upload-image' ).on( 'click', function( e ) {
		e.preventDefault();

		var $input = $( this ).prev( 'input' );

		if ( typeof file != 'undefined' ) {
			file.close();
		}

		file = wp.media();

		//callback for selected image
		file.on( 'select', function() {
			var attachments = file.state().get( 'selection' ).toJSON();
			$input.val( attachments[0].url );
		} );

		// Open modal
		file.open();
	} );
} );
