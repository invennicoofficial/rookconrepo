$(document).ready(function() {
	if($('[name="timesheet_time_format"]').val() != undefined && $('[name="timesheet_time_format"]').val() == 'decimal') {
		$('.timesheet_div .timepicker').each(function() {
			$(this).timepicker('destroy');
			$(this).removeClass('timepicker');
		});
	}
});
function calculateHoursByStartEndTimes(input) {
	var block = $(input).closest('tr');
	var start_time = $(block).find('[name="start_time[]"]').val();
	var end_time = $(block).find('[name="end_time[]"]').val();

	if(start_time != '' && start_time != undefined && end_time != '' && end_time != undefined) {
		var start_minutes = 0;

		var start_arr = start_time.split(':');
		var arr_ampm = start_arr[1].split(' ');
		if(arr_ampm[1] != undefined && arr_ampm[1].toLowerCase() == 'pm' && parseInt(start_arr[0]) != 12) {
			start_arr[0] = parseInt(start_arr[0]) + 12;
		}
		start_minutes = (parseInt(start_arr[0]*60) + parseInt(arr_ampm[0]));

		var end_minutes = 0;

		var end_arr = end_time.split(':');
		var arr_ampm = end_arr[1].split(' ');
		if(arr_ampm[1] != undefined && arr_ampm[1].toLowerCase() == 'pm' && parseInt(end_arr[0]) != 12) {
			end_arr[0] = parseInt(end_arr[0]) + 12;
		}
		end_minutes = (parseInt(end_arr[0]*60) + parseInt(arr_ampm[0]));

		var diff_minutes = end_minutes - start_minutes;
		if(diff_minutes > 0) {
			var new_hours = parseInt(diff_minutes / 60);
			var new_minutes = parseInt(diff_minutes % 60);
			new_minutes = new_minutes.toString().length > 1 ? new_minutes : '0'+new_minutes.toString();

			var new_time = new_hours+':'+new_minutes;
			$(block).find('[name="total_hrs[]"]').val(new_time).change();
		}
	}
}