<?php
/*
comment
*/
include ('../include.php');
checkAuthorised('driving_log');
?>
<script type="text/javascript">
$(document).on('change', 'select[name="priority[]"]', function() { selectPriority(this); });

function closethatthing() {
	window.location.href = "driving_log_timer.php?drivinglogid=<?php echo $_GET['drivinglogid']; ?>";
}
   
function clickAme(sel) {
	var typeId = sel.id;
    $("#"+typeId).attr("readonly", false);
}
function clickComment(sel) {
	var typeId = sel.id;
    $("#"+typeId).attr("readonly", false);
}
function changeAme(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	
	var check_time = $('#'+typeId)
        .closest('tr')
        .prev('tr')
        .find('input[type=time]').attr('value');
		
	if(check_time !== '') {
		if(check_time > stage) {
			alert("Please make sure your start time ("+stage+") is not before "+check_time);
			return false;
		}
	}
	
	// Cannot be after end time.
	
	var check_time = $('#'+typeId)
        .closest('td')
        .next('td')
        .find('.end_timer_time_val').text();
		
	if(check_time !== '') {
		if(check_time < stage) {
			alert("Please make sure your start time ("+stage+") is not greater than this timer's end time ("+check_time+").");
			return false;
		}
	}

    if(!stage.match(/^(\d\d):(\d\d)\s?(?:AM|PM)?$/)) {
        alert('Invalid Time Format');
        location.reload();
        return false;
    }

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "driving_log_ajax_all.php?fill=amendments&action=update&id="+arr[1]+'&value='+stage+'&column='+arr[0],
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
			
		}
	});
}
function changeEndTime(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	
	var check_time = $('#'+typeId)
        .closest('tr')
        .next('tr').find('td.end_time_get')
        .find('input[type=time]').attr('value');
		
	if(check_time !== '') {
		if(check_time <= stage) {
			alert("Please make sure your end time ("+stage+") is not after or equal to "+check_time);
			return false;
		}
	}
	
	// Cannot be after end time.
	
	var check_time = $(sel).next().text();
		
	if(check_time !== '') {
		if(check_time > stage) {
			alert("Please make sure your start time ("+check_time+") is not greater than this timer's end time ("+stage+").");
			return false;
		}
	}

    if(!stage.match(/^(\d\d):(\d\d)\s?(?:AM|PM)?$/)) {
        alert('Invalid Time Format');
        location.reload();
        return false;
    }

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "driving_log_ajax_all.php?fill=amendments&action=update_end_time&id="+arr[1]+'&value='+stage+'&column='+arr[0],
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}
function changeComment(sel) {
	//var stage = sel.value;
    var stage = encodeURIComponent(sel.value);
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "driving_log_ajax_all.php?fill=amendments&action=update_comment&id="+arr[1]+'&value='+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}
function selectPriority(sel) {
	var priority = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "driving_log_ajax_all.php?fill=timerpriority&timerid="+arr[1]+'&priority='+priority,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}
function addAme(sel) {
    $(".add_new_ame").show();
}

function saveAme(sel) {
	var drivinglogid = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

    var timer_name = $(".timer_name").val();
    var ame_time = $(".ame_time").val();
	var ender_time = $(".ender_time").val();
    var com1 = $(".comment").val();
	
	if(ame_time > ender_time) {
		alert('Start time cannot be after the end time.');
		return false;
	} else if (ame_time == ender_time) {
		alert('Start time cannot be equal to the end time.');
		return false;
	}
	
	var check_time = $('.check_the_time')
        .closest('tr')
        .prev('tr')
        .find('input[type=time]').attr('value');
 
	if(check_time !== '') {
		if(check_time > ame_time) {
			alert("Please make sure your start time is not before "+check_time);
			return false;
		}
	} 

    var com2 = com1.replace(/ /g,'***');
    var comment = com2.replace("&", "__");

    if(!ame_time.match(/^(\d\d):(\d\d)\s?(?:AM|PM)?$/)) {
        alert('Invalid time format for Start Time.');
        //location.reload();
        return false;
    }
	if(!ender_time.match(/^(\d\d):(\d\d)\s?(?:AM|PM)?$/)) {
        alert('Invalid time format for End Time.');
        //location.reload();
        return false;
    }
	
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "driving_log_ajax_all.php?fill=amendments&action=add&value=0&id="+drivinglogid+"&timer_name="+timer_name+'&ender_time='+ender_time+'&ame_time='+ame_time+'&comment='+comment,
		dataType: "html",   //expect html to be returned
		success: function(response){
            //window.location = 'amendments.php?graph=off&drivinglogid='+drivinglogid;
			location.reload();
		}
	}); 
}

 <?php if(isset($_GET['showtime']) && $_GET['showtime'] == 'cycle' && (isset($_GET['timertype']) && ($_GET['timertype'] == 'on_duty_timer' || $_GET['timertype'] == 'driving_timer'))) { ?>
	<?php if($_GET['timertype'] == 'on_duty_timer') { ?>
		var type = 'on_duty_timer';
		var timer = '<?php echo $_GET['timer_val']; ?>';
	<?php } else { ?>
		var type = 'driving_timer';
		var timer = '<?php echo $_GET['timer_val']; ?>';
	<?php } ?>
	console.log(timer);
 <?php } else { ?>
<?php } ?>

