<div class="col-md-12">
    <div class="form-group pull-right1">
        <label for="office_zip" class="col-sm-4 control-label">
            <button class='btn btn-success start-timer-btn brand-btn mobile-block' style="height: 150px; width: 400px; font-size: 60px;">Start</button>
            <button class='btn btn-success resume-timer-btn brand-btn mobile-block' style="height: 150px; width: 400px; font-size: 60px;">Resume</button>
            <button class='btn pause-timer-btn brand-btn mobile-block' style="height: 150px; width: 400px; font-size: 60px;">Pause</button>
			<strong class='timer-label'>Time Tracked:</strong>
        </label>
        <div class="col-sm-8">

            <input type='text' name='timer' id='timer_value' style="width: 50%;" class='form-control timer' placeholder='0 sec' value='<?php echo $timer; ?>' />&nbsp;&nbsp;

            <!-- <button class='btn btn-danger remove-timer-btn hidden brand-btn mobile-block'>Remove Timer</button> -->
            <!-- <button type="submit" name="timer_add" value="timer_add" id="timer_submit" class="btn brand-btn pull-right1 timer_submit">Submit</button> -->

        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group pull-right1">
        <label for="office_zip" class="col-sm-4 control-label">Timer Date</label>
        <div class="col-sm-8">

            <input type='text' name='timer_date' id='timer_date' class='form-control datepicker' value='<?php echo $date; ?>' />&nbsp;&nbsp;

            <!-- <button class='btn btn-danger remove-timer-btn hidden brand-btn mobile-block'>Remove Timer</button> -->
            <!-- <button type="submit" name="timer_add" value="timer_add" id="timer_submit" class="btn brand-btn pull-right1 timer_submit">Submit</button> -->

        </div>
    </div>
</div>
<script>
$(document).ready(function () {
	var dayprogramid = $('#dayprogramid').val();
	var time = $('#timer_value').val();
	$('.start-timer-btn').show();
	$('.resume-timer-btn').hide();
	$('.pause-timer-btn').hide();
	$('.complete_timer').hide();
	if(time != 0) {
		if(time.indexOf('#') > -1) {
			$('#timer_value').val(time.replace(/#/g,''));
			complete_timer();
		}
		else {
			$('.start-timer-btn').hide();
			$('.resume-timer-btn').show();
			var arr = time.split(':');
			var seconds = arr[0] * 3600 + arr[1] * 60 + arr[2] * 1;
			$('.timer').timer({
				seconds: seconds
			}).timer('pause');
		}
	}
	$('.start-timer-btn').click(start_timer);
	$('.resume-timer-btn').click(resume_timer);
	$('.pause-timer-btn').click(pause_timer);
});

function start_timer() {
	$('.timer').timer('start');
	$('.start-timer-btn').hide();
	$('.pause-timer-btn').show();
	var programid = $('#dayprogramid').val();
	var login_contactid = $('#login_contactid').val();
	var date = $('#timer_date').val();
	var timer_value = $('#timer_value').val();
	$.ajax({    //create an ajax request to program_ajax.php
		type: "GET",
		url: "program_ajax.php?fill=starttimer&programid="+programid+"&timer_value="+timer_value+"&login_contactid="+login_contactid+"&date="+date,
		dataType: "html",   //expect html to be returned
		success: function(response) {
			$('#timer_date').val(response);
		}
	});
	return false;
}
function resume_timer() {
	$('.timer').timer('resume');
	$('.resume-timer-btn').hide();
	$('.pause-timer-btn').show();
	var programid = $('#dayprogramid').val();
	var login_contactid = $('#login_contactid').val();
	var date = $('#timer_date').val();
	var timer_value = $('#timer_value').val();
	$.ajax({    //create an ajax request to program_ajax.php
		type: "GET",
		url: "program_ajax.php?fill=starttimer&programid="+programid+"&timer_value="+timer_value+"&login_contactid="+login_contactid+"&date="+date,
		dataType: "html",   //expect html to be returned
		success: function(response) {
			$('#timer_date').val(response);
		}
	});
	return false;
}
function pause_timer() {
	$('.timer').timer('pause');
	$('.resume-timer-btn').show();
	$('.pause-timer-btn').hide();
	var programid = $('#dayprogramid').val();
	var login_contactid = $('#login_contactid').val();
	var date = $('#timer_date').val();
	var timer_value = $('#timer_value').val();
	$.ajax({    //create an ajax request to program_ajax.php
		type: "GET",
		url: "program_ajax.php?fill=pausetimer&programid="+programid+"&timer_value="+timer_value+"&login_contactid="+login_contactid+"&date="+date,
		dataType: "html",   //expect html to be returned
		success: function(response) {
			$('#timer_date').val(response);
		}
	});
	return false;
}
function complete_timer() {
	$('.timer').timer('pause');
	$('.start-timer-btn').hide();
	$('.resume-timer-btn').hide();
	$('.pause-timer-btn').hide();
	$('.complete_timer').show();
	$('.complete-timer-btn').text('Completed');
	$('.complete-timer-btn').off().click(function() { return false; });
	var programid = $('#dayprogramid').val();
	var login_contactid = $('#login_contactid').val();
	var date = $('#timer_date').val();
	var timer_value = $('#timer_value').val();
	$.ajax({    //create an ajax request to program_ajax.php
		type: "GET",
		url: "program_ajax.php?fill=endtimer&programid="+programid+"&timer_value="+timer_value+"&login_contactid="+login_contactid+"&date="+date,
		dataType: "html",
		success: function(response) {
		}
	});
	return false;
}
</script>