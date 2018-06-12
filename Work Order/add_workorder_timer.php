<div class="form-group">
    <div class="timer-field col-sm-4">
        <button type="submit" name="start_timer_btn" value="Submit" class="btn btn-success start-timer-btn brand-btn mobile-block btn-lg start_timer_btn">Start</button>

        <input type='text' name='timer' class='form-control timer' id='start_timer' placeholder='0 sec' />
    </div>
</div>

<div class="form-group">
    <div class="timer-field col-sm-4">
        <button type="submit" name="break_timer_btn" value="Submit" class="btn btn-success start-timer-btn brand-btn mobile-block btn-lg break_timer_btn">Break</button>

        <input type='text' name='timer' class='form-control timer' id='break_timer' placeholder='0 sec' />
    </div>
</div>

<div class="form-group">
    <div class="timer-field col-sm-4">
        <button type="submit" name="end_break_timer_btn" value="Submit" class="btn btn-success start-timer-btn brand-btn mobile-block btn-lg end_break_timer_btn">End Break</button>
    </div>
</div>

<div class="form-group">
    <div class="timer-field col-sm-4">
        <button type="submit" name="end_day_timer_btn" value="Submit" class="btn btn-success start-timer-btn brand-btn mobile-block btn-lg end_day_timer_btn">End Day</button>
    </div>
</div>

<!--
<div class="col-md-12">
    <div class="form-group pull-right1">
        <label for="office_zip" class="col-sm-4 control-label"></label>
        <div class="col-sm-8">
            <button class='btn btn-success start-timer-btn brand-btn mobile-block' style="
            height: 75px; width: 100px; font-size: 30px;">Start</button>
            <button class='btn btn-success resume-timer-btn hidden brand-btn mobile-block' style="
            height: 75px; width: 175px; font-size: 30px;">End Break</button>
            <button class='btn pause-timer-btn hidden brand-btn mobile-block' style="
            height: 75px; width: 100px; font-size: 30px;">Break</button>
            <input type='text' name='timer' id='timer_value' style="width: 50%;" class='form-control timer' placeholder='0 sec' />&nbsp;&nbsp;
        </div>
    </div>
