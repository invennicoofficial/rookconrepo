<?php if(empty($_GET['timerid']) && !empty($_GET['contactid'])): ?>
	<div class="form-group">
		<label for="fax_number"	class="col-sm-4	control-label"><button type="submit" name="start_timer_btn" value="Submit" class="btn btn-success wo-start-timer-btn brand-btn mobile-block btn-lg start_timer_btn" style="width:100%;">Start</button></label>
		<div class="col-sm-8">
			<input type='text' name='timer' class='form-control timer' id='start_timer' placeholder='0 sec' />
		</div>
	</div>

	<div class="form-group">
		<label for="fax_number"	class="col-sm-4	control-label">
			<button type="submit" name="break_timer_btn" value="Submit" class="btn btn-success wo-start-timer-btn brand-btn mobile-block btn-lg break_timer_btn" style="width:100%;">Break</label>
		<div class="col-sm-8">
			<input type='text' name='timer' class='form-control timer' id='break_timer' placeholder='0 sec' />
		</div>
	</div>

	<div class="form-group">
		<label for="fax_number"	class="col-sm-4	control-label">
			<button type="submit" name="end_break_timer_btn" value="Submit" class="btn btn-success wo-start-timer-btn brand-btn mobile-block btn-lg end_break_timer_btn" style="width:100%;">End Break</label>
		<div class="col-sm-8">
		</div>
	</div>

	<div class="form-group">
		<label for="fax_number"	class="col-sm-4	control-label">
			<button type="submit" name="end_day_timer_btn" value="Submit" class="btn btn-success wo-start-timer-btn brand-btn mobile-block btn-lg end_day_timer_btn" style="width:100%;">End Task</label>
		<div class="col-sm-8">
		</div>
	</div>

	<div class="form-group">
		<label for="fax_number"	class="col-sm-4	control-label">
			<button type="submit" name="end_day_timer_btn" value="Submit" class="btn btn-success wo-start-timer-btn brand-btn mobile-block btn-lg end_day_timer_btn" style="width:100%;">End Day</label>
		<div class="col-sm-8">
		</div>
	</div>

<?php else: ?>
	<div class="staff_group additional_staff">
        <input type="hidden" name="project_timer_id[]" value="<?php echo $_GET['timerid']; ?>">
        <?php if(empty($_GET['contactid'])): ?>
            <div class="form-group">
                <label for="timer_staff" class="col-sm-4 control-label">Staff:</label>
                <div class="col-sm-8">
                    <select data-placeholder="Choose Staff Member..." name="timer_staff[]" class="chosen-select-deselect form-control">
                        <option value=''></option>
                        <?php
                            $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
                            foreach($query as $id) {
                                $selected = '';
                                //$selected = $id == $search_user ? 'selected = "selected"' : '';
                                $selected = ( $contactid==$id ) ? 'selected="selected"' : '';
                                echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
                            }
                          ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="timer_date"	class="col-sm-4	control-label">Task:</label>
                <div class="col-sm-8">
                    <select data-placeholder="Choose Task..." name="task[]" class="chosen-select-deselect form-control">
                        <option value=""></option><?php
                        $all_task = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT `task` FROM `field_config_project_manage` WHERE `tile`='$tile' AND `tab`='$tab_url' AND `accordion`= 'Deliverables'" ) );
                        $each_tab = array_filter(array_map('trim', explode(',', $all_task['task'])));
                        asort($each_tab);
                        foreach ( $each_tab as $each_task ) {
                            $selected = ( $task==$each_task ) ? 'selected="selected"' : '';
                            echo '<option value="'. $each_task .'" '. $selected .'>'. $each_task .'</option>';
                        } ?>
                    </select>
                </div>
            </div>
        <?php endif; ?>
		<div class="form-group">
			<label for="timer_date"	class="col-sm-4	control-label">Work Date:</label>
			<div class="col-sm-8">
				<input type='text' name='timer_date[]' id='date_0' value='<?= $timer_date; ?>' class='form-control datepicker' placeholder='YYYY-MM-DD' />
			</div>
		</div>
        <div class="form-group">
            <label for="fax_number"	class="col-sm-4	control-label">Timer Duration:</label>
            <div class="col-sm-8">
                <input type='text' name='duration[]' class='form-control timepicker-15' value='<?php echo $duration; ?>' placeholder='0 sec' onchange="set_hours(this);" />
            </div>
        </div>
		<?php if(strpos($value_config, ','."Regular Hours".',') !== FALSE): ?>
			<div class="form-group">
				<label for="fax_number"	class="col-sm-4	control-label">Regular Hours:</label>
				<div class="col-sm-8">
					<input type='text' name='regular_hrs[]' class='form-control timepicker-15' value='<?php echo $regular_hrs; ?>' placeholder='0 sec' onchange="set_hours(this);" />
				</div>
			</div>
		<?php else: ?>
			<input type="hidden" name="regular_hrs[]" value="<?= $regular_hrs ?>">
		<?php endif; ?>
		<?php if(strpos($value_config, ','."Overtime Hours".',') !== FALSE): ?>
			<div class="form-group">
				<label for="fax_number"	class="col-sm-4	control-label">Overtime Hours:</label>
				<div class="col-sm-8">
					<input type='text' name='overtime_hrs[]' class='form-control timepicker-15' value='<?php echo $overtime_hrs; ?>' placeholder='0 sec' onchange="set_hours(this);" />
				</div>
			</div>
		<?php endif; ?>
		<?php if(strpos($value_config, ','."Travel Hours".',') !== FALSE): ?>
			<div class="form-group">
				<label for="fax_number"	class="col-sm-4	control-label">Travel Hours:</label>
				<div class="col-sm-8">
					<input type='text' name='travel_hrs[]' class='form-control timepicker-15' value='<?php echo $travel_hrs; ?>' placeholder='0 sec' />
				</div>
			</div>
		<?php endif; ?>
		<?php if(strpos($value_config, ','."Subsist Hours".',') !== FALSE): ?>
			<div class="form-group">
				<label for="fax_number"	class="col-sm-4	control-label">Subsistence Hours:</label>
				<div class="col-sm-8">
					<input type='text' name='subsist_hrs[]' class='form-control timepicker-15' value='<?php echo $subsist_hrs; ?>' placeholder='0 sec' />
				</div>
			</div>
		<?php endif; ?>
        <div class="form-group">
            <label for="fax_number"	class="col-sm-4	control-label">Task Start:</label>
            <div class="col-sm-8">
                <input type='text' name='start_clock[]' class='form-control datetimepicker' value='<?php echo $start_clock; ?>' placeholder='HH:MM' />
            </div>
        </div>
        <div class="form-group">
            <label for="fax_number"	class="col-sm-4	control-label">Task End:</label>
            <div class="col-sm-8">
                <input type='text' name='end_clock[]' class='form-control datetimepicker' value='<?php echo $end_clock; ?>' placeholder='HH:MM' />
            </div>
        </div>
    </div><!-- .additional_staff -->
        
    <div id="add_here_new_staff"></div>
    
    <button id="add_row_staff" class="btn brand-btn pull-right">Add More</button>
