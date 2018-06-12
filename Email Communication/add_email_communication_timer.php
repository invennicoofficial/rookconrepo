<?php $start_time = 0;
$timer_type = '';
if($timer_row = mysqli_fetch_array(mysqli_query($dbc, "SELECT start_timer_time, timer_type FROM email_communication_timer WHERE communication_id='$email_communicationid' AND created_by='".$_SESSION['contactid']."' AND created_date='".date('Y-m-d')."' ORDER BY commtimerid DESC LIMIT 1"))) {
	if($timer_row['start_timer_time'] > 0) {
		$start_time = time() - $timer_row['start_timer_time'];
		$timer_type = $timer_row['timer_type'];
	}
}
?>
<div class="col-md-12">
    <div class="form-group pull-right1">
        <label for="office_zip" class="col-sm-4 control-label">Timer:</label>
        <div class="col-sm-8">
            <input type='text' name='timer' id='timer_value' style="width: 50%; float: left;" class='form-control timer' placeholder='0 sec' />&nbsp;&nbsp;
			<input type="hidden" name="start_time" value="<?php echo $start_time; ?>" class="start_time">
			<input type="hidden" id="timer_type" value="<?php echo $timer_type; ?>">
            <button class='btn btn-success start-timer-btn brand-btn mobile-block'>Start</button>
            <button class='btn btn-success resume-timer-btn hidden brand-btn mobile-block'>Resume/End Break</button>
            <button class='btn pause-timer-btn hidden brand-btn mobile-block'>Pause/Break</button>
            <!-- <button class='btn btn-danger remove-timer-btn hidden brand-btn mobile-block'>Remove Timer</button> -->
            <!-- <button type="submit" name="timer_add" value="timer_add" id="timer_submit" class="btn brand-btn pull-right1 timer_submit">Submit</button> -->

        </div>
    </div>
</div>
	<script>
        $(document).ready(function () {
            $('.timer_submit').hide();
            var comm_id = $('#email_communicationid').val();
            var timer_type = $('#timer_type').val();
            var start_time = $(".start_time").val();
            if(start_time != 0 && start_time != undefined) {
                $('.timer').timer({
                    seconds: start_time //Specify start time in seconds
                });
                var hasTimer = false;
                if(timer_type == 'Work') {
                    $('.pause-timer-btn, .remove-timer-btn').removeClass('hidden');
                } else {
                    $('.pause-timer-btn').addClass('hidden');
                    $('.resume-timer-btn').removeClass('hidden');
                }
                $('.start-timer-btn').addClass('hidden');
                $('.timer_submit').show();
            }
        });

	    (function(){
			var hasTimer = false;
			// Init timer start
			$('.start-timer-btn').on('click', function() {
                $('.timer_submit').show();
				hasTimer = true;
				$('.timer').timer({
					editable: true
				});
				$(this).addClass('hidden');
				$('.pause-timer-btn, .remove-timer-btn').removeClass('hidden');
                var comm_id = $('#email_communicationid').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "communication_ajax_all.php?fill=starttimer&comm_id="+comm_id+"&login_contactid=<?php echo $_SESSION['contactid']; ?>",
                    dataType: "html",   //expect html to be returned
                    success: function(response) {
                    }
                });
				return false;
			});

			// Init timer resume
			$('.resume-timer-btn').on('click', function() {
				$('.timer').timer('resume');
				$(this).addClass('hidden');
				$('.pause-timer-btn, .remove-timer-btn').removeClass('hidden');
                var comm_id = $('#email_communicationid').val();
                var timer_value = $('#timer_value').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "communication_ajax_all.php?fill=resumetimer&comm_id="+comm_id+"&timer_value="+timer_value+"&login_contactid=<?php echo $_SESSION['contactid']; ?>",
                    dataType: "html",   //expect html to be returned
                    success: function(response) {
                        hasTimer = false;
                        $('.timer').timer('remove');
                        hasTimer = true;
                        $('.timer').timer({
                            seconds:0,
                            editable: true
                        });
                    }
                });
				return false;
			});

			// Init timer pause
			$('.pause-timer-btn').on('click', function() {
				$('.timer').timer('pause');
				$(this).addClass('hidden');
				$('.resume-timer-btn').removeClass('hidden');
                var comm_id = $('#email_communicationid').val();
                var timer_value = $('#timer_value').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "communication_ajax_all.php?fill=pausetimer&comm_id="+comm_id+"&timer_value="+timer_value+"&login_contactid=<?php echo $_SESSION['contactid']; ?>",
                    dataType: "html",   //expect html to be returned
                    success: function(response) {
                        hasTimer = false;
                        $('.timer').timer('remove');
                        hasTimer = true;
                        $('.timer').timer({
                            seconds:0,
                            editable: true
                        });
                    }
                });
				return false;
			});

			// Remove timer
			$('.remove-timer-btn').on('click', function() {
				hasTimer = false;
				$('.timer').timer('remove');
				$(this).addClass('hidden');
				$('.start-timer-btn').removeClass('hidden');
				$('.pause-timer-btn, .resume-timer-btn').addClass('hidden');
				return false;
			});

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
	</script>