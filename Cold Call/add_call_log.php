<?php
/*
Add Cold Call
*/
include ('../include.php');
error_reporting(0);

if (isset($_POST['add_call_log'])) {
	$created_date = date('Y-m-d');
    //$created_by = $_SESSION['contactid'];

    $created_by = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);

    $m = 0;
	if($_POST['new_business'] != '') {
		$name = encryptIt($_POST['new_business']);
        $query_insert_inventory = "INSERT INTO `contacts` (`category`, `name`) VALUES ('Cold Call Contact', '$name')";
        $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);
        $businessid = mysqli_insert_id($dbc);
        $m = 1;
	} else {
        $businessid = $_POST['businessid'];
	}

	if($_POST['new_contact'] != '') {
		$first_name = encryptIt($_POST['new_contact']);
        $query_insert_inventory = "INSERT INTO `contacts` (`category`, `businessid`, `name`, `first_name`, `office_phone`, `email_address`) VALUES ('Cold Call Business', '$businessid', '$name', '$first_name', '$office_phone', '$email_address')";
        $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);
        $contactid = mysqli_insert_id($dbc);
        $m = 1;
	} else {
        $contactid = $_POST['contactid'];
	}

    $call_subject = filter_var($_POST['call_subject'],FILTER_SANITIZE_STRING);
    $call_duration = filter_var($_POST['call_duration'],FILTER_SANITIZE_STRING);
    $call_notes = filter_var(htmlentities($_POST['call_notes']),FILTER_SANITIZE_STRING);
    $next_action = filter_var($_POST['next_action'],FILTER_SANITIZE_STRING);
    $new_reminder = filter_var($_POST['new_reminder'],FILTER_SANITIZE_STRING);
    $status = $_POST['status'];

    if(empty($_POST['calllogid'])) {
        $query_insert_vendor = "INSERT INTO `calllog_pipeline` (`created_date`, `created_by`, `businessid`, `contactid`, `call_subject`, `call_duration`, `call_notes`, `next_action`, `new_reminder`, `status`) VALUES ('$created_date', '$created_by', '$businessid', '$contactid', '$call_subject', '$call_duration', '$call_notes', '$next_action', '$new_reminder', '$status')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $calllogid = mysqli_insert_id($dbc);
        $url = 'Added';
		$old_action = '';
		$recipient = get_email($dbc, $_SESSION['contactid']);
    } else {
        $calllogid = $_POST['calllogid'];
        $query_update_vendor = "UPDATE `calllog_pipeline` SET `businessid` = '$businessid', `contactid` = '$contactid', `call_subject` = '$call_subject', `call_duration` = '$call_duration', `call_notes` = '$call_notes', `next_action` = '$next_action', `new_reminder` = '$new_reminder', `status` = '$status' WHERE `calllogid` = '$calllogid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
		$call_log = mysqli_fetch_array(mysqli_query($dbc, "SELECT `next_action`, `created_by` FROM `calllog_pipeline` WHERE `calllogid`='$calllogid'"));
		$recip_name = explode(' ',$call_log['created_by']);
		$recip_first = encryptIt($recip_name[0]);
		$recip_last = encryptIt($recip_name[1]);
		$recipient = decryptIt(mysqli_fetch_array(mysqli_query($dbc, "SELECT `email_address` FROM `contacts` WHERE `first_name`='$recip_first' AND `last_name`='$recip_last'"))['email_address']);
    }
	
	//Schedule Reminders
	if($recipient != '' && $new_reminder != '' && $new_reminder != '0000-00-00' && $old_action != $next_action) {
		$body = filter_var(htmlentities('This is a reminder about a call log that needs to be followed up with.<br />
			The scheduled next action is: '.$call_log['next_action'].'<br />
			Click <a href="'.WEBSITE_URL.'/Cold Call/add_call_log.php?calllogid='.$calllogid.'">here</a> to review the call log.'), FILTER_SANITIZE_STRING);
		$verify = "calllog_pipeline#*#next_action#*#calllogid#*#".$calllogid."#*#".$call_log['next_action'];
        mysqli_query($dbc, "UPDATE `reminders` SET `done` = 1 WHERE `src_table` = 'calllog' AND `src_tableid` = '$calllogid'");
		$reminder_result = mysqli_query($dbc, "INSERT INTO `reminders` (`recipient`, `reminder_date`, `reminder_type`, `subject`, `body`, `src_table`, `src_tableid`)
			VALUES ('$recipient', '$new_reminder', 'Call Log Reminder', 'Reminder of Call Log', '$body', 'calllog', '$calllogid')");
	}

    //Notes
    $note_heading = filter_var($_POST['note_heading'],FILTER_SANITIZE_STRING);
    $ticket_comment = htmlentities($_POST['comment']);
    $t_comment = filter_var($ticket_comment,FILTER_SANITIZE_STRING);

    if($t_comment != '') {
        $email_comment = $_POST['email_comment'];
        $query_insert_ca = "INSERT INTO `call_log_notes` (`calllogid`, `comment`, `email_comment`, `created_date`, `created_by`, `note_heading`) VALUES ('$calllogid', '$t_comment', '$email_comment', '$created_date', '$created_by', '$note_heading')";
        $result_insert_ca = mysqli_query($dbc, $query_insert_ca);

        if ($_POST['send_email_on_comment'] == 'Yes') {
            $email = get_email($dbc, $email_comment);
            $subject = 'Note Added on Cold Call.';

            $email_body = 'Note : '.$_POST['comment'].'<br><br>';

            //send_email('', $email, '', '', $subject, $email_body, '');
        }
    }
    //Notes

    echo '<script type="text/javascript"> window.location.replace("call_log.php"); </script>';

 //   mysqli_close($dbc);//Close the DB Connection
}
?>
<script type="text/javascript">
$(document).ready(function() {
    $("#form1").submit(function( event ) {
        var heading = $("#heading").val();
        var category = $("input[name=category]").val();
        var heading = $("input[name=heading]").val();
        if (heading == '' || category == '' || heading == '' ) {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
    });

    $("#task_businessid").change(function() {
        if($("#task_businessid option:selected").text() == 'New Business') {
                $( "#new_business" ).show();
        } else {
            $( "#new_business" ).hide();
        }

		var businessid = this.value;

        $.ajax({    //create an ajax request to load_page.php
            type: "GET",
            url: "call_log_ajax_all.php?fill=assigncontact&businessid="+businessid,
            dataType: "html",   //expect html to be returned
            success: function(response){
                var arr = response.split('**#**');
				$('#call_log_contact').html(arr[0]);
				$("#call_log_contact").trigger("change.select2");
            }
        });

    });

    $("#call_log_contact").change(function() {
        if($("#call_log_contact option:selected").text() == 'New Contact') {
                $( "#new_contact" ).show();
        } else {
            $( "#new_contact" ).hide();
        }
    });

	$('[name=next_action],[name=status]').on('chosen:showing_dropdown', function() {
		if($(".popover-examples").is(":hidden")) { return; }
		$('.chosen-results li').each(function() {
			var info = "";
			if($(this).text() == "Call Again") {
				info = "All Cold Calls must be scheduled; it's mandatory that any Cold Call with the Next Action of Call Again must be assigned a Reminder/Follow Up Date for when the next call is to happen.";
			}
			else if($(this).text() == "Move To Sales Pipeline") {
				info = "Before any Cold Call can be moved to any staff Sales Pipeline, you must select to whom you wish to transfer the Cold Call lead to. Once the Next Action of Move To Sales Pipeline is applied and you select from the pop up window that appears the staff name you which to transfer this Sales Pipeline lead to, the Cold Call lead will be moved to the To Be Scheduled section of the selected staff and they will be alerted via email that they have been assigned a new lead for review. This lead will have a status of Not Scheduled so staff know to schedule the next action with this lead.";
			}
			else if($(this).text() == "Transfer Lead") {
				info = "All Cold Call staff have the ability to transfer Cold Calls back and forth to one another for follow up. Once you select the Next Action of Transfer Lead and select the staff from the pop up window, they will be alerted of the new lead via email and the Cold Call lead will transition to the To Be Scheduled section of their Cold Call with a Not Scheduled status.";
			}
			else if($(this).text() == "Not Scheduled") {
				info = "The goal is to have every Cold Call lead scheduled. Any Cold Call leads that aren’t scheduled with a Reminder/Follow Up date will be assigned to the To Be Scheduled sub tab of your Cold Call. Only scheduled Cold Call leads will be reassigned to the date assigned in your call Log Schedule and display in your software calendar accordingly.";
			}
			else if($(this).text() == "Scheduled") {
				info = "All Cold Call leads with a Reminder/Follow Up date assigned to them will be moved into your Cold Call Schedule and scheduled in your software calendar for reference. All scheduled but not due Cold Calls are labeled with a Scheduled status.";
			}
			else if($(this).text() == "Missed Cold Call") {
				info = "Any scheduled Cold Call that passes the date is was scheduled for Reminder/Follow Up automatically changes status to Missed Cold Call. All Missed Cold Call leads turn yellow and the Cold Call is automatically moved to the To Be Scheduled sub tab of your Cold Call for rescheduling.";
			}
			else if($(this).text() == "Past Due Cold Call") {
				info = "Any Cold Call lead that goes 2 days without being touched turns red in the To Be Scheduled sub tab, waiting to then be rescheduled for Next Action.";
			}
			else if($(this).text() == "Available") {
				info = "Available leads are found in the Lead Bank sub tab of the Cold Call tile. All leads with the status of Available are visible to the entire company and anyone can access these leads by reassigning them to themselves. The Lead Bank holds all New and Abandoned leads and can be a great source for finding warm and new leads. Leads from this section can be moved to any individual Cold Call by clicking into them and reassigning/scheduling them for action.";
			}
			else if($(this).text() == "Abandoned") {
				info = "Any Cold Call lead that goes 3 days without being touched moves to an Abandoned status. The software views Abandoned leads as unscheduled and requiring prioritization. Cold Call leads will only remain yours for 5 days. If any lead is left with an Abandoned status for longer than 5 days, the lead is then moved to the Available Lead sub tab which can be viewed by the entire company. Abandoned leads and leads that have been posted as Available are first come first serve.";
			}
			else if($(this).text() == "Lost/Archive") {
				info = "Cold Calls that should no longer be pursued by the company or where the contact has explicitly requested to no longer be contacted should be removed from the Cold Call by applying a Lost/Archive status to them. No Cold Calls are ever lost; Archives can be searched and leads can be reactivated by security approved staff.";
			}
			$(this).addClass('popover-examples');
			$(this).data('toggle','tooltip');
			$(this).prepend('<a data-toggle="tooltip" data-placement="top" title="'+info+'"><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a> ');
		});
		$(".popover-examples a").tooltip({
			placement: 'top',
			trigger: 'hover'
		});
		$(".chosen-container .popover-examples a").hover(function() {
			var clone = $('.tooltip').clone();
			var pixels = parseInt(clone.css('top'));
			console.log(pixels);
			clone.css('top',(pixels+60)+'px');
			$('.tooltip').hide();
			$(this).parents('.chosen-container').append(clone);
		});
		$(".popover-examples a").mouseout(function() {
			$('.tooltip').remove();
		});
	});
});
</script>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('calllog');
?>
<div class="container">
	<div class="row">

    <h1>Add Cold Call</h1>
	<div class="pad-left gap-top double-gap-bottom"><a href="call_log.php" class="btn config-btn">Back to Dashboard</a></div>

    <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

    <?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pipeline_fields FROM field_config_calllog"));
        $value_config = ','.$get_field_config['pipeline_fields'].',';

        $created_by = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
        $primary_staff = $_SESSION['contactid'];

        $max_calllogid = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT MAX(calllogid) AS max_calllogid FROM calllog_pipeline"));

        $calllogid = $max_calllogid['max_calllogid']+1;
        $businessid = '';
        $contactid = '';
        $call_subject = '';
        $call_duration = '';
        $call_notes = '';
        $next_action = '';
        $new_reminder = '';
        $status = '';

        if(!empty($_GET['calllogid'])) {

            $calllogid = $_GET['calllogid'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM calllog_pipeline WHERE calllogid='$calllogid'"));

            $businessid = $get_contact['businessid'];
            $contactid = $get_contact['contactid'];
            $call_subject = $get_contact['call_subject'];
            $call_duration = $get_contact['call_duration'];
            $call_notes = $get_contact['call_notes'];
            $next_action = $get_contact['next_action'];
            $new_reminder = $get_contact['new_reminder'];
            $status = $get_contact['status'];

        ?>
        <input type="hidden" id="calllogid" name="calllogid" value="<?php echo $calllogid ?>" />
        <?php   }      ?>

        <div class="panel-group" id="accordion2">

            <?php if (strpos($value_config, ','."CL#".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info" >
                            CL#<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_info" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="company_name" class="col-sm-4 control-label">CL#:</label>
                            <div class="col-sm-8">
                              <input name="calllogid1" readonly value="<?php echo $calllogid; ?>" type="text" class="form-control">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (stripos($value_config, ','."Business Information".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_desc" >
                            Business Information<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_desc" class="panel-collapse collapse">
                    <div class="panel-body">

                       <div class="form-group clearfix">
                            <label for="first_name" class="col-sm-4 control-label text-right">Business:</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Choose a Business..." name="businessid" id="task_businessid" class="chosen-select-deselect form-control1" width="380">
                                  <option value=""></option>
                                  <?php
                                    $query = mysqli_query($dbc,"SELECT name, contactid FROM contacts WHERE name != '' AND deleted=0 ORDER BY name");
                                    echo "<option value = 'New Business'>New Business</option>";
                                    while($row = mysqli_fetch_array($query)) {
                                        if ($businessid == $row['contactid']) {
                                            $selected = 'selected="selected"';
                                        } else {
                                            $selected = '';
                                        }
                                        echo "<option ".$selected." value='". $row['contactid']."'>".decryptIt($row['name']).'</option>';
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>

                       <div class="form-group" id="new_business" style="display: none;">
                        <label for="travel_task" class="col-sm-4 control-label">New Business
                        </label>
                        <div class="col-sm-8">
                            <input name="new_business" type="text" class="form-control"/>
                        </div>
                        </div>


                     </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Contact Information".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_cost" >
                            Contact Information<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_cost" class="panel-collapse collapse">
                    <div class="panel-body">

                        <?php if(!empty($_GET['calllogid'])) { ?>
                        <div class="form-group clearfix">
                            <label for="first_name" class="col-sm-4 control-label text-right">Contact:</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Choose a Client..." id="call_log_contact" name="contactid" class="chosen-select-deselect form-control1" width="380">
                                  <option value=""></option>
								  <?php
									$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE businessid = '$businessid' AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
									foreach($query as $id) {
										$selected = '';
										$selected = $id == $contactid ? 'selected = "selected"' : '';
										echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
									}
								  ?>
                                </select>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="form-group clearfix">
                            <label for="first_name" class="col-sm-4 control-label text-right">Contact:</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Choose a Client..." id="call_log_contact" name="contactid" class="chosen-select-deselect form-control1" width="380">
                                  <option value=""></option>
                                </select>
                            </div>
                        </div>

                       <div class="form-group" id="new_contact" style="display: none;">
                        <label for="travel_task" class="col-sm-4 control-label">New Contact
                        </label>
                        <div class="col-sm-8">
                            <input name="new_contact" type="text" class="form-control"/>
                        </div>
                      </div>

                        <?php } ?>

                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Call Information".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_client" >
                            Call Information<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_client" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="company_name" class="col-sm-4 control-label">Call Subject:</label>
                            <div class="col-sm-8">
                              <input name="call_subject" value="<?php echo $call_subject; ?>" type="text" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="company_name" class="col-sm-4 control-label">Call Duration:</label>
                            <div class="col-sm-8">
                              <input name="call_duration" value="<?php echo $call_duration; ?>" type="text" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="site_name" class="col-sm-4 control-label">Call Notes:</label>
                            <div class="col-sm-8">
                              <textarea name="call_notes" rows="3" cols="50" class="form-control"><?php echo $call_notes; ?></textarea>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Next Steps".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_staff" >
                            Next Steps<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_staff" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="form-group">
							<label for="fax_number"	class="col-sm-4	control-label">Next Action:</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Choose a Next Action..." name="next_action" class="chosen-select-deselect form-control" width="380">
                                  <option value=""></option>
                                  <?php $tabs = get_config($dbc, 'calllog_next_action');
                                    $each_tab = explode(',', $tabs);
                                    foreach ($each_tab as $cat_tab) {
                                        if ($next_action == $cat_tab) {
                                            $selected = 'selected="selected"';
                                        } else {
                                            $selected = '';
                                        }
                                        echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>

                          <div class="form-group">
                            <label for="company_name" class="col-sm-4 control-label">Reminder/Follow Up:</label>
                            <div class="col-sm-8">
                              <input name="new_reminder" value="<?php echo $new_reminder; ?>" type="text" class="datepicker">
                            </div>
                          </div>

                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Status".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_status" >
                            Status<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_status" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="fax_number"	class="col-sm-4	control-label">Status:</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Choose a Status..." name="status" class="chosen-select-deselect form-control" width="380">
                                  <option value=""></option>
                                  <?php
                                    $tabs = get_config($dbc, 'calllog_lead_status');
                                    $each_tab = explode(',', $tabs);
                                    foreach ($each_tab as $cat_tab) {
                                        if ($status == $cat_tab) {
                                            $selected = 'selected="selected"';
                                        } else {
                                            $selected = '';
                                        }
                                        echo "<option $selected value='$cat_tab'>$cat_tab</option>";
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Lead Notes".',') !== FALSE) {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <span class="popover-examples list-inline" style="margin:0 5px 0 0;">
							<a data-toggle="tooltip" data-placement="top" title="Add any notes related to this call log lead."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a>
						</span>
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_staff_pos" >
                            Lead Notes<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <?php
                $accord = '';
                if(!empty($_GET['go'])) {
                    $accord = ' in';
                } ?>
                <div id="collapse_staff_pos" class="panel-collapse collapse <?php echo $accord; ?>">
                    <div class="panel-body">

                        <?php
                        include ('add_call_log_note.php');
                        ?>

                    </div>
                </div>
            </div>
            <?php } ?>

        </div>

        <div class="form-group">
			<p><span class="hp-red"><em>Required Fields *</em></span></p>
        </div>

        <div class="form-group">
            <div class="col-sm-6">
                <a href="call_log.php" class="btn brand-btn btn-lg">Back</a>
				<!--<a href="#" class="btn brand-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
			</div>
			<div class="col-sm-6">
				<button type="submit" name="add_call_log" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
			</div>
        </div>

        

    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>