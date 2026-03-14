function send_request(varlist = null)
{
	let params = {
		is_ajax: 1,
	};

	if (varlist) {
		params = Object.assign(params, varlist);
	}

	$.ajax({
		url: location.href,
	    cache: false,
	    dataType : "html",
	    timeout: 15000,
	    data: params,
	    success: function (data, textStatus) 
		{	
			$("#smeta_details_wrapper").html(data);
			$("#smeta2_table_main_wrapper").html(data);
			$('#smeta2_wrapper .smeta2-switch-link').removeClass("loading");
			smeta_sites_calculate();
		},
		error: function (data, textStatus) {
		   	console.log(data);
		   	$("#smeta_details_wrapper").replaceWith(data);
		},
	    beforeSend: function(){
	    	$("#smeta_details_wrapper").addClass("loading");
    	},
	   	complete: function(){
	    	$("#smeta_details_wrapper").removeClass("loading");
	   	},
		type : "POST"
	});
}

function send_fixed_manufacturer_value(material_id, value)
{
	send_request({
		fix_manufacturer_id: material_id,
		fix_value: value,
	});
}

function toggle_group_request(id, value = 0) 
{
	send_request({
		smeta_group_id: id,
		gid: value,
	});
}

function set_smeta_param(id, value) 
{
	send_request({
		[id]: value,
	});
}

function check_smeta_print() 
{
	var smeta_exists = parseInt($("#common_navi").attr("data-smeta"));
	if (smeta_exists < 1)
	{
		$("#smeta_print_hint").addClass("slideUp");
		return false;
	}
	else
	{
		return true;
	}
}

function close_smeta_print_hint() 
{
	$("#smeta_print_hint").removeClass("slideUp");
}

function go_to_workers_stats() 
{
	_height = $("#workers_stats").offset().top - 45;	
	$('html, body').animate({scrollTop: _height + 'px'}, 300);	
}

function go_to_workers_norise() 
{
	_height = $("#workers_norise").offset().top - 45;	
	$('html, body').animate({scrollTop: _height + 'px'}, 800);	
}

function go_to_workers_price() 
{
	_height = $("#workers_price").offset().top - 45;	
	$('html, body').animate({scrollTop: _height + 'px'}, 800);	
}

function go_to_workers_contacts() 
{
	_height = $("#workers_contacts").offset().top - 45;	
	$('html, body').animate({scrollTop: _height + 'px'}, 800);	
}

function calculate_smeta_item() 
{
	var item = $("#start_calculate_groups .start_calculate_item.hidden").eq(0);
	if (!item.hasClass("hidden")) 
	{
		$("#start_calculate_groups").addClass("hidden");
		$("#start_calculate_total").addClass("loading");
		setTimeout(function(){
			window.location.href = "/smeta/details/";
		}, 1500);
		return false;
	}
	
	var parent = item.parent(".start_calculate_group");
	if (!parent.hasClass("slideUp"))
	{
		$("#start_calculate_groups .start_calculate_group").removeClass("slideUp");
		parent.addClass("slideUp");
		var value = parent.attr("data-value");
		var min_value = parent.attr("data-minvalue");
		var old_value = $("#start_calculate_total_firms b").text();
		var old_min_value = $("#start_calculate_total_brigades b").text();
		smeta_numeric_animation(old_value, value, $("#start_calculate_total_firms b"));
		smeta_numeric_animation(old_min_value, min_value, $("#start_calculate_total_brigades b"));
	}
	
	item.removeClass("hidden");
	setTimeout(function(){
		item.addClass("finished");
		calculate_smeta_item();
	}, 200);
}

function smeta_numeric_animation(old_value, new_value, wrapper)
{
	old_value = parseInt(old_value);
	new_value = parseInt(new_value) + old_value;

	if (old_value == new_value || !wrapper.length) return false;
	
	$({numberValue: old_value}).animate({numberValue: new_value}, {
		duration: 300,
		easing: 'linear',
		step: function () { 
			wrapper.text(Math.ceil(this.numberValue)); 
		},
		done: function () {
			wrapper.text(Math.ceil(this.numberValue));
		}
	});
}

