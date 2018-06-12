<?php
/*
Add Service Code
*/
include ('../include.php');
error_reporting(0);
checkAuthorised('booking');

if (isset($_POST['submit'])) {
    $wid = $_POST['wid'];

    if($wid != '') {
        $sql = mysqli_query($dbc, "DELETE FROM waitlist WHERE waitlistid='$wid'");
    }

    $name = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
    $fee = filter_var($_POST['fee'],FILTER_SANITIZE_STRING);

    $today_date = date('Y-m-d');
    $patientid = $_POST['patientid'];
    $injuryid = $_POST['injuryid'];
    $injury_data = get_all_from_injury($dbc, $injuryid, 'injury_name').' : '.get_all_from_injury($dbc, $injuryid, 'injury_type'). ' - '.get_all_from_injury($dbc, $injuryid, 'injury_date');
    $treatment_type = $_POST['treatment_type'];
    $upload_document = '';
    $therapistsid = $_POST['therapistsid'];
    $notes = filter_var($_POST['notes'],FILTER_SANITIZE_STRING);
    $follow_up_call_date = $_POST['follow_up_call_date'];
    $follow_up_call_status = $_POST['follow_up_call_status'];
    $type = $_POST['type'];

    if($_FILES["upload_document"]["name"] != '') {
        $upload_document = implode('#$#', $_FILES["upload_document"]["name"]);
    } else {
        $upload_document = '';
    }
    $upload_document_md5 = '';
    for($i = 0; $i < count($_FILES['upload_document']['name']); $i++) {
        move_uploaded_file($_FILES["upload_document"]["tmp_name"][$i], "Download/".$_FILES["upload_document"]["name"][$i]) ;
        $upload_document_md5 .= md5_file("Download/".$_FILES["upload_document"]["name"][$i]).'#*FFM*#';
    }
    $upload_document_md5 = rtrim($upload_document_md5, "#*FFM*#");
    $followup_call_set_before = get_config($dbc, 'followup_call_set_before');

    if(empty($_POST['bookingid'])) {

        // For Calendar //
        $get_patient = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT first_name, last_name FROM contacts WHERE contactid='$patientid'"));

        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT first_name, last_name FROM contacts WHERE contactid='$therapistsid'"));
        $therapist = $get_staff['first_name'].' '.$get_staff['last_name'];

        $get_roomid = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT id FROM mrbs_room WHERE room_name='$therapist'"));

        $patient = decryptIt($get_patient['first_name']).' '.decryptIt($get_patient['last_name']);
        $patientstatus = $_POST['follow_up_call_status'];
        $room_id = $get_roomid['id'];
        $timestamp = date('Y-m-d H:i:s');
        $name = $notes;

        if($_POST['appoint_date'] != '') {
            $appoint_date = $_POST['appoint_date'];
            $end_appoint_date = $_POST['end_appoint_date'];
            $start_time = strtotime($appoint_date);
            $end_time = strtotime($end_appoint_date);

            $query_insert_cal = "INSERT INTO `mrbs_entry` (`patient`, `injury`, `patientstatus`, `start_time`, `end_time`, `room_id`, `timestamp`, `name`, `type`) VALUES ('$patient', '$injury_data', '$patientstatus', '$start_time', '$end_time', '$room_id', '$timestamp', '$name', '$type')";
            $result_insert_cal = mysqli_query($dbc, $query_insert_cal);
            $calid = mysqli_insert_id($dbc);

			$upload_document = htmlspecialchars($upload_document, ENT_QUOTES);
            $query_insert_booking = "INSERT INTO `booking` (`today_date`, `patientid`, `injuryid`, `treatment_type`, `upload_document`, `upload_document_md5`, `therapistsid`, `appoint_date`, `end_appoint_date`, `notes`, `follow_up_call_date`, `follow_up_call_status`, `type`, `calid`) VALUES ('$today_date', '$patientid', '$injuryid', '$treatment_type', '$upload_document', '$upload_document_md5', '$therapistsid', '$appoint_date', '$end_appoint_date', '$notes', '$follow_up_call_date', '$follow_up_call_status', '$type', '$calid')";
            $result_insert_booking = mysqli_query($dbc, $query_insert_booking);
        } else {
            for($i = 0; $i < count($_POST['block_appoint_date']); $i++) {
                $block_appoint_date = $_POST['block_appoint_date'][$i];
                $block_end_appoint_date = $_POST['block_end_appoint_date'][$i];

                $block_start_time = strtotime($block_appoint_date);
                $block_end_time = strtotime($block_end_appoint_date);

                if($block_appoint_date != '') {
                    $block_booking = 1;
                    $query_insert_cal = "INSERT INTO `mrbs_entry` (`patient`, `injury`, `patientstatus`, `start_time`, `end_time`, `room_id`, `timestamp`, `name`, `type`) VALUES ('$patient', '$injury_data', '$patientstatus', '$block_start_time', '$block_end_time', '$room_id', '$timestamp', '$name', '$type')";
                    $result_insert_cal = mysqli_query($dbc, $query_insert_cal);
                    $calid = mysqli_insert_id($dbc);

                    $query_insert_booking = "INSERT INTO `booking` (`today_date`, `patientid`, `injuryid`, `treatment_type`, `upload_document`, `upload_document_md5`, `therapistsid`, `appoint_date`, `end_appoint_date`, `notes`, `follow_up_call_date`, `follow_up_call_status`, `calid`, `block_booking`, `type`) VALUES ('$today_date', '$patientid', '$injuryid', '$treatment_type', '$upload_document', '$upload_document_md5', '$therapistsid', '$block_appoint_date', '$block_end_appoint_date', '$notes', '$follow_up_call_date', '$follow_up_call_status', '$calid', '$block_booking', '$type')";
                    $result_insert_booking = mysqli_query($dbc, $query_insert_booking);
                }
            }
        }

        $referralid = $_POST['referralid'];
        if($referralid != '') {
            $query_update_cal = "UPDATE `crm_referrals` SET `appoint_date` = '$appoint_date', `follow_up_call_date` = '$follow_up_call_date' WHERE `referralid` = '$referralid'";
            $result_update_cal = mysqli_query($dbc, $query_update_cal);
        }

        $url = 'Added';
    } else {
        $bookingid = $_POST['bookingid'];
        $calid = $_POST['calid'];
        if($upload_document != '') {
            $ud = '#$#'.$upload_document;
            $ud_md5 = '#*FFM*#'.$upload_document_md5;
        }

        // For Calendar //
        $get_patient = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT first_name, last_name FROM contacts WHERE contactid='$patientid'"));

        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT first_name, last_name FROM contacts WHERE contactid='$therapistsid'"));
        $therapist = $get_staff['first_name'].' '.$get_staff['last_name'];
        $get_roomid = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT id FROM mrbs_room WHERE room_name='$therapist'"));

        $appoint_date = $_POST['appoint_date'];
        $end_appoint_date = $_POST['end_appoint_date'];
        $start_time = strtotime($appoint_date);
        $end_time = strtotime($end_appoint_date);

        $patient = decryptIt($get_patient['first_name']).' '.decryptIt($get_patient['last_name']);
        $patientstatus = $_POST['follow_up_call_status'];
        $room_id = $get_roomid['id'];
        $timestamp = date('Y-m-d H:i:s');
        $name = $notes;

        $query_update_cal = "UPDATE `mrbs_entry` SET `patient` = '$patient', `patientstatus` = '$patientstatus', `start_time` = '$start_time', `end_time` = '$end_time', `room_id` = '$room_id', `timestamp` = '$timestamp', `name` = '$name', `type` = '$type' WHERE `id` = '$calid'";
        $result_update_cal = mysqli_query($dbc, $query_update_cal);

        $query_update_booking = "UPDATE `booking` SET `today_date` = '$today_date', `patientid` = '$patientid', `injuryid` = '$injuryid', `treatment_type` = '$treatment_type', `upload_document` = concat(upload_document, '$ud'), `upload_document_md5` = concat(upload_document_md5, '$ud_md5'), `therapistsid` = '$therapistsid', `appoint_date` = '$appoint_date', `end_appoint_date` = '$end_appoint_date', `notes` = '$notes', `follow_up_call_date` = '$follow_up_call_date', `follow_up_call_status` = '$follow_up_call_status', `type` = '$type' WHERE `bookingid` = '$bookingid'";
        $result_update_booking = mysqli_query($dbc, $query_update_booking);

        //
        if($follow_up_call_status == 'Late Cancellation / No-Show') {
            $fee = get_id_from_servicetype($dbc, 'Late Cancellation/No Show');
            $query_update_patient = "UPDATE `patients` SET `account_balance` = account_balance - '$fee' WHERE `contactid` = '$patientid'";
            $result_update_patient = mysqli_query($dbc, $query_update_patient);

            $query_update_booking = "UPDATE `booking` SET `deleted` = 1 WHERE `bookingid` = '$bookingid'";
            $result_update_booking = mysqli_query($dbc, $query_update_booking);
        }
        if($follow_up_call_status == 'Cancelled') {
            $query_update_booking = "UPDATE `booking` SET `deleted` = 1 WHERE `bookingid` = '$bookingid'";
            $result_update_booking = mysqli_query($dbc, $query_update_booking);
        }
        $url = 'Updated';
    }

    if($follow_up_call_status == 'Late Cancellation / No-Show') {
        echo '<script type="text/javascript"> alert("NOTE : Booking Removed from list and Late Cancellation/No Show Fee added to Patient Profile."); window.location.replace("booking.php?contactid='.$therapistsid.'"); </script>';
    } else {
        echo '<script type="text/javascript"> alert("Booking Successfully '.$url.'"); window.location.replace("booking.php?contactid='.$therapistsid.'"); </script>';
    }

    mysqli_close($dbc); //Close the DB Connection
}