</div>
-->
    <style>
    .start-timer-btn {
        height: 150px; width: 400px; font-size: 60px;
    }

    </style>
	<script>
        $(document).ready(function () {
            var workorderid = $('#workorderid').val();
            var timer_type = $('#timer_type').val();

            var start_time = $(".start_time").val();
            if(window.location.hash) {
                var type = window.location.hash.substr(1);
                $('#'+type).timer({
                    seconds: start_time //Specify start time in seconds
                });
                $('#'+type).show();

                if(type == 'start_timer') {
                    $('#break_timer').hide();

                    $('.start_timer_btn').attr('disabled', true);
                    $('.start_timer_btn').removeClass('active');

                    $('.break_timer_btn').attr('disabled', false);
                    $('.break_timer_btn').removeClass('active');

                    $('.break_timer_btn').show();
                    $('.end_break_timer_btn').hide();
                    $('.end_day_timer_btn').show();

					setActiveTimer('.start_timer_btn');
                }
                if(type == 'break_timer') {
                    $('#start_timer').hide();
                    $('.start_timer_btn').hide();
                    //$('#start_timer').hide();
                    //$('#break_timer').hide();
                    //$('.break_timer_btn').hide();
                    $('.end_break_timer_btn').show();
                    $('.end_day_timer_btn').hide();
                    $('.break_timer_btn').attr('disabled', true);
                    $('.break_timer_btn').removeClass('active');

					setActiveTimer('.break_timer_btn');
                }
            } else {
                //$('#start_timer').hide();
                $('#break_timer').hide();
                $('.break_timer_btn').hide();
                $('.end_break_timer_btn').hide();
                $('.end_day_timer_btn').hide();
            }

            /*
            $('.timer_submit').hide();

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
            */
        });

	    (function(){
			var hasTimer = false;
			// Init timer start

			$('.start_timer_btn').on('click', function() {
                var timer_task = $('#timer_task').val();
                if(timer_task == '') {
                    alert('Please add task before you start Timer.');
                    return false;
                }

				//setActiveTimer(this);

                $('.start_timer_btn').attr('disabled', true);
				$('.start_timer_btn').removeClass('active');

                $('.break_timer_btn').attr('disabled', false);
                $('.break_timer_btn').removeClass('active');

                $('.break_timer_btn').show();
                $('.end_break_timer_btn').hide();
                $('.end_day_timer_btn').show();

                var url_hash = parent.location.hash;
                parent.location.hash = '';

                document.location.hash = "start_timer";
                $('#start_timer').show();
                $(url_hash).hide();

                hasTimer = true;
                var timer_value = $(url_hash).val();
                $(url_hash).timer('remove');
                //$('#off_duty_timer').timer('resume');
                $('#start_timer').timer({
                    seconds: 0 //Specify start time in seconds
                });
                var timer_name = url_hash.substring(1, url_hash.length);

                var workorderid = $('#workorderid').val();
                var timer_contactid = $('#timer_contactid').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "workorder_ajax_all.php?fill=startworkordertimer&workorderid="+workorderid+"&timer_contactid="+timer_contactid+"&timer_task="+timer_task,
                    dataType: "html",   //expect html to be returned
                    success: function(response) {
                        window.location.replace("../Punch Card/punch_card.php");
                    }
                });

				return false;
			});

			$('.break_timer_btn').on('click', function() {
				//setActiveTimer(this);

                var timer_value = $('#start_timer').val();

                $('.break_timer_btn').attr('disabled', true);
				$('.break_timer_btn').removeClass('active');

                $('.start_timer_btn').attr('disabled', false);
                $('.start_timer_btn').removeClass('active');

                $('.start_timer_btn').hide();
                $('#start_timer').hide();
                $('#break_timer').hide();
                $('.end_break_timer_btn').show();
                $('.end_day_timer_btn').hide();

                var url_hash = parent.location.hash;
                parent.location.hash = '';

                document.location.hash = "break_timer";

                $('#break_timer').show();
                $(url_hash).hide();

                hasTimer = true;

                //var timer_value = $(url_hash).val();
                $(url_hash).timer('remove');
                //$('#off_duty_timer').timer('resume');
                $('#break_timer').timer({
                    seconds: 0 //Specify start time in seconds
                });

                var workorderid = $('#workorderid').val();
                var timer_contactid = $('#timer_contactid').val();
                var timer_task = $('#timer_task').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "workorder_ajax_all.php?fill=pauseworkordertimer&workorderid="+workorderid+"&timer_value="+timer_value+"&timer_contactid="+timer_contactid+"&timer_task="+timer_task,
                    dataType: "html",   //expect html to be returned
                    success: function(response) {
                        window.location.replace("../Punch Card/punch_card.php");
                        //hasTimer = false;
                        //$(url_hash).timer('remove');
                        //hasTimer = true;
                        //$('#break_timer').timer({
                        //    seconds:0,
                        //    editable: true
                        //});
                    }
                });
                return false;
			});

			$('.end_break_timer_btn').on('click', function() {
				//setActiveTimer(this);
                var timer_value = $('#break_timer').val();

                $('#break_timer').hide();
                $('.break_timer_btn').hide();
                $('.end_break_timer_btn').hide();
                $('.end_day_timer_btn').hide();
                $('.start_timer_btn').show();
                $('#start_timer').show();

                //$('.break_timer_btn').attr('disabled', false);
				//$('.break_timer_btn').removeClass('active');

                $('.start_timer_btn').attr('disabled', false);
                $('.start_timer_btn').removeClass('active');

                var url_hash = parent.location.hash;
                parent.location.hash = '';

                document.location.hash = "";

                //$('#break_timer').show();

                $(url_hash).hide();

                //hasTimer = true;

                //var timer_value = $(url_hash).val();
                $(url_hash).timer('remove');

                //$('#break_timer').timer({
                //    seconds: 0 //Specify start time in seconds
                //});

                var workorderid = $('#workorderid').val();
                var timer_contactid = $('#timer_contactid').val();
                var timer_task = $('#timer_task').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "workorder_ajax_all.php?fill=endworkordertimer&workorderid="+workorderid+"&timer_value="+timer_value+"&timer_contactid="+timer_contactid+"&timer_task="+timer_task,
                    dataType: "html",   //expect html to be returned
                    success: function(response) {
                        window.location.replace("../Punch Card/punch_card.php");
                        //hasTimer = false;
                        //$(url_hash).timer('remove');
                        //hasTimer = true;
                        //$('#break_timer').timer({
                        //    seconds:0,
                        //    editable: true
                        //});
                    }
                });
                return false;
			});

			$('.end_day_timer_btn').on('click', function() {
				//setActiveTimer(this);
                var timer_value = $('#start_timer').val();

                $('#break_timer').hide();
                $('.break_timer_btn').hide();
                $('.end_break_timer_btn').hide();
                $('.end_day_timer_btn').hide();
                $('.start_timer_btn').show();
                $('#start_timer').show();

                //$('.break_timer_btn').attr('disabled', false);
				//$('.break_timer_btn').removeClass('active');

                $('.start_timer_btn').attr('disabled', false);
                $('.start_timer_btn').removeClass('active');

                var url_hash = parent.location.hash;
                parent.location.hash = '';

                document.location.hash = "";

                //$('#break_timer').show();

                $(url_hash).hide();

                //hasTimer = true;

                //var timer_value = $(url_hash).val();
                $(url_hash).timer('remove');

                //$('#break_timer').timer({
                //    seconds: 0 //Specify start time in seconds
                //});

                var workorderid = $('#workorderid').val();
                var timer_contactid = $('#timer_contactid').val();
                var timer_task = $('#timer_task').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "workorder_ajax_all.php?fill=enddayworkordertimer&workorderid="+workorderid+"&timer_value="+timer_value+"&timer_contactid="+timer_contactid+"&timer_task="+timer_task,
                    dataType: "html",   //expect html to be returned
                    success: function(response) {
                        window.location.replace("../Punch Card/punch_card.php");
                        //hasTimer = false;
                        //$(url_hash).timer('remove');
                        //hasTimer = true;
                        //$('#break_timer').timer({
                        //    seconds:0,
                        //    editable: true
                        //});
                    }
                });
                return false;
			});

			/*
            $('.start-timer-btn').on('click', function() {
                $('.timer_submit').show();
				hasTimer = true;
				$('.timer').timer({
					editable: true
				});
				$(this).addClass('hidden');
				$('.pause-timer-btn, .remove-timer-btn').removeClass('hidden');
                var workorderid = $('#workorderid').val();
                var timer_contactid = $('#timer_contactid').val();
                var timer_task = $('#timer_task').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "workorder_ajax_all.php?fill=startworkordertimer&workorderid="+workorderid+"&timer_contactid="+timer_contactid+"&timer_task="+timer_task,
                    dataType: "html",   //expect html to be returned
                    success: function(response) {
                    }
                });
				return false;
			});
            */

			// Init timer resume
			/*
            $('.resume-timer-btn').on('click', function() {
				$('.timer').timer('resume');
				$(this).addClass('hidden');
				$('.pause-timer-btn, .remove-timer-btn').removeClass('hidden');
                var workorderid = $('#workorderid').val();
                var timer_value = $('#timer_value').val();
                var timer_contactid = $('#timer_contactid').val();
                var timer_task = $('#timer_task').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "workorder_ajax_all.php?fill=resumeworkordertimer&workorderid="+workorderid+"&timer_value="+timer_value+"&timer_contactid="+timer_contactid+"&timer_task="+timer_task,
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
            */

			// Init timer pause
			/*$('.pause-timer-btn').on('click', function() {
				$('.timer').timer('pause');
				$(this).addClass('hidden');
				$('.resume-timer-btn').removeClass('hidden');
                var workorderid = $('#workorderid').val();
                var timer_value = $('#timer_value').val();
                var timer_contactid = $('#timer_contactid').val();
                var timer_task = $('#timer_task').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "workorder_ajax_all.php?fill=pauseworkordertimer&workorderid="+workorderid+"&timer_value="+timer_value+"&timer_contactid="+timer_contactid+"&timer_task="+timer_task,
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
            */

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