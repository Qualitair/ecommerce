jQuery(document).ready(function($) {

	//PRESET IMAGES start

	$('#presetImagesSelect').on('change', function() {

		if($(this).val() !='noPresetSelected' ){

			//alert($(this).val());
			console.log('u selektua ' + $(this).val());
			$('.gema75_badges_quick_preview').html('<img  src="' + $(this).val() + '">');

			//remove badge padding,background color etc since we`re going to use a preset image
			$('.gema75_badges_quick_preview').css({
													   'background-color' : 'transparent',
													   color : 'transparent',
													   'padding' : '0',
													   'border-radius' : '0',
													   'border' : 'none'
													});

		}

	});

	//PRESET IMAGES ends

	//Check if the input text field for the image has value and update the preview image box
	if($('#gema75_badge_image_input').val()!=''){
		 $('#previewImageUploaded').html('<p><img  src="' + $('#gema75_badge_image_input').val() + '"><div class="previewImageUploadedRemove"  style="color:#ff0000;cursor: pointer;font-size: 14px;"> <span class="dashicons dashicons-dismiss"  ></span>Remove</div></p>');

		//update preview
		//$('.gema75_badges_quick_preview').css('background-image', 'url(' + $('#gema75_badge_image_input').val() + ')');
		$('.gema75_badges_quick_preview').html('<img  src="' + $('#gema75_badge_image_input').val() + '">');
	}

	var formfield = null;

	$('#upload_image_button').click(function() {
		$('html').addClass('Image');
		formfield = $('#gema75_badge_image_input').attr('name');
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
	});


	window.gema75_wc_badge_send_to_editor = window.send_to_editor;
	window.send_to_editor = function(html){

		var fileurl;

		if (formfield != null) {

			fileurl = $('img',html).attr('src');
			$('#gema75_badge_image_input').val(fileurl);

			var  htmlForPreviewImageUploadedDiv ='<img  src="' + fileurl + '">';
			htmlForPreviewImageUploadedDiv+='<div class="previewImageUploadedRemove"  style="color:#ff0000;cursor: pointer;font-size: 14px;"> <span class="dashicons dashicons-dismiss"  ></span>Remove</div>';

			$('#previewImageUploaded').html(htmlForPreviewImageUploadedDiv);

			$('.previewImageUploadedRemove').click(function(){
				$('#previewImageUploaded').html('');
				$('#gema75_badge_image_input').val('');
				
			});

			//update preview with the custom image 
			$('.gema75_badges_quick_preview').html('<img  src="' + fileurl + '">');

			//reset the "preset images" 
			$('#presetImagesSelect').val('noPresetSelected').attr("selected", "selected");

			tb_remove();
			$('html').removeClass('Image');
			formfield = null;
			
		} else {
			
			window.gema75_wc_badge_send_to_editor(html);
			
		}
		
	};


	$('.previewImageUploadedRemove').click(function(){
		$('#previewImageUploaded').html('');
		$('#gema75_badge_image_input').val('');
	});

});