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
	var start_time = $(block).find('[name="start_time"]').val();
	var end_time = $(block).find('[name="end_time"]').val();

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
            var hours_block = $(block).find('[name=time_cards_id]').filter(function() { return this.value > 0; });
            if(hours_block.length == 0) {
                var hours_block = $(block).find('[name=time_cards_id]');
            }
            hours_block = hours_block.first().closest('td').find('[name^=total_hrs]');
			hours_block.val(new_time);
            saveField(hours_block.get(0));
		}
	}
}

// Update Time Sheet Fields
function saveFieldMethod(field) {
    var line = $(field).closest('tr');
    if(field.name == 'driving_time') {
        doneSaving();
        return;
    } else if(field.name == 'total_hrs') {
        var blocks = $(field).closest('td');
    } else if(field.name == 'start_time' || field.name == 'end_time' || field.name == 'ticketid' || field.name == 'type_of_time' || field.name == 'comment_box') {
        if(line.find('[name=time_cards_id]').filter(function() { return this.value > 0; }).length > 0) {
            var blocks = line.find('[name=time_cards_id]').filter(function() { return this.value > 0; }).first().closest('td');
        } else {
            var blocks = line.find('[name=time_cards_id]').first().closest('td');
        }
    } else {
        var blocks = [];
        line.find('[name=time_cards_id]').filter(function() { return this.value > 0; }).each(function() {
            blocks.push($(this).closest('td'));
        });
    }
    var saveValue = field.value;
    if(field.type == 'checkbox' && field.checked == false) {
        if($(field).data('uncheck') != undefined) {
            saveValue = $(field).data('uncheck');
        } else {
            saveValue = '';
        }
    }
    var block_length = blocks.length;
    $(blocks).each(function() {
        var block = $(this);
        $.post('time_cards_ajax.php?action=update_time', {
            field: field.name,
            value: saveValue,
            type_of_time: block.find('[name=type_of_time]').val(),
            id: block.find('[name=time_cards_id]').val(),
            date: line.find('[name=date]').val(),
            staff: line.find('[name=staff]').val(),
            siteid: line.find('[name=siteid]').val(),
            projectid: line.find('[name=projectid]').val(),
            clientid: line.find('[name=clientid]').val(),
            ticketid: line.find('[name=ticketid]').val(),
            ticketattachedid: block.find('[name=ticketattachedid]').val(),
            page: $('[name=current_page]').val()
        }, function(response) {
            if(response > 0) {
                block.find('[name=time_cards_id]').val(response);
            } else if(response != '') {
                console.log(response);
            }
            block_length--;
            if(block_length == 0) {
                doneSaving();
            }
        });
    });
    if(block_length == 0) {
        doneSaving();
    }
}