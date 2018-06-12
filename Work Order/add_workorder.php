<?php
/*
Add Vendor
*/
include ('../include.php');
error_reporting(0);

/*
if (isset($_POST['tasklist'])) {
	$created_date = date('Y-m-d');
    $created_by = $_SESSION['contactid'];
    $workorderid = $_POST['workorderid'];
    $task = filter_var($_POST['task'],FILTER_SANITIZE_STRING);
    $task_clientid = $_POST['task_clientid'];
    $task_contactid = $_POST['task_contactid'];
    $task_tododate = $_POST['task_tododate'];
    $query_insert_ca = "INSERT INTO `tasklist` (`workorderid`, `clientid`, `task`, `contactid`, `created_date`, `created_by`, `task_tododate`) VALUES ('$workorderid', '$task_clientid', '$task', '$task_contactid', '$created_date', '$created_by', '$task_tododate')";
    $result_insert_ca = mysqli_query($dbc, $query_insert_ca);

    $url = 'add_workorder.php?workorderid='.$workorderid;
    echo '<script type="text/javascript"> alert("Work Order Created/Updated"); window.location.replace("'.$url.'"); </script>';
}
*/

if (isset($_POST['timer_add'])) {
    $url = 'add_workorder.php?workorderid='.$workorderid;
    echo '<script type="text/javascript"> window.location.replace("'.$url.'"); </script>';
}

if (isset($_POST['submit'])) {
	$clientid = $_POST['clientid'];
	//$service_type = filter_var($_POST['service_type'],FILTER_SANITIZE_STRING);
    //$service = filter_var($_POST['service'],FILTER_SANITIZE_STRING);

    $service_type = $_POST['service_type'];

    $serviceid = $_POST['serviceid'];
    $service = get_services($dbc, $serviceid, 'category').' : '.get_services($dbc, $serviceid, 'heading');
    $sub_heading = filter_var($_POST['sub_heading'],FILTER_SANITIZE_STRING);
    $heading = filter_var($_POST['heading'],FILTER_SANITIZE_STRING);
	$created_date = date('Y-m-d');

    $timer_contactid = $_POST['timer_contactid'];
    if($timer_contactid != '') {
        $created_by = $timer_contactid;
    } else {
        $created_by = $_SESSION['contactid'];
    }

    $contactid = ',';
    if($_POST['contactid'][0] == 'Assign to All') {
        $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE deleted=0 AND category='Staff'");
        while($row = mysqli_fetch_array($query)) {
            $contactid .= $row['contactid'].',';
        }
    } else {
        $contactid .= implode(',',$_POST['contactid']).',';
    }

    $a_work = htmlentities($_POST['assign_work']);
    $assign_work = filter_var($a_work,FILTER_SANITIZE_STRING);

    $to_do_date = $_POST['to_do_date'];
    $internal_qa_date = $_POST['internal_qa_date'];
    $deliverable_date = $_POST['deliverable_date'];
    $max_time = $_POST['max_time_hour'].':'.$_POST['max_time_minute'].':00';

    $total_days = $_POST['total_days'];
    $projectid = $_POST['projectid'];

    if(empty($_POST['workorderid'])) {
        $query_insert_ca = "INSERT INTO `workorder` (`clientid`, `contactid`, `service_type`, `service`, `sub_heading`, `heading`, `created_date`, `created_by`, `assign_work`, `to_do_date`, `internal_qa_date`, `deliverable_date`, `projectid`) VALUES ('$clientid', '$contactid', '$service_type', '$service', '$sub_heading', '$heading', '$created_date', '$created_by', '$assign_work', '$to_do_date', '$internal_qa_date', '$deliverable_date', '$projectid')";
        $result_insert_ca = mysqli_query($dbc, $query_insert_ca);
        $workorderid = mysqli_insert_id($dbc);

        echo insert_day_overview($dbc, $created_by, 'Work Order', date('Y-m-d'), '', 'Created Work Order #'.$workorderid);

    } else {
        $workorderid = $_POST['workorderid'];

        echo insert_day_overview($dbc, $created_by, 'Work Order', date('Y-m-d'), '', 'Updated Work Order #'.$workorderid);

        $query_update_workorder = "UPDATE `workorder` SET `to_do_date` = '$to_do_date', `internal_qa_date` = '$internal_qa_date', `deliverable_date` = '$deliverable_date', `max_time` = '$max_time', `total_days` = '$total_days', `assign_work` = '$assign_work' WHERE `workorderid` = '$workorderid'";
        $result_update_workorder = mysqli_query($dbc, $query_update_workorder);

        //Comment
        $day_end_note = $_POST['day_end_note'];
        if($day_end_note != '') {
            $type = 'day';
            $workorder_comment = htmlentities($_POST['day_end_note']);
            $t_comment = filter_var($workorder_comment,FILTER_SANITIZE_STRING);
			$sender = (!empty($_POST['day_email_sender']) ? $_POST['email_sender'] : '');
			$subject = $_POST['day_email_subject'];
			$email_body = str_replace(['[NOTE]','[WORKORDERID]'], [$workorder_comment,$workorderid], $_POST['day_email_body']);
        } else {
            $type = 'note';
            $note_heading = filter_var($_POST['note_heading'],FILTER_SANITIZE_STRING);
            $workorder_comment = htmlentities($_POST['workorder_comment']);
            $t_comment = filter_var($workorder_comment,FILTER_SANITIZE_STRING);
			$sender = (!empty($_POST['note_email_sender']) ? $_POST['email_sender'] : '');
			$subject = $_POST['note_email_subject'];
			$email_body = str_replace(['[NOTE]','[WORKORDERID]'], [$workorder_comment,$workorderid], $_POST['note_email_body']);
        }
        if($t_comment != '') {
            $email_comment = $_POST['email_comment'];
            $query_insert_ca = "INSERT INTO `workorder_comment` (`workorderid`, `comment`, `email_comment`, `created_date`, `created_by`, `type`, `note_heading`) VALUES ('$workorderid', '$t_comment', '$email_comment', '$created_date', '$created_by', '$type', '$note_heading')";
            $result_insert_ca = mysqli_query($dbc, $query_insert_ca);

            if ($_POST['send_email_on_comment'] == 'Yes') {
                //Code for Send Email
                $email = get_email($dbc, $email_comment);

				if($email != '') {
					try {
						send_email($sender, $email, '', '', $subject, $email_body, '');
					} catch(Exception $e) {
						echo "<script>alert('Unable to send email. Please try again later.');</script>";
					}
				}
            }
        }

        $timer = $_POST['timer'];
        $end_time = date('g:i A');

        $start_time = 0;
        if($timer != '0' && $timer != '00:00:00' && $timer != '') {
            $query_update_workorder = "UPDATE `workorder_timer` SET `end_time` = '$end_time', `start_timer_time` = '$start_time' WHERE `workorderid` = '$workorderid' AND created_by='$created_by' AND created_date='$created_date' AND end_time IS NULL";
            $result_update_workorder = mysqli_query($dbc, $query_update_workorder);

            $query_update_workorder = "UPDATE `workorder` SET `start_time` = '0' WHERE `workorderid` = '$workorderid'";
            $result_update_workorder = mysqli_query($dbc, $query_update_workorder);
        }
    }

    //deliverables
    if($_POST['status'] != '') {
        $status = $_POST['status'];
        $query_insert_ca = "INSERT INTO `workorder_deliverables` (`workorderid`, `status`, `contactid`, `created_date`, `created_by`) VALUES ('$workorderid', '$status', '$contactid', '$created_date', '$created_by')";
        $result_insert_ca = mysqli_query($dbc, $query_insert_ca);

        $query_update_workorder = "UPDATE `workorder` SET `contactid` = '$contactid', `status` = '$status' WHERE `workorderid` = '$workorderid'";
        $result_update_workorder = mysqli_query($dbc, $query_update_workorder);

        //Mail
        $get_user = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT email_address FROM contacts WHERE contactid='$contactid'"));
        $to = $get_user['email_address'];

		$sender = (!empty($_POST['deliverable_email_sender']) ? $_POST['email_sender'] : '');
		$subject = $_POST['deliverable_email_subject'];
		$email_body = str_replace(['[ESTIMATEID]'], [$workorderid], $_POST['deliverable_email_body']);

		if($to != '') {
			try {
				send_email($sender, $to, '', '', $subject, $email_body, '');
			} catch(Exception $e) {
				echo "<script>alert('Unable to send email. Please try again later.');</script>";
			}
		}
        //Mail
    }

    //Document
    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }
    for($i = 0; $i < count($_FILES['upload_document']['name']); $i++) {
        $document = htmlspecialchars($_FILES["upload_document"]["name"][$i], ENT_QUOTES);

        move_uploaded_file($_FILES["upload_document"]["tmp_name"][$i], "download/".$_FILES["upload_document"]["name"][$i]) ;

        if($document != '') {
            $query_insert_client_doc = "INSERT INTO `workorder_document` (`workorderid`, `type`, `document`, `created_date`, `created_by`) VALUES ('$workorderid', 'Support Document', '$document', '$created_date', '$created_by')";
            $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
        }
    }

    for($i = 0; $i < count($_POST['support_link']); $i++) {
        $support_link = $_POST['support_link'][$i];

        if($support_link != '') {
            $query_insert_client_doc = "INSERT INTO `workorder_document` (`workorderid`, `type`, `link`, `created_date`, `created_by`) VALUES ('$workorderid', 'Support Document', '$support_link', '$created_date', '$created_by')";
            $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
        }
    }

    for($i = 0; $i < count($_FILES['review_upload_document']['name']); $i++) {
        $review_document = $_FILES["review_upload_document"]["name"][$i];

        move_uploaded_file($_FILES["review_upload_document"]["tmp_name"][$i], "download/".$_FILES["review_upload_document"]["name"][$i]) ;

        if($review_document != '') {
            $query_insert_client_doc = "INSERT INTO `workorder_document` (`workorderid`, `type`, `document`, `created_date`, `created_by`) VALUES ('$workorderid', 'Review Document', '$review_document', '$created_date', '$created_by')";
            $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
        }
    }

    for($i = 0; $i < count($_POST['support_review_link']); $i++) {
        $support_review_link = $_POST['support_review_link'][$i];

        if($support_review_link != '') {
            $query_insert_client_doc = "INSERT INTO `workorder_document` (`workorderid`, `type`, `link`, `created_date`, `created_by`) VALUES ('$workorderid', 'Review Document', '$support_review_link', '$created_date', '$created_by')";
            $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
        }
    }

	if(!empty($_POST['from'])) {
		$url = $_POST['from'];
	}
    else if(!empty($_POST['from_wo'])) {
        $from_wo = $_POST['from_wo'];
        if($from_wo == 'daysheet') {
            $url = 'workorder.php?workorderid='.$workorderid.'&contactid='.$created_by.'&from=daysheet';
        }
        if($from_wo == 'punchcard') {
            $url = '../Punch Card/punch_card.php';
        }
    } else if(!empty($_POST['from_co'])) {
        $url = 'workorder.php?workorderid='.$workorderid.'&contactid='.$_POST['from_co'].'';
    } else {
        if(empty($_POST['workorderid'])) {
            $url = 'workorder.php?contactid='.$created_by;
        } else {
            $url = 'workorder.php?workorderid='.$workorderid.'&contactid='.$created_by;
        }
    }

    echo '<script type="text/javascript"> window.location.replace("'.$url.'"); </script>';
}
?>
<script type="text/javascript">
$(document).ready(function() {
    $("#form1").submit(function( event ) {
        var workorderid = $("#workorderid").val();
        if(workorderid == undefined) {
            var projectid = $("#projectid").val();

            var heading = $("input[name=heading]").val();
            //var status = $("#status").val();
            //var contactid = $("#contactid").val();

            if (projectid == '' || heading == '') {
                alert("Please make sure you have filled in all of the required fields.");
                return false;
            }
        }
    });
	setSave();
});
var workorderid = 0;
function send_email(button) {
	$.ajax({
		url: 'workorder_ajax_all.php?action=send_email',
		method: 'POST',
		data: {
			table: $(button).data('table'),
			id_field: $(button).data('id-field'),
			id: $(button).data('id'),
			field: $(button).data('field'),
			recipient: $(button).closest('.email_div').find('.email_recipient').val(),
			sender: $(button).closest('.email_div').find('.email_sender').val(),
			subject: $(button).closest('.email_div').find('.email_subject').val(),
			body: $(button).closest('.email_div').find('.email_body').val()
		},
		success: function(response) {
			if(response != '') {
				alert(response);
			}
			$(button).closest('.email_div').hide();
		}
	});
}
function setSave() {
	workorderid = $('[name=workorderid]').val();
	$('[data-table]').off('change',saveField).change(saveField);
}