?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#patientid").change(function() {
            var referralid = $("#referralid").val();
            if(referralid != '') {
                window.location = 'add_booking.php?referralid='+referralid+'&patientid='+this.value;
            } else {
                window.location = 'add_booking.php?patientid='+this.value;
            }
        });

        $("#injuryid").change(function() {
            var patientid = $("#patientid").val();
            var wid = $("#wid").val();
            var referralid = $("#referralid").val();

            window.location = 'add_booking.php?injuryid='+this.value+'&patientid='+patientid;
            if(wid != '') {
                window.location = 'add_booking.php?injuryid='+this.value+'&patientid='+patientid+'&wid='+wid;
            }
            if(referralid != '') {
                window.location = 'add_booking.php?injuryid='+this.value+'&patientid='+patientid+'&referralid='+referralid;
            }
            if(wid != '' && referralid != '') {
                window.location = 'add_booking.php?injuryid='+this.value+'&patientid='+patientid+'&wid='+wid+'&referralid='+referralid;
            }
        });

        $(".block_booking_display").hide();
        $(".normal_booking_btn").hide();

        $(".block_booking_btn").click(function() {
            $(".block_booking_display").show();
            $(".normal_booking_display").hide();
            $(".normal_booking_btn").show();
            $(".block_booking_btn").hide();
        });

        $(".normal_booking_btn").click(function() {
            $(".block_booking_display").hide();
            $(".normal_booking_display").show();
            $(".block_booking_btn").show();
            $(".normal_booking_btn").hide();
        });

        $(".new_patient").hide();
        $(".add_new_patient").click(function() {
                $(".patient").hide();
                $(".add_new_patient").hide();
                $('.new_patient').show();
        });
        $(".add_existing_patient").click(function() {
                $(".patient").show();
                $(".add_new_patient").show();
                $('.new_patient').hide();
        });

        $("#form1").submit(function( event ) {
            var patientid = $("#patientid").val();
            var first_name = $("input[name=first_name]").val();
            var injuryid = $("#injuryid").val();
            var therapistsid = $("#therapistsid").val();
            if (injuryid == '' || (patientid == '' && first_name == '') || therapistsid == '') {
                alert("Please make sure you have filled in all of the required fields.");
                return false;
            }
        });

        $('#add_row_service').on( 'click', function () {
            $(".hide_show_service").show();
            var clone = $('.additional_service').clone();

            clone.find('.datetimepicker').val('');
            clone.find('.datetimepicker').val('');
            var numItems = ($('.datetimepicker').length/2);
            clone.find('.booking_head').html(numItems);

            clone.find('.form-control').val(0);
            clone.find('.datetimepicker').attr('id', 'appointdate_'+numItems);
            clone.find('.datetimepicker').attr('id', 'endappointdate_'+numItems);

            clone.removeClass("additional_service");
            $('#add_here_new_service').append(clone);

            clone.find('.datetimepicker').each(function() {
                $(this).removeAttr('id').removeClass('hasDatepicker');
                $('.datetimepicker').datetimepicker({dateFormat: 'yy-mm-dd', timeFormat: 'hh:mm tt', changeYear: true, changeMonth: true, yearRange: '1960:2025'});
            });

            return false;
        });

        //
        /*
        var bookingid = $("#bookingid").val();
        var followup_call_set_before = $("#followup_call_set_before").val();

        if (typeof bookingid === "undefined") {
            var startDateTextBox = $('#range_example_3_start');
            var endDateTextBox = $('#range_example_3_end');

            $.timepicker.datetimeRange(
                startDateTextBox,
                endDateTextBox,
                {
                    minInterval: (1000*60*60), // 1hr
                    minuteGrid: 30,
                    hourMin: 8,
                    hourMax: 17,
                    dateFormat: 'yy-mm-dd',
                    timeFormat: "hh:mm tt",
                    start: {}, // start picker options
                    end: {} // end picker options
                }
            );

            var i;
            var numItems = $('.appointdatetimepicker').length-1;
            for(i=1;i<=numItems;i++) {
                var blockStartDateTextBox = $('#appointdate_'+i);
                var blockEndDateTextBox = $('#endappointdate_'+i);

                $.timepicker.datetimeRange(
                    blockStartDateTextBox,
                    blockEndDateTextBox,
                    {
                        minInterval: (1000*60*60), // 1hr
                        minuteGrid: 30,
                        hourMin: 8,
                        hourMax: 17,
                        dateFormat: 'yy-mm-dd',
                        timeFormat: "hh:mm tt",
                        start: {}, // start picker options
                        end: {} // end picker options
                    }
                );
            }
        } else {
            $(".appointdatetimepicker").datetimepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: '1960:2017',
                dateFormat: 'yy-mm-dd',
                timeFormat: "hh:mm tt",
                minuteGrid: 30,
                hourMin: 8,
                hourMax: 16,
                minDate: 0
            });
        }
        */
        //

	$('.iframe_open').click(function(){
		if($(this).hasClass("adder")) {
			var id = $(this).attr('id');
		   $('#iframe_instead_of_window').attr('src', '<?php echo WEBSITE_URL; ?>/Contacts/add_injury.php?type=contact&contactid='+id);
		   $('.iframe_title').text('Currently Adding an Injury');
		} else {
		   $('#iframe_instead_of_window').attr('src', '<?php echo WEBSITE_URL; ?>/Contacts/add_contacts.php?from=booking&type=patients');
		   $('.iframe_title').text('Add New Patient');
		}
			$('.iframe_holder').show(1000);
			$('.hide_on_iframe').hide(1000);
	});

	$('.close_iframer').click(function(){
		var result = confirm("Are you sure you want to close this window?");
		if (result) {
			$('.iframe_holder').hide(1000);
			$('.hide_on_iframe').show(1000);
			location.reload();
		}
	});
});
</script>
</head>

