jQuery(document).ready(function($) {

	var data = {
		'action': 'do_sync_product',
		'whatever': 1234
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	$('.btn-ajax-sync:enabled').unbind('click').bind('click', function() {
		var btn = $(this),
			title = $(this).html();
		
		data.call = $(this).attr('action');

		//okay lets bind something
		$(window).bind('beforeunload', function(){
		   return "WARNING! \nSyncing product is not yet done.\nProcess might be terminated.\nAre you sure?";
		});

		btn.attr('disabled', 'true').addClass('loading').html('<img class="duap-on-button-loader" src="/wp-content/uploads/2015/12/loading110.gif"/> <span>Syncronizing... Please wait, it might takes several minutes.</span>');
		$('.duap-result-content').html('<p class="prepare">Preparing....</p><div class="sect"></div>');

		jQuery.post(ajaxurl, data, function(response) {
			//getting the result
			$('.duap-result-content .prepare').addClass('done').html("Done.");
			$('.duap-result-content .sect').html(response);

			btn.removeAttr('disabled').
				removeClass('loading').
				html(title);

			$(window).unbind('beforeunload');
		});
	});

	$('.duap-api-form-table').find('input:text').each( function() {
		if($(this).data('content') == '') $('.btn-ajax-sync').attr('disabled', 'disabled');
	});

});