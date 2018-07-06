$(document).ready(function() {
	if($(window).width() > 767) {
		resizeScreen();
		$(window).resize(function() {
			resizeScreen();
		});
	}
});
function resizeScreen() {
	var height = $(window).height() - $('#inventory_div .scale-to-fill').offset().top - $('#footer').outerHeight();
	if(height < 250) {
		$('#inventory_div .scale-to-fill .main-screen,#inventory_div .tile-sidebar .sidebar,#inventory_div .scale-to-fill.tile-content').height(height);
	}
}