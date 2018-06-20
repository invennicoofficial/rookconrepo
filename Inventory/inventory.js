$(document).ready(function() {
	if($(window).width() > 767) {
		resizeScreen();
		$(window).resize(function() {
			resizeScreen();
		});
	}
});
function resizeScreen() {
	//var height = $(window).height() - $('#inventory_div .scale-to-fill').offset().top - $('#footer').outerHeight();
    $('#inventory_div .scale-to-fill,#inventory_div .scale-to-fill .main-screen,#inventory_div .tile-sidebar, #inventory_div .sidebar').height($('#inventory_div').height() - $('.tile-header').height() + 15);
	if(height < 250) {
		//$('#inventory_div .scale-to-fill .main-screen,#inventory_div .tile-sidebar,#inventory_div .scale-to-fill.tile-content').height(height);
	}
}