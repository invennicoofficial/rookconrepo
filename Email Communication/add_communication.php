<?php
/*
Add Vendor
*/
include ('../include.php');
error_reporting(0);
$back_url = 'email_communication.php?type=Internal';
if(!empty($_GET['from_url'])) {
	$back_url = urldecode($_GET['from_url']);
}
else if($communication_type == 'Internal') {
	$back_url = 'email_communication.php?type=Internal';
}
else if($communication_type == 'External') {
	$back_url = 'email_communication.php?type=External';
}

if (isset($_POST['submit'])) {
    $communication_type = $_POST['comm_type'];
    $businessid = $_POST['businessid'];
	$contactid = $_POST['contactid'];
 	$projectid = $_POST['projectid'];
	$client_projectid = '';
	if(substr($projectid,0,1) == 'C') {
		$client_projectid = substr($projectid,1);
		$projectid = '';
	}

    $subject = htmlentities($_POST['subject']);
    $email_body = htmlentities($_POST['email_body']);

    $from_name = filter_var($_POST['from_name'],FILTER_SANITIZE_STRING);
    $from_email = filter_var($_POST['from_email'],FILTER_SANITIZE_STRING);
    $to_contact = implode(',',$_POST['businesscontact_to_emailid']);
    $cc_contact = implode(',',$_POST['businesscontact_cc_emailid']);
    $to_staff = implode(',',$_POST['companycontact_to_emailid']);
    $cc_staff = implode(',',$_POST['companycontact_cc_emailid']);
    $new_emailid = filter_var($_POST['new_emailid'],FILTER_SANITIZE_STRING);

	$follow_up_date = $_POST['followup_date'];
	$follow_up_by = $_POST['followup_by'];

	$today_date = date('Y-m-d');
    $created_by = $_SESSION['contactid'];

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }

    $meeting_attachment = '';
    for($i = 0; $i < count($_FILES['upload_document']['name']); $i++) {
        $document = htmlspecialchars($_FILES["upload_document"]["name"][$i], ENT_QUOTES);

        move_uploaded_file($_FILES["upload_document"]["tmp_name"][$i], "download/".$_FILES["upload_document"]["name"][$i]) ;

        if($document != '') {
            $query_insert_client_doc = "INSERT INTO `email_communicationid_upload` (`email_communicationid`, `document`, `created_by`, `created_date`) VALUES ('0', '$document', '$created_by', '$today_date')";
            $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);

            $file_support = 'download/'.$document;
            $meeting_attachment .= $file_support.'*#FFM#*';
        }
    }

    $meeting_email_send = '';
    // Meeting Note Email
    if($to_contact != '' || $to_staff != '') {
        $meeting_email_send = $to_contact.','.$to_staff;
    }

    $meeting_cc_email_send = '';
    if($cc_contact != '' || $cc_staff != '' || $new_emailid != '') {
        $meeting_cc_email_send = $cc_contact.','.$cc_staff.','.$new_emailid;
    }

    if($meeting_email_send != '') {
        $meeting_arr_email = array_filter(explode(",",$meeting_email_send));
    }

    if($meeting_cc_email_send != '') {
        $meeting_cc_arr_email = array_filter(explode(",",$meeting_cc_email_send));
    }

    $send_body = '';
    if($meeting_email_send != '' || $meeting_cc_email_send != '') {
        if($businessid != '') {
            $send_body .= '<b>Business : </b>'.get_client($dbc, $businessid).'<br>';
        }
        if($contactid != '') {
            $send_body .= '<b>Contact : </b>'.get_staff($dbc, $contactid).'<br>';
        }
        if($projectid != '') {
			$project_tabs = get_config($dbc, 'project_tabs');
			if($project_tabs == '') {
				$project_tabs = 'Client,SR&ED,Internal,R&D,Business Development,Process Development,Addendum,Addition,Marketing,Manufacturing,Assembly';
			}
			$project_tabs = explode(',',$project_tabs);
			foreach($project_tabs as $item) {
				if(preg_replace('/[^a-z_]/','',str_replace(' ','_',strtolower($item))) == get_project($dbc, $projectid, 'projecttype')) {
					$send_body .= '<b>Project : </b>'.$item.' : '.get_project($dbc, $projectid, 'project_name').'<br><br>';
				}
			}
        }
        if($email_body != '') {
            $send_body .= $_POST['email_body'];
        }
				
        if (empty($from_email)) {
            $from_email = '';
        }
		try {
			if($meeting_attachment == '') {
				send_email([$from_email => $from_name], $meeting_arr_email, $meeting_cc_arr_email , '', $subject, $send_body, '');
			} else {
				send_email([$from_email => $from_name], $meeting_arr_email, $meeting_cc_arr_email , '', $subject, $send_body, $meeting_attachment);
			}
		} catch(Exception $e) {
			echo "<script> alert('Unable to send the email: ".$e->getMessage()."'); </script>";
		}
    }
    // Meeting Note Email

    if(empty($_POST['email_communicationid']) || count($meeting_arr_email) > 0 || count($meeting_cc_arr_email) > 0) {
        $query_insert_ca = "INSERT INTO `email_communication` (`communication_type`, `businessid`, `contactid`, `projectid`, `client_projectid`, `subject`, `email_body`, `to_contact`, `cc_contact`, `to_staff`, `cc_staff`, `new_emailid`, `today_date`, `created_by`, `follow_up_by`, `follow_up_date`, `from_email`, `from_name`) VALUES ('$communication_type', '$businessid', '$contactid', '$projectid', '$client_projectid', '$subject', '".filter_var($email_body,FILTER_SANITIZE_STRING)."', '$to_contact', '$cc_contact', '$to_staff', '$cc_staff', '$new_emailid', '$today_date', '$created_by', '$follow_up_by', '$follow_up_date', '$from_email', '$from_name')";
        $result_insert_ca = mysqli_query($dbc, $query_insert_ca);
        $email_communicationid = mysqli_insert_id($dbc);

		$overview = 'Added Email Communication #'.$email_communicationid;
		if(!empty($_POST['timer']) && $_POST['timer'] != '') {
			$overview .= ' - Added Time : '.$_POST['timer'];
		}
		if($projectid > 0) {
			$user = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
			mysqli_query($dbc, "INSERT INTO `project_history` (`updated_by`, `description`, `projectid`) VALUES ('$user', 'Sent Email #".$email_communicationid."', '$projectid')");
		}
    } else {
        $email_communicationid = $_POST['email_communicationid'];
        $query_update_ticket = "UPDATE `email_communication` SET `communication_type` = '$communication_type', `businessid` = '$businessid', `contactid` = '$contactid', `projectid` = '$projectid', `client_projectid` = '$client_projectid', `subject` = '$subject', `email_body` = '".filter_var($email_body,FILTER_SANITIZE_STRING)."', `to_contact` = '$to_contact', `cc_contact` = '$cc_contact', `to_staff` = '$to_staff', `cc_staff` = '$cc_staff', `new_emailid` = '$new_emailid', `follow_up_by` = '$follow_up_by', `follow_up_date` = '$follow_up_date', `from_email` = '$from_email', `from_name` = '$from_name' WHERE `email_communicationid` = '$email_communicationid'";
        $result_update_ticket = mysqli_query($dbc, $query_update_ticket);

		$overview = 'Updated Email Communication #'.$email_communicationid;
		if(!empty($_POST['timer']) && $_POST['timer'] != '') {
			$overview .= ' - Added Time : '.$_POST['timer'];
		}
    }
	//Connect the attachments to the current communication
	mysqli_query($dbc, "UPDATE `email_communicationid_upload` SET `email_communicationid`='$email_communicationid' WHERE `email_communicationid`=0 AND `created_by`='$created_by' AND `created_date`='$today_date'");
	echo insert_day_overview($dbc, $created_by, 'Communication', date('Y-m-d'), '', $overview);

	$timer = $_POST['timer'];
	$end_time = date('g:i A');

	$start_time = 0;
	if($timer != '0' && $timer != '00:00:00' && $timer != '') {
		$query_update_ticket = "UPDATE `email_communication_timer` SET `end_time` = '$end_time', `start_timer_time` = '$start_time', `timer`='$timer', `communication_id` = '$email_communicationid' WHERE `communication_id` = '$email_communicationid' AND created_by='$created_by' AND end_time IS NULL";
		$result_update_ticket = mysqli_query($dbc, $query_update_ticket);

		$query_update_ticket = "UPDATE `email_communication_timer` SET `start_timer_time` = '0', `communication_id`='$email_communicationid' WHERE (`communication_id` = '$email_communicationid' OR `communication_id` = 0 OR `communication_id` IS NULL) AND `created_by`='$created_by'";
		$result_update_ticket = mysqli_query($dbc, $query_update_ticket);
	}

    echo '<script type="text/javascript"> window.location.replace("'.$back_url.'"); </script>';
}
?>
<script type="text/javascript">
$(document).ready(function() {

    $("#businessid").change(function() {
        var comm_type = $("#comm_type").val();
		window.location = 'add_communication.php?type='+comm_type+'&bid='+this.value;
	});
    $("#form1").submit(function( event ) {
        var email_communicationid = $("#email_communicationid").val();
        if(email_communicationid == undefined) {
            var businessid = $("#businessid").val();
            var serviceid = $("#serviceid").val();
            var service_type = $("#service_type").val();
            var service_category = $("#service_category").val();

            var heading = $("input[name=heading]").val();

            var status = $("#status").val();
            var contactid = $("#contactid").val();
            var category = $("#category").val();

            if (businessid == '' || serviceid == '' || service_type == '' || service_category == '' || heading == '' || status == '' || category == '' || contactid == null) {
                alert("Please make sure you have filled in all of the required fields.");
                return false;
            }
        }
    });

});
</script>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('email_communication');
?>
<div class="container">
  <div class="row">
    <h1>Email Communication</h1>
	<div class="pad-left gap-top double-gap-bottom"><a href="<?php echo $back_url; ?>" class="btn config-btn">Back to Dashboard</a></div>

	<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
    <?php
		if(!empty($_GET['category'])) {
			$comm_type = $_GET['category'];
			echo '<input type="hidden" id="comm_type" name="comm_type" value="'.$comm_type.'" />';
		} else if(!empty($_GET['type'])) {
            $comm_type = $_GET['type'];
            echo '<input type="hidden" id="comm_type" name="comm_type" value="'.$comm_type.'" />';
        }

		if ( $comm_type=='Internal' ) {
			$get_field_config	= mysqli_fetch_assoc(mysqli_query($dbc,"SELECT `internal_communication` FROM `field_config`"));
			$value_config		= ',' . $get_field_config['internal_communication'] . ',';
		} else {
			$get_field_config	= mysqli_fetch_assoc(mysqli_query($dbc,"SELECT `external_communication` FROM `field_config`"));
			$value_config		= ',' . $get_field_config['external_communication'] . ',';
		}

        $clientid = '';
        $businessid = '';

        if(!empty($_GET['bid'])) {
            $businessid = $_GET['bid'];
        }

        /*
        if(!empty($_GET['clientid'])) {
            $clientid = $_GET['clientid'];
            $businessid = get_contact($dbc, $clientid, 'businessid');
        }
        */
        if(!empty($_GET['projectid'])) {
            $projectid = $_GET['projectid'];
            $businessid = get_project($dbc, $projectid, 'businessid');
            $clientid = get_project($dbc, $projectid, 'clientid');
        }

		$followup_by = '';
		$followup_date = '';
        $contactid = $_SESSION['contactid'];
        if(!empty($_GET['email_communicationid'])) {

            $email_communicationid = $_GET['email_communicationid'];
            $get_ticket = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM email_communication WHERE email_communicationid='$email_communicationid'"));

            $businessid = $get_ticket['businessid'];

            $contactid = $get_ticket['contactid'];
            if($businessid == '') {
                $businessid = get_contact($dbc, $contactid, 'businessid');
            }

            $projectid = $get_ticket['projectid'];
			$followup_by = $get_ticket['follow_up_by'];
			$followup_date = $get_ticket['follow_up_date'];

            $subject = $get_ticket['subject'];
            $email_body = $get_ticket['email_body'];
        ?>
        <input type="hidden" id="email_communicationid" name="email_communicationid" value="<?php echo $email_communicationid ?>" />
        <?php   }      ?>

        <div class="panel-group" id="accordion2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info" >
                            Information<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_info" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <?php
                            include ('add_business_info.php');
                        ?>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_td" >
                            Email Communication Details<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_td" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php
                            include ('add_email_communication_info.php');
                        ?>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_td1" >
                            Email Attachment<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_td1" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php
                            include ('add_view_email_communication_documents.php');
                        ?>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_td2" >
                            Email Communication<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_td2" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php
                            include ('add_email_communication_send.php');
                        ?>
                    </div>
                </div>
            </div>

			<?php if (strpos($value_config, ','."Communication Timer".',') !== FALSE) { ?>
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
							<?php
								include ('add_email_communication_timer.php');
							?>
						</div>
					</div>
				</div>
			<?php } ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_followup" >
                            Follow Up<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_followup" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php
                            include ('add_email_communication_follow_up.php');
                        ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="form-group">
			<p><span class="hp-red"><em>Required Fields *</em></span></p>
        </div>

        <div class="form-group">
            <div class="col-sm-6">
                <a href="<?php echo $back_url; ?>" class="btn brand-btn btn-lg">Back</a>
            </div>
            <div class="col-sm-6">
                <button type="submit" name="submit" value="submit" class="btn brand-btn btn-lg pull-right">Submit</button>
            </div>
        </div>

        <style>
            .chosen-container {
                width:100%;
            }
        </style>

    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>