/*function saveAme(sel) {
	var drivinglogid = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

    var timer_name = $(".timer_name").val();
    var ame_time = $(".ame_time").val();
	var ender_time = $(".ender_time").val();
    var com1 = $(".comment").val();
	
	var check_time = $('.check_the_time')
        .closest('tr')
        .prev('tr')
        .find('input[type=time]').attr('value');
		
	
	if(check_time !== '') {
		if(check_time > ame_time) {
			alert("Please make sure your start time is not before "+check_time);
			return false;
		}
	} 
	
	if(ame_time > ender_time) {
		alert('Start time cannot be after the end time.');
		return false;
	} else if (ame_time == ender_time) {
		alert('Start time cannot be equal to the end time.');
		return false;
	}

    var com2 = com1.replace(/ /g,'***');
    var comment = com2.replace("&", "__");

    if(!ame_time.match(/^(\d\d):(\d\d)\s?(?:AM|PM)?$/)) {
        alert('Invalid time format for Start Time.');
        //location.reload();
        return false;
    }
	if(!ender_time.match(/^(\d\d):(\d\d)\s?(?:AM|PM)?$/)) {
        alert('Invalid time format for End Time.');
        //location.reload();
        return false;
    }
	
	
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "driving_log_ajax_all.php?fill=timer_amendments&id="+drivinglogid+"&timer_name="+timer_name+'&ender_time='+ender_time+'&ame_time='+ame_time+'&comment='+comment,
		dataType: "html",   //expect html to be returned
		success: function(response){
            //window.location = 'amendments.php?graph=off&drivinglogid='+drivinglogid;
			location.reload();
		}
	});
	
	}*/

</script>
<?php
$drivinglogid = $_GET['drivinglogid'];
$query_insert_graph = 'DELETE FROM driving_log_timer WHERE inspection_mode=1 AND drivinglogid = "'.$drivinglogid.'"';
$result_insert_graph = mysqli_query($dbc, $query_insert_graph);

include ('fix_negative_bug.php');

$query_check_credentials = "SELECT * FROM driving_log_timer WHERE drivinglogid = '$drivinglogid' ORDER BY level";
$result = mysqli_query($dbc, $query_check_credentials);

echo "<table class='table table-bordered' style='width: 100%;'>";
echo "<tr><th>Order</th><th>Timer Name</th>
    <th>Start Time
	<span class='popover-examples list-inline pull-right'>&nbsp;
    <a data-toggle='tooltip' data-placement='top' title='Click on a time box below to change a start time.'><img src='".WEBSITE_URL."/img/info-w.png' width='20'></a>
    </span>
	</th>
    <th>End Time
	<span class='popover-examples list-inline pull-right'>&nbsp;
    <a data-toggle='tooltip' data-placement='top' title='Click on a time box below to change an end time.'><img src='".WEBSITE_URL."/img/info-w.png' width='20'></a>
    </span>
	</th>
    <th>Comment
    <span class='popover-examples list-inline pull-right'>&nbsp;
    <a data-toggle='tooltip' data-placement='top' title='Click on a text box below to change a comment.'><img src='".WEBSITE_URL."/img/info-w.png' width='20'></a>
    </span>
    </th></tr>";
    $my = 0;
