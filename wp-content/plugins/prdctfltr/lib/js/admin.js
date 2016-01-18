(function($){
"use strict";

	var indexBefore = -1;

	function getIndex(itm, list) {
		var i;
		for (i = 0; i < list.length; i++) {
			if (itm[0] === list[i]) break;
		}
		return i >= list.length ? -1 : i;
	}

	
	$('.prdctfltr_customizer').sortable({
		cursor:'move',
		start: function(event, ui) {
			indexBefore = getIndex(ui.item, $('.prdctfltr_customizer > span'));
		},
		stop: function(event, ui) {

			var count = $('.prdctfltr_customizer span.adv').length;

			if ( count > 0 ) {
				var i = 0;
				$('.prdctfltr_customizer span.adv').each( function() {
					var curr_el = $(this);
					var curr = curr_el.attr('data-id');

					curr_el.find('[name]').each( function() {
						var attr = $(this).attr('name');
						$(this).attr('name', attr.replace('['+curr+']', '['+i+']'))
					});

					curr_el.attr('data-id', i);

					i++;
				});

			}

			var count = $('.prdctfltr_customizer span.rng').length;

			if ( count > 0 ) {
				var i = 0;
				$('.prdctfltr_customizer span.rng').each( function() {
					var curr_el = $(this);
					var curr = curr_el.attr('data-id');

					curr_el.find('[name]').each( function() {
						var attr = $(this).attr('name');
						$(this).attr('name', attr.replace('['+curr+']', '['+i+']'))
					});

					curr_el.attr('data-id', i);

					i++;
				});

			}


			var indexAfter = getIndex(ui.item,$('.prdctfltr_customizer > span'));

			if (indexBefore==indexAfter) {
				return;
			} else {
				if (indexBefore<indexAfter) {
					$($('#wc_settings_prdctfltr_active_filters option')[indexBefore]).insertAfter($($('#wc_settings_prdctfltr_active_filters option')[indexAfter]));
				}
				else {
					$($('#wc_settings_prdctfltr_active_filters option')[indexBefore]).insertBefore($($('#wc_settings_prdctfltr_active_filters option')[indexAfter]));
				}
			}


		}
	});

	$(document).on('click', '.prdctfltr_c_visible', function() {
		var curr_el = $(this).parent();

		var curr_index = getIndex(curr_el, $('.prdctfltr_customizer > span'));

		if ( curr_el.find('.prdctfltr-eye').length > 0 ) {
			curr_el.find('.prdctfltr-eye').removeClass('prdctfltr-eye').addClass('prdctfltr-eye-disabled');
			$('#wc_settings_prdctfltr_active_filters option').eq(curr_index).prop("selected", false);
		}
		else {
			curr_el.find('.prdctfltr-eye-disabled').removeClass('prdctfltr-eye-disabled').addClass('prdctfltr-eye');
			$('#wc_settings_prdctfltr_active_filters option').eq(curr_index).prop("selected", true);
		}
		return false;
	});

	$(document).on('click', '.prdctfltr_c_delete', function() {
		var curr_el = $(this).parent();

		var curr_index = getIndex(curr_el, $('.prdctfltr_customizer > span'));

		$('#wc_settings_prdctfltr_active_filters option').eq(curr_index).remove();

		$('.prdctfltr_c_add_filter[data-filter='+curr_el.attr('data-filter')+']').removeClass('pf_active').find('i').removeClass('prdctfltr-eye').addClass('prdctfltr-eye-disabled');

		curr_el.remove();

		return false;
	});

	$(document).on('change', '.prdctfltr_adv_select', function() {

		var curr_el = $(this).parent();
		var curr = curr_el.closest('.adv').attr('data-id');

		var curr_data = {
			action: 'prdctfltr_c_terms',
			taxonomy: $(this).find('option:selected').attr('value')
		};

		$.post(prdctfltr.ajax, curr_data, function(response) {
			if (response) {
				response = response.replace(/\%%/g, curr);
				curr_el.next().replaceWith(response);
			} else { 
				alert('Error!');
			}
		});

	});

	$(document).on('click', '.prdctfltr_c_add_filter', function() {
		var curr_el = $(this);
		
		if ( curr_el.hasClass('pf_active') ) {

			if ( confirm('Deactivate?') === false ) {
				return;
			}

			$('#wc_settings_prdctfltr_active_filters option[value='+curr_el.attr('data-filter')+']').remove();
			$('.prdctfltr_customizer span[data-filter='+curr_el.attr('data-filter')+']').remove();

			curr_el.removeClass('pf_active').find('i').removeClass('prdctfltr-eye').addClass('prdctfltr-eye-disabled');

		}
		else {

			if ( confirm('Activate?') === false ) {
				return;
			}

			$('#wc_settings_prdctfltr_active_filters').append('<option value="'+curr_el.attr('data-filter')+'" selected="selected">'+curr_el.find('span').text()+'</option>');
			$('.prdctfltr_customizer').append('<span class="pf_element" data-filter="'+curr_el.attr('data-filter')+'"><span>'+curr_el.find('span').text()+'</span><a href="#" class="prdctfltr_c_delete"><i class="prdctfltr-delete"></i></a><a href="#" class="prdctfltr_c_move"><i class="prdctfltr-move"></i></a></span>');

			curr_el.addClass('pf_active').find('i').removeClass('prdctfltr-eye-disabled').addClass('prdctfltr-eye');

		}
		
		return false;
	});

	$(document).on('click', '.prdctfltr_c_add.pf_advanced', function() {

		var curr_el = $(this).parent().next();
		var curr = curr_el.find('.pf_element.adv').length;

		var curr_data = {
			action: 'prdctfltr_c_fields',
			pf_id: curr
		};

		$.post(prdctfltr.ajax, curr_data, function(response) {
			if (response) {
				response = response.split('%SPLIT%');

				var adv_ui = '<span class="pf_element adv" data-filter="advanced" data-id="'+response[0]+'"><span>Advanced Filter</span><a href="#" class="prdctfltr_c_delete"><i class="prdctfltr-delete"></i></a><a href="#" class="prdctfltr_c_move"><i class="prdctfltr-move"></i></a><span class="pf_options_holder">'+response[1]+'</span></span>';
				$('#wc_settings_prdctfltr_active_filters').append('<option value="advanced" selected="selected">Advanced Filter</option>');

				var curr_append = curr_el.append(adv_ui);

			} else { 
				alert('Error!');
			}
		});

		return false;
	});


	function makeVals(formControl, controlType, value) {

		switch (controlType) {
			case 'text':
				$(formControl).val(value);
			break;
			case 'number':
				$(formControl).val(value);
			break;
			case 'textarea':
				$(formControl).val(value);
			break;
			case 'radio':
				$(formControl).val(value);
			break;
			case 'checkbox':
				$(formControl).prop('checked', ( value == 'yes' ? true : false ));
				break;
			case 'select':
				$(formControl).val(value);
				break;
			case 'multiselect':
				if ( value !== null ) {
					$(formControl).val(value);
				}
				break;
		}
		return;
	}


	function getVals(formControl, controlType) {

		switch (controlType) {
			case 'text':
				var value = $(formControl).val();
			break;
			case 'number':
				var value = $(formControl).val();
			break;
			case 'textarea':
				var value = $(formControl).val();
			break;
			case 'radio':
				var value = $(formControl).val();
			break;
			case 'checkbox':
				if ($(formControl).is(":checked")) {
					value = 'yes';
				}
				else {
					value = 'no';
				}
				break;
			case 'select':
				var value = $(formControl).val();
				break;
			case 'multiselect':
				var value = $(formControl).val() || [];
				break;
		}
		return value;
	}


	$(document).on('click', '.prdctfltr_or_add', function() {

		if ( confirm('Add override?') === false ) {
			return false;
		};

		var curr = $(this).closest('p');

		var curr_data = {
			action: 'prdctfltr_or_add',
			curr_tax: curr.attr('class'),
			curr_term: curr.find('.prdctfltr_or_select').val(),
			curr_override: curr.find('.prdctfltr_filter_presets').val()
		};

		if ( curr_data.curr_term == undefined || curr_data.curr_override == 'default' ) {
			alert('Please select both term and filter preset.');
			return;
		}

		$.post(prdctfltr.ajax, curr_data, function(response) {
			if (response) {
				curr.prepend('<span class="prdctfltr_override"><input type="checkbox" class="pf_override_checkbox" /> Term slug : <span class="slug">'+curr.find('.prdctfltr_or_select').val()+'</span> Filter Preset : <span class="preset">'+curr.find('.prdctfltr_filter_presets').val()+'</span> <a href="#" class="button prdctfltr_or_remove">Remove Override</a><span class="clearfix"></span></span>')
				alert('Added');
			} else { 
				alert('Error!');
			}
		});

		return false;
	});

	$(document).on('click', '.prdctfltr_or_remove', function() {

		if ( confirm('Remove override?') === false ) {
			return false;
		};

		var curr = $(this).closest('p');
		var curr_remove = $(this).parent();

		var curr_data = {
			action: 'prdctfltr_or_remove',
			curr_tax: curr.attr('class'),
			curr_term: curr_remove.find('.slug').text()
		};

		$.post(prdctfltr.ajax, curr_data, function(response) {
			if (response) {
				curr_remove.remove();
				alert('Removed');
			} else {
				alert('Error!');
			}
		});

		return false;

	});

	$(document).on('click', '#prdctfltr_save', function() {

		var curr_saving = {};

		var inputs = $('input[name^=wc_settings_prdctfltr], select[name^=wc_settings_prdctfltr], textarea[name^=wc_settings_prdctfltr]'), tmp;
		$.each(inputs, function(i, obj) {
			var tag = ( $(obj).prop('tagName') == 'INPUT' ? $(obj).attr('type') : $(obj).prop('tagName').toLowerCase() );
			curr_saving[$(obj).attr('name').replace('[]', '')] = getVals($(obj), tag);
		});

		if ( $('.pf_element.adv').length > 0 ) {
			var i = 0;

			curr_saving['wc_settings_prdctfltr_advanced_filters'] = {};
			curr_saving['wc_settings_prdctfltr_advanced_filters']['pfa_title'] = [];
			curr_saving['wc_settings_prdctfltr_advanced_filters']['pfa_taxonomy'] = [];
			curr_saving['wc_settings_prdctfltr_advanced_filters']['pfa_include'] = [];
			curr_saving['wc_settings_prdctfltr_advanced_filters']['pfa_order'] = [];
			curr_saving['wc_settings_prdctfltr_advanced_filters']['pfa_orderby'] = [];
			curr_saving['wc_settings_prdctfltr_advanced_filters']['pfa_multiselect'] = [];
			curr_saving['wc_settings_prdctfltr_advanced_filters']['pfa_relation'] = [];
			curr_saving['wc_settings_prdctfltr_advanced_filters']['pfa_adoptive'] = [];
			curr_saving['wc_settings_prdctfltr_advanced_filters']['pfa_none'] = [];

			$('.pf_element.adv').each( function() {
				var curr_el = $(this);
				curr_saving['wc_settings_prdctfltr_advanced_filters']['pfa_title'][i] = curr_el.find('input[name^="pfa_title"]').val();
				curr_saving['wc_settings_prdctfltr_advanced_filters']['pfa_taxonomy'][i] = curr_el.find('select[name^="pfa_taxonomy"] option:selected').val();
				curr_saving['wc_settings_prdctfltr_advanced_filters']['pfa_include'][i] = curr_el.find('select[name^="pfa_include"]').val();
				curr_saving['wc_settings_prdctfltr_advanced_filters']['pfa_order'][i] = curr_el.find('select[name^="pfa _order"]').val();
				curr_saving['wc_settings_prdctfltr_advanced_filters']['pfa_orderby'][i] = curr_el.find('select[name^="pfa _orderby"]').val();
				curr_saving['wc_settings_prdctfltr_advanced_filters']['pfa_multiselect'][i] = ( curr_el.find('input[name^="pfa_multiselect"]:checked').length > 0 ? 'yes' : 'no' );
				curr_saving['wc_settings_prdctfltr_advanced_filters']['pfa_relation'][i] = curr_el.find('input[name^="pfa_relation"]').val();
				curr_saving['wc_settings_prdctfltr_advanced_filters']['pfa_adoptive'][i] = ( curr_el.find('input[name^="pfa_adoptive"]:checked').length > 0 ? 'yes' : 'no' );
				curr_saving['wc_settings_prdctfltr_advanced_filters']['pfa_none'][i] = ( curr_el.find('input[name^="pfa_none"]:checked').length > 0 ? 'yes' : 'no' );
				i++;
			});

		}

		if ( $('.pf_element.rng').length > 0 ) {
			var m = 0;

			curr_saving['wc_settings_prdctfltr_range_filters'] = {};
			curr_saving['wc_settings_prdctfltr_range_filters']['pfr_title'] = [];
			curr_saving['wc_settings_prdctfltr_range_filters']['pfr_taxonomy'] = [];
			curr_saving['wc_settings_prdctfltr_range_filters']['pfr_include'] = [];
			curr_saving['wc_settings_prdctfltr_range_filters']['pfr_order'] = [];
			curr_saving['wc_settings_prdctfltr_range_filters']['pfr_orderby'] = [];
			curr_saving['wc_settings_prdctfltr_range_filters']['pfr_style'] = [];
			curr_saving['wc_settings_prdctfltr_range_filters']['pfr_grid'] = [];

			$('.pf_element.rng').each( function() {
				var curr_el = $(this);
				curr_saving['wc_settings_prdctfltr_range_filters']['pfr_title'][m] = curr_el.find('input[name^="pfr_title"]').val();
				curr_saving['wc_settings_prdctfltr_range_filters']['pfr_taxonomy'][m] = curr_el.find('select[name^="pfr_taxonomy"] option:selected').val();
				curr_saving['wc_settings_prdctfltr_range_filters']['pfr_include'][m] = curr_el.find('select[name^="pfr_include"]').val();
				curr_saving['wc_settings_prdctfltr_range_filters']['pfr_order'][m] = curr_el.find('select[name^="pfr_order"]').val();
				curr_saving['wc_settings_prdctfltr_range_filters']['pfr_orderby'][m] = curr_el.find('select[name^="pfr_orderby"]').val();
				curr_saving['wc_settings_prdctfltr_range_filters']['pfr_style'][m] = curr_el.find('select[name^="pfr_style"] option:selected').val();
				curr_saving['wc_settings_prdctfltr_range_filters']['pfr_grid'][m] = ( curr_el.find('input[name^="pfr_grid"]:checked').length > 0 ? 'yes' : 'no' );
				m++;
			});

		}

		var curr_name = prompt('Enter template name to save it', $('select#prdctfltr_filter_presets option:selected:not([value="default"])').val() );

		if ( curr_name == '' || curr_saving == '' ) {
			alert('Missing name or settings.');
			return false;
		}
		if ( curr_name === null ) {
			return false;
		}

		var curr_data = {
			action: 'prdctfltr_admin_save',
			curr_name: curr_name,
			curr_settings: JSON.stringify(curr_saving)
		};

		$.post(prdctfltr.ajax, curr_data, function(response) {
			if (response) {
				if ( $('select#prdctfltr_filter_presets option[value="'+curr_data.curr_name+'"]').length == 0 ) {
					$('select#prdctfltr_filter_presets').append('<option value="'+curr_data.curr_name+'">'+curr_data.curr_name+'</option>');
				}
				alert('Saved');
			} else {
				alert('Error!');
			}
		});
		
		return false;

	});


	$(document).on('click', '#prdctfltr_load', function() {

		if ( confirm('Load?') === false ) {
			return false;
		};

		var curr_data = {
			action: 'prdctfltr_admin_load',
			curr_name: $('select#prdctfltr_filter_presets option:selected').val()
		};

		if ( curr_data.curr_name == '' || curr_data.curr_name == 'default' ) {
			alert('Not selected.');
			return false;
		}

		$.post(prdctfltr.ajax, curr_data, function(response) {
			if (response) {
				var curr_preset = $.parseJSON(response);

				var inputs = $('input[name^=wc_settings_prdctfltr], select[name^=wc_settings_prdctfltr], textarea[name^=wc_settings_prdctfltr]'), tmp;
				$.each(inputs, function(i, obj) {
					var tag = ( $(obj).prop('tagName') == 'INPUT' ? $(obj).attr('type') : $(obj).prop('tagName').toLowerCase() );
					if ( tag == 'select' && $(obj).prop('multiple') == true ) {
						tag = 'multiselect';
					}

					var curr_val = curr_preset[$(obj).attr('name').replace('[]','')];
					
					if ( curr_val === undefined || curr_val === null ) {
						if ( tag == 'select' && $(obj).prop('multiple') == false ) {
							$(obj).find('option:first-child').attr('selected', 'selected').trigger('change');
						}
					}
					else {
						makeVals($(obj), tag, curr_val);
					}
				});

				if ( curr_preset['wc_settings_prdctfltr_active_filters'] !== undefined ) {
					var curr_el = $('.prdctfltr_customizer');
					var curr_flds = $('.prdctfltr_customizer_fields');

					curr_el.empty();
					curr_flds.find('a.prdctfltr_c_add_filter:not(.pf_advanced)').removeClass('pf_active');
					curr_flds.find('a.prdctfltr_c_add_filter:not(.pf_advanced) i').removeAttr('class').addClass('prdctfltr-eye-disabled');

					$('#wc_settings_prdctfltr_active_filters').empty();

					var curr=0,zurr=0;
					$.each(curr_preset['wc_settings_prdctfltr_active_filters'], function(index, pf_filter) {
						$('#wc_settings_prdctfltr_active_filters').append('<option value="'+pf_filter+'" selected="selected">'+pf_filter+'</option>');

						if ( pf_filter == 'advanced' ) {

							curr_el.append('<span class="pf_element adv" data-filter="'+pf_filter+'" data-id="'+curr+'"><span>Advanced Filter</span><a href="#" class="prdctfltr_c_delete"><i class="prdctfltr-delete"></i></a><a href="#" class="prdctfltr_c_move"><i class="prdctfltr-move"></i></a><span class="pf_options_holder"></span></span>');

							var curr_data = {
								action: 'prdctfltr_c_fields',
								pfa_title: curr_preset['wc_settings_prdctfltr_advanced_filters']['pfa_title'] !== undefined ? curr_preset['wc_settings_prdctfltr_advanced_filters']['pfa_title'][curr] : '',
								pfa_taxonomy: curr_preset['wc_settings_prdctfltr_advanced_filters']['pfa_taxonomy'] !== undefined ? curr_preset['wc_settings_prdctfltr_advanced_filters']['pfa_taxonomy'][curr] : '',
								pfa_include: curr_preset['wc_settings_prdctfltr_advanced_filters']['pfa_include'] !== undefined ? curr_preset['wc_settings_prdctfltr_advanced_filters']['pfa_include'][curr] : [],
								pfa_order: curr_preset['wc_settings_prdctfltr_advanced_filters']['pfa_order'] !== undefined ? curr_preset['wc_settings_prdctfltr_advanced_filters']['pfa_order'][curr] : '',
								pfa_orderby: curr_preset['wc_settings_prdctfltr_advanced_filters']['pfa_orderby'] !== undefined ? curr_preset['wc_settings_prdctfltr_advanced_filters']['pfa_orderby'][curr] : 'DESC',
								pfa_multiselect: curr_preset['wc_settings_prdctfltr_advanced_filters']['pfa_multiselect'] !== undefined ? curr_preset['wc_settings_prdctfltr_advanced_filters']['pfa_multiselect'][curr] : 'no',
								pfa_relation: curr_preset['wc_settings_prdctfltr_advanced_filters']['pfa_relation'] !== undefined ? curr_preset['wc_settings_prdctfltr_advanced_filters']['pfa_relation'][curr] : 'and',
								pfa_adoptive: curr_preset['wc_settings_prdctfltr_advanced_filters']['pfa_adoptive'] !== undefined ? curr_preset['wc_settings_prdctfltr_advanced_filters']['pfa_adoptive'][curr] : 'no',
								pfa_none: curr_preset['wc_settings_prdctfltr_advanced_filters']['pfa_none'] !== undefined ? curr_preset['wc_settings_prdctfltr_advanced_filters']['pfa_none'][curr] : 'no',
								pf_id: curr
							};

							$.post(prdctfltr.ajax, curr_data, function(response) {
								if (response) {
									response = response.split('%SPLIT%');
									curr_el.find('.pf_element.adv[data-id="'+response[0]+'"]').find('.pf_options_holder').append(response[1]);
								} else {
									alert('Error!');
								}
							});
							curr++;

						}
						else if ( pf_filter == 'range' ) {

							curr_el.append('<span class="pf_element rng" data-filter="'+pf_filter+'" data-id="'+zurr+'"><span>Range Filter</span><a href="#" class="prdctfltr_c_delete"><i class="prdctfltr-delete"></i></a><a href="#" class="prdctfltr_c_move"><i class="prdctfltr-move"></i></a><span class="pf_options_holder"></span></span>');

							var curr_data = {
								action: 'prdctfltr_r_fields',
								pfr_title: curr_preset['wc_settings_prdctfltr_range_filters']['pfr_title'] !== undefined ? curr_preset['wc_settings_prdctfltr_range_filters']['pfr_title'][zurr] : '',
								pfr_taxonomy: curr_preset['wc_settings_prdctfltr_range_filters']['pfr_taxonomy'] !== undefined ? curr_preset['wc_settings_prdctfltr_range_filters']['pfr_taxonomy'][zurr] : '',
								pfr_include: curr_preset['wc_settings_prdctfltr_range_filters']['pfr_include'] !== undefined ? curr_preset['wc_settings_prdctfltr_range_filters']['pfr_include'][zurr] : [],
								pfr_order: curr_preset['wc_settings_prdctfltr_range_filters']['pfr_order'] !== undefined ? curr_preset['wc_settings_prdctfltr_range_filters']['pfr_order'][zurr] : '',
								pfr_orderby: curr_preset['wc_settings_prdctfltr_range_filters']['pfr_orderby'] !== undefined ? curr_preset['wc_settings_prdctfltr_range_filters']['pfr_orderby'][zurr] : 'DESC',
								pfr_style: curr_preset['wc_settings_prdctfltr_range_filters']['pfr_style'] !== undefined ? curr_preset['wc_settings_prdctfltr_range_filters']['pfr_style'][zurr] : 'flat',
								pfr_grid: curr_preset['wc_settings_prdctfltr_range_filters']['pfr_grid'] !== undefined ? curr_preset['wc_settings_prdctfltr_range_filters']['pfr_grid'][zurr] : 'no',
								pf_id: zurr
							};

							$.post(prdctfltr.ajax, curr_data, function(response) {
								if (response) {
									response = response.split('%SPLIT%');
									curr_el.find('.pf_element.rng[data-id="'+response[0]+'"]').find('.pf_options_holder').append(response[1]);
								} else {
									alert('Error!');
								}
							});
							zurr++;
						}
						else {
							curr_flds.find('a.prdctfltr_c_add_filter[data-filter="'+pf_filter+'"]').addClass('pf_active');
							curr_flds.find('a.prdctfltr_c_add_filter[data-filter="'+pf_filter+'"] i').removeAttr('class').addClass('prdctfltr-eye');
							curr_el.append('<span class="pf_element" data-filter="'+pf_filter+'"><span>'+curr_flds.find('a.prdctfltr_c_add_filter[data-filter="'+pf_filter+'"] span').text()+'</span><a href="#" class="prdctfltr_c_delete"><i class="prdctfltr-delete"></i></a><a href="#" class="prdctfltr_c_move"><i class="prdctfltr-move"></i></a></span>');
						}
						
					});
					
				}

				alert('Loaded');
			} else {
				alert('Error!');
			}
		});

		return false;

	});
	$(document).on('click', '#prdctfltr_delete', function() {

		if ( confirm('Delete?') === false ) {
			return false;
		};

		var curr_data = {
			action: 'prdctfltr_admin_delete',
			curr_name: $('select#prdctfltr_filter_presets option:selected').val()
		};

		if ( curr_data.curr_name == '' ) {
			alert('Not selected.');
			return false;
		}

		$.post(prdctfltr.ajax, curr_data, function(response) {
			if (response) {
				$('select#prdctfltr_filter_presets option[value="'+curr_data.curr_name+'"]').remove();
				alert('Deleted');
			} else {
				alert('Error!');
			}
		});

		return false;

	});
	
	
	$('#wc_settings_prdctfltr_selected, #wc_settings_prdctfltr_attributes').closest('tr').hide();



	$(document).on('click', '.prdctfltr_c_add.pf_range', function() {

		var curr_el = $(this).parent().next();
		var curr = curr_el.find('.pf_element.rng').length;

		var curr_data = {
			action: 'prdctfltr_r_fields',
			pf_id: curr
		};

		$.post(prdctfltr.ajax, curr_data, function(response) {
			if (response) {
				response = response.split('%SPLIT%');

				var adv_ui = '<span class="pf_element rng" data-filter="range" data-id="'+response[0]+'"><span>Range Filter</span><a href="#" class="prdctfltr_c_delete"><i class="prdctfltr-delete"></i></a><a href="#" class="prdctfltr_c_move"><i class="prdctfltr-move"></i></a><span class="pf_options_holder">'+response[1]+'</span></span>';
				$('#wc_settings_prdctfltr_active_filters').append('<option value="range" selected="selected">Range Filter</option>');

				var curr_append = curr_el.append(adv_ui);

			} else { 
				alert('Error!');
			}
		});

		return false;
	});

	$(document).on('change', '.prdctfltr_rng_select', function() {

		var curr_el = $(this).parent();
		var curr = curr_el.closest('.rng').attr('data-id');
		var curr_selected = $(this).find('option:selected').attr('value');

		var curr_data = {
			action: 'prdctfltr_r_terms',
			taxonomy: curr_selected
		};

		$.post(prdctfltr.ajax, curr_data, function(response) {
			if (response) {
				response = response.replace(/\%%/g, curr);
				curr_el.next().replaceWith(response);
				if ( curr_selected == 'price' ) {
					curr_el.next().next().find('select').prop( 'disabled', true );
					curr_el.next().next().next().find('select').prop( 'disabled', true );
				}
				else {
					curr_el.next().next().find('select').prop( 'disabled', false );
					curr_el.next().next().next().find('select').prop( 'disabled', false );
				}
			} else {
				alert('Error!');
			}
		});

	});

	if ( $('#prdctfltr_save_default').length > 0 ) {
		$('input[name="save"]').before( '<a href="#" id="prdctfltr_save_bottom" class="button-primary">' + $('#prdctfltr_save').text() + '</a>' );
		$('input[name="save"]').val( $('#prdctfltr_save_default').text() );

		$('#prdctfltr_save_bottom').on( 'click', function() {
			$('#prdctfltr_save').trigger('click');
			return false;
		});
	}
	if ( $('.prdctfltr_or_select').length > 0 ) {
		$('input[name="save"]').hide();
	}

	$(document).on('click', '.prdctfltr_or_remove_selected', function() {
		var curr = $(this).closest('p');

		curr.find('input.pf_override_checkbox:checked').each( function() {
			$(this).parent().find('.prdctfltr_or_remove').click();
		});

		return false;
	});

	$(document).on('click', '.prdctfltr_or_remove_all', function() {
		var curr = $(this).closest('p');

		curr.find('.prdctfltr_or_remove').click();

		return false;
	});

	$(document).on('click', '#prdctfltr_save_default', function() {
		var curr = $(this).closest('form');

		curr.submit();

		return false;
	});

	$(document).on('click', '#prdctfltr_reset_default', function() {
		window.location.href = window.location.href;

		return false;
	});

})(jQuery);