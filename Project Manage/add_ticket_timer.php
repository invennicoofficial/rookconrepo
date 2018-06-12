<?php

if(!empty($_GET['projectmanageid'])) {
    echo '<h4>Current Time(s)</h4>';
    $query_check_credentials = "SELECT * FROM project_manage_assign_to_timer WHERE projectmanageid='$projectmanageid' ORDER BY assigntotimerid DESC";
    $result = mysqli_query($dbc, $query_check_credentials);
    $num_rows = mysqli_num_rows($result);
    if($num_rows > 0) {
        echo "<table class='table table-bordered'>
        <tr class=''>
        <th>Type</th>
        <th>Time</th>
        <th>Date</th>
        <th>Added By</th>
        </tr>";
        $times = array();
        while($row = mysqli_fetch_array($result)) {
            echo '<tr>';
            $by = $row['created_by'];
            echo '<td data-title="Schedule">'.$row['timer_type'].'</td>';
            echo '<td data-title="Schedule">'.$row['start_time'].' - '.$row['end_time'].'</td>';
            echo '<td data-title="Schedule">'.$row['created_date'].'</td>';
            echo '<td data-title="Schedule">'.get_staff($dbc, $by).'</td>';
            echo '</tr>';
            //$total_time += strtotime($row['timer']);
            $times[] = $row['timer'];
        }
        echo '</table>';
    }
}

//echo $time = date("h:i:s",$total_time);
?>
<div class="col-md-12">
    <div class="form-group pull-right1">
        <label for="office_zip" class="col-sm-4 control-label">Timer:</label>
        <div class="col-sm-8">
            <input type='text' name='timer' id='timer_value' style="width: 50%; float: left;" class='form-control timer' placeholder='0 sec' />&nbsp;&nbsp;
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
            var projectmanageid = $('#projectmanageid').val();
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
                var projectmanageid = $('#projectmanageid').val();
                var login_contactid = $('#login_contactid').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "project_manage_ajax_all.php?fill=starttickettimer&projectmanageid="+projectmanageid+"&login_contactid="+login_contactid,
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
                var projectmanageid = $('#projectmanageid').val();
                var timer_value = $('#timer_value').val();
                var login_contactid = $('#login_contactid').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "project_manage_ajax_all.php?fill=resumetickettimer&projectmanageid="+projectmanageid+"&timer_value="+timer_value+"&login_contactid="+login_contactid,
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
                var projectmanageid = $('#projectmanageid').val();
                var timer_value = $('#timer_value').val();
                var login_contactid = $('#login_contactid').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "project_manage_ajax_all.php?fill=pausetickettimer&projectmanageid="+projectmanageid+"&timer_value="+timer_value+"&login_contactid="+login_contactid,
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