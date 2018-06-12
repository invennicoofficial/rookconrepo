<div class="col-md-12">
    <div class="form-group pull-right1">
        <label for="office_zip" class="col-sm-4 control-label">Timer:</label>
        <div class="col-sm-8">
            <input type='text' name='timer' id='timer_value' style="width: 50%; float: left;" class='form-control timer' placeholder='0 sec' />&nbsp;&nbsp;
            <button class='btn btn-success start-timer-btn brand-btn mobile-block'>Start</button>
            <button class='btn btn-success resume-timer-btn hidden brand-btn mobile-block'>Resume/End Break</button>
            <button class='btn pause-timer-btn hidden brand-btn mobile-block'>Pause/Break</button>
            <button class='btn stop-timer-btn hidden brand-btn mobile-block'>Stop</button>
            <!-- <button class='btn btn-danger remove-timer-btn hidden brand-btn mobile-block'>Remove Timer</button> -->
            <!-- <button type="submit" name="timer_add" value="timer_add" id="timer_submit" class="btn brand-btn pull-right1 timer_submit">Submit</button> -->
        </div>
    </div>
</div>
	<script>
	$(document).ready(function() {
		$('#collapse_timer [class*=timepicker],#tab_section_view_ticket_timer [class*=timepicker]').timepicker('option','onClose',function() {
			if(this.value != '00:00' && this.value != '') {
				$.ajax({
					url: 'ticket_ajax_all.php?action=update_fields',
					method: 'POST',
					data: {
						table: 'ticket_time_list',
						field: 'time_length',
						value: this.value,
						id: 0,
						id_field: 'id',
						ticketid: ticketid,
						type: (this.name == 'max_time_add' ? 'Completion Estimate' : (this.name == 'max_qa_time_add' ? 'QA Estimate' : (this.name == 'manual_time_track' ? 'Manual Time' : ''))),
						type_field: 'time_type',
						attach: user_id,
						attach_field: 'created_by'
					}, success: function(response) {
						reloadTimes();
					}
				});
				addTimes(this);
				this.value = '00:00';
			}
		});
	});
		function reloadTimes() {
			$.ajax({
				url: 'ticket_time_tracking.php?ticketid='+ticketid,
				success: function(response) {
					$('.tracked_time_div').html(response);
				}
			});
		}
        $(document).ready(function () {
            $('.timer_submit').hide();
            var ticketid = $('#ticketid').val();
            var timer_type = $('#timer_type').val();
            var start_time = $(".start_time").val();
            if(start_time != 0 && start_time != undefined) {
                $('.timer').timer({
                    seconds: start_time //Specify start time in seconds
                });
                var hasTimer = false;
                if(timer_type == 'Work') {
                    $('.pause-timer-btn, .remove-timer-btn, .stop-timer-btn').removeClass('hidden');
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
                $('.timer_submit').show();
				hasTimer = true;
				$('.timer').timer({
					editable: true
				});
				$(this).addClass('hidden');
				$('.pause-timer-btn, .remove-timer-btn, .stop-timer-btn').removeClass('hidden');
                var ticketid = $('#ticketid').val();
                var login_contactid = $('#login_contactid').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "ticket_ajax_all.php?fill=starttickettimer&ticketid="+ticketid+"&login_contactid="+login_contactid,
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
				$('.pause-timer-btn, .remove-timer-btn,.stop-timer-btn').removeClass('hidden');
                var ticketid = $('#ticketid').val();
                var timer_value = $('#timer_value').val();
                var login_contactid = $('#login_contactid').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "ticket_ajax_all.php?fill=resumetickettimer&ticketid="+ticketid+"&timer_value="+timer_value+"&login_contactid="+login_contactid,
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
                var ticketid = $('#ticketid').val();
                var timer_value = $('#timer_value').val();
                var login_contactid = $('#login_contactid').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "ticket_ajax_all.php?fill=pausetickettimer&ticketid="+ticketid+"&timer_value="+timer_value+"&login_contactid="+login_contactid,
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
	function stopTimers() {
		$('.timer').timer('stop');
		$(this).addClass('hidden');
		$('.pause-timer-btn, .resume-timer-btn').addClass('hidden');
		$('.start-timer-btn').removeClass('hidden');
		var ticketid = $('#ticketid').val();
		var timer_value = $('#timer_value').val();
		var login_contactid = $('#login_contactid').val();
		hasTimer = false;
		$('.timer').timer('remove');
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "ticket_ajax_all.php?fill=stoptickettimer&ticketid="+ticketid+"&timer_value="+timer_value+"&login_contactid="+login_contactid,
			dataType: "html",   //expect html to be returned
			success: function(response) {
				reloadTimes();
			}
		});
		return false;
	}
	function addTimes(timer) {
		$.ajax({
			url: 'ticket_ajax_all.php?action=update_max_time',
			method: 'POST',
			data: {
				ticketid: ticketid,
			}
		});
	}
	</script>