smeta_sites_timer1 = false;
smeta_sites_timer2 = false;
smeta_sites_timer3 = false;
function smeta_sites_calculate()
{
	if ($("#smeta_details_sites .smeta_details_site").length)
	{
		var time = parseInt($("#smeta_details_sites .smeta_details_site").eq(0).attr("data-timeout"));
		window.clearTimeout(smeta_sites_timer1);
		smeta_sites_timer1 = setTimeout(function(){
			$("#smeta_details_sites .smeta_details_site").eq(0).addClass("ready");
		}, time);
		
		var time = parseInt($("#smeta_details_sites .smeta_details_site").eq(1).attr("data-timeout"));
		window.clearTimeout(smeta_sites_timer2);
		smeta_sites_timer2 = setTimeout(function(){
			$("#smeta_details_sites .smeta_details_site").eq(1).addClass("ready");
		}, time);
			
		var time = parseInt($("#smeta_details_sites .smeta_details_site").eq(2).attr("data-timeout"));
		window.clearTimeout(smeta_sites_timer3);
		smeta_sites_timer3 = window.setTimeout(function(){
			$("#smeta_details_sites .smeta_details_site").eq(2).addClass("ready");
		}, time);
	}
}

function validate_workers_intro_form() 
{
	var id = 'workers_intro_form';
	var container = $("#workers_intro_form");
	var validation_trigger = true;
	$("#"+id+" .required select, #"+id+" .required input, #"+id+" .required textarea").each(function(){
		
		if (!$(this).val() || $(this).val() == '0') 
		{
			validation_trigger = false;
		}
		
	});
	
	if (!$("#project_type_1").val() && !$("#project_type_2").val()) validation_trigger = false;
	
	if (!validation_trigger)
	{
		container.find(".pre_form_common_error").addClass('expandUp');
	}
	
	return validation_trigger;
}

