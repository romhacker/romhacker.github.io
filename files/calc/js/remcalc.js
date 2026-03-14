function validate_remcalc_promo () {
	
	var validation_trigger = true;
	$("#remcalc_promo_form select, #remcalc_promo_form input").each(function(){
		
		if (!$(this).val() || $(this).val() == '0') 
		{
			$(this).parent(".remcalc_promo_input").addClass("errored");
			validation_trigger = false;
		}
		
	});
	
	if (validation_trigger)
	{
		$("#remcalc_promo_form").addClass('ready');
		$("#remcalc_promo_form").submit();
	}		
}

function validate_remcalc_form () {
	
	var validation_trigger = true;
	$("#remcalc_request .select_wrapper select, #remcalc_request .select_wrapper input").each(function(){
		
		if (!$(this).val()) 
		{
			$("#remdis_request_error").addClass("active");
			$("#remdis_request_error").addClass("expandUp");
			validation_trigger = false;
		}
		
	});
	
	return validation_trigger;		
}

function validate_visit_form () {
	
	var validation_trigger = true;
	$("#remcalc_visit_form .select_wrapper select, #remcalc_visit_form .select_wrapper input").each(function(){
		
		if (!$(this).val()) 
		{
			$("#remcalc_visit_error").addClass("active");
			$("#remcalc_visit_error").addClass("expandUp");
			validation_trigger = false;
		}
		
	});
	
	return validation_trigger;		
}

function remcalc_go_to_smeta () {
	
	$('.pre_text').animate({scrollTop: 0 + 'px'}, 0);	
	_height = $("#remcalc_request").offset().top - 120;	
	$('.pre_text').animate({scrollTop: _height + 'px'}, 500);	
}

function remcalc_go_to_remdis () {
	
	$('.pre_text').animate({scrollTop: 0 + 'px'}, 0);	
	_height = $("#remcalc_remdiscount").offset().top - 120;	
	$('.pre_text').animate({scrollTop: _height + 'px'}, 500);	
}

function open_objects_group (num) {
	
	var group = 'group'+num;
	$("#remcalc_objects_navi1 span").removeClass("active");
	$("#remcalc_objects_navi1 span."+group).addClass("active");
	$("#remcalc_objects_navi2 .remcalc_objects_navi_group").removeClass("active");
	$("#remcalc_objects_navi2 .remcalc_objects_navi_group."+group).addClass("active");
	$("#remcalc_objects_navi2 .remcalc_objects_navi_group."+group+" span").eq(0).trigger("click");
}

function open_objects_item (num) {
	
	var item = 'item'+num;
	$("#remcalc_objects_navi2 .remcalc_objects_navi_group span").removeClass("active");
	$("#remcalc_objects_navi2 .remcalc_objects_navi_group span."+item).addClass("active");
	$("#remcalc_objects_list .remcalc_object").removeClass("slideLeft");
	$("#remcalc_objects_list .remcalc_object."+item).addClass("slideLeft");
}

function remcalc_open_review (num) {
	
	var item = 'review'+num;
	$("#remcalc_reviews_navi .remcalc_reviews_navi_item").removeClass("active");
	$("#remcalc_reviews_navi .remcalc_reviews_navi_item."+item).addClass("active");
	$("#remcalc_reviews_wrap .remcalc_review").removeClass("slideLeft");
	$("#remcalc_reviews_wrap .remcalc_review."+item).addClass("slideLeft");
}

function remcalc_mini_slider(mtime) 
{	
	var wrapper = $("#remcalc_promo2_adv");
	
	if (wrapper.hasClass("step3"))
	{
		wrapper.removeClass("step3");
	}
	else if(wrapper.hasClass("step2"))
	{
		wrapper.removeClass("step2");
		wrapper.addClass("step3");
	}
	else
	{
		wrapper.addClass("step2");
	}
	
	setTimeout(function(){
		remcalc_mini_slider(mtime);
	}, mtime);
}

$(function() {

	if ($("#remcalc_promo2").length)
	{
		setTimeout(function(){
			$("#remcalc_promo2").addClass("active");
			var mtime = 5000;
			setTimeout(function(){
				remcalc_mini_slider(mtime);
			}, mtime);
		}, 3000);
	}

	if ($("#remcalc_promo").length)
	{
		setTimeout(function(){
			$("#remcalc_promo").addClass("active");
		}, 3000);
	}

	if ($("#goto_remcalc_request").length)
	{
		_height = $("#remcalc_request").offset().top - 120;	
		$('.pre_text').animate({scrollTop: _height + 'px'}, 0);
	}

	if ($("#remcalc_objects_navi").length)
	{
		open_objects_group(1);
	}

	if ($("#remcalc_reviews_navi").length)
	{
		remcalc_open_review(1);
	}

	$("#remcalc_promo_form select, #remcalc_promo_form input").focus(function(){
		$(this).parent(".remcalc_promo_input").removeClass("errored");
	});	
	
	$("#remcalc_promo_form").submit(function(){
		if (!$("#remcalc_promo_form").hasClass('ready'))
		{
			validate_remcalc_promo();
			return false;
		}
	});	
		
});

