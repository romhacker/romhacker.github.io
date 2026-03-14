$(function() {
	
	$(document).on('click', '#budget_calculator_navi .budget_calculator_navi_item', function(e) {
		$("#budget_calculator_navi .budget_calculator_navi_item").removeClass("active");
		$(this).addClass("active");
	});
	
	var menu_hover_timeout = null;
	$("#budget_calculator_menu_wrapper").mouseenter(function(e) {
		clearTimeout(menu_hover_timeout);
		var this_menu = $(this);
		menu_hover_timeout = setTimeout(function() {
			var width = parseInt($("#budget_calculator_menu").outerWidth());
			this_menu.css("width", width+"px");
			this_menu.addClass("active");
		}, 300);
	}).mouseleave(function(e) {
		clearTimeout(menu_hover_timeout);
		menu_hover_timeout = setTimeout(function() {
			$("#budget_calculator_menu_wrapper").css("width", "40px");
			$("#budget_calculator_menu_wrapper").removeClass("active");
		}, 500);
	});
	
});