while($row = mysqli_fetch_array( $result )) {
    echo '<tr>';
    $color_off = '';
    $timerid = $row['timerid'];
    if($row['off_duty_timer'] != '') {
        echo timer_iframe($row['amendments'], 'priority_'.$timerid, $row['level'], 'Off Duty Time', $row['off_duty_time'], 'off_'.$timerid, $row['end_off_duty_time'], 'comment_'.$timerid, $row['dl_comment']);
    }
    $color_s = '';
    if($row['sleeper_berth_timer'] != '') {
        echo timer_iframe($row['amendments'], 'priority_'.$timerid, $row['level'], 'Sleeper Berth Time', $row['sleeper_berth_time'], 'sleeper_'.$timerid, $row['end_sleeper_berth_time'], 'comment_'.$timerid, $row['dl_comment']);
    }
    $color_d = '';
    if($row['driving_timer'] != '') {
        echo timer_iframe($row['amendments'], 'priority_'.$timerid, $row['level'], 'Driving Time', $row['driving_time'], 'driving_'.$timerid, $row['end_driving_time'], 'comment_'.$timerid, $row['dl_comment']);

    }
    $color_on = '';
    if($row['on_duty_timer'] != '') {
        echo timer_iframe($row['amendments'], 'priority_'.$timerid, $row['level'], 'On Duty Time', $row['on_duty_time'], 'on_'.$timerid, $row['end_on_duty_time'], 'comment_'.$timerid, $row['dl_comment']);
    }
    echo '</tr>';
    $my = $row['level'];
}

echo '<tr class="add_new_ame" style="display: none;">';
echo '<td>'.($my+1).'</td>';
echo '<td>
    <select data-placeholder="Choose a Timer..." name="timer_name" class="chosen-select-deselect form-control timer_name" width="380">
      <option value=""></option>
      <option value="Off-Duty">Off-Duty</option>
      <option value="Sleeper Berth">Sleeper Berth</option>
      <option value="Driving">Driving</option>
      <option value="On-Duty">On-Duty</option>
    </select>
    </td>';
echo "<td><input type='time' class='form-control input-sm ame_time' name='' placeholder='00:00 AM/PM'  value=''></td>";
echo "<td><input type='time' class='form-control input-sm ender_time' name='' placeholder='00:00 AM/PM'></td>";
echo "<td><input style='width:100%' type='text' class='form-control comment' name=''>";
echo '<button type="button" name="submit" onClick="saveAme(this)"  value="'.$drivinglogid.'" class="btn brand-btn smt-btn check_the_time">Submit</button></td>';
echo '</tr>';

echo '</table>';
echo '<button type="button" name="add_new_button" onClick="addAme(this)" value="Submit" class="btn brand-btn pull-right smt">Add Amendments</button>';
echo '<span class="popover-examples list-inline pull-right" style="margin-top:5px;"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to add line items in your driving log. This will show in your daily summary when you complete your end of day."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';


function timer_iframe($amendments, $level_id, $level, $timer_name, $timer_time, $timer_id, $end_timer_time, $comment_id, $comment) {
    $color_off = '';
    if($amendments != '00:00:00') {
        $amm_off = ' : '.$amendments;
        $color_off = 'style = "color:red; "';
    }
    $line_timer = '';
    $line_timer .= '<td>';

    $line_timer .= '<select data-placeholder="Choose a Priority..." name="priority[]" id="'.$level_id.'" class="chosen-select-deselect form-control" required width="380">';
        for ($i = 1; $i <= 100; $i++) {
            if ($i == $level) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }
            $line_timer .= "<option ".$selected." value='". $i."'>".$i.'</option>';
        }
    $line_timer .= '</select>';
    $line_timer .= '</td>';

    $line_timer .= '<td '.$color_off.'>'.$timer_name.'</td>';
    $line_timer .= '<td '.$color_off.' data-title="Time" class="start_time_get">'.date("g:i a", strtotime($timer_time)).'';
    $line_timer .= "<input type='time'  onClick='clickAme(this)' onfocusout='changeAme(this)' class='form-control input-sm' placeholder='00:00 AM/PM' id='".$timer_id."' name='' value='".DATE("H:i", STRTOTIME($timer_time))."' class=''>";
    $line_timer .= '</td>';
    $line_timer .= '<td '.$color_off.' data-title="Time" class="end_time_get">' .date("g:i a", strtotime($end_timer_time)) .'<span class="end_timer_time_val" style="display:none;">'.DATE("H:i", STRTOTIME($end_timer_time)).'</span>';
	$line_timer .= "<input type='time' onClick='clickAme(this)' onfocusout='changeEndTime(this)' class='form-control input-sm' placeholder='00:00 AM/PM' id='".$timer_id."' name='' value='".DATE("H:i", STRTOTIME($end_timer_time))."' class=''>";
	$line_timer .= '<span class="beg_timer_time_val" style="display:none;">'.DATE("H:i", STRTOTIME($timer_time)).'</span></td>';
    $line_timer .= "<td ".$color_off." data-title='Comment'><input type='text'  onClick='clickComment(this)' onChange='changeComment(this)' class='form-control input-sm' id='".$comment_id."' name='' value='".$comment."' class=''></td>";
    return $line_timer;
}

include ('reset_cycle.php');
if(isset($_GET['showtime']) && $_GET['showtime'] == 'cycle') {
	include ('cycle_time_chart.php');	
}
?>