function saveField() {
	if(this.type == 'file') {
		var files = new FormData();
		for(var i = 0; i < this.files.length; i++) {
			files.append('files[]',this.files[i]);
		}
		files.append('table',$(this).data('table'));
		files.append('field',this.name);
		files.append('workorder',workorderid);
		$.ajax({
			url: 'workorder_ajax_all.php?action=add_file',
			method: 'POST',
			processData: false,
			contentType: false,
			data: files,
			success: function(response) {
				window.location.reload();
			}
		});
	} else {
		var block = $(this).closest('.multi-block');
		var table_name = $(this).data('table');
		var save_value = this.value;
		if(this.name.substr(-2) == '[]' && this.type == 'select') {
			var value = [];
			$(this).find('option:selected').each(function() {
				value.push(this.value);
			});
			save_value = value.join(',');
		} else if($(this).is('[data-concat]')) {
			var value = [];
			$('[name='+this.name+']').each(function() {
				if(this.type != 'checkbox' || this.checked) {
					value.push(this.value);
				}
			});
			save_value = value.join($(this).data('concat'));
		}
		$.ajax({
			url: 'workorder_ajax_all.php?action=update_fields',
			method: 'POST',
			data: {
				table: table_name,
				field: this.name.replace('[]',''),
				value: save_value,
				id: $(this).data('id'),
				id_field: $(this).data('id-field'),
				workorderid: workorderid,
				type: $(this).data('type'),
				type_field: $(this).data('type-field')
				attach: $(this).data('attach'),
				attach_field: $(this).data('attach-field')
			},
			success: function(response) {
				if(response > 0) {
					if(block.length > 0) {
						block.find('[data-table='+table_name+']').data('id',response);
					} else {
						$('[data-table='+table_name+']').data('id',response);
						workorderid = (table_name == 'workorder' ? response : workorderid);
					}
				}
			}
		});
	}
}
</script>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised();
$back_url = '';
if(!empty($_GET['from'])) {
	$back_url = urldecode($_GET['from']);
}
?>
<div class="container">
  <div class="row">

	<form id="form1" name="form1" method="post"	action="add_workorder.php" enctype="multipart/form-data" class="form-horizontal" role="form">
	<input type="hidden" name="from" value="<?php echo $back_url; ?>">
        <h1 class="">
            <?php
            $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT workorder FROM field_config"));
            $value_config = ','.$get_field_config['workorder'].',';

            if($_GET['from'] == 'punchcard') {
                echo 'Punch Card';
            } else if($_GET['from'] == 'daysheet') {
                echo 'Work Order Daysheet';
            } else {
                echo 'Work Order';
            }

            if(empty($_GET['from'])) {
                if(empty($_GET['contactid'])) {
                    echo '<a href="workorder.php" class="btn brand-btn pull-right">Work Order List</a>';
                } else {
                    echo '<a href="workorder.php?contactid='.$_GET['contactid'].'" class="btn brand-btn pull-right">Work Order List</a>';
                }
            } else {
                if($_GET['from'] == 'punchcard') {
                    echo '<a href="../Punch Card/punch_card.php" class="btn brand-btn pull-right">Punch Card List</a>';
                }
            }
            ?>
            <?php if(!empty($_GET['workorderid'])) { ?>
            <!-- <br><br>
            <a class="btn brand-btn" data-toggle="collapse" data-parent="#accordion2" href="#collapse_timer" >
               Start WO Time Tracking
            </a>
            <a class="btn brand-btn" data-toggle="collapse" data-parent="#accordion2" href="#collapse_dayt" >
               Start WO Day Tracking
            </a>
            -->
            <?php } ?>
        </h1>

        <?php
        if(!empty($_GET['from'])) {
            echo '<input type="hidden" name="from_wo" value="'.$_GET['from'].'">';
            if($_GET['from'] == 'daysheet') {
                $back_url = '../Daysheet/workorder_daysheet.php?contactid='.$_GET['contactid'];
            }
            if($_GET['from'] == 'punchcard') {
                $back_url = '../Punch Card/punch_card.php?contactid='.$_GET['contactid'];
            }
        } else {
			$back_url = 'workorder.php';
        //if(!empty($_GET['contactid'])) {
            echo '<input type="hidden" name="from_co" value="'.$_GET['contactid'].'">';
            $back_url = 'workorder.php?contactid='.$_GET['contactid'];
        } ?>

		<div class="pad-left double-gap-bottom"><a href="<?php echo $back_url; ?>" class="btn config-btn">Back to Dashboard</a></div>
		<br>

		<?php
        if(!empty($_GET['workorderid'])) {

            $workorderid = $_GET['workorderid'];
            $get_workorder = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM workorder WHERE workorderid='$workorderid'"));

            $businessid = $get_workorder['businessid'];
            $clientid = $get_workorder['clientid'];
            if($businessid ==  '' || $businessid ==  0) {
                $businessid = get_contact($dbc, $clientid, 'businessid');
            }
            $projectid = $get_workorder['projectid'];
            $service_type = $get_workorder['service_type'];
            $service = $get_workorder['service'];
            $sub_heading = $get_workorder['sub_heading'];
            $heading = $get_workorder['heading'];
            $assign_work = $get_workorder['assign_work'];

            $created_date = $get_workorder['created_date'];
            $created_by = $get_workorder['created_by'];

            $created_date = date('Y-m-d');
            $login_id = $_GET['contactid'];
            // AND timer_type='Break' AND end_time IS NULL

            $get_workorder_timer = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT start_timer_time, timer_type FROM workorder_timer WHERE workorderid='$workorderid' AND created_by='$login_id' AND created_date='$created_date' ORDER BY workordertimerid DESC LIMIT 1"));

            $start_time = $get_workorder_timer['start_timer_time'];
            $timer_type = $get_workorder_timer['timer_type'];

            if($start_time == '0' || $start_time == '') {
                $time_seconds = 0;
            } else {
                $time_seconds = (time()-$start_time);
            }

            $to_do_date = $get_workorder['to_do_date'];
            $internal_qa_date = $get_workorder['internal_qa_date'];
            $deliverable_date = $get_workorder['deliverable_date'];
            $status = $get_workorder['status'];
            $max_time = explode(':', $get_workorder['max_time']);
            $total_days = $get_workorder['total_days'];

            echo '<span class="pull-right">Created By '.get_staff($dbc, $created_by).' On '.$created_date.'';
            //include ('add_workorder_timer.php');
            echo '</span><br><br><br>';
        ?>

        <input type="hidden" class="start_time" value="<?php echo $time_seconds ?>">
        <input type="hidden" id="workorderid" name="workorderid" value="<?php echo $workorderid ?>" />
        <input type="hidden" id="login_contactid" value="<?php echo $_SESSION['contactid'] ?>" />
        <input type="hidden" id="timer_type" value="<?php echo $timer_type ?>" />
        <input type="hidden" name="timer_contactid" id="timer_contactid" value="<?php echo $_GET['contactid'] ?>" />
        <?php   }

        if(!empty($_GET['workorderid']) && !empty($_GET['contactid'])) {
            if (strpos($value_config, ','."Timer".',') !== FALSE) {
                include ('add_view_workorder_timer.php');
            }
        }
        ?>
		
        <div class="panel-group" id="accordion2">

            <?php if (strpos($value_config, ','."Information".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
						<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add any necessary project information. This will group your Work Orders under a certain project, under a certain customer."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info">
							<?php if (PROJECT_TILE=='Projects') { echo "Project"; } else { echo PROJECT_TILE; } ?> Information<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_info" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <?php
                        //if(empty($_GET['workorderid'])) {
                            include ('add_project_info.php');
                        //} else {
                          //  include ('view_project_info.php');
                        //}
                        ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if(!empty($_GET['workorderid'])) { ?>
            <!-- <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_comm" >
                           <?php if (PROJECT_TILE=='Projects') { echo "Project"; } else { echo PROJECT_TILE; } ?> Details<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_comm" class="panel-collapse collapse">
                    <div class="panel-body">
                     <?php include ('view_project_detail.php'); ?>
                    </div>
                </div>
            </div> -->
            <?php } ?>

            <?php if (strpos($value_config, ','."Fees".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to attach fees to this work order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_fees">Fees<span class="glyphicon glyphicon-plus"></span></a>
                    </h4>
                </div>

                <div id="collapse_fees" class="panel-collapse collapse">
                    <div class="panel-body">
						<div class="hide-titles-mob">
							<label class="col-sm-4 text-center">Fee Name</label>
							<label class="col-sm-4 text-center">Details</label>
							<label class="col-sm-3 text-center">Amount</label>
						</div>
						<?php foreach(explode(',',$get_workorder['fee_name']) as $i => $fee_name) { ?>
							<div class="multi-block">
								<div class="col-sm-4">
									<label class="control-label show-on-mob">Fee Name:</label>
									<input type="text" name="fee_name" data-table="workorder" data-id="<?= $workorderid ?>" data-id-field="workorderid" data-concat="," value="<?= $fee_name ?>" class="form-control">
								</div>
								<div class="col-sm-4">
									<label class="control-label show-on-mob">Fee Details:</label>
									<input type="text" name="fee_details" data-table="workorder" data-id="<?= $workorderid ?>" data-id-field="workorderid" data-concat="," value="<?= explode(',',$get_workorder['fee_details'])[$i] ?>" class="form-control">
								</div>
								<div class="col-sm-3">
									<label class="control-label show-on-mob">Fee Amount:</label>
									<input type="number" min=0 step="0.01" name="fee_amt" data-table="workorder" data-id="<?= $workorderid ?>" data-id-field="workorderid" data-concat="," value="<?= explode(',',$get_workorder['fee_amt'])[$i] ?>" class="form-control">
								</div>
								<div class="col-sm-1">
									<img class="inline-img pull-right" onclick="addMulti(this);" src="../img/icons/ROOK-add-icon.png">
									<img class="inline-img pull-right" onclick="remMulti(this);" src="../img/remove.png">
								</div>
								<div class="clearfix"></div>
							</div>
						<?php } ?>
                    </div>
                </div>
            </div>
            <?php } ?>
			
			<?php if(strpos($value_config, ',Staff,') !== FALSE) { ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to attach multiple Staff to this Work Order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_staff">
							   Staff<span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_staff" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="hide-titles-mob">
								<label class="col-sm-3 text-center">Staff</label>
								<label class="col-sm-3 text-center">Position</label>
								<label class="col-sm-3 text-center">Start Shift</label>
								<label class="col-sm-2 text-center">Hours</label>
							</div>
							<?php $query = mysqli_query($dbc, "SELECT * FROM `workorder_attached` WHERE `src_table`='Staff' AND `deleted`=0 AND `workorderid`='$workorderid' AND `tile_name`='".FOLDER_NAME."' ORDER BY `position` = 'Team Lead' DESC, `position` = 'Primary' DESC, `position` = 'Assigned'");
							$staff = mysqli_fetch_assoc($query);
							do { ?>
								<div class="multi-block">
									<div class="col-sm-3">
										<label class="show-on-mob control-label">Staff:</label>
										<select name="item_id" data-table="workorder_attached" data-id="<?= $staff['id'] ?>" data-id-field="id" data-type="Staff" data-type-field="src_table" class="chosen-select-deselect"><option></option>
											<?php foreach($staff_list as $staff_id) { ?>
												<option <?= $staff_id == $staff['item_id'] ? 'selected' : '' ?> value="<?= $staff_id ?>"><?= get_contact($dbc, $staff_id) ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="col-sm-3">
										<label class="show-on-mob control-label">Position:</label>
										<select name="position" data-table="workorder_attached" data-id="<?= $staff['id'] ?>" data-id-field="id" data-type="Staff" data-type-field="src_table" class="chosen-select-deselect"><option></option>
											<option <?= 'Team Lead' == $staff['position'] ? 'selected' : '' ?> value="Team Lead">Team Lead</option>
											<option <?= 'Primary' == $staff['position'] ? 'selected' : '' ?> value="Primary">Primary</option>
											<?php $positions = mysqli_query($dbc, "SELECT `name` FROM `positions` ORDER BY `name`");
											while($position = mysqli_fetch_assoc($positions)) { ?>
												<option <?= $position['name'] == $staff['position'] ? 'selected' : '' ?> value="<?= $position['name'] ?>"><?= $position['name'] ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="col-sm-3">
										<label class="show-on-mob control-label">Start Shift:</label>
										<input type="text" min=0 step="0.01" name="shift_start" data-table="workorder_attached" data-id="<?= $staff['id'] ?>" data-id-field="id" data-type="Staff" data-type-field="src_table" class="timepicker form-control" value="<?= $staff['shift_start'] ?>">
									</div>
									<div class="col-sm-2">
										<label class="show-on-mob control-label">Hours:</label>
										<input type="number" min=0 step="0.01" name="hours_estimated" data-table="workorder_attached" data-id="<?= $staff['id'] ?>" data-id-field="id" data-type="Staff" data-type-field="src_table" class="form-control" value="<?= $staff['hours_estimated'] ?>">
									</div>
									<div class="col-sm-1">
										<img class="inline-img pull-right" onclick="addMulti(this);" src="../img/icons/ROOK-add-icon.png">
										<img class="inline-img pull-right" onclick="remMulti(this);" src="../img/remove.png">
									</div>
								<div class="clearfix"></div>
								</div>
							<?php } while($staff = mysqli_fetch_assoc($staff)); ?>
						</div>
					</div>
				</div>
			<?php } ?>
			
			<?php if(strpos($value_config, ',Members,') !== FALSE) { ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to attach multiple Members to this work order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_members">
							   Members<span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>
					
					<script> function showMember(src) {
						$('#collapse_members').find('img.black-color').addClass('counterclockwise');
						$(src).removeClass('counterclockwise');
						$('#member_iframe').load(function() {
							$(this).off('load').load(close_member_iframe);
							$('#member_iframe_div').show();
							$('#member_iframe_div').height('calc('+this.contentWindow.document.body.scrollHeight+'px + 6em)');
						}).src('../Members/contacts_inbox.php?edit='+$(src).closest('.multi-block').find('[name=item_id]').val());
					}
					function close_member_iframe() {
						$('#collapse_members').find('img.black-color').addClass('counterclockwise');
						$('#member_iframe_div').hide();
					} </script>
					<div id="collapse_members" class="panel-collapse collapse">
						<div class="panel-body">
							<?php $member_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `category` IN ('Member')"),MYSQLI_ASSOC));
							$query = mysqli_query($dbc, "SELECT * FROM `workorder_attached` WHERE `src_table`='Members' AND `deleted`=0 AND `workorderid`='$workorderid' AND `tile_name`='".FOLDER_NAME."'");
							$member = mysqli_fetch_assoc($query);
							do { ?>
								<div class="multi-block">
									<div class="form-group">
										<label class="col-sm-4 control-label">Member:</label>
										<div class="col-sm-7">
											<select name="item_id" data-table="workorder_attached" data-id="<?= $member['id'] ?>" data-id-field="id" data-type="Members" data-type-field="src_table" class="chosen-select-deselect"><option></option>
												<?php foreach($member_list as $member_id) { ?>
													<option <?= $member_id == $member['item_id'] ? 'selected' : '' ?> value="<?= $member_id ?>"><?= get_contact($dbc, $member_id) ?></option>
												<?php } ?>
											</select>
										</div>
										<div class="col-sm-1">
											<img class="inline-img pull-left black-color counterclockwise" onclick="showMember(this);" src="../img/icons/dropdown-arrow.png">
											<img class="inline-img pull-right" onclick="addMulti(this);" src="../img/icons/ROOK-add-icon.png">
											<img class="inline-img pull-right" onclick="remMulti(this);" src="../img/remove.png">
										</div>
										<div class="clearfix"></div>
									</div>
									<div id="member_iframe_div" style="display:none">
										<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' onclick="close_member_iframe();" width="45px" style='position:relative; right: 1em; top:1em; float:right; cursor:pointer;'>
										<span class='iframe_title' style='color:white; font-weight:bold; position: relative; left:1em; top:0.25em; font-size:3em;'>Member</span>
										<iframe name="member_iframe" id="member_iframe" style="border: 1em solid gray; border-top: 5em solid gray; margin-top: -4em; width: 100%;" src=""></iframe>
									</div>
								</div>
							<?php } while($member = mysqli_fetch_assoc($query)); ?>
						</div>
					</div>
				</div>
			<?php } ?>
			
			<?php if(strpos($value_config, ',Clients,') !== FALSE) { ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to attach multiple Clients to this work order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_client">
							   Clients<span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<script> function showClient(src) {
						$('#collapse_client').find('img.black-color').addClass('counterclockwise');
						$(src).removeClass('counterclockwise');
						$('#client_iframe').load(function() {
							$(this).off('load').load(close_client_iframe);
							$('#client_iframe_div').show();
							$('#client_iframe_div').height('calc('+this.contentWindow.document.body.scrollHeight+'px + 6em)');
						}).src('../ClientInfo/contacts_inbox.php?edit='+$(src).closest('.multi-block').find('[name=item_id]').val());
					}
					function close_client_iframe() {
						$('#collapse_client').find('img.black-color').addClass('counterclockwise');
						$('#client_iframe_div').hide();
					} </script>
					<div id="collapse_client" class="panel-collapse collapse">
						<div class="panel-body">
							<?php $client_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `category` IN ('Clients')"),MYSQLI_ASSOC));
							$query = mysqli_query($dbc, "SELECT * FROM `workorder_attached` WHERE `src_table`='Clients' AND `deleted`=0 AND `workorderid`='$workorderid' AND `tile_name`='".FOLDER_NAME."'");
							$client = mysqli_fetch_assoc($query);
							do { ?>
								<div class="multi-block">
									<div class="form-group">
										<label class="col-sm-4 control-label">Client:</label>
										<div class="col-sm-7">
											<select name="item_id" data-table="workorder_attached" data-id="<?= $staff['id'] ?>" data-id-field="id" data-type="Clients" data-type-field="src_table" class="chosen-select-deselect"><option></option>
												<?php foreach($client_list as $client_id) { ?>
													<option <?= $client_id == $client['item_id'] ? 'selected' : '' ?> value="<?= $client_id ?>"><?= get_contact($dbc, $client_id) ?></option>
												<?php } ?>
											</select>
										</div>
										<div class="col-sm-1">
											<img class="inline-img pull-left black-color counterclockwise" onclick="showClient(this);" src="../img/icons/dropdown-arrow.png">
											<img class="inline-img pull-right" onclick="addMulti(this);" src="../img/icons/ROOK-add-icon.png">
											<img class="inline-img pull-right" onclick="remMulti(this);" src="../img/remove.png">
										</div>
										<div class="clearfix"></div>
									</div>
									<div id="client_iframe_div" style="display:none">
										<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' onclick="close_client_iframe();" width="45px" style='position:relative; right: 1em; top:1em; float:right; cursor:pointer;'>
										<span class='iframe_title' style='color:white; font-weight:bold; position: relative; left:1em; top:0.25em; font-size:3em;'>Client</span>
										<iframe name="client_iframe" id="client_iframe" style="border: 1em solid gray; border-top: 5em solid gray; margin-top: -4em; width: 100%;" src=""></iframe>
									</div>
								</div>
							<?php } while($client = mysqli_fetch_assoc($query)); ?>
						</div>
					</div>
				</div>
			<?php } ?>
			
			<?php if(strpos($value_config, ',Wait List,') !== FALSE) { ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to attach multiple additional individuals to this work order as a Wait List."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_waitlist">
							   Wait List<span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_waitlist" class="panel-collapse collapse">
						<div class="panel-body">
							<?php $waitlist_list = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name`, `cell_phone` FROM `contacts` WHERE `category` IN ('Members','Clients','Customer','Patient')"));
							$query = mysqli_query($dbc, "SELECT * FROM `workorder_attached` WHERE `src_table`='Wait List' AND `deleted`=0 AND `workorderid`='$workorderid' AND `tile_name`='".FOLDER_NAME."'");
							$waitlist = mysqli_fetch_assoc($query);
							do { ?>
								<div class="multi-block">
									<div class="form-group">
										<label class="col-sm-4 control-label">Wait List:</label>
										<div class="col-sm-7">
											<select name="item_id" data-table="workorder_attached" data-id="<?= $staff['id'] ?>" data-id-field="id" data-type="Wait List" data-type-field="src_table" class="chosen-select-deselect"><option></option>
												<?php foreach($waitlist_list as $waitlist_option) { ?>
													<option <?= $waitlist_option['contactid'] == $waitlist['item_id'] ? 'selected' : '' ?> value="<?= $waitlist_option['contactid'] ?>"><?= $waitlist_option['first_name'].' '.$waitlist_option['last_name'].($waitlist_option['cell_phone'] != '' ? ' - '.$waitlist_option['cell_phone'] : '') ?></option>
												<?php } ?>
											</select>
										</div>
										<div class="col-sm-1">
											<img class="inline-img pull-right" onclick="addMulti(this);" src="../img/icons/ROOK-add-icon.png">
											<img class="inline-img pull-right" onclick="remMulti(this);" src="../img/remove.png">
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
							<?php } while($waitlist = mysqli_fetch_assoc($query)); ?>
						</div>
					</div>
				</div>
			<?php } ?>

            <?php if (strpos($value_config, ','."Check In".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
						<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to record Check Ins for the Work Order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_checkin" >
                           Check In<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_checkin" class="panel-collapse collapse">
                    <div class="panel-body">
						<?php include('add_workorder_checkin.php'); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Medication".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
						<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to record Medication Administration for the Work Order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_medication" >
                           Medication Administration<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_medication" class="panel-collapse collapse">
                    <div class="panel-body">
						<div class="hide-titles-mob">
							<label class="col-sm-3 text-center">Medication</label>
							<label class="col-sm-3 text-center">Dosage</label>
							<label class="col-sm-3 text-center">Time</label>
							<label class="col-sm-2 text-center">Administered</label>
						</div>
						<?php $medications = mysqli_query($dbc, "SELECT * FROM `workorder_attached` WHERE `src_table`='medication' AND `line_id`='0' AND `deleted`=0");
						$medication = mysqli_fetch_assoc($medications);
						do { ?>
							<div class="multi-block">
								<div class="col-sm-3">
									<label class="show-on-mob">Medication:</label>
									<input type="text" name="position" data-table="workorder_attached" data-id="<?= $medication['id'] ?>" data-id-field="id" data-type="medication" data-type-field="src_table" class="form-control" value="<?= $medication['position'] ?>">
								</div>
								<div class="col-sm-3">
									<label class="show-on-mob">Dosage:</label>
									<input type="text" name="description" data-table="workorder_attached" data-id="<?= $medication['id'] ?>" data-id-field="id" data-type="medication" data-type-field="src_table" class="form-control" value="<?= $medication['description'] ?>">
								</div>
								<div class="col-sm-3">
									<label class="show-on-mob">Time:</label>
									<input type="text" name="shift_start" data-table="workorder_attached" data-id="<?= $medication['id'] ?>" data-id-field="id" data-type="medication" data-type-field="src_table" class="form-control" value="<?= $medication['shift_start'] ?>">
								</div>
								<div class="col-sm-2">
									<div class="toggleSwitch">
										<input type="hidden" name="arrived" data-table="workorder_attached" data-id="<?= $medication['id'] ?>" data-id-field="id" data-type="medication" data-type-field="src_table" value="<?= $medication['arrived'] ?>" class="toggle">
										<span style="<?= $medication['arrived'] > 0 ? 'display: none;' : '' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" class="inline-img"> Not Administered</span>
										<span style="<?= $medication['arrived'] > 0 ? '' : 'display: none;' ?>"><img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" class="inline-img"> Administered</span>
									</div>
								</div>
								<div class="col-sm-1">
									<img class="inline-img pull-right" onclick="addMulti(this);" src="../img/icons/ROOK-add-icon.png">
									<img class="inline-img pull-right" onclick="remMulti(this);" src="../img/remove.png">
								</div>
								<div class="clearfix"></div>
							</div>
						<?php } while($medication = mysqli_fetch_assoc($medications)); ?>
						<div class="form-group">
							<label class="col-sm-4 control-label">Administered By:</label>
							<div class="col-sm-8">
								<?php $output_name = "meds_administered";
								include('../phpsign/sign_multiple.php'); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Witnessed By:</label>
							<div class="col-sm-8">
								<?php $output_name = "meds_witness";
								include('../phpsign/sign_multiple.php'); ?>
							</div>
						</div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Services".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
						<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add Services for the Work Order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_services" >
                           Services<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_services" class="panel-collapse collapse">
                    <div class="panel-body">
						<?php include ('add_workorder_info.php'); ?>

						<div class="form-group">
							<label for="first_name" class="col-sm-4 control-label"><span class="text-red">*</span> Heading:</label>
							<div class="col-sm-8">
								<input name="heading" type="text" data-table="workorder" data-id="<?= $workorderid ?>" data-id-field="workorderid" value="<?php echo $heading; ?>" class="form-control">

								<!--<select data-placeholder="Choose a Heading..." name="heading" class="chosen-select-deselect form-control" width="380">
								  <option value=""></option>
								  <?php
									$tabs = get_config($dbc, 'workorder_heading');
									$each_tab = explode(',', $tabs);
									foreach ($each_tab as $cat_tab) {
										if ($heading == $cat_tab) {
											$selected = 'selected="selected"';
										} else {
											$selected = '';
										}
										echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
									}
								  ?>
								</select>
								-->
							</div>
						</div>
						
					  <div class="form-group">
						<label for="site_name" class="col-sm-4 control-label">Description:</label>
						<div class="col-sm-8">
						  Use Service Description : <input type="checkbox" onclick='changeDesc(this);' style="height: 20px; width: 20px;">

						  <textarea name="assign_work" id="assign_work" data-table="workorder" data-id="<?= $workorderid ?>" data-id-field="workorderid" rows="4" cols="50" class="form-control" ><?php echo $assign_work; ?></textarea>
						</div>
					  </div>
                        <?php include ('add_view_workorder_checklist.php'); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Equipment".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
						<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add Equipment for the Work Order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_equipment" >
                           Equipment<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_equipment" class="panel-collapse collapse">
                    <div class="panel-body">
						<script>
						function filterEquipment(select) {
							var block = $(select).closest('.multi-block');
							if(select.name == 'equipmentid') {
								var option = $(select).find('option:selected');
								block.find('[name=eq_category]').val(option.data('category')).trigger('change.select2');
								block.find('[name=eq_make]').val(option.data('make')).trigger('change.select2');
								block.find('[name=eq_model]').val(option.data('model')).trigger('change.select2');
							} else if(select.name == 'eq_category') {
								block.find('[name=equipmentid] option').show().filter(function() { return $(this).data('category') != select.value; }).hide();
								block.find('[name=equipmentid]').trigger('change.select2');
							} else if(select.name == 'eq_make') {
								block.find('[name=equipmentid] option').show().filter(function() { return $(this).data('make') != select.value; }).hide();
								block.find('[name=equipmentid]').trigger('change.select2');
							} else if(select.name == 'eq_model') {
								block.find('[name=equipmentid] option').show().filter(function() { return $(this).data('model') != select.value; }).hide();
								block.find('[name=equipmentid]').trigger('change.select2');
							}
						}
						</script>
						<?php $equipment_list = mysqli_query($dbc, "SELECT `workorder_attached`.`id`, `workorder_attached`.`item_id`, `workorder_attached`.`rate`, `workorder_attached`.`status`, `equipment`.* FROM `workorder_attached` LEFT JOIN `equipment` ON `workorder_attached`.`src_table`='equipment' AND `workorder_attached`.`item_id`=`equipment`.`equipmentid` WHERE `workorder_attached`.`workorderid`='$workorderid' AND `workorder_attached`.`deleted`=0");
						$equipment = mysqli_fetch_assoc($equipment_list);
						do { ?>
							<div class="multi-block">
								<div class="form-group">
									<label class="control-label col-sm-4">Category:</label>
									<div class="col-sm-8">
										<select name="eq_category" class="chosen-select-deselect" onchange="filterEquipment(this);"><option></option>
											<?php $groups = mysqli_query($dbc, "SELECT `category` FROM `equipment` WHERE `deleted`=0 GROUP BY `category` ORDER BY `category`");
											while($category = mysqli_fetch_assoc($groups)) { ?>
												<option <?= $equipment['category'] == $category['category'] ? 'selected' : '' ?> value="<?= $category['category'] ?>"><?= $category['category'] ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4">Make:</label>
									<div class="col-sm-8">
										<select name="eq_make" class="chosen-select-deselect" onchange="filterEquipment(this);"><option></option>
											<?php $groups = mysqli_query($dbc, "SELECT `make` FROM `equipment` WHERE `deleted`=0 GROUP BY `make` ORDER BY `make`");
											while($make = mysqli_fetch_assoc($groups)) { ?>
												<option <?= $equipment['make'] == $make['make'] ? 'selected' : '' ?> value="<?= $make['make'] ?>"><?= $make['make'] ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4">Model:</label>
									<div class="col-sm-8">
										<select name="eq_model" class="chosen-select-deselect" onchange="filterEquipment(this);"><option></option>
											<?php $groups = mysqli_query($dbc, "SELECT `model` FROM `equipment` WHERE `deleted`=0 GROUP BY `model` ORDER BY `model`");
											while($model = mysqli_fetch_assoc($groups)) { ?>
												<option <?= $equipment['model'] == $model['model'] ? 'selected' : '' ?> value="<?= $model['model'] ?>"><?= $model['model'] ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4">Unit #:</label>
									<div class="col-sm-8">
										<select name="item_id" data-table="workorder_attached" data-id="<?= $equipment['id'] ?>" data-id-field="id" data-type="equipment" data-type-field="src_table" class="chosen-select-deselect" onchange="filterEquipment(this);"><option></option>
											<?php $groups = mysqli_query($dbc, "SELECT `category`, `make`, `model`, `unit_number`, `equipmentid` FROM `equipment` WHERE `deleted`=0 ORDER BY `category`, `make`, `model`, `unit_number`");
											while($units = mysqli_fetch_assoc($groups)) { ?>
												<option data-category="<?= $units['category'] ?>" data-make="<?= $units['make'] ?>" data-model="<?= $units['model'] ?>" <?= $equipment['item_id'] == $units['equipmentid'] ? 'selected' : '' ?> value="<?= $units['equipmentid'] ?>"><?= $units['unit_number'] ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4">Rate:</label>
									<div class="col-sm-8">
										<input type="number" min=0 step="0.01" name="rate" data-table="workorder_attached" data-id="<?= $equipment['id'] ?>" data-id-field="id" data-type="equipment" data-type-field="src_table" class="form-control" value="<?= $equipment['rate'] ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4">Status:</label>
									<div class="col-sm-7">
										<select name="status" data-table="workorder_attached" data-id="<?= $equipment['id'] ?>" data-id-field="id" data-type="equipment" data-type-field="src_table" class="chosen-select-deselect"><option></option>
											<option value='Active' <?php if ($equipment['status']=='Active') echo 'selected="selected"';?> >Active</option>
											<option value='In Service' <?php if ($equipment['status']=='In Service') echo 'selected="selected"';?> >In Service</option>
											<option value='Service Required' <?php if ($equipment['status']=='Service Required') echo 'selected="selected"';?> >Service Required</option>
											<option value='On Site' <?php if ($equipment['status']=='On Site') echo 'selected="selected"';?> >On Site</option>
											<option value='Inactive' <?php if ($equipment['status']=='Inactive') echo 'selected="selected"';?> >Inactive</option>
											<option value='Sold' <?php if ($equipment['status']=='Sold') echo 'selected="selected"';?> >Sold</option>
										</select>
									</div>
									<div class="col-sm-1">
										<img class="inline-img pull-right" onclick="addMulti(this);" src="../img/icons/ROOK-add-icon.png">
										<img class="inline-img pull-right" onclick="remMulti(this);" src="../img/remove.png">
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						<?php } while($equipment = mysqli_fetch_assoc($equipment_list)); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Checklist".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
						<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to create Checklists for the Work Order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_checklist" >
                           Checklist<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_checklist" class="panel-collapse collapse">
                    <div class="panel-body">
						<?php include('add_workorder_checklist.php'); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Emergency".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
						<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to record Emergency Plan information for the Work Order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_emergency" >
                           Emergency Plan<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_emergency" class="panel-collapse collapse">
                    <div class="panel-body">
						<div class="form-group">
							<label class="control-label col-sm-4">Police/Fire/EMS:</label>
							<div class="col-sm-8">
								<input type="text" name="police_contact" data-table="workorder" data-id="<?= $workorderid ?>" data-id-field="workorderid" value="<?= $get_workorder['police_contact'] == '' ? '911' : $get_workorder['police_contact'] ?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4">Poison Control:</label>
							<div class="col-sm-8">
								<input type="text" name="poison_contact" data-table="workorder" data-id="<?= $workorderid ?>" data-id-field="workorderid" value="<?= $get_workorder['poison_contact'] ?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4">Non-Emergency Contact:</label>
							<div class="col-sm-8">
								<input type="text" name="non_emergency_contact" data-table="workorder" data-id="<?= $workorderid ?>" data-id-field="workorderid" value="<?= $get_workorder['non_emergency_contact'] ?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4">Emergency Contact:</label>
							<div class="col-sm-8">
								<input type="text" name="emergency_contact" data-table="workorder" data-id="<?= $workorderid ?>" data-id-field="workorderid" value="<?= $get_workorder['emergency_contact'] ?>" class="form-control">
							</div>
						</div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Safety".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
						<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to review Safety Documents for the Work Order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_safety" >
                           Safety<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_safety" class="panel-collapse collapse">
                    <div class="panel-body">
						<?php include('add_workorder_safety.php'); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Materials".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
						<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add Materials for the Work Order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_materials" >
                           Materials<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_materials" class="panel-collapse collapse">
                    <div class="panel-body">
						<script>
						function filterMaterials(select) {
							var block = $(select).closest('.multi-block');
							if(select.name == 'materialid') {
								var option = $(select).find('option:selected');
								block.find('[name=mat_category]').val(option.data('category')).trigger('change.select2');
								block.find('[name=mat_sub]').val(option.data('sub-category')).trigger('change.select2');
							} else if(select.name == 'mat_category') {
								block.find('[name=materialid] option').show().filter(function() { return $(this).data('category') != select.value; }).hide();
								block.find('[name=materialid]').trigger('change.select2');
							} else if(select.name == 'mat_sub') {
								block.find('[name=materialid] option').show().filter(function() { return $(this).data('sub-category') != select.value; }).hide();
								block.find('[name=materialid]').trigger('change.select2');
							}
						}
						</script>
						<?php $material_list = mysqli_query($dbc, "SELECT `workorder_attached`.`id`, `workorder_attached`.`item_id`, `workorder_attached`.`rate`, `workorder_attached`.`status`, `material`.* FROM `workorder_attached` LEFT JOIN `material` ON `workorder_attached`.`src_table`='material' AND `workorder_attached`.`item_id`=`material`.`materialid` WHERE `workorder_attached`.`workorderid`='$workorderid' AND `workorder_attached`.`deleted`=0");
						$material = mysqli_fetch_assoc($material_list);
						do { ?>
							<div class="multi-block">
								<div class="form-group">
									<label class="control-label col-sm-4">Category:</label>
									<div class="col-sm-8">
										<select name="mat_category" class="chosen-select-deselect" onchange="filterMaterials(this);"><option></option>
											<?php $groups = mysqli_query($dbc, "SELECT `category` FROM `material` WHERE `deleted`=0 GROUP BY `category` ORDER BY `category`");
											while($category = mysqli_fetch_assoc($groups)) { ?>
												<option <?= $material['category'] == $category['category'] ? 'selected' : '' ?> value="<?= $category['category'] ?>"><?= $category['category'] ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4">Sub-Category:</label>
									<div class="col-sm-8">
										<select name="mat_sub" class="chosen-select-deselect" onchange="filterMaterials(this);"><option></option>
											<?php $groups = mysqli_query($dbc, "SELECT `sub_category` FROM `material` WHERE `deleted`=0 GROUP BY `sub_category` ORDER BY `sub_category`");
											while($sub_cat = mysqli_fetch_assoc($groups)) { ?>
												<option <?= $material['sub_category'] == $sub_cat['sub_category'] ? 'selected' : '' ?> value="<?= $sub_cat['sub_category'] ?>"><?= $sub_cat['sub_category'] ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4">Type:</label>
									<div class="col-sm-8">
										<select name="item_id" data-table="workorder_attached" data-id="<?= $material['id'] ?>" data-id-field="id" data-type="material" data-type-field="src_table" class="chosen-select-deselect" onchange="filterEquipment(this);"><option></option>
											<?php $groups = mysqli_query($dbc, "SELECT `category`, `sub_category`, `name`, `materialid` FROM `material` WHERE `deleted`=0 ORDER BY `category`, `sub_category`, `name`");
											while($units = mysqli_fetch_assoc($groups)) { ?>
												<option data-category="<?= $units['category'] ?>" data-sub-category="<?= $units['sub_category'] ?>" <?= $material['item_id'] == $units['materialid'] ? 'selected' : '' ?> value="<?= $units['materialid'] ?>"><?= $units['name'] ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4">Quantity:</label>
									<div class="col-sm-7">
										<input type="number" min=0 step="0.01" name="qty" data-table="workorder_attached" data-id="<?= $material['id'] ?>" data-id-field="id" data-type="material" data-type-field="src_table" class="form-control" value="<?= $material['qty'] ?>">
									</div>
									<div class="col-sm-1">
										<img class="inline-img pull-right" onclick="addMulti(this);" src="../img/icons/ROOK-add-icon.png">
										<img class="inline-img pull-right" onclick="remMulti(this);" src="../img/remove.png">
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						<?php } while($material = mysqli_fetch_assoc($material_list)); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Purchase Orders".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
						<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to create Purchase Orders for the Work Order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_purchase_orders" >
                           Purchase Orders<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_purchase_orders" class="panel-collapse collapse">
                    <div class="panel-body">
						<?php include('add_workorder_purchase_orders.php'); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Documents".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
						<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to attach any links and attachments to the Work Order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_doc">
						   Documents<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_doc" class="panel-collapse collapse">
                    <div class="panel-body">
                    <?php include ('add_view_workorder_documents.php'); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Deliverables".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
						<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to set the deliverables for the Work Order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_deli" >
                           Deliverables<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_deli" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php include ('add_view_workorder_deliverables.php'); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Delivery".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
						<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to set the deliverables for the Work Order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_delivery" >
                           Delivery Details<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_delivery" class="panel-collapse collapse">
                    <div class="panel-body">
						<h4>Pickup</h4>
						<?php if (strpos($value_config, ','."Delivery Pickup".',') !== FALSE) { ?>
							<div class="form-group">
								<label class="col-sm-4 control-label">Location Name:</label>
								<div class="col-sm-8">
									<input type="text" name="pickup_name" class="form-control" data-table="workorder" data-id="<?= $get_workorder['workorderid'] ?>" data-id-field="workorderid" value="<?= $get_workorder['pickup_name'] ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Address:</label>
								<div class="col-sm-8">
									<input type="text" name="pickup_address" class="form-control" data-table="workorder" data-id="<?= $get_workorder['workorderid'] ?>" data-id-field="workorderid" value="<?= $get_workorder['pickup_address'] ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">City:</label>
								<div class="col-sm-8">
									<input type="text" name="pickup_city" class="form-control" data-table="workorder" data-id="<?= $get_workorder['workorderid'] ?>" data-id-field="workorderid" value="<?= $get_workorder['pickup_city'] ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Postal Code:</label>
								<div class="col-sm-8">
									<input type="text" name="pickup_postal_code" class="form-control" data-table="workorder" data-id="<?= $get_workorder['workorderid'] ?>" data-id-field="workorderid" value="<?= $get_workorder['pickup_postal_code'] ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Google Maps Link:</label>
								<div class="col-sm-8">
									<input type="text" name="pickup_link" class="form-control" data-table="workorder" data-id="<?= $get_workorder['workorderid'] ?>" data-id-field="workorderid" value="<?= $get_workorder['pickup_link'] ?>">
								</div>
							</div>
						<?php } ?>
						<?php if (strpos($value_config, ','."Delivery Pickup Date".',') !== FALSE) { ?>
							<input type="hidden" name="pickup_date" data-table="workorder" data-id="<?= $get_workorder['workorderid'] ?>" data-id-field="workorderid" value="<?= $get_workorder['pickup_date'] ?>">
							<div class="form-group">
								<label class="col-sm-4 control-label">Pickup Date:</label>
								<div class="col-sm-8">
									<input type="text" name="pickup_date_date" class="form-control datepicker" onchange="$('[name=pickup_date]').val($('[name=pickup_date_date]').val()+' '+$('[name=pickup_date_time]').val()).change();" value="<?= substr($get_workorder['pickup_date'],0,10) ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Pickup Time:</label>
								<div class="col-sm-8">
									<input type="text" name="pickup_date_time" class="form-control datepicker" onchange="$('[name=pickup_date]').val($('[name=pickup_date_date]').val()+' '+$('[name=pickup_date_time]').val()).change();" value="<?= substr($get_workorder['pickup_date'],10) ?>">
								</div>
							</div>
						<?php } ?>
						<?php if (strpos($value_config, ','."Delivery Pickup Order".',') !== FALSE) { ?>
							<div class="form-group">
								<label class="col-sm-4 control-label">Order #:</label>
								<div class="col-sm-8">
									<select name="pickup_order" class="chosen-select-deselect form-control" data-table="workorder" data-id="<?= $get_workorder['workorderid'] ?>" data-id-field="workorderid"><option></option>
										<?php $orders = mysqli_query($dbc, "SELECT * FROM `sales_order` WHERE `deleted`=0");
										while($order = mysqli_fetch_assoc($orders)) { ?>
											<option <?= $order['posid'] == $get_workorder['pickup_order'] ? 'selected' : '' ?> value="<?= $order['posid'] ?>">Order #<?= $order['posid'] ?> <?= $order['invoice_date'] ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						<?php } ?>
						<h4>Drop Off</h4>
						<?php if (strpos($value_config, ','."Delivery Dropoff".',') !== FALSE) { ?>
							<div class="form-group">
								<label class="col-sm-4 control-label">Address:</label>
								<div class="col-sm-8">
									<input type="text" name="dropoff_address" class="form-control" data-table="workorder" data-id="<?= $get_workorder['workorderid'] ?>" data-id-field="workorderid" value="<?= $get_workorder['dropoff_address'] ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">City:</label>
								<div class="col-sm-8">
									<input type="text" name="dropoff_city" class="form-control" data-table="workorder" data-id="<?= $get_workorder['workorderid'] ?>" data-id-field="workorderid" value="<?= $get_workorder['dropoff_city'] ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Postal Code:</label>
								<div class="col-sm-8">
									<input type="text" name="dropoff_postal_code" class="form-control" data-table="workorder" data-id="<?= $get_workorder['workorderid'] ?>" data-id-field="workorderid" value="<?= $get_workorder['dropoff_postal_code'] ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Google Maps Link:</label>
								<div class="col-sm-8">
									<input type="text" name="dropoff_link" class="form-control" data-table="workorder" data-id="<?= $get_workorder['workorderid'] ?>" data-id-field="workorderid" value="<?= $get_workorder['dropoff_link'] ?>">
								</div>
							</div>
						<?php } ?>
						<?php if (strpos($value_config, ','."Delivery Dropoff Date".',') !== FALSE) { ?>
							<input type="hidden" name="dropoff_date" data-table="workorder" data-id="<?= $get_workorder['workorderid'] ?>" data-id-field="workorderid" value="<?= $get_workorder['dropoff_date'] ?>">
							<div class="form-group">
								<label class="col-sm-4 control-label">Drop Off Date:</label>
								<div class="col-sm-8">
									<input type="text" name="dropoff_date_date" class="form-control datepicker" onchange="$('[name=dropoff_date]').val($('[name=dropoff_date_date]').val()+' '+$('[name=dropoff_date_time]').val()).change();" value="<?= substr($get_workorder['dropoff_date'],0,10) ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Drop Off Time:</label>
								<div class="col-sm-8">
									<input type="text" name="dropoff_date_time" class="form-control datepicker" onchange="$('[name=dropoff_date]').val($('[name=dropoff_date_date]').val()+' '+$('[name=dropoff_date_time]').val()).change();" value="<?= substr($get_workorder['dropoff_date'],10) ?>">
								</div>
							</div>
						<?php } ?>
						<?php if (strpos($value_config, ','."Delivery Dropoff Order".',') !== FALSE) { ?>
							<div class="form-group">
								<label class="col-sm-4 control-label">Order #:</label>
								<div class="col-sm-8">
									<select name="dropoff_order" class="chosen-select-deselect form-control" data-table="workorder" data-id="<?= $get_workorder['workorderid'] ?>" data-id-field="workorderid"><option></option>
										<?php $orders = mysqli_query($dbc, "SELECT * FROM `sales_order` WHERE `deleted`=0");
										while($order = mysqli_fetch_assoc($orders)) { ?>
											<option <?= $order['posid'] == $get_workorder['dropoff_order'] ? 'selected' : '' ?> value="<?= $order['posid'] ?>">Order #<?= $order['posid'] ?> <?= $order['invoice_date'] ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						<?php } ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Description".',') !== FALSE) { ?>
            <!--
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_desc" >
                           Description<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_desc" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php
                        if(empty($_GET['workorderid'])) { ?>
                          <div class="form-group">
                            <label for="site_name" class="col-sm-4 control-label">Description:</label>
                            <div class="col-sm-8">
                              <textarea name="assign_work" rows="4" cols="50" class="form-control" ></textarea>
                            </div>
                          </div>
                        <div class="form-group">
                            <div class="col-sm-4">
                                <a href="workorder.php" class="btn brand-btn">Back</a>
                            </div>
                            <div class="col-sm-8">
                                <button type="submit" name="submit" value="submit" class="btn brand-btn pull-right">Submit</button>
                            </div>
                        </div>
                        <?php } else { ?>
                          <div class="form-group">
                            <label for="site_name" class="col-sm-4 control-label">Description:</label>
                            <div class="col-sm-8">
                              <?php echo html_entity_decode($assign_work); ?>
                            </div>
                          </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            -->
            <?php } ?>

            <?php if (strpos($value_config, ','."Timer".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_timer" >
                           Time Tracking<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_timer" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php include ('add_view_workorder_timer.php'); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php //if(!empty($_GET['workorderid'])) { ?>
				<?php if (strpos($value_config, ','."Timer".',') !== FALSE) { ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_dayt" >
							   Day Tracking<span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_dayt" class="panel-collapse collapse">
						<div class="panel-body">
							<?php include ('add_view_day_tracking.php'); ?>
						</div>
					</div>
				</div>
				<?php } ?>
				
				<?php if (strpos($value_config, ','."Addendum".',') !== FALSE) {
					$comment_type = 'addendum'; ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_addendum" >
								   Addendum<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_addendum" class="panel-collapse collapse">
							<div class="panel-body">
							 <?php include ('add_view_workorder_comment.php'); ?>
							</div>
						</div>
					</div>
				<?php } ?>
				
				<?php if (strpos($value_config, ','."Client Log".',') !== FALSE) {
					$comment_type = 'client_log'; ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_client_log" >
								   Log Notes<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_client_log" class="panel-collapse collapse">
							<div class="panel-body">
							 <?php include ('add_view_workorder_comment.php'); ?>
							</div>
						</div>
					</div>
				<?php } ?>
				
				<?php if (strpos($value_config, ','."Debrief".',') !== FALSE) {
					$comment_type = 'debrief'; ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_debrief" >
								   Debrief<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_debrief" class="panel-collapse collapse">
							<div class="panel-body">
							 <?php include ('add_view_workorder_comment.php'); ?>
							</div>
						</div>
					</div>
				<?php } ?>
				
				<?php if (strpos($value_config, ','."Notes".',') !== FALSE) {
					$comment_type = 'note'; ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_notes" >
								   Notes<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_notes" class="panel-collapse collapse">
							<div class="panel-body">
							 <?php include ('add_view_workorder_comment.php'); ?>
							</div>
						</div>
					</div>

					<!--Removed due to not being used
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_pnotes" >
								   Project Notes<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_pnotes" class="panel-collapse collapse">
							<div class="panel-body">
							 <?php //include ('add_view_project_comment.php'); ?>
							</div>
						</div>
					</div>-->
				<?php } ?>
				
				<?php if (strpos($value_config, ','."Summary".',') !== FALSE) { ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_summary" >
								   Summary<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_summary" class="panel-collapse collapse">
							<div class="panel-body">
								<?php if(strpos($value_config, ',Time Tracking,') !== FALSE) { ?>
									<div class="form-group">
										<label class="col-sm-4 control-label">Start Time On Site:</label>
										<div class="col-sm-8">
											<input type="text" name="start_time" data-table="workorder" data-id="<?= $workorderid ?>" data-id-field="workorderid" class="form-control datetimepicker" value="<?= $get_workorder['start_time'] ?>">
										</div>
										<label class="col-sm-4 control-label">End Time On Site:</label>
										<div class="col-sm-8">
											<input type="text" name="end_time" data-table="workorder" data-id="<?= $workorderid ?>" data-id-field="workorderid" class="form-control datetimepicker" value="<?= $get_workorder['end_time'] ?>">
										</div>
										<div class="col-sm-4 text-center">Staff</div>
										<div class="col-sm-4 text-center">Task</div>
										<div class="col-sm-3 text-center"><span class="popover-examples list-inline">
												<a href="" data-toggle="tooltip" data-placement="top" title="This is the time that has been saved. It does not include time currently being tracked. It cannot be edited while you are tracking time. In order to edit it, you will first need to stop the timer."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
											</span>Hours Tracked</div>
										<?php $staff = mysqli_query($dbc, "SELECT * FROM `workorder_attached` WHERE `tile_name`='".FOLDER_NAME."' AND `workorderid`='$workorderid' AND `deleted`=0 AND `position`!='Team Lead' AND `src_table`='Staff'");
										$summary = mysqli_fetch_array($staff);
										do { ?>
											<div class="form-group summary">
												<div class="col-sm-4"><select name="item_id" data-table="workorder_attached" data-id="<?= $summary['id'] ?>" data-id-field="id" data-type="Staff" data-type-field="src_table" class="form-control chosen-select-deselect"><option></option>
													<?php foreach($staff_list as $id) {
														echo "<option ".($id == $summary['item_id'] ? 'selected' : '')." value='$id'>".get_contact($dbc, $id)."</option>";
													} ?></select></div>
												<div class="col-sm-4"><select name="position" data-table="workorder_attached" data-id="<?= $summary['id'] ?>" data-id-field="id" data-type="Staff" data-type-field="src_table" class="form-control chosen-select-deselect" data-row="<?= $j ?>"><option></option>
														<?php foreach($task_list as $task_group) {
															$task_group = explode('*#*',$task_group);
															echo "<optgroup label='".$task_group[0]." Tasks' />";
															unset($task_group[0]); ?>
															<?php foreach($task_group as $task_name) { ?>
																<option <?= ($summary['position'] == $task_name ? 'selected' : '') ?> value="<?= $task_name ?>"><?= $task_name ?></option>
															<?php } ?>
														<?php } ?>
														<option></option>
													</select>
												</div>
												<div class="col-sm-3"><input data-disabled="<?= $summary['hours_tracked'] > 0 ? 'true' : 'false' ?>" <?= $summary_timer > 0 && empty($summary['hours_tracked']) ? 'readonly' : '' ?> data-table="workorder_attached" data-id="<?= $summary['id'] ?>" data-id-field="id" data-type="Staff" data-type-field="src_table" type="number" name="hours_tracked" value="<?= $summary['hours_tracked'] ?>" class="form-control" min="0" step="any"></div>
												<div class="col-sm-1"><a href="" onclick="$(this).closest('.form-group.summary').remove(); return false;"><img src="<?= WEBSITE_URL ?>/img/remove.png"></a></div>
												<input type="hidden" name="summary_timer_start[]" value="<?= $summary['timer_start'] ?>">
												<input type="hidden" name="summary_disabled[]" value="<?= $summary['hours_tracked'] ?>">
											</div>
										<?php } while($summary = mysqli_fetch_array($staff)); ?>
										<button class="btn brand-btn pull-right" id="summary_btn" onclick="add_staff_summary(); return false;">Add</button>
										<div class="clearfix"></div>
										<label class="col-sm-4 control-label">Total Time On Site:</label>
										<div class="col-sm-8">
											<input type="text" name="total_time_on_site" class="form-control timepicker" value="">
										</div>
									</div>
								<?php } ?>
								<?php if(strpos($value_config, ',Member Log Notes,') !== FALSE) { ?>
									<h4>Member Specific Daily Log Notes</h4>
									<?php $comment_type = 'member_note';
									include ('add_view_workorder_comment.php');
								} ?>
								<div class="form-group">
									<label class="col-sm-4">Signature</label>
									<div class="col-sm-8">
										<?php $output_name = 'summary_signature';
										include('../phpsign/sign_multiple.php'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
            <?php //} ?>

        </div>

        <div class="form-group">
			<p><span class="hp-red"><em>Required Fields *</em></span></p>
        </div>
        <style>
            .chosen-container {
                min-width: 700px !important;
            }
        </style>

    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>