<?php endif; ?>

    <style>
    .wo-start-timer-btn {
        height: 150px; width: 400px; font-size: 60px;
    }

    </style>
	<script>
	function get_seconds(time) {
		if(time == '') {
			return 0;
		}
		var result = 0;
		time = time.split(':');
		while(time.length > 0) {
			result = result * 60 + parseInt(time.shift());
		}
		// Round to the nearest increment of 15
		result = Math.round(result / (15)) * (15);
		return result;
	}
	function make_time(time) {
		var result = [];
		while(time > 0) {
			var temp = time % 60;
			time = (time - temp) / 60;
			result.unshift(('0'+temp).substr(-2));
		}
		while(result.length < 2) {
			result.unshift('00');
		}
		return result.join(':');
	}
	function set_hours(src) {
		var group = $(src).closest('.staff_group');
		var duration = get_seconds(group.find('[name="duration[]"]').val());
		var regular = get_seconds(group.find('[name="regular_hrs[]"]').val());
		var overtime = get_seconds(group.find('[name="overtime_hrs[]"]').val());
		if(src.name == 'duration[]' && duration > overtime) {
			regular = duration - overtime;
		} else if(src.name == 'duration[]' && duration <= overtime) {
			overtime = duration;
			regular = duration - overtime;
		} else if(src.name == 'regular_hrs[]' && duration > regular) {
			overtime = duration - regular;
		} else if(src.name == 'regular_hrs[]' && duration <= regular) {
			overtime = 0;
			duration = regular;
		} else if(src.name == 'overtime_hrs[]' && duration > overtime) {
			regular = duration - overtime;
		} else if(src.name == 'overtime_hrs[]' && duration <= overtime) {
			duration = regular + overtime;
		}
		group.find('[name="duration[]"]').val(make_time(duration));
		group.find('[name="regular_hrs[]"]').val(make_time(regular));
		group.find('[name="overtime_hrs[]"]').val(make_time(overtime));
	}
        $(document).ready(function () {
            var projectmanageid = $('#projectmanageid').val();
            //var timer_type = $('#timer_type').val();

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
                    $('.end_break_timer_btn').show();
                    $('.end_day_timer_btn').hide();
                    $('.break_timer_btn').attr('disabled', true);
                    $('.break_timer_btn').removeClass('active');

					setActiveTimer('.break_timer_btn');
                }
            } else {
                $('#break_timer').hide();
                $('.break_timer_btn').hide();
                $('.end_break_timer_btn').hide();
                $('.end_day_timer_btn').hide();
            }
            
            $('#add_row_staff').on( 'click', function () {
				destroyInputs();
				var clone = $('.additional_staff').clone();
				clone.find('.form-control').val('');
				resetChosen(clone.find("select[class^=chosen]"));
				clone.removeClass("additional_staff");
				$('#add_here_new_staff').append(clone);
				initInputs();
				return false;
            });
        });

	    (function(){
			var hasTimer = false;
			// Init timer start

			$('.start_timer_btn').on('click', function() {
                var timer_task = $('input[name=task]:checked').val();
                if (typeof timer_task === "undefined" || timer_task == '') {
					alert('Please add task before you start Timer.');
					return false;
                }

                $('.start_timer_btn').attr('disabled', true).removeClass('active');
                $('.break_timer_btn').attr('disabled', false).removeClass('active');

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
                $('#start_timer').timer({
                    seconds: 0 //Specify start time in seconds
                });
                var timer_name = url_hash.substring(1, url_hash.length);

                var projectmanageid = $('#projectmanageid').val();
                if (typeof timer_task === "undefined") {
                    timer_task = 0;
                }
                var timer_contactid = $('#timer_contactid').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "project_manage_ajax_all.php?fill=startworkordertimer&projectmanageid="+projectmanageid+"&timer_task="+timer_task+"&timer_contactid="+timer_contactid,
                    dataType: "html",   //expect html to be returned
                    success: function(response) {
                        var tab = $('#tab').val();
                        var tile = $('#tile').val();
                        window.location.replace("<?php echo $return_url; ?>");
                    }
                });

				return false;
			});

			$('.break_timer_btn').on('click', function() {
                var timer_value = $('#start_timer').val();

                $('.break_timer_btn').attr('disabled', true).removeClass('active');
                $('.start_timer_btn').attr('disabled', false).removeClass('active');

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
                $(url_hash).timer('remove');
                $('#break_timer').timer({
                    seconds: 0 //Specify start time in seconds
                });

                var projectmanageid = $('#projectmanageid').val();
                var timer_contactid = $('#timer_contactid').val();
                var timer_task = $('input[name=task]:checked').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "project_manage_ajax_all.php?fill=pauseworkordertimer&projectmanageid="+projectmanageid+"&timer_value="+timer_value+"&timer_task="+timer_task+"&timer_contactid="+timer_contactid,
                    dataType: "html",   //expect html to be returned
                    success: function(response) {
                        var tab = $('#tab').val();
                        var tile = $('#tile').val();
                        window.location.replace("<?php echo $return_url; ?>");
                    }
                });
                return false;
			});

			$('.end_break_timer_btn').on('click', function() {
                var timer_value = $('#break_timer').val();

                $('#break_timer').hide();
                $('.break_timer_btn').hide();
                $('.end_break_timer_btn').hide();
                $('.end_day_timer_btn').hide();
                $('.start_timer_btn').show();
                $('#start_timer').show();

                $('.start_timer_btn').attr('disabled', false).removeClass('active');

                var url_hash = parent.location.hash;
                parent.location.hash = '';
                document.location.hash = "";

                $(url_hash).hide();
                $(url_hash).timer('remove');

                var projectmanageid = $('#projectmanageid').val();
                var timer_contactid = $('#timer_contactid').val();
                var timer_task = $('input[name=task]:checked').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "project_manage_ajax_all.php?fill=endworkordertimer&projectmanageid="+projectmanageid+"&timer_value="+timer_value+"&timer_task="+timer_task+"&timer_contactid="+timer_contactid,
                    dataType: "html",   //expect html to be returned
                    success: function(response) {
                    }
                });
                return false;
			});

			$('.end_day_timer_btn').on('click', function() {
                var timer_value = $('#start_timer').val();

                $('#break_timer').hide();
                $('.break_timer_btn').hide();
                $('.end_break_timer_btn').hide();
                $('.end_day_timer_btn').hide();
                $('.start_timer_btn').show();
                $('#start_timer').show();

                $('.start_timer_btn').attr('disabled', false);
                $('.start_timer_btn').removeClass('active');

                var url_hash = parent.location.hash;
                parent.location.hash = '';

                document.location.hash = "";

                $(url_hash).hide();

                $(url_hash).timer('remove');

                var projectmanageid = $('#projectmanageid').val();
                var timer_contactid = $('#timer_contactid').val();
                var timer_task = $('input[name=task]:checked').val();
                $.ajax({    //create an ajax request to load_page.php
                    type: "GET",
                    url: "project_manage_ajax_all.php?fill=enddayworkordertimer&projectmanageid="+projectmanageid+"&timer_value="+timer_value+"&timer_task="+timer_task+"&timer_contactid="+timer_contactid,
                    dataType: "html",   //expect html to be returned
                    success: function(response) {
                        var tab = $('#tab').val();
                        var tile = $('#tile').val();
                        window.location.replace("<?php echo $return_url; ?>");
                    }
                });
                return false;
			});

			// Remove timer
			$('.remove-timer-btn').on('click', function() {
				hasTimer = false;
				$('.timer').timer('remove');
				$(this).addClass('hidden');
				$('.wo-start-timer-btn').removeClass('hidden');
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