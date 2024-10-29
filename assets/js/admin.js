jQuery(document).ready(function($){
	'user strict';

	$('#afads-tabs').tabs();

	$('.woocommerce-help-tip').tipTip({
		activation: 'hover',
		keepAlive: false,
		fadeIn:    50,
		fadeOut:   50,
		delay:     200
	});

	if (jQuery('.wc-shipping-zone-method-rows').length ) {


		setTimeout( function() {

			remove_class_from_edit_link();

		} ,1000 );
		setTimeout( function() {

			remove_class_from_edit_link();

		} ,2000 );
		setTimeout( function() {

			remove_class_from_edit_link();


		} ,3000 );
	}
	jQuery('.afads-tabs').find('.afads-tabs-ul').removeAttr('class');

	var ajaxurl = afads_php_vars.admin_url;
	var nonce   = afads_php_vars.nonce;

	var pricing_product_row  = afads_php_vars.product_row;
	var pricing_category_row = afads_php_vars.category_row;
	var pricing_weight_row   = afads_php_vars.weight_row;
	var pricing_shipping_row = afads_php_vars.shipping_row;
	var condition_new_rule   = afads_php_vars.condition_row;
	var condition_new_group  = afads_php_vars.condition_group

	var all_operators_fields   = afads_condition_var.all_operators_fields;
	var custom_dropdown_fields = afads_condition_var.custom_dropdown_fields;
	var text_field             = afads_condition_var.input_field_text;
	var number_field           = afads_condition_var.input_field_number;

	// change operators
	$(document).on('change', 'select.afads-condition-type-select', function(){
		let select_val = $(this).val();
		$(this).closest('div.afads-condition-wrap').find('.afads-condition-operator-select').children().each(function(){
			if( $.inArray(select_val, all_operators_fields ) > -1 ) {
				$(this).prop('disabled', false);
			} else {
				if($.inArray($(this).val(), ['=', '!=']) < 0 ){
					$(this).prop('disabled', true);
				}
			}
		});
	});

	// change value field
	$(document).on('change', 'select.afads-condition-type-select', function(){

		let select_val   = $(this).val();
		let condition_id = $(this).data('condition_id');
		let group_id     = $(this).data('group_id');


		if( $.inArray(select_val, all_operators_fields) > -1 ) {

			let insert_html = number_field;
			insert_html = insert_html.replace(/{{group_id}}/g, group_id);
			insert_html = insert_html.replace(/{{condition_id}}/g, condition_id);
			$(this).closest('div.afads-condition-wrap').find('.afads-condition-value').html( insert_html );

		} else if( $.inArray(select_val, Object.keys(custom_dropdown_fields) ) > -1 ) {

			let field = custom_dropdown_fields[select_val];

			field = field.replace(/{{group_id}}/g, group_id);
			field = field.replace(/{{condition_id}}/g, condition_id);

			$(this).closest('div.afads-condition-wrap').find('.afads-condition-value').html(field);
			afads_activate_live_search(select_val);

		} else{

			let insert_html = text_field;
			insert_html = insert_html.replace(/{{group_id}}/g, group_id);
			insert_html = insert_html.replace(/{{condition_id}}/g, condition_id);
			$(this).closest('div.afads-condition-wrap').find('.afads-condition-value').html(insert_html);

		}
	});

	// -------------------------------------------------- check all condition when window get load  .
		// console.log( custom_dropdown_fields );

	$('select.afads-condition-type-select').each( function(){
		let select_val = $(this).val();

		$(this).closest('div.afads-condition-wrap').find('.afads-condition-operator-select').children().each(function(){
			if( $.inArray(select_val, all_operators_fields ) > -1 ) {
				$(this).prop('disabled', false);
			} else {

				if($.inArray($(this).val(), ['=', '!=']) < 0 ){
					$(this).prop('disabled', true);
				}
			}
		});

	});
		// ----------------------------------------------.

	$(document).on('click','.afads_add_category_price_rule',function(){
		$(this).closest('.afads-tabs-content').find('table tbody').append(pricing_category_row);
		activate_product_categories_search();
	});

	$(document).on('click','.afads_add_product_price_rule',function(){
		$(this).closest('.afads-tabs-content').find('table tbody').append(pricing_product_row);
		activate_product_search();
	});

	$(document).on('click','.afads_add_weight_price_rule',function(){
		$(this).closest('.afads-tabs-content').find('table tbody').append(pricing_weight_row);
	});

	$(document).on('click','.afads_add_shipping_class_price_rule',function(){
		$(this).closest('.afads-tabs-content').find('table tbody').append(pricing_shipping_row);
	});

	$(document).on('click', 'button.afads-add-group-condition', function(){

		let group_id     		= $(this).data('group_id');
		let condition_id 		= $(this).closest('.div-conditions-group').find('.afads-condition-wrap').length;

		var index  				= condition_id +1;
		var last_index 			= $(this).closest('.div-conditions-group').find('.afads-condition-type-select').last().data('condition_id');

		for (var i = 0; i <= last_index + 1; i++) {
			
			if( $(this).closest('.div-conditions-group').find('select[data-condition_id="'+ i +'"]').length ) {

			}

		}

		var last_condition_id 	= $(this).closest('.div-conditions-group').find('select.afads-condition-type-select').data('condition_id');


		insert_html 		= condition_new_rule.replace(/{{group_id}}/g, group_id);
		insert_html 		= insert_html.replace(/{{condition_id}}/g, condition_id+1);

		$(this).closest('.div-conditions-group').find('.afads-conditions-wrap').append(insert_html);



	});

	$(document).on('click', '.afads-condition-remove span', function(){
		
		$(this).closest('.afads-condition-wrap').remove();
	});

	$(document).on('click', '.button-afads-add-group', function(){
		
		let group_id    = $(this).closest('.afads-shipping-instance-fields').find('.div-conditions-wrapper').length ? $(this).closest('.afads-shipping-instance-fields').find('.div-conditions-wrapper').length : $(this).closest('.afads-metabox-fields').find('.div-conditions-group').length;

		let insert_html = condition_new_group.replace(/{{group_id}}/g, group_id);

		insert_html     = insert_html.replace(/{{group_number}}/g, group_id+1);

		$(this).closest('.afads-metabox-fields').find('.div-conditions-wrapper').append( insert_html );

		$(this).closest('.afads-shipping-instance-fields').find('.div-conditions-wrapper').append( insert_html );

	});

	$(document).on('click', '.afads-pricing-table .dashicons-no-alt', function(){
		$(this).closest('tr').remove();
	});

	$(document).on('click', '.afads-dupliacte-group-btn', function(e){
		e.preventDefault();
		$(this).closest('.div-conditions-group').clone().insertAfter($(this).closest('.div-conditions-group'));
	});

	$(document).on('click', '.afads-remove-group-btn', function(e){
		e.preventDefault();
		$(this).closest('.div-conditions-group').remove();
	});

	activate_product_search();
	activate_product_categories_search();
	afads_activate_users_search();
	activate_product_categories_search();
	activate_product_search_multiple();
	activate_product_categories_search_multiple();
	activate_product_tags_search_multiple();

	function afads_activate_live_search(select_val){
		switch( select_val ) {
		case 'customer':
			afads_activate_users_search();
			return;
		case 'product':
			activate_product_search();
			return;
		case 'category':
			activate_product_categories_search();
			return;
		case 'products':
			activate_product_search_multiple();
			return;
		case 'categories':
			activate_product_categories_search_multiple();
			return;
		case 'tags':
			activate_product_tags_search_multiple();
			return;
		}
	}

	function activate_product_search(){

		$('.afads_product_search').select2({

			ajax: {
				url: ajaxurl, // AJAX URL is predefined in WordPress admin
				dataType: 'json',
				type: 'POST',
				delay: 250, // delay in ms while typing when to perform a AJAX search
				data: function (params) {
					return {
						q: params.term, // search query
						action: 'afads_search_products', // AJAX action for admin-ajax.php
						nonce: nonce // AJAX nonce for admin-ajax.php
					};
				},
				processResults: function( data ) {
					var options = [];
					if ( data ) {

						// data is the array of arrays, and each of them contains ID and the Label of the option
						$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
							options.push( { id: text[0], text: text[1]  } );
						});

					}
					return {
						results: options
					};
				},
				cache: true
			},
			placeholder: 'Choose Products',
			multiple: false,
			// minimumInputLength: 3 // the minimum of symbols to input before perform a search
			
		});
	}

	function activate_product_categories_search(){
		
		$('.afads_category_search').select2({

			ajax: {
				url: ajaxurl, // AJAX URL is predefined in WordPress admin
				dataType: 'json',
				type: 'POST',
				delay: 250, // delay in ms while typing when to perform a AJAX search
				data: function (params) {
					return {
						q: params.term, // search query
						action: 'afads_search_product_categories', // AJAX action for admin-ajax.php
						nonce: nonce // AJAX nonce for admin-ajax.php
					};
				},
				processResults: function( data ) {
					var options = [];
					if ( data ) {

						// data is the array of arrays, and each of them contains ID and the Label of the option
						$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
							options.push( { id: text[0], text: text[1]  } );
						});

					}
					return {
						results: options
					};
				},
				cache: true
			},
			placeholder: 'Choose Category',
			multiple: false,
			// minimumInputLength: 3 // the minimum of symbols to input before perform a search
			
		});
	}

	function activate_product_search_multiple(){

		$('.afads_product_search_multiple').select2({

			ajax: {
				url: ajaxurl, // AJAX URL is predefined in WordPress admin
				dataType: 'json',
				type: 'POST',
				delay: 250, // delay in ms while typing when to perform a AJAX search
				data: function (params) {
					return {
						q: params.term, // search query
						action: 'afads_search_products', // AJAX action for admin-ajax.php
						nonce: nonce // AJAX nonce for admin-ajax.php
					};
				},
				processResults: function( data ) {
					var options = [];
					if ( data ) {

						// data is the array of arrays, and each of them contains ID and the Label of the option
						$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
							options.push( { id: text[0], text: text[1]  } );
						});

					}
					return {
						results: options
					};
				},
				cache: true
			},
			placeholder: 'Choose Products',
			multiple: true,
			// minimumInputLength: 3 // the minimum of symbols to input before perform a search
			
		});
	}

	function activate_product_categories_search_multiple(){
		
		$('.afads_category_search_multiple').select2({

			ajax: {
				url: ajaxurl, // AJAX URL is predefined in WordPress admin
				dataType: 'json',
				type: 'POST',
				delay: 250, // delay in ms while typing when to perform a AJAX search
				data: function (params) {
					return {
						q: params.term, // search query
						action: 'afads_search_product_categories', // AJAX action for admin-ajax.php
						nonce: nonce // AJAX nonce for admin-ajax.php
					};
				},
				processResults: function( data ) {
					var options = [];
					if ( data ) {

						// data is the array of arrays, and each of them contains ID and the Label of the option
						$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
							options.push( { id: text[0], text: text[1]  } );
						});

					}
					return {
						results: options
					};
				},
				cache: true
			},
			placeholder: 'Choose Category',
			multiple: true,
			// minimumInputLength: 3 // the minimum of symbols to input before perform a search
			
		});
	}

	function activate_product_tags_search_multiple(){
		
		$('.afads_tag_search_multiple').select2({

			ajax: {
				url: ajaxurl, // AJAX URL is predefined in WordPress admin
				dataType: 'json',
				type: 'POST',
				delay: 250, // delay in ms while typing when to perform a AJAX search
				data: function (params) {
					return {
						q: params.term, // search query
						action: 'afads_search_product_tags', // AJAX action for admin-ajax.php
						nonce: nonce // AJAX nonce for admin-ajax.php
					};
				},
				processResults: function( data ) {
					var options = [];
					if ( data ) {

						// data is the array of arrays, and each of them contains ID and the Label of the option
						$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
							options.push( { id: text[0], text: text[1]  } );
						});

					}
					return {
						results: options
					};
				},
				cache: true
			},
			placeholder: 'Choose Tag',
			multiple: true,
			// minimumInputLength: 3 // the minimum of symbols to input before perform a search
			
		});
	}

	function afads_activate_users_search(){
		
		$('.afads-customers').select2({

			ajax: {
				url: ajaxurl, // AJAX URL is predefined in WordPress admin
				dataType: 'json',
				type: 'POST',
				delay: 250, // delay in ms while typing when to perform a AJAX search
				data: function (params) {
					return {
						q: params.term, // search query
						action: 'afads_search_users', // AJAX action for admin-ajax.php
						nonce: nonce // AJAX nonce for admin-ajax.php
					};
				},
				processResults: function( data ) {
					var options = [];
					if ( data ) {

						// data is the array of arrays, and each of them contains ID and the Label of the option
						$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
							options.push( { id: text[0], text: text[1]  } );
						});

					}
					return {
						results: options
					};
				},
				cache: true
			},
			placeholder: 'Choose User',
			multiple: false,
			// minimumInputLength: 3 // the minimum of symbols to input before perform a search
			
		});
	}


	function remove_class_from_edit_link() {

		jQuery('a.wc-shipping-zone-method-settings').each( function() {

			if ( jQuery(this).hasClass('wc-shipping-zone-method-settings') ) {

				jQuery(this).removeClass('wc-shipping-zone-method-settings');
			}

		});
	}



});