<body>
<?php include_once ('../navigation.php');

?>
<div class="container">
  <div class='iframe_holder' style='display:none;'>

		<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
		<span class='iframe_title' style='font-weight:bold; position: relative; left: 20px; font-size: 30px;'></span>
		<iframe id="iframe_instead_of_window" style='width: 100%;' height="1000px; border:0;" src=""></iframe>
    </div>
	<div class="row hide_on_iframe">

        <h1 class="triple-pad-bottom">Booking</h1>

		<form id="form1" name="form1" method="post" action="add_booking.php" enctype="multipart/form-data" class="form-horizontal" role="form">
       <?php
        $followup_call_set_before = get_config($dbc, 'followup_call_set_before');
       ?>
       <input type="hidden" id="followup_call_set_before" value="<?php echo $followup_call_set_before; ?>" />
        <?php
        if(!empty($_GET['referralid'])) { ?>
            <input type="hidden" id="referralid" name="referralid" value="<?php echo $_GET['referralid'] ?>" />
        <?php }
        if(!empty($_GET['wid'])) { ?>
            <input type="hidden" id="wid" name="wid" value="<?php echo $_GET['wid'] ?>" />
        <?php }

        $today_date = date('Y-m-d');
		$patientid = '';
        $injuryid = '';
        $treatment_type = '';
        $upload_document = '';
        $therapistsid = '';
        $appoint_date = '';
        $end_appoint_date = '';
        $notes = '';
        $follow_up_call_date = '';
        $follow_up_call_status = 'Booked Unconfirmed';
        $calid = '';
        $type = '';

        if(!empty($_GET['tid'])) {
            $therapistsid = $_GET['tid'];
        }

        if(!empty($_GET['bookingid'])) {
            $bookingid = $_GET['bookingid'];
            $get_site = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM booking WHERE bookingid='$bookingid'"));
            $calid = $get_site['calid'];
            $patientid = $get_site['patientid'];
            $injuryid = $get_site['injuryid'];
            $treatment_type = $get_site['treatment_type'];
			$upload_document = $get_site['upload_document'];
            $upload_document_md5 = $get_site['upload_document_md5'];
            $therapistsid = $get_site['therapistsid'];
            $appoint_date = $get_site['appoint_date'];
            $end_appoint_date = $get_site['end_appoint_date'];
            $notes = $get_site['notes'];
            $follow_up_call_date = $get_site['follow_up_call_date'];
            $follow_up_call_status = $get_site['follow_up_call_status'];
            $type = $get_site['type'];
        ?>
        <input type="hidden" id="bookingid" name="bookingid" value="<?php echo $bookingid ?>" />
        <input type="hidden" id="calid" name="calid" value="<?php echo $calid ?>" />
        <?php   }
        if(!empty($_GET['patientid'])) {
            $patientid = $_GET['patientid'];
        }
        if(!empty($_GET['injuryid'])) {
            $injuryid = $_GET['injuryid'];
            $get_the = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT therapistsid FROM booking WHERE patientid='$patientid' AND injuryid='$injuryid'"));
            $therapistsid = $get_the['therapistsid'];
        }
        ?>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Today's Date<span class="empire-red">*</span>:</label>
            <div class="col-sm-8">
                <input name="today_date" disabled value="<?php echo $today_date; ?>" type="text" class="datepicker"></p>
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Patient<span class="hp-red">*</span>:</label>
            <div class="col-sm-8">
				<select id="patientid" data-placeholder="Select a Patient..." name="patientid" class="chosen-select-deselect form-control" width="380">
					<option value=""></option>
					<?php
					$query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Patient' AND status=1 AND deleted=0");
					while($row = mysqli_fetch_array($query)) {
                        if (($patientid == $row['contactid']) || ($_GET['patientid'] == $row['contactid'])) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
						echo "<option ".$selected." value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
					}
					?>
				</select>
            </div>
          </div>

          <div class="form-group">
            <label for="first_name" class="col-sm-4 control-label"></label>
            <div class="col-sm-8">
            <?php
                //echo '<a class="btn brand-btn pull-right iframe_open">Add New Patient</a>';
				//	 echo '<a class="btn brand-btn pull-right" href="#"  onclick=" window.open(\''.WEBSITE_URL.'/Contact/add_contact.php?from=booking&type=patients\', \'newwindow\', \'width=900, height=900\'); return false;">Add New Patient</a>';
            ?>
            </div>
          </div>

          <div class="form-group patient">
            <label for="site_name" class="col-sm-4 control-label">Injury<span class="hp-red">*</span>:</label>
            <div class="col-sm-8">
				<select id="injuryid" data-placeholder="Select an Injury..." name="injuryid" class="chosen-select-deselect form-control" width="380">
					<option value=""></option>
					<?php
					$query = mysqli_query($dbc,"SELECT contactid, injuryid, injury_name, injury_date, injury_type FROM patient_injury WHERE contactid='$patientid' AND discharge_date IS NULL AND deleted=0");
					while($row = mysqli_fetch_array($query)) {
                        if ($injuryid == $row['injuryid']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
						echo "<option ".$selected." value='". $row['injuryid']."'>".$row['injury_name'].' : '.$row['injury_type']. ' - '.$row['injury_date'].'</option>';
					}
					?>
				</select>
            </div>
          </div>

          <div class="form-group">
            <label for="first_name" class="col-sm-4 control-label"></label>
            <div class="col-sm-8">
            <?php
                //echo '<a class="btn brand-btn pull-right iframe_open adder" id="'.$patientid.'">Add New Injury</a>';
				//echo '<a class="btn brand-btn pull-right" href="#"  onclick=" window.open(\''.WEBSITE_URL.'/Contact/add_injury.php?type=contact&contactid='.$patientid.'\', \'newwindow\', \'width=900, height=900\'); return false;">Add New Injury</a>';
            ?>
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Therapist<span class="hp-red">*</span>:</label>
            <div class="col-sm-8">
				<select id="therapistsid" data-placeholder="Select a Therapist..." name="therapistsid" class="chosen-select-deselect form-control" width="380">
					<option value=""></option>
					<?php
						$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE (category_contact = 'Physical Therapist' OR category_contact = 'Massage Therapist' OR category_contact = 'Osteopathic Therapist') AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
						foreach($query as $id) {
							$selected = '';
							$selected = $id == $therapistsid ? 'selected = "selected"' : '';
							echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
						}
					?>
				</select>
            </div>
          </div>

		    <!--
            <div class="form-group">
			    <label for="phone_number" class="col-sm-4 control-label">Treatment Type:</label>
			    <div class="col-sm-8">
                    <select data-placeholder="Choose a Treatment Type..."  name="treatment_type" id="treatment_type" class="chosen-select-deselect form-control" width="380">
                        <option value=""></option>
                        <option <?php if ($treatment_type=='New Assessment') echo 'selected="selected"';?> value="New Assessment">New Assessment</option>
                        <option <?php if ($treatment_type=='Planned Treatment') echo 'selected="selected"';?> value="Planned Treatment">Planned Treatment</option>
                        <option <?php if ($treatment_type=='Emergency Treatment') echo 'selected="selected"';?> value="Emergency Treatment">Emergency Treatment</option>
                    </select>
                </div>
		    </div>
            -->

          <div class="form-group">
            <label for="file[]" class="col-sm-4 control-label">Upload Document:</label>
            <div class="col-sm-8">
            <?php if((!empty($_GET['bookingid'])) && ($upload_document != '')) {
                    $file_names = explode('#$#', $upload_document);
                    $file_names_md5 = explode('#*FFM*#', $upload_document_md5);
                    echo '<ul>';
                    $i=0;
                    foreach($file_names as $file_name) {
                        $md5 = md5_file("Download/".$file_name);
                        if(($file_name != '') && ($md5 == $file_names_md5[$i])) {
                            echo '<li><a href="Download/'.$file_name.'" target="_blank">'.$file_name.'</a></li>';
                        } else if($md5 != $file_names_md5[$i]) {
                            echo '<li>'.$file_name.' (Error : File Change)</li>';
                        }
                        $i++;
                    }
                    echo '</ul>';
                ?>
                <input type="hidden" name="upload_document" value="<?php echo $upload_document; ?>" />
                <input multiple name="upload_document[]" type="file" id="file" data-filename-placement="inside" class="form-control" />
              <?php } else { ?>
              <input multiple name="upload_document[]" type="file" id="file" data-filename-placement="inside" class="form-control" />
              <?php } ?>

            </div>
          </div>

          <?php if(empty($_GET['bookingid'])) { ?>
          <div class="form-group">
            <label for="first_name" class="col-sm-4 control-label"></label>
            <div class="col-sm-8">
                <button type="button" name="block_booking" value="block_booking" class="block_booking_btn btn brand-btn pull-right">Block Booking</button>
            </div>
          </div>

          <div class="form-group">
            <label for="first_name" class="col-sm-4 control-label"></label>
            <div class="col-sm-8">
                <button type="button" name="block_booking1" value="block_booking1" class="normal_booking_btn btn brand-btn pull-right">1 Booking</button>
            </div>
          </div>
          <?php }
          ?>

          <span class="normal_booking_display">
          <div class="form-group clearfix">
            <label for="first_name" class="col-sm-4 control-label text-right">Start Appointment Date & Time:
                <span class="popover-examples list-inline">
                    <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Click on the 15 minute interval labels to specify those times"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="25"></a>
                </span>
            </label>
            <div class="col-sm-8">
                <input name="appoint_date" id="range_example_3_start" value="<?php echo $appoint_date; ?>" type="text" placeholder="Click for Datepicker" class="datetimepicker"></p>
            </div>
          </div>

          <div class="form-group clearfix">
            <label for="first_name" class="col-sm-4 control-label text-right">End Appointment Date & Time:
                <span class="popover-examples list-inline">
                    <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Click on the 15 minute interval labels to specify those times"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="25"></a>
                </span>
            </label>
            <div class="col-sm-8">
                <input name="end_appoint_date" id="range_example_3_end" value="<?php echo $end_appoint_date; ?>" type="text" placeholder="Click for Datepicker" class="datetimepicker"></p>
            </div>
          </div>
          </span>

          <?php if(empty($_GET['bookingid'])) { ?>
          <span class="block_booking_display">

            <div class="form-group">
                <label for="" class="col-sm-4 control-label"></label>
                <div class="col-sm-8">
                    <div class="form-group clearfix">
                        <label class="col-sm-2">Booking</label>
                        <label class="col-sm-4">Start Appointment Date & Time
                            <span class="popover-examples list-inline">
                                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Click on the 15 minute interval labels to specify those times"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="25"></a>
                            </span>
                        </label>
                        <label class="col-sm-3">End Appointment Date & Time
                            <span class="popover-examples list-inline">
                                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Click on the 15 minute interval labels to specify those times"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="25"></a>
                            </span>
                        </label>
                    </div>

                    <?php
                    $total_block = get_config($dbc, 'minumum_block_booking_appointments');
                    for($i=1; $i<$total_block;$i++) {
                    ?>
                    <div class="clearfix">
                        <div class="clearfix"></div>
                        <div class="form-group clearfix">
                            <div class="col-sm-2">
                                <?php echo $i;?>
                            </div>
                            <div class="col-sm-4">
                                <input name="block_appoint_date[]" id="appointdate_<?php echo $i; ?>" type="text" placeholder="Click for Datepicker" class="datetimepicker"></p>
                            </div>
                            <div class="col-sm-3">
                                <input name="block_end_appoint_date[]" id="endappointdate_<?php echo $i; ?>" type="text" placeholder="Click for Datepicker" class="datetimepicker"></p>
                            </div> <!-- Quantity -->
                        </div>
                    </div>
                    <?php } ?>

                    <div class="additional_service clearfix">
                        <div class="clearfix"></div>
                        <div class="form-group clearfix">
                            <div class="col-sm-2">
                               <span class="booking_head"><?php echo $i;?></span>
                            </div>
                            <div class="col-sm-4">
                                <input name="block_appoint_date[]" id="appointdate_<?php echo $i; ?>" type="text" placeholder="Click for Datepicker" class="datetimepicker"></p>
                            </div>
                            <div class="col-sm-3">
                                <input name="block_end_appoint_date[]" id="endappointdate_<?php echo $i; ?>" type="text" placeholder="Click for Datepicker" class="datetimepicker"></p>
                            </div> <!-- Quantity -->
                        </div>
                    </div>

                    <div id="add_here_new_service"></div>

                    <div class="form-group triple-gapped clearfix">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button id="add_row_service" class="btn brand-btn pull-left">Add Booking</button>
                        </div>
                    </div>

                </div>
            </div>
          </span>
          <?php } ?>

          <div class="form-group clearfix">
            <label for="first_name" class="col-sm-4 control-label text-right">Appointment Status:</label>
            <div class="col-sm-8">
                <select data-placeholder="Select a Status..." name="follow_up_call_status" class="chosen-select-deselect form-control input-sm">
                    <option value=""></option>
                    <option value="Booked Unconfirmed" <?php if ($follow_up_call_status == "Booked Unconfirmed") { echo " selected"; } ?> >Booked Unconfirmed</option>
                    <option value="Booked Confirmed" <?php if ($follow_up_call_status == "Booked Confirmed") { echo " selected"; } ?> >Booked Confirmed</option>
                    <option value="Arrived" <?php if ($follow_up_call_status == "Arrived") { echo " selected"; } ?> >Arrived</option>
                    <option value="Invoiced" <?php if ($follow_up_call_status == "Invoiced") { echo " selected"; } ?> >Invoiced</option>
                    <option value="Paid" <?php if ($follow_up_call_status == "Paid") { echo " selected"; } ?> >Paid</option>
                    <option value="Rescheduled" <?php if ($follow_up_call_status == "Rescheduled") { echo " selected"; } ?> >Rescheduled</option>
                    <option value="Late Cancellation / No-Show" <?php if ($follow_up_call_status == "Late Cancellation / No-Show") { echo " selected"; } ?> >Late Cancellation / No-Show</option>
                    <option value="Call Again Left Message" <?php if ($follow_up_call_status == "Call Again Left Message") { echo " selected"; } ?> >Call Again Left Message</option>
                    <option value="Call Again No Message" <?php if ($follow_up_call_status == "Call Again No Message") { echo " selected"; } ?> >Call Again No Message</option>

                    <option value="Cancelled" <?php if ($follow_up_call_status == "Cancelled") { echo " selected"; } ?> >Cancelled</option>
                </select>
            </div>
          </div>

          <div class="form-group clearfix">
            <label for="first_name" class="col-sm-4 control-label text-right">Type:</label>
            <div class="col-sm-8">
                <select data-placeholder="Choose a Type..." name="type" class="chosen-select-deselect form-control input-sm">
                    <option value=""></option>
                    <?php $appointment_types = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `appointment_type` WHERE `deleted` = 0"),MYSQLI_ASSOC);
                    foreach ($appointment_types as $appointment_type) {
                        echo '<option '.($type == $appointment_type['id'] ? 'selected' : '').' value="'.$appointment_type['id'].'">'.$appointment_type['name'].'</option>';
                    } ?>
                </select>
            </div>
          </div>

          <div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Notes:</label>
            <div class="col-sm-8">
              <textarea name="notes" rows="5" cols="50" class="form-control"><?php echo $notes; ?></textarea>
            </div>
          </div>

          <div class="form-group clearfix">
            <label for="first_name" class="col-sm-4 control-label text-right">Follow Up Call:</label>
            <div class="col-sm-8">
                <input name="follow_up_call_date" placeholder="Click for Datepicker" value="<?php echo $follow_up_call_date; ?>" type="text" class="datefuturepicker"></p>
            </div>
          </div>

             <div class="form-group">
                <div class="col-sm-4">
                    <p><span class="empire-red pull-right"><em>Required Fields *</em></span></p>
                </div>
                <div class="col-sm-8"></div>
            </div>

          <div class="form-group">
            <div class="col-sm-4 clearfix">
                <a href="booking.php?contactid=<?php echo $therapistsid; ?>" class="btn brand-btn pull-right">Back</a>
            </div>
            <div class="col-sm-8">
                <button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
            </div>
          </div>

        

        </form>
    </div>
  </div>

<?php include ('../footer.php'); ?>