$(function() {
	
	$(document).on('click', '.weight_edit', function(e) {
		$(".weight_wrapper").removeClass("active");
		$(this).closest(".weight_wrapper").addClass("active");
		var field = $(this).closest(".weight_wrapper").siblings(".weight_input").find("input");
		field.focus();
		field.select();
	});
	
	$(document).on('click', '#workers_checkbox_wrapper .workers_checkbox', function(e) {
		$("#workers_checkbox_wrapper .workers_checkbox").removeClass("active");
		$("#workers_checkbox_wrapper .workers_checkbox input").val("");
		$(this).children("input").val("1");
		$(this).addClass("active");
	});

	$(document).on('blur', '.weight_input input', function(e) {
		if ($(".weight_wrapper").hasClass('active')) {
			$(this).val($(this).attr("data-value"));
			$(".weight_wrapper").removeClass("active");
			$(this).closest(".weight").children(".weight_wrapper").removeClass("active");
		}
	});

	$(document).on('keyup', '.weight_input input', function(e) {
		if (e.key === 'Escape') {
			$(this).val($(this).attr("data-value"));
			$(".weight_wrapper").removeClass("active");
			$(this).closest(".weight").children(".weight_wrapper").removeClass("active");
		}
	});

	$(document).on('mousedown', '.weight_input .weight_save', function(e) {
		e.preventDefault();
	});

	$(document).on('click', '.weight_input .weight_save', function(e) {
		let input = $(this).closest('.weight_input').find('input');
		save_custom_weight(input);
	});

	$(document).on('click', '.smeta2_add_element', function(e) {
		if ($(this).next(".smeta2_add_element_alert").length) {
			$(this).next(".smeta2_add_element_alert").addClass("slideDown");
		} else {
			$(this).parents("tr").next("tr.smeta2_element_form").toggleClass("active");
			$(this).prev(".smeta2_add_element_comment").toggleClass("slideDown");
		}
	});

	$(document).on('click', '.smeta2_element_form a.mce-button', function(e) {
		if ($(this).hasClass("loading")) return false;
		$(this).addClass("loading");
	});

	$(document).on('keyup', '.weight_input input', function(e) {
		if (e.keyCode === 13) {
			save_custom_weight($(this));
		}
	});

	function save_custom_weight(input) {

		$(".weight_wrapper").removeClass("active");

		var value = input.val();
		var id = input.attr("data-id");
		var old_value = input.attr("data-value");
		var type = input.attr("data-type");

		if (value != old_value) {

			input.closest('td').find('.weight_wrapper .smeta2_icon.loading').show();
			input.closest('td').find('.weight_wrapper .weight_edit').hide();
			input.closest('td').find('.price_weight').text(value);

			// input.val(input.attr("data-value"));
			if (type === 'material') {
				send_request({
					fix_material_id: id,
					fix_value: value,
				});
			} else if (type === 'prices_objects') {
				send_request({
					action: 'set_price_object',
					fix_prices_objects_id: id,
					fix_value: value,
					object_id: $("body").attr("data-object-id"),
				});
			} else {
				send_request({
					action: 'set_smeta_fix',
					fix_id: id,
					fix_value: value,
					value_original: input.attr("data-value_original"),
					is_domain_price: input.closest('tr').hasClass('price_domain') ? 1 : 0,
				});
			}
		}

		input.closest(".weight").children(".weight_wrapper").removeClass("active");
	}
	
	$(document).on('change', '#smeta_details_wrapper select.material_manufacturer', function(e) {
		$("#smeta_details_wrapper .weight_wrapper").removeClass("active");
		var value = $(this).prop('selectedIndex') > -1 ? $(this).val() : '';
		var material_id = $(this).attr("data-materialId");
		var old_value = $(this).attr("data-value");
		if (value != old_value) {
			send_fixed_manufacturer_value(material_id, value);
		}

		$(this).parents(".weight").children(".weight_wrapper").removeClass("active");
	});

	$(document).on('change', '.select_floors', function(e) {
		update_smeta();
	});

	function update_smeta() {

		let floors = {};
		let floors2 = {};
		$('.select_floors').each(function(index, element) {
			let floor_id = $(element).data('floor_id');
			let plan_id = $(element).val();
			floors[floor_id] = plan_id;
			floors2['flr_' + floor_id] = plan_id;
		});

		if ($('.smeta2-switch-link.split-rooms.active').length) {
			floors['rooms'] = 'on';
			floors2['rooms'] = 'on';
		}

		if ($('.smeta2-switch-link.hide-empty.active').length) {
			floors['hide'] = 'on';
			floors2['hide'] = 'on';
		}

		if ($('.smeta2-switch-link.show-hidden-price.active').length) {
			floors['show_price'] = 'on';
			floors2['show_price'] = 'on';
		}

		if ($('.smeta2-switch-link.split-rooms').length) {
			floors['new'] = '1';
			floors2['new'] = '1';
		}

		$('.smeta2_options_item.print').attr('href', '?print&' + jQuery.param(floors2));
		$('.smeta2_options_item.xls').attr('href', '?excel&' + jQuery.param(floors2));
		window.history.pushState({}, '', window.location.pathname + '?' + jQuery.param(floors2));
		send_request({floors: floors});
		try {
			let action_excel = $('#smeta_xls form').attr('action').split('?');
			$('#smeta_xls form').attr('action', action_excel[0] + '?' + jQuery.param(floors2));
		} catch (e) { }
	}
	
	if ($("#smeta_details_sites").length)
	{
		smeta_sites_calculate();
	}
	
	if ($("#start_calculate_groups").length)
	{
		var total_count = $("#start_calculate_groups .start_calculate_item").length * 200;
		calculate_smeta_item();
		$("#start_calculate_meter .meter span").animate({
			width: "100%",
		}, total_count, function() {
			
		});	
	}
	
	if ($("#start_smeta_meter").length)
	{
		$("#start_smeta_meter_title").addClass("expandUp");
		$("#start_smeta_meter .meter span").animate({
			width: "75%",
		}, 1500, function() {
			$("#start_smeta_meter_title").removeClass("expandUp");
			setTimeout(function(){
				
				if ($("#start_smeta_meter").hasClass("no_data"))
				{
					setTimeout(function(){
						$(".start_smeta_error.data").addClass("slideUp");
					}, 500);
				}
				else
				{
					$("#start_smeta_meter_title").addClass("step2");
					$("#start_smeta_meter_title").addClass("expandUp");
					$("#start_smeta_meter .meter span").animate({
						width: "100%",
					}, 800, function() {
						if ($("#start_smeta_meter").hasClass("not_saved"))
						{
							setTimeout(function(){
								$(".start_smeta_error.save").addClass("slideUp");
							}, 500);	
						}
						else
						{
							setTimeout(function(){
								$("#start_smeta_meter").remove();
								$("#start_smeta_title").addClass("step2");
								if ($("#start_smeta_form").length)
								{
									$("#start_smeta_form").addClass("slideUp");
								}
								else
								{
									window.location.replace("/planner/smeta/calculate/");
								}
								
							}, 1000);	
						}		
					});
				}
				
			}, 100);			
		});
	}

	$(document).on('click', '#smeta2_wrapper .smeta2-switch-link', function(e) {
		$(this).toggleClass("active");
		$(this).addClass("loading");
		update_smeta();
	});

	function save_price_object(tr) {

		let title = tr.find('input[name="title"]').val().trim();
		let price = tr.find('input[name="price"]').val();
		let weight = tr.find('input[name="weight"]').val();
		let unit_id = tr.find('select[name="unit_id"]').val();
		let group_id = tr.find('select[name="group_id"]').val();
		let object_id = $('body').attr('data-object-id');
		let price_object_id = tr.data('prices_objects_id');

		if (!title || !price || !weight) {
			tr.find('.button-save_price_object').removeClass('loading');
			return;
		}

		send_request({
			action: 'save_price_object',
			title: title,
			price: price,
			weight: weight,
			unit_id: unit_id,
			group_id: group_id,
			object_id: object_id,
			price_object_id: price_object_id,
		});
	}

	$(document).on('click', '.button-save_price_object', function(e) {
		save_price_object($(this).closest('tr[data-group_id]'));
	});
	$(document).on('keyup', 'tr.price_object .smeta2_element_input input', function(e) {
		if (e.keyCode === 13) {
			save_price_object($(this).closest('tr[data-group_id]'));
		}
	});

	$(document).on('click', '.button-delete_price_object', function(e) {

		let tr = $(this).closest('tr[data-prices_objects_id]');

		let id = tr.data('prices_objects_id');
		let object_id = $("body").attr("data-object-id");

		if (!id) {
			return;
		}

		if (!confirm('Вы уверены?')) {
			return;
		}

		send_request({
			action: 'delete_price_object',
			id: id,
			object_id: object_id,
		});
	});

	$(document).on('click', '.button-edit_price_object', function(e) {

		let tr = $(this).closest('tr[data-prices_objects_id]');

		// tr.addClass('smeta2_element_form').addClass('active');
		tr.find('.price_object_td').hide();
		tr.find('.price_object_input').show();
		tr.find('td.price').removeClass('align-right').addClass('align-center').removeClass('price');
	});

	function save_price_domain(tr) {

		if (tr.find('.smeta2_icon.save').hasClass('loading')
			|| tr.find('.smeta2_icon.save').hasClass('is_saved')
		) {
			return;
		}

		tr.find('.smeta2_icon.save').addClass('loading');

		let title = tr.find('input[name="title"]').val().trim();
		let price = tr.find('input[name="price"]').val();
		let position_id = tr.find('input[name="position_id"]').val();
		let unit_id = tr.find('select[name="unit_id"]').val();
		let group_id = tr.find('select[name="group_id"]').val();
		let group_id_old = tr.data('group_id');
		let prices_domain_id = tr.data('prices_domain_id');
		let position_id_old = tr.find('input[name="position_id"]').data('value');

		if (!title || !price) {
			if (!prices_domain_id) {
				tr.find('.button-add_price_domain').removeClass('loading');
			} else {
				tr.find('.smeta2_icon.save').removeClass('loading');
			}
			return;
		}

		$.ajax({
			type : "POST",
			url: location.href,
			data: {
				action: 'save_price_domain',
				title: title,
				price: price,
				position_id: position_id,
				unit_id: unit_id,
				group_id: group_id,
				prices_domain_id: prices_domain_id,
			},
			dataType: "json",
			timeout: 15000,
			success: function (data, textStatus) {
				if (data.status === 'success') {
					if (prices_domain_id && position_id == position_id_old && group_id == group_id_old) {
						tr.find('.smeta2_icon.save').removeClass('loading').addClass('is_saved').attr('data-hint', 'Сохранено');
						if (data.value !== undefined) {
							tr.find('input[name="price"]').val(data.value);
						}
					} else {
						send_request();
					}
				}
			},
		});
	}

	// сохранение пользовательской расценки
	$(document).on('input', 'tr[data-prices_domain_id] .smeta2_element_input input', function(e) {
		$(this).closest('tr').find('.smeta2_icon.save').removeClass('is_saved').attr('data-hint', 'Сохранить');
	});
	$(document).on('click', '.button-add_price_domain', function(e) {
		save_price_domain($(this).closest('tr[data-group_id]'));
	});
	$(document).on('change', 'tr[data-prices_domain_id] .smeta2_element_input input', function(e) {
		save_price_domain($(this).closest('tr'));
	});
	$(document).on('change', 'tr[data-prices_domain_id] .smeta2_element_input select', function(e) {
		$(this).closest('tr').find('.smeta2_icon.save').removeClass('is_saved').attr('data-hint', 'Сохранить');
		save_price_domain($(this).closest('tr'));
	});
	$(document).on('click', 'tr[data-prices_domain_id] .smeta2_icon.save', function(e) {
		save_price_domain($(this).closest('tr'));
	});
	$(document).on('keyup', 'tr.price_domain .smeta2_element_input input', function(e) {
		if (e.keyCode === 13) {
			save_price_domain($(this).closest('tr'));
		}
	});

	$(document).on('click', '.button-delete_price_domain', function(e) {

		let tr = $(this).closest('tr[data-prices_domain_id]');

		let id = tr.data('prices_domain_id');

		if (!id) {
			return;
		}

		if (!confirm('Вы уверены?')) {
			return;
		}

		$.ajax({
			type : "POST",
			url: location.href,
			data: {
				action: 'delete_price_domain',
				id: id,
			},
			dataType: "json",
			timeout: 15000,
			success: function (data, textStatus) {
				if (data.status === 'success') {
					tr.remove();
				}
			},
		});
	});

	$(document).on('click', '.button-set_visible_price', function(e) {

		$("#modal_hint").hide();
		$(this).addClass('loading');

		let tr = $(this).closest('tr');

		let price_id = tr.data('price_id');

		send_request({
			action: 'set_visible_price',
			price_id: price_id,
			value: tr.hasClass('hidden') ? 'show' : 'hide',
		});
	});

	function set_price_domain_base(tr) {

		if (tr.find('.smeta2_icon.save').hasClass('loading')
			|| tr.find('.smeta2_icon.save').hasClass('is_saved')
		) {
			return;
		}

		tr.find('.smeta2_icon.save').addClass('loading');

		let price_id = tr.data('price_id');
		let price = +tr.find('input[name="price"]').val();

		$.ajax({
			type : "POST",
			url: location.href,
			data: {
				action: 'set_price_domain_base',
				price_id: price_id,
				price: price,
			},
			dataType: "json",
			timeout: 15000,
			success: function (data, textStatus) {
				if (data.status === 'success') {
					tr.find('.smeta2_icon.save').removeClass('loading').addClass('is_saved').attr('data-hint', 'Сохранено');
					if (data.value !== undefined) {
						tr.find('input[name="price"]').val(data.value);
					}
					if (data.reload) {
						send_request();
					}
				}
			},
		});
	}

	// изменение базовой расценки
	$(document).on('input', 'tr[data-price_id] .smeta2_element_input input', function(e) {
		$(this).closest('tr').find('.smeta2_icon.save').removeClass('is_saved').attr('data-hint', 'Сохранить');
	});
	$(document).on('change', 'tr[data-price_id] .smeta2_element_input input', function(e) {
		set_price_domain_base($(this).closest('tr'));
	});
	$(document).on('click', 'tr[data-price_id] .smeta2_icon.save', function(e) {
		set_price_domain_base($(this).closest('tr'));
	});
	$(document).on('keyup', 'tr[data-price_id] .smeta2_element_input input', function(e) {
		if (e.keyCode === 13) {
			set_price_domain_base($(this).closest('tr'));
		}
	});
});

