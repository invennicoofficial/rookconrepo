<div class="col-md-12">
    <div class="form-group pull-right1">
        <label for="office_zip" class="col-sm-4 control-label">Timer:</label>
        <div class="col-sm-8">
            <input type='text' name='timer' id='timer_value' style="width: 50%; float: left;" class='form-control timer' placeholder='0 sec' />&nbsp;&nbsp;
            <button class='btn btn-success start-timer-btn brand-btn mobile-block'>Start</button>
            <button class='btn btn-success resume-timer-btn hidden brand-btn mobile-block'>Resume/End Break</button>
            <button class='btn pause-timer-btn hidden brand-btn mobile-block'>Pause/Break</button>
            <button class='btn stop-timer-btn hidden brand-btn mobile-block'>Stop</button>
        </div>
    </div>
</div>

<script>
	function reloadTimes() {
		$.ajax({
			url: 'meeting_time_tracking.php?agendameetingid='+$('#agendameetingid').val(),
			success: function(response) {
				//destroyInputs();
				$('.tracked_time_div').html(response);
			}
		});
	}
    $(document).ready(function () {
        var agendameetingid = $('#agendameetingid').val();
        var timer_type = $('#timer_type').val();
        var start_time = $(".start_time").val();
        if(start_time != 0 && start_time != undefined) {
            $('.timer').timer({
                seconds: start_time //Specify start time in seconds
            });
            var hasTimer = false;
            if(timer_type == 'Meeting') {
                $('.pause-timer-btn, .stop-timer-btn').removeClass('hidden');
            } else {
                $('.pause-timer-btn').addClass('hidden');
                $('.resume-timer-btn, .stop-timer-btn').removeClass('hidden');
            }
            $('.start-timer-btn').addClass('hidden');
            $('.timer_submit').show();
        }
    });

    (function(){
		var hasTimer = false;
		// Init timer start
		$('.start-timer-btn').on('click', function() {
			hasTimer = true;
			$('.timer').timer({
				editable: true
			});
			$(this).addClass('hidden');
			$('.pause-timer-btn, .stop-timer-btn').removeClass('hidden');
            var agendameetingid = $('#agendameetingid').val();
            var login_contactid = $('#login_contactid').val();
            $.ajax({    //create an ajax request to load_page.php
                type: "GET",
                url: "agenda_ajax.php?fill=startmeetingtimer&agendameetingid="+agendameetingid+"&login_contactid="+login_contactid,
                dataType: "html",   //expect html to be returned
                success: function(response) {
					reloadTimes();
                }
            });
			return false;
		});

		// Init timer resume
		$('.resume-timer-btn').on('click', function() {
			$('.timer').timer('resume');
			$(this).addClass('hidden');
			$('.pause-timer-btn,.stop-timer-btn').removeClass('hidden');
            var agendameetingid = $('#agendameetingid').val();
            var timer_value = $('#timer_value').val();
            var login_contactid = $('#login_contactid').val();
            $.ajax({    //create an ajax request to load_page.php
                type: "GET",
                url: "agenda_ajax.php?fill=resumemeetingtimer&agendameetingid="+agendameetingid+"&timer_value="+timer_value+"&login_contactid="+login_contactid,
                dataType: "html",   //expect html to be returned
                success: function(response) {
                    hasTimer = false;
                    $('.timer').timer('remove');
                    hasTimer = true;
                    $('.timer').timer({
                        seconds:0,
                        editable: true
                    });
					reloadTimes();
                }
            });
			return false;
		});

		// Init timer pause
		$('.pause-timer-btn').on('click', function() {
			$('.timer').timer('pause');
			$(this).addClass('hidden');
			$('.resume-timer-btn,.stop-timer-btn').removeClass('hidden');
            var agendameetingid = $('#agendameetingid').val();
            var timer_value = $('#timer_value').val();
            var login_contactid = $('#login_contactid').val();
            $.ajax({    //create an ajax request to load_page.php
                type: "GET",
                url: "agenda_ajax.php?fill=pausemeetingtimer&agendameetingid="+agendameetingid+"&timer_value="+timer_value+"&login_contactid="+login_contactid,
                dataType: "html",   //expect html to be returned
                success: function(response) {
                    hasTimer = false;
                    $('.timer').timer('remove');
                    hasTimer = true;
                    $('.timer').timer({
                        seconds:0,
                        editable: true
                    });
					reloadTimes();
                }
            });
			return false;
		});

		// Init timer stop
		$('.stop-timer-btn').on('click', stopTimers);

		// Additional focus event for this demo
		$('.timer').on('focus', function() {
			if(hasTimer) {
				$('.pause-timer-btn').addClass('hidden');
				$('.resume-timer-btn').removeClass('hidden');
			}
		});

		// Additional blur event for this demo
		$('.timer').on('blur', function() {
			if(hasTimer) {
				$('.pause-timer-btn').removeClass('hidden');
				$('.resume-timer-btn').addClass('hidden');
			}
		});
	})();
function stopTimers() {
	$('.timer').timer('stop');
	$(this).addClass('hidden');
	$('.pause-timer-btn, .resume-timer-btn').addClass('hidden');
	$('.start-timer-btn').removeClass('hidden');
	var agendameetingid = $('#agendameetingid').val();
	var timer_value = $('#timer_value').val();
	var login_contactid = $('#login_contactid').val();
	hasTimer = false;
	$('.timer').timer('remove');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "agenda_ajax.php?fill=stopmeetingtimer&agendameetingid="+agendameetingid+"&timer_value="+timer_value+"&login_contactid="+login_contactid,
		dataType: "html",   //expect html to be returned
		success: function(response) {
			reloadTimes();
		}
	});
	return false;
}
</script>
