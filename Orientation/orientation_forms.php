<?php
/*
Dashboard
*/
include ('../include.php');
include_once('../tcpdf/tcpdf.php');
include ('orientation_pdf.php');
error_reporting(0);

if (isset($_POST['submit_emp_info_medical_form'])) {

    $contactid = $_POST['contactid'];
    $home_phone = filter_var($_POST['home_phone'],FILTER_SANITIZE_STRING);
	$cell_phone = filter_var($_POST['cell_phone'],FILTER_SANITIZE_STRING);
    $sin = filter_var($_POST['sin'],FILTER_SANITIZE_STRING);
    $health_care_card_number = filter_var($_POST['health_care_card_number'],FILTER_SANITIZE_STRING);
    $eme1_name = filter_var($_POST['eme1_name'],FILTER_SANITIZE_STRING);
    $eme1_phone = filter_var($_POST['eme1_phone'],FILTER_SANITIZE_STRING);
    $eme1_relationship = filter_var($_POST['eme1_relationship'],FILTER_SANITIZE_STRING);
    $eme2_name = filter_var($_POST['eme2_name'],FILTER_SANITIZE_STRING);
    $eme2_phone = filter_var($_POST['eme2_phone'],FILTER_SANITIZE_STRING);
    $eme2_relationship = filter_var($_POST['eme2_relationship'],FILTER_SANITIZE_STRING);
    $medical_conditions = filter_var($_POST['medical_conditions'],FILTER_SANITIZE_STRING);
    $medications = filter_var($_POST['medications'],FILTER_SANITIZE_STRING);
    $today_date = $_POST['today_date'];

	$result = mysqli_query($dbc, "SELECT id FROM orientation_emp_info_medical_form WHERE contactid='$contactid'");
	$num_rows = mysqli_num_rows($result);

	if($num_rows > 0) {
		$query_insert_emp_info_medical_form = "UPDATE `orientation_emp_info_medical_form` SET `home_phone` = '$home_phone', `sin` = '$sin', `health_care_card_number` = '$health_care_card_number', `eme1_name` = '$eme1_name', `eme1_phone` = '$eme1_phone', `eme1_relationship` = '$eme1_relationship', `eme2_name` = '$eme2_name', `eme2_phone` = '$eme2_phone', `eme2_relationship` = '$eme2_relationship', `medical_conditions` = '$medical_conditions', `medications` = '$medications' WHERE `contactid` = '$contactid'";
        $url_msg = 'Updated';
	} else {
		$query_insert_emp_info_medical_form = "INSERT INTO `orientation_emp_info_medical_form` (`contactid`, `home_phone`, `sin`, `health_care_card_number`, `eme1_name`, `eme1_phone`, `eme1_relationship`, `eme2_name`, `eme2_phone`, `eme2_relationship`, `medical_conditions`, `medications`, `today_date`) VALUES ('$contactid', '$home_phone', '$sin', '$health_care_card_number', '$eme1_name', '$eme1_phone', '$eme1_relationship', '$eme2_name', '$eme2_phone', '$eme2_relationship', '$medical_conditions', '$medications', '$today_date')";
        $url_msg = 'Added';

		$query_update_orientation = "UPDATE `orientation` SET `emp_info_medical_form` = 1 WHERE `contactid` = '$contactid'";

		$result_update_orientation = mysqli_query($dbc, $query_update_orientation);
	}
    $result_insert_emp_info_medical_form = mysqli_query($dbc, $query_insert_emp_info_medical_form);

	$query_update_emp = "UPDATE `staff` SET `home_phone` = '$home_phone', `mobile_phone` = '$cell_phone' WHERE `contactid` = '$contactid'";
	$result_update_emp = mysqli_query($dbc, $query_update_emp);

    echo emp_info_medical_form($dbc,$contactid);

    echo '<script type="text/javascript"> window.location.replace("orientation.php?contactid='.$contactid.'"); </script>';

    //header('Location: orientation.php?contactid='.$contactid);
}

if (isset($_POST['submit_emp_driver_info_form'])) {

    $contactid = $_POST['contactid'];
    $driver_license_number = filter_var($_POST['driver_license_number'],FILTER_SANITIZE_STRING);
    $expiry_date = $_POST['expiry_date'];
    $class = filter_var($_POST['class'],FILTER_SANITIZE_STRING);
	$truck_standard_transmission = filter_var($_POST['truck_standard_transmission'],FILTER_SANITIZE_STRING);
	$tdg_ticket = filter_var($_POST['tdg_ticket'],FILTER_SANITIZE_STRING);
    $tdg_expiry_date = $_POST['tdg_expiry_date'];
    $today_date = $_POST['today_date'];

	$result = mysqli_query($dbc, "SELECT id FROM orientation_emp_driver_info_form WHERE contactid='$contactid'");
	$num_rows = mysqli_num_rows($result);

	if($num_rows > 0) {
		$query_insert_emp_driver_info_form = "UPDATE `orientation_emp_driver_info_form` SET `driver_license_number` = '$driver_license_number', `expiry_date` = '$expiry_date', `class` = '$class', `truck_standard_transmission` = '$truck_standard_transmission', `tdg_ticket` = '$tdg_ticket', `tdg_expiry_date` = '$tdg_expiry_date' WHERE `contactid` = '$contactid'";
        $url_msg = 'Updated';
	} else {
		$query_insert_emp_driver_info_form = "INSERT INTO `orientation_emp_driver_info_form` (`contactid`, `driver_license_number`, `expiry_date`, `class`, `truck_standard_transmission`, `tdg_ticket`, `tdg_expiry_date`, `today_date`) VALUES ('$contactid', '$driver_license_number', '$expiry_date', '$class', '$truck_standard_transmission', '$tdg_ticket', '$tdg_expiry_date', '$today_date')";

		$query_update_orientation = "UPDATE `orientation` SET `emp_driver_info_form` = 1 WHERE `contactid` = '$contactid'";
		$result_update_orientation = mysqli_query($dbc, $query_update_orientation);
        $url_msg = 'Added';
    }

    $result_insert_emp_driver_info_form = mysqli_query($dbc, $query_insert_emp_driver_info_form);

    echo emp_driver_info_form($dbc,$contactid);

    echo '<script type="text/javascript"> window.location.replace("orientation.php?contactid='.$contactid.'"); </script>';

}

if (isset($_POST['submit_time_clock_policy'])) {

    $contactid = $_POST['contactid'];

    $query_update_orientation = "UPDATE `orientation` SET `time_clock_policy` = 1 WHERE `contactid` = '$contactid'";

    $result_update_orientation = mysqli_query($dbc, $query_update_orientation);

    echo '<script type="text/javascript"> window.location.replace("orientation.php?contactid='.$contactid.'"); </script>';
}

if (isset($_POST['submit_confidential_information'])) {

    $contactid = $_POST['contactid'];

    $query_update_orientation = "UPDATE `orientation` SET `confidential_information` = 1 WHERE `contactid` = '$contactid'";

    $result_update_orientation = mysqli_query($dbc, $query_update_orientation);

    echo '<script type="text/javascript"> window.location.replace("orientation.php?contactid='.$contactid.'"); </script>';
}

if (isset($_POST['submit_pay_agreement'])) {

    $contactid = $_POST['contactid'];
    $agreement_date = $_POST['agreement_date'];
    $regular_time_pay = filter_var($_POST['regular_time_pay'],FILTER_SANITIZE_STRING);
    $today_date = $_POST['today_date'];

	$result = mysqli_query($dbc, "SELECT id FROM orientation_pay_agreement WHERE contactid='$contactid'");
	$num_rows = mysqli_num_rows($result);

	if($num_rows > 0) {
		$query_insert_pay_agreement = "UPDATE `orientation_pay_agreement` SET `agreement_date` = '$agreement_date', `regular_time_pay` = '$regular_time_pay' WHERE `contactid` = '$contactid'";
	} else {
		$query_insert_pay_agreement = "INSERT INTO `orientation_pay_agreement` (`contactid`, `agreement_date`, `regular_time_pay`, `today_date`) VALUES ('$contactid', '$agreement_date', '$regular_time_pay', '$today_date')";

		$query_update_orientation = "UPDATE `orientation` SET `pay_agreement` = 1 WHERE `contactid` = '$contactid'";

		$result_update_orientation = mysqli_query($dbc, $query_update_orientation);
    }

    $result_insert_pay_agreement = mysqli_query($dbc, $query_insert_pay_agreement);

    echo '<script type="text/javascript"> window.location.replace("orientation.php?contactid='.$contactid.'"); </script>';
}

if (isset($_POST['submit_copy_of_driver_lic_safety_tickets'])) {
    $contactid = $_POST['contactid'];
	$type = filter_var($_POST['type'],FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
    $issue_date = $_POST['issue_date'];
    $expiry_date = $_POST['expiry_date'];
    $doc_name = htmlspecialchars($_FILES["file"]["name"], ENT_QUOTES);

    if (!file_exists('download/orientation')) {
        mkdir('download/orientation', 0777, true);
    }

    move_uploaded_file($_FILES["file"]["tmp_name"], "download/orientation/" . $_FILES["file"]["name"]) ;

    $query_insert_copy_of_driver_lic_safety_tickets = "INSERT INTO `orientation_copy_of_driver_lic_safety_tickets` (`contactid`, `type`, `description`, `issue_date`, `expiry_date`, `doc_name`) VALUES ('$contactid', '$type', '$description', '$issue_date', '$expiry_date', '$doc_name')";

    $result_insert_copy_of_driver_lic_safety_tickets = mysqli_query($dbc, $query_insert_copy_of_driver_lic_safety_tickets);

    $query_update_orientation = "UPDATE `orientation` SET `copy_of_driver_lic_safety_tickets` = 1 WHERE `contactid` = '$contactid'";

    $result_update_orientation = mysqli_query($dbc, $query_update_orientation);

    echo '<script type="text/javascript"> window.location.replace("orientation.php?contactid='.$contactid.'"); </script>';
}

if (isset($_POST['submit_direct_deposit_info'])) {

    $contactid = $_POST['contactid'];
    $financial_institution_name = filter_var($_POST['financial_institution_name'],FILTER_SANITIZE_STRING);
    $transit_number = filter_var($_POST['transit_number'],FILTER_SANITIZE_STRING);
    $financial_institution_number = filter_var($_POST['financial_institution_number'],FILTER_SANITIZE_STRING);
    $account_number = filter_var($_POST['account_number'],FILTER_SANITIZE_STRING);
    $today_date = $_POST['today_date'];

	$result = mysqli_query($dbc, "SELECT id FROM orientation_direct_deposit_info WHERE contactid='$contactid'");
	$num_rows = mysqli_num_rows($result);

	if($num_rows > 0) {
		$query_insert_direct_deposit_info = "UPDATE `orientation_direct_deposit_info` SET `financial_institution_name` = '$financial_institution_name', `transit_number` = '$transit_number', `financial_institution_number` = '$financial_institution_number', `account_number` = '$account_number' WHERE `contactid` = '$contactid'";
	} else {
		$query_insert_direct_deposit_info = "INSERT INTO `orientation_direct_deposit_info` (`contactid`, `financial_institution_name`, `transit_number`, `financial_institution_number`, `account_number`, `today_date`) VALUES ('$contactid', '$financial_institution_name', '$transit_number', '$financial_institution_number', '$account_number', '$today_date')";

		$query_update_orientation = "UPDATE `orientation` SET `direct_deposit_info` = 1 WHERE `contactid` = '$contactid'";
		$result_update_orientation = mysqli_query($dbc, $query_update_orientation);
	}

    $result_insert_direct_deposit_info = mysqli_query($dbc, $query_insert_direct_deposit_info);
    echo direct_deposit_info($dbc,$contactid);

    echo '<script type="text/javascript"> window.location.replace("orientation.php?contactid='.$contactid.'"); </script>';
}

if (isset($_POST['submit_emp_substance_abuse_policy_confirm'])) {

    $contactid = $_POST['contactid'];
    $query_update_orientation = "UPDATE `orientation` SET `emp_substance_abuse_policy_confirm` = 1 WHERE `contactid` = '$contactid'";

    $result_update_orientation = mysqli_query($dbc, $query_update_orientation);

    echo '<script type="text/javascript"> window.location.replace("orientation.php?contactid='.$contactid.'"); </script>';
}

if (isset($_POST['submit_emp_right_to_refuse_unsafe_work_form'])) {

    $contactid = $_POST['contactid'];
    $query_update_orientation = "UPDATE `orientation` SET `emp_right_to_refuse_unsafe_work_form` = 1 WHERE `contactid` = '$contactid'";

    $result_update_orientation = mysqli_query($dbc, $query_update_orientation);

    echo '<script type="text/javascript"> window.location.replace("orientation.php?contactid='.$contactid.'"); </script>';
}

if (isset($_POST['submit_emp_light_duty_awareness'])) {

    $contactid = $_POST['contactid'];
    $query_update_orientation = "UPDATE `orientation` SET `emp_light_duty_awareness` = 1 WHERE `contactid` = '$contactid'";

    $result_update_orientation = mysqli_query($dbc, $query_update_orientation);

    echo '<script type="text/javascript"> window.location.replace("orientation.php?contactid='.$contactid.'"); </script>';
}

if (isset($_POST['submit_emp_shop_yard_office_ori'])) {

    $contactid = $_POST['contactid'];
    $query_update_orientation = "UPDATE `orientation` SET `emp_shop_yard_office_ori` = 1 WHERE `contactid` = '$contactid'";

    $result_update_orientation = mysqli_query($dbc, $query_update_orientation);

    echo '<script type="text/javascript"> window.location.replace("orientation.php?contactid='.$contactid.'"); </script>';
}

if (isset($_POST['submit_benefits_app'])) {

    $contactid = $_POST['contactid'];
    $benefit_app_submit = $_POST['benefit_app_submit'];
    $benefit_app_accepted = $_POST['benefit_app_accepted'];

	$result = mysqli_query($dbc, "SELECT id FROM orientation_benefits_app WHERE contactid='$contactid'");
	$num_rows = mysqli_num_rows($result);

	if($num_rows > 0) {
		$query_insert_direct_deposit_info = "UPDATE `orientation_benefits_app` SET `benefit_app_submit` = '$benefit_app_submit', `benefit_app_accepted` = '$benefit_app_accepted' WHERE `contactid` = '$contactid'";
        $result_update_orientation = mysqli_query($dbc, $query_insert_direct_deposit_info);
	} else {
		$query_insert_direct_deposit_info = "INSERT INTO `orientation_benefits_app` (`contactid`, `benefit_app_submit`, `benefit_app_accepted`) VALUES ('$contactid', '$benefit_app_submit', '$benefit_app_accepted')";
        $result_update_orientation = mysqli_query($dbc, $query_insert_direct_deposit_info);

		$query_update_orientation = "UPDATE `orientation` SET `benefits_app` = 1 WHERE `contactid` = '$contactid'";
		$result_update_orientation = mysqli_query($dbc, $query_update_orientation);
	}

    echo '<script type="text/javascript"> window.location.replace("orientation.php?contactid='.$contactid.'"); </script>';
}

if (isset($_POST['submit_emp_trained_in_ppe_req'])) {

    $contactid = $_POST['contactid'];
    $query_update_orientation = "UPDATE `orientation` SET `emp_trained_in_ppe_req` = 1 WHERE `contactid` = '$contactid'";

    $result_update_orientation = mysqli_query($dbc, $query_update_orientation);

    echo '<script type="text/javascript"> window.location.replace("orientation.php?contactid='.$contactid.'"); </script>';
}

if (isset($_POST['submit_verbal_training_in_eme_res_plan'])) {

    $contactid = $_POST['contactid'];
    $query_update_orientation = "UPDATE `orientation` SET `verbal_training_in_eme_res_plan` = 1 WHERE `contactid` = '$contactid'";

    $result_update_orientation = mysqli_query($dbc, $query_update_orientation);

    echo '<script type="text/javascript"> window.location.replace("orientation.php?contactid='.$contactid.'"); </script>';
}

?>

<script type="text/javascript">

    $(document).ready(function() {
        $("input[name$='tdg_ticket']").click(function() {
            var test = $(this).val();
            if(test == 'Yes') {
                $(".tdg_expiry_date").show();
            } else {
                $(".tdg_expiry_date").hide();
            }
        });

    });

</script>

</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
	<div class="row">

        <?php
            //$contactid = $_SESSION['contactid'];
            $contactid = $_GET['contactid'];
            $form_name = $_GET['form_name'];
		    $result_staff = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM contacts WHERE contactid='$contactid'"));

            if($form_name == 'emp_info_medical_form') { ?>

                <form id="form1" name="emp_info_medical_form" method="post" action="orientation_forms.php" enctype="multipart/form-data" class="form-horizontal" role="form">

                    <h1 class="double-pad-bottom">Employee Information & Medical form</h1>
					<div class="pad-left double-gap-bottom"><a href="orientation.php" class="btn config-btn">Back to Dashboard</a></div>
                    <input type="hidden" name="contactid" value="<?php echo $contactid; ?>" >

					<?php
					$home_phone = '';
					$sin = '';
					$health_care_card_number = '';
					$eme1_name =	'';
					$eme1_phone	= '';
					$eme1_relationship = '';
					$eme2_name = '';
					$eme2_phone = '';
					$eme2_relationship = '';
					$medical_conditions = '';
					$medications = '';

					$result = mysqli_query($dbc, "SELECT * FROM orientation_emp_info_medical_form WHERE contactid='$contactid'");
					$num_rows = mysqli_num_rows($result);
					$emp_info_medical_form = mysqli_fetch_assoc($result);
					if($num_rows > 0) {
						$home_phone = $emp_info_medical_form['home_phone'];
						$sin = $emp_info_medical_form['sin'];
						$health_care_card_number = $emp_info_medical_form['health_care_card_number'];
						$eme1_name =	$emp_info_medical_form['eme1_name'];
						$eme1_phone	= $emp_info_medical_form['eme1_phone'];
						$eme1_relationship	= $emp_info_medical_form['eme1_relationship'];
						$eme2_name = $emp_info_medical_form['eme2_name'];
						$eme2_phone = $emp_info_medical_form['eme2_phone'];
						$eme2_relationship =	$emp_info_medical_form['eme2_relationship'];
						$medical_conditions	= $emp_info_medical_form['medical_conditions'];
						$medications	= $emp_info_medical_form['medications'];
					}
					?>
                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Name:</label>
                        <div class="col-sm-8">
                          <input name="employee_name" type="text" readonly value="<?php echo $result_staff['first_name']; ?> <?php echo $result_staff['last_name']; ?>" class="form-control" />
                        </div>
                    </div>

                    <!-- <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Address:</label>
                        <div class="col-sm-8">
                          <input name="address" type="text"  value="<?php echo $result_staff['mail_street'].' '.$result_staff['mail_city'].' '.$result_staff['mail_state'].' '.$result_staff['mail_country'].' '.$result_staff['mail_zip']; ?>" class="form-control" />
                        </div>
                    </div> -->

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Home Phone:<br><em>Format : (000) 000-0000</em></label>
                        <div class="col-sm-8">
                          <input name="home_phone" type="text" value="<?php echo $result_staff['home_phone']; ?>" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Cell Phone:<br><em>Format : (000) 000-0000</em></label>
                        <div class="col-sm-8">
                          <input name="cell_phone" type="text"  value="<?php echo $result_staff['mobile_phone']; ?>"   class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">S.I.N.<span class="text-red">*</span>:</label>
                        <div class="col-sm-8">
                          <input name="sin" type="text" required value="<?php echo $sin; ?>" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Health Care Card Number<span class="text-red">*</span>:</label>
                        <div class="col-sm-8">
                          <input name="health_care_card_number" value="<?php echo $health_care_card_number; ?>" type="text" required class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Contact in Case of Emergency:</label>
                        <div class="col-sm-8">

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Name:</label>
                        <div class="col-sm-8">
                          <input name="eme1_name" type="text" value="<?php echo $eme1_name; ?>" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Phone:<br><em>Format : (000) 000-0000</em></label>
                        <div class="col-sm-8">
                          <input name="eme1_phone" type="text" value="<?php echo $eme1_phone; ?>" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Relationship:</label>
                        <div class="col-sm-8">
                          <input name="eme1_relationship" type="text" value="<?php echo $eme1_relationship; ?>" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Name:</label>
                        <div class="col-sm-8">
                          <input name="eme2_name" type="text" value="<?php echo $eme2_name; ?>" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Phone:<br><em>Format : (000) 000-0000</em></label>
                        <div class="col-sm-8">
                          <input name="eme2_phone" type="text" value="<?php echo $eme2_phone; ?>" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Relationship:</label>
                        <div class="col-sm-8">
                          <input name="eme2_relationship" type="text" value="<?php echo $eme2_relationship; ?>" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Do you have any medical conditions that Washtech needs to know about? If yes, please list:
                    </label>
                        <div class="col-sm-8">
                          <input name="medical_conditions" value="<?php echo $medical_conditions; ?>" type="text" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Are you on any medications? If yes, please list:
                    </label>
                        <div class="col-sm-8">
                          <input name="medications" type="text" value="<?php echo $medications; ?>" class="form-control" />
                          <p><em>If in future you are on any medications you must notify a Washtech representative so proper care for yourself in the event of an emergency may be given.</em></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <p>I have been advised and aware of the location of the Washtech Safely Manual and will review during my time of employment. In signing this agree to all terms and conditions of employment set down by this program.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <label for="site_name"><input type="checkbox" name="accept_policy" required value=1>&nbsp; Information which I provided here is true and correct. I have read and understand the content of this policy.<em class="text-red">*</em></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Date:</label>
                        <div class="col-sm-8">
                          <input name="today_date" type="text" readonly value="<?php echo date('Y-m-d'); ?>"   class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <p><span class="text-red pull-right"><em>Required Fields *</em></span></p>
                        </div>
                        <div class="col-sm-8"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
						<?php if(!empty($_GET['from'])) {
                            echo '<a href="eis.php" class="btn brand-btn pull-right">Back</a>';
						} else {
							echo '<a href="orientation.php" class="btn brand-btn btn-lg pull-right">Back</a>';
							//echo '<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>';
						} ?>
                        </div>
                        <div class="col-sm-8">
                            <button type="submit" name="submit_emp_info_medical_form" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                        </div>
                    </div>

                </form>

            <?php } ?>

            <?php if($form_name == 'emp_driver_info_form') { ?>
                <form id="form1" name="emp_info_medical_form" method="post" action="orientation_forms.php" enctype="multipart/form-data" class="form-horizontal" role="form">

                    <h1 class="double-pad-bottom">Employee Driver Information Form</h1>
					<div class="pad-left double-gap-bottom"><a href="orientation.php" class="btn config-btn">Back to Dashboard</a></div>
                    <input type="hidden" name="contactid" value="<?php echo $contactid; ?>">
					<?php
					$driver_license_number = '';
					$expiry_date = '';
					$class = '';
					$truck_standard_transmission =	'';
					$tdg_ticket	= '';
                    $tdg_expiry_date = '';

					$result = mysqli_query($dbc, "SELECT * FROM orientation_emp_driver_info_form WHERE contactid='$contactid'");
					$num_rows = mysqli_num_rows($result);
					$emp_driver_info_form = mysqli_fetch_assoc($result);
					$yes_truck_standard_transmission = '';
					$no_truck_standard_transmission = '';
					$yes_tdg_ticket = '';
					$no_tdg_ticket = '';

					if($num_rows > 0) {
						$driver_license_number = $emp_driver_info_form['driver_license_number'];
						$expiry_date = $emp_driver_info_form['expiry_date'];
						$class = $emp_driver_info_form['class'];
						$truck_standard_transmission =	$emp_driver_info_form['truck_standard_transmission'];
						$tdg_ticket	= $emp_driver_info_form['tdg_ticket'];

						$yes_truck_standard_transmission = $truck_standard_transmission=='Yes' ? 'checked' : '';
						$no_truck_standard_transmission = $truck_standard_transmission=='No' ? 'checked' : '';

						$yes_tdg_ticket = $tdg_ticket=='Yes' ? 'checked' : '';
						$no_tdg_ticket = $tdg_ticket=='No' ? 'checked' : '';
                        $tdg_expiry_date = $emp_driver_info_form['tdg_expiry_date'];

                        if($tdg_ticket=='Yes') {
                            $tdg = 'style = "display:block;"';
                        } else {
                            $tdg = 'style = "display:none;"';
                        }
					}
					?>
                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Name:</label>
                        <div class="col-sm-8">
                          <input name="employee_name" type="text" readonly value="<?php echo $result_staff['first_name']; ?> <?php echo $result_staff['last_name']; ?>" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Driver License Number<span class="text-red">*</span>:</label>
                        <div class="col-sm-8">
                          <input name="driver_license_number" required value="<?php echo $driver_license_number; ?>" type="text" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group clearfix orientation_date">
                        <label for="first_name" class="col-sm-4 control-label text-right">Expiry Date:</label>
                        <div class="col-sm-8">
                            <input name="expiry_date" type="text" class="datepicker" value="<?php echo $expiry_date; ?>"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Class:</label>
                        <div class="col-sm-8">
                          <input name="class" type="text" value="<?php echo $class; ?>" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group clearfix location">
                        <label for="site_name" class="col-sm-4 control-label text-right">Can you drive a truck with a standard transmission?</label>
                        <div class="col-sm-8">
                          <div class="radio">
                        <label class="pad-right"><input type="radio" name="truck_standard_transmission" value="Yes" <?php echo $yes_truck_standard_transmission; ?> >&nbsp; Yes</label>
                        <label class="pad-right"><input type="radio" <?php echo $no_truck_standard_transmission; ?> name="truck_standard_transmission" value="No" >&nbsp; No </label>
                          </div>
                        </div>
                    </div>

                    <div class="form-group clearfix location">
                        <label for="site_name" class="col-sm-4 control-label text-right">Do you have a current TDG (Transportation of Dangerous Goods)Ticket?</label>
                        <div class="col-sm-8">
                          <div class="radio">
                        <label class="pad-right"><input type="radio" <?php echo $yes_tdg_ticket; ?> name="tdg_ticket" value="Yes" >&nbsp; Yes</label>
                        <label class="pad-right"><input type="radio" <?php echo $no_tdg_ticket; ?> name="tdg_ticket" value="No" >&nbsp; No </label>
                          </div>
                        </div>
                    </div>

                    <div class="form-group tdg_expiry_date" <?php echo $tdg;?> >
                        <label for="site_name" class="col-sm-4 control-label">TDG Expiry Date:</label>
                        <div class="col-sm-8">
                          <input name="tdg_expiry_date" type="text" value="<?php echo $tdg_expiry_date; ?>" class="datepicker" />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <p>As an employee of Washtech you are to follow all laws of the road. If at any time Washtech representatives feel that you are not operating equipment in a safe and courteous manner Washtech reserves the right to suspend you from operating any or all equipment.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <label for="site_name"><input type="checkbox" name="accept_policy" required value=1>&nbsp; Information which I provided here is true and correct. I have read and understand the content of this policy.<em class="text-red">*</em></label>
                        </div>
                    </div>

                    <h1 class="double-pad-bottom">VEHICLE POLICY</h1>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <p>Washtech provides certain employees with vehicles in order to carry out the duties and functions related to their positions. The use of a Company vehicle is subject to conditions and restrictions, which must be observed by all employees assigned to operate a Company vehicle.</p>

                            <p>When you operate a Washtech vehicle, you accept certain responsibilities to yourself, to the Company, and to the public. Our name and reputation rides with you. It is expected that you will drive with care at all times using common sense and good judgement.</p>

                            <p>The Company is committed to ensuring that all vehicles provided for the use of employees are in good working order, operated in a safe manner.</p>

                            <p>All Company employees eligible for vehicle assignment must be in possession of a valid driver's license in good standing. The driver's license must be of the appropriate class and free of limiting restrictions with respect to the vehicle assigned to the employee. A copy of the driver's license and abstract is required for insurance purpose.</p>

                            <p>All Company vehicles must be operated in a safe and responsible manner in accordance with applicable traffic laws and regulations. The employee must ensure the vehicle is in proper working order. This includes regular and required maintenance.</p>

                            <p>Washtech employees are responsible for all fines and tickets resulting from the operation of the Company vehicle to with they are assigned or operating. This includes any moving violations, photo radar tickets, parking tickets, or any other violations of applicable traffic laws and regulations.</p>
                            <p>Washtech prohibits the operation of any Company vehicle while the driver is under the influence of alcohol or intoxicating drugs.</p>
                            <p>All persons in a Company vehicle must wear seat belts at all times.</p>
                            <p>Failure to comply with the Company policy may result in disciplinary action, up to and including dismissal.</p>
                            <h3>PERSONAL USE</h3>
                            <p>Personal use of Company vehicles shall be limited to the employee to which the vehicle is assigned. The Company may permit, at the discretion of the Company and with prior notification to the employee, immediate members of the employee's family to operate the vehicle subject to the conditions of this Policy and other Policies of the Company.</p>
                            <p>At no time shall a Company vehicle be operated by an individual under the age of twenty-one (21) years of age.</p>
                            <p>The employee's responsibilities regarding the use and operation of the Company vehicle under this Policy extends to any personal use of the Company vehicle.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <label for="site_name"><input type="checkbox" name="accept_policy" required value=1>&nbsp; Information which I provided here is true and correct. I have read and understand the content of this policy.<em class="text-red">*</em></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Date:</label>
                        <div class="col-sm-8">
                          <input name="today_date" type="text" readonly value="<?php echo date('Y-m-d'); ?>" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <p><span class="text-red pull-right"><em>Required Fields *</em></span></p>
                        </div>
                        <div class="col-sm-8"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
						<?php if(!empty($_GET['from'])) {
                            echo '<a href="eis.php" class="btn brand-btn pull-right">Back</a>';
						} else {
							echo '<a href="orientation.php" class="btn brand-btn btn-lg pull-right">Back</a>';
							//echo '<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>';
						} ?>
                        </div>
                        <div class="col-sm-8">
                            <button type="submit" name="submit_emp_driver_info_form" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                        </div>
                    </div>

                </form>

            <?php } ?>


            <?php if($form_name == 'time_clock_policy') { ?>
                <form id="form1" name="time_clock_policy_form" method="post" action="orientation_forms.php" enctype="multipart/form-data" class="form-horizontal" role="form">
                    <input type="hidden" name="contactid" value="<?php echo $contactid; ?>">

                    <h1 class="double-pad-bottom">Work Hours Policy</h1>
					<div class="pad-left double-gap-bottom"><a href="orientation.php" class="btn config-btn">Back to Dashboard</a></div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <p>Hours of Operation are 08:00-16:30, Monday-Friday.Employees are expected to be present during regular Hours of Operation.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <label for="site_name"><input type="checkbox" name="accept_policy" required value=1>&nbsp; I have read and understand the terms and conditions of Washtech's Work Hours Policy. I agree to the condition herein.<em class="text-red">*</em></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <p><span class="text-red pull-right"><em>Required Fields *</em></span></p>
                        </div>
                        <div class="col-sm-8"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
                            <a href="orientation.php" class="btn brand-btn btn-lg pull-right">Back</a>
							<!--<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
                        </div>
                        <div class="col-sm-8">
                            <button type="submit" name="submit_time_clock_policy" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                        </div>
                    </div>

                    <br/>
                </form>

            <?php } ?>

            <?php if($form_name == 'confidential_information') { ?>
                <form id="form1" name="confidential_information" method="post" action="orientation_forms.php" enctype="multipart/form-data" class="form-horizontal" role="form">
                    <input type="hidden" name="contactid" value="<?php echo $contactid; ?>">

                    <h1 class="double-pad-bottom">Confidential Information</h1>
					<div class="pad-left double-gap-bottom"><a href="orientation.php" class="btn config-btn">Back to Dashboard</a></div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">

                            <input type="hidden" name="contactid" value="<?php echo $contactid; ?>">

                            <p>Employees may not disclose to any outside person confidential information about Washtech’s activities, except when properly authorized to do so in the normal course of business.  Similarly, employees must respect confidentiality of information received from co-workers. </p>
                            <p>It is unethical for employees to use confidential information gained by virtue of their employment at Washtech for their personal gain, or to the benefit of friends, relatives or associates.</p>
                            <p>Employees my not, either directly or indirectly, acquire or dispose of any information obtained in the course of work at Washtech, which has not been publically disclosed. If an employee is not sure whether or not information has been publically disclosed, they must consult with a manager or owner. </p>
                            <p>Sensitive Information includes:</p>
                            <ul>
                                <li>Financial Information</li>
                                <li>New products and services that are being researched and developed</li>
                                <li>Business plans or negotiations</li>
                                <li>Sales Results</li>
                                <li>Confidential information of all existing and future customers</li>
                                <li>Confidential information of all co-workers</li>
                            </ul>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <label for="site_name"><input type="checkbox" name="accept_policy" required value=1>&nbsp; Information which I have provided here is true and correct.  I have read and understand the content of this policy.<em class="text-red">*</em></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
                            <a href="orientation.php" class="btn brand-btn btn-lg pull-right">Back</a>
							<!--<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
                        </div>
                        <div class="col-sm-8">
                            <button type="submit" name="submit_confidential_information" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                        </div>
                    </div>

                    <br/>
                </form>


            <?php } ?>

            <?php if($form_name == 'pay_agreement') { ?>
                <form id="form1" name="pay_agreement_form" method="post" action="orientation_forms.php" enctype="multipart/form-data" class="form-horizontal" role="form">

                    <h1 class="double-pad-bottom">Pay Agreement</h1>
					<div class="pad-left double-gap-bottom"><a href="orientation.php" class="btn config-btn">Back to Dashboard</a></div>
                    <input type="hidden" name="contactid" value="<?php echo $contactid; ?>">
					<?php
					$agreement_date = '';
					$regular_time_pay = '';

					$result = mysqli_query($dbc, "SELECT * FROM orientation_pay_agreement WHERE contactid='$contactid'");
					$num_rows = mysqli_num_rows($result);
					$pay_agreement = mysqli_fetch_assoc($result);
					if($num_rows > 0) {
						$agreement_date = $pay_agreement['agreement_date'];
						$regular_time_pay = $pay_agreement['regular_time_pay'];
					}
					?>

                        <div class="form-group clearfix orientation_date">
                            <label for="first_name" class="col-sm-4 control-label text-right">Hire Date:</label>
                            <div class="col-sm-8">
                                <input name="agreement_date" type="text" class="datepicker" value="<?php echo $agreement_date; ?>"></p>
                            </div>
                        </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Salary:</label>
                        <div class="col-sm-8">
                          <input name="regular_time_pay" value="<?php echo $regular_time_pay; ?>" type="text" class="form-control regular_time_pay" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label"></label>
                        <div class="col-sm-8">
                          <p>On-Call Pay Agreement:</p>
                          On-Call Pay is $100 per week (Overtime 1) plus $35/hr (Overtime 2) for any after-hours service calls that are performed by the employee on call.
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <label for="site_name"><input type="checkbox" name="accept_policy" required value=1>&nbsp; Information which I provided here is true and correct. I have read and understand the content of this policy.<em class="text-red">*</em></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Date:</label>
                        <div class="col-sm-8">
                          <input name="today_date" type="text" readonly value="<?php echo date('Y-m-d'); ?>"   class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <p><em>Probationary period consists of 3 months from the start date of employment.</em></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <p><span class="text-red pull-right"><em>Required Fields *</em></span></p>
                        </div>
                        <div class="col-sm-8"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
						<?php if(!empty($_GET['from'])) {
                            echo '<a href="eis.php" class="btn brand-btn btn-lg pull-right">Back</a>';
						} else {
							echo '<a href="orientation.php" class="btn brand-btn btn-lg pull-right">Back</a>';
							//echo '<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>';
						} ?>
                        </div>
                        <div class="col-sm-8">
                            <button type="submit" name="submit_pay_agreement" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                        </div>
                    </div>

                </form>
            <?php } ?>

            <?php if($form_name == 'direct_deposit_info') { ?>

                <form id="form1" name="direct_deposit_info_form" method="post" action="orientation_forms.php" enctype="multipart/form-data" class="form-horizontal" role="form">

                    <h1 class="double-pad-bottom">Employee Direct Deposit Information</h1>
					<div class="pad-left double-gap-bottom"><a href="orientation.php" class="btn config-btn">Back to Dashboard</a></div>
                    <input type="hidden" name="contactid" value="<?php echo $contactid; ?>">
					<?php
					$financial_institution_name = '';
					$transit_number = '';
					$financial_institution_number = '';
					$account_number =	'';

					$result = mysqli_query($dbc, "SELECT * FROM orientation_direct_deposit_info WHERE contactid='$contactid'");
					$num_rows = mysqli_num_rows($result);
					$direct_deposit_info_form = mysqli_fetch_assoc($result);
					if($num_rows > 0) {
						$financial_institution_name = $direct_deposit_info_form['financial_institution_name'];
						$transit_number = $direct_deposit_info_form['transit_number'];
						$financial_institution_number = $direct_deposit_info_form['financial_institution_number'];
						$account_number =	$direct_deposit_info_form['account_number'];
					}
					?>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <p>Washtech provides direct depositing of your bi-weekly pay. The following mandatory information is required.
                            <p>Required:
                                <ol>
                                    <li>A void cheque</li>
                                    <h3 class="pad-bottom double">OR</h3>
                                    <li><em>Please fill out the form below</em></li>
                                </ol>
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Financial Institution Name:</label>
                        <div class="col-sm-8">
                          <input name="financial_institution_name" value="<?php echo $financial_institution_name; ?>" type="text" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Transit Number:</label>
                        <div class="col-sm-8">
                          <input name="transit_number" type="text" value="<?php echo $transit_number; ?>" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Financial Institution Number:</label>
                        <div class="col-sm-8">
                          <input name="financial_institution_number" value="<?php echo $financial_institution_number; ?>" type="text" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Account Number:</label>
                        <div class="col-sm-8">
                          <input name="account_number" type="text" value="<?php echo $account_number; ?>" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <p>I <strong><?php echo $result_staff['first_name']; ?> <?php echo $result_staff['last_name']; ?></strong> give Washtech permission to direct deposit my pay according to the information I have provided above. I understand that any incorrect information I have provided may result in a delay in receiving my pay from Washtech</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <label for="site_name"><input type="checkbox" name="accept_policy" required value=1>&nbsp; Information which I provided here is true and correct. I have read and understand the content of this policy.<em class="text-red">*</em></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Date:</label>
                        <div class="col-sm-8">
                          <input name="today_date" type="text" readonly value="<?php echo date('Y-m-d'); ?>"   class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <img src="img/employee_direct_deposit_check.png" width="400" height="244" border="0" alt="">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <p><span class="text-red pull-right"><em>Required Fields *</em></span></p>
                        </div>
                        <div class="col-sm-8"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
						<?php if(!empty($_GET['from'])) {
                            echo '<a href="eis.php" class="btn brand-btn btn-lg pull-right">Back</a>';
						} else {
							echo '<a href="orientation.php" class="btn brand-btn btn-lg pull-right">Back</a>';
							//echo '<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>';
						} ?>
                        </div>
                        <div class="col-sm-8">
                            <button type="submit" name="submit_direct_deposit_info" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                        </div>
                    </div>

                    <br/>
                </form>

            <?php } ?>

            <?php if($form_name == 'emp_substance_abuse_policy_confirm') { ?>
                <form id="form1" name="emp_substance_abuse_policy_confirm_form" method="post" action="orientation_forms.php" enctype="multipart/form-data" class="form-horizontal" role="form">

                    <h1 class="double-pad-bottom">Employee Substance Abuse Policy Confirmation</h1>
					<div class="pad-left double-gap-bottom"><a href="orientation.php" class="btn config-btn">Back to Dashboard</a></div>
                    <input type="hidden" name="contactid" value="<?php echo $contactid; ?>">

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <p>Washtech has a strict zero tolerance Drug and Alcohol Policy. Any Employee who is suspected to be under the influence of drugs or alcohol while on any Washtech work sites will be sent home immediately.</p>
                            <p>Washtech reserves the right under the Disciplinary Action Policy to go directly to Level 3 termination if deemed necessary. Anyone involved in a serious accident will have to submit to a drug test.</p>
                            <p>At no time shall illegal drugs or alcohol be on the property of Washtech, in any of the Washtech vehicles, or on the property of Washtech's clients.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <p>I <strong><?php echo $result_staff['first_name']; ?> <?php echo $result_staff['last_name']; ?></strong> give my consent for drug and alcohol testing for the following conditions:</p>
                            <ul>
                                <li>Post Accident</li>
                                <li>Reasonable Cause</li>
                                <li>Pre Access</li>
                                <li>Return to Duty</li>
                                <li>Follow Up</li>
                            </ul>
                            <p><em>See Section 2 for details.</em></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <label for="site_name"><input type="checkbox" name="accept_policy" required value=1>&nbsp; I agree to the terms & conditions get forth in Washtech project's substance abuse policy.<em class="text-red">*</em></label>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-4">
                            <p><span class="text-red pull-right"><em>Required Fields *</em></span></p>
                        </div>
                        <div class="col-sm-8"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
                            <a href="orientation.php" class="btn brand-btn btn-lg pull-right">Back</a>
							<!--<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
                        </div>
                        <div class="col-sm-8">
                            <button type="submit" name="submit_emp_substance_abuse_policy_confirm" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                        </div>
                    </div>

                    <br/>
                </form>

            <?php } ?>

            <?php if($form_name == 'emp_right_to_refuse_unsafe_work_form') { ?>
                <form id="form1" name="emp_right_to_refuse_unsafe_work_form" method="post" action="orientation_forms.php" enctype="multipart/form-data" class="form-horizontal" role="form">

                    <h1 class="double-pad-bottom">Employee Right To Refuse Unsafe Work</h1>
					<div class="pad-left double-gap-bottom"><a href="orientation.php" class="btn config-btn">Back to Dashboard</a></div>
                    <input type="hidden" name="contactid" value="<?php echo $contactid; ?>">

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <p>Washtech will always follow rules set out by Occupational Health & Safety. In a case where your safety or the safety or other workers is in question, "You have the right to refuse unsafe work."</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <label for="site_name"><input type="checkbox" name="accept_policy" required value=1>&nbsp; Information which I provided here is true and correct. I have read and understand the content of this policy.<em class="text-red">*</em></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <p><span class="text-red pull-right"><em>Required Fields *</em></span></p>
                        </div>
                        <div class="col-sm-8"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
                            <a href="orientation.php" class="btn brand-btn btn-lg pull-right">Back</a>
							<!--<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
                        </div>
                        <div class="col-sm-8">
                            <button type="submit" name="submit_emp_right_to_refuse_unsafe_work_form" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                        </div>
                    </div>

                    <br/>
                </form>


            <?php } ?>

            <?php if($form_name == 'emp_shop_yard_office_ori') { ?>
                <form id="form1" name="emp_shop_yard_office_ori_form" method="post" action="orientation_forms.php" enctype="multipart/form-data" class="form-horizontal" role="form">

                    <h1 class="double-pad-bottom">Employee Shop, Yard & Office Orientation</h1>
					<div class="pad-left double-gap-bottom"><a href="orientation.php" class="btn config-btn">Back to Dashboard</a></div>
                    <input type="hidden" name="contactid" value="<?php echo $contactid; ?>">

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <p>I have received a map of Washtech's shop and yard which shows buildings, exits, fire extinguishers, first aid kits, and muster point. I have reviewed and I am familiar with the emergency response plan for the shop, yard & office.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <label for="site_name"><input type="checkbox" name="accept_policy" required value=1>&nbsp; Information which I provided here is true and correct. I have read and understand the content of this policy.<em class="text-red">*</em></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <p><span class="text-red pull-right"><em>Required Fields *</em></span></p>
                        </div>
                        <div class="col-sm-8"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
                            <a href="orientation.php" class="btn brand-btn btn-lg pull-right">Back</a>
							<!--<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
                        </div>
                        <div class="col-sm-8">
                            <button type="submit" name="submit_emp_shop_yard_office_ori" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                        </div>
                    </div>

                    <br/>
                </form>
            <?php } ?>

            <?php if($form_name == 'benefits_app') { ?>
                <form id="form1" name="benefits_app_form" method="post" action="orientation_forms.php" enctype="multipart/form-data" class="form-horizontal" role="form">

                    <h1 class="double-pad-bottom">Benefits Application</h1>
					<div class="pad-left double-gap-bottom"><a href="orientation.php" class="btn config-btn">Back to Dashboard</a></div>
                    <input type="hidden" name="contactid" value="<?php echo $contactid; ?>">

					<?php
					$benefit_app_submit = '';
					$benefit_app_accepted = '';

					$result = mysqli_query($dbc, "SELECT * FROM orientation_benefits_app WHERE contactid='$contactid'");
					$num_rows = mysqli_num_rows($result);
					$direct_deposit_info_form = mysqli_fetch_assoc($result);
					if($num_rows > 0) {
						$benefit_app_submit = $direct_deposit_info_form['benefit_app_submit'];
						$benefit_app_accepted = $direct_deposit_info_form['benefit_app_accepted'];
					}
					?>

                    <div class="form-group">
                        <div class="col-sm-4">

                        </div>
                        <div class="col-sm-8">
                            Upon completion of the 90 day probationary period at Washtech, employees are eligible to join the Group Benefits offered by Washtech through Greenshield Canada.
                        </div>
                    </div>

                    <div class="form-group clearfix orientation_date">
                        <label for="first_name" class="col-sm-4 control-label text-right">Application Submitted:</label>
                        <div class="col-sm-8">
                            <input name="benefit_app_submit" value="<?php echo $benefit_app_submit;?>" type="text" class="datepicker"></p>
                        </div>
                    </div>

                    <div class="form-group clearfix orientation_date">
                        <label for="first_name" class="col-sm-4 control-label text-right">Application Accepted:</label>
                        <div class="col-sm-8">
                            <input name="benefit_app_accepted" value="<?php echo $benefit_app_accepted;?>" type="text" class="datepicker"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <label for="site_name"><input type="checkbox" name="accept_policy" required value=1>&nbsp; I agree to the terms & conditions.</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <p><span class="text-red pull-right"><em>Required Fields *</em></span></p>
                        </div>
                        <div class="col-sm-8"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
                            <a href="orientation.php" class="btn brand-btn btn-lg pull-right">Back</a>
							<!--<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
                        </div>
                        <div class="col-sm-8">
                            <button type="submit" name="submit_benefits_app" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                        </div>
                    </div>

                    <br/>
                </form>

            <?php } ?>

            <?php if($form_name == 'copy_of_driver_lic_safety_tickets') { ?>
                <form id="form1" name="benefits_app_form" method="post" action="orientation_forms.php" enctype="multipart/form-data" class="form-horizontal" role="form">

                    <h1 class="double-pad-bottom">Copy Of Driver's Licence & Safety Tickets</h1>
					<div class="pad-left double-gap-bottom"><a href="orientation.php" class="btn config-btn">Back to Dashboard</a></div>
                    <input type="hidden" name="contactid" value="<?php echo $contactid; ?>">
					<?php
					$result = mysqli_query($dbc, "SELECT * FROM orientation_copy_of_driver_lic_safety_tickets WHERE contactid='$contactid'");

					$num_rows = mysqli_num_rows($result);
					if($num_rows > 0) {
						echo "<table class='table table-bordered'>";
						echo "<tr>
						<th>Type</th>
						<th>Description</th>
						<th>Issue Date</th>
						<th>Expiry Date</th>
						<th>Document</th>
						";
						echo "</tr>";
					}
					while($row = mysqli_fetch_array( $result ))
					{
						echo '<td>' . $row['type'] . '</td>';
						echo '<td>' . html_entity_decode($row['description']) . '</td>';
						echo '<td>' . $row['issue_date'] . '</td>';
						echo '<td>' . $row['expiry_date'] . '</td>';
                        if($row['doc_name'] != '') {
						    echo '<td><a href="download/orientation/'.$row['doc_name'].'" target="_blank">View</a></td>';
                        } else {
                            echo '<td>-</td>';
                        }
						echo "</tr>";
					}

					echo '</table>';

					?>
				  <div class="form-group">
					<label for="additional_note" class="col-sm-4 control-label">Type:</label>
					<div class="col-sm-8 half-pad-top">
					  <select data-placeholder="Choose a Type..." name="type" class="chosen-select-deselect form-control" width="380">
						<option value=""></option>
						<option value="Driver Licence">Driver Licence</option>
						<option value="Safety Ticket">Safety Ticket</option>
					  </select>
					</div>
				  </div>

                    <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Description:</label>
                        <div class="col-sm-8">
                            <textarea name="description" rows="5" cols="50" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group clearfix orientation_date">
                        <label for="first_name" class="col-sm-4 control-label text-right">Issue Date:</label>
                        <div class="col-sm-8">
                            <input name="issue_date" type="text" class="datepicker"></p>
                        </div>
                    </div>

                    <div class="form-group clearfix orientation_date">
                        <label for="first_name" class="col-sm-4 control-label text-right">Expiry Date:</label>
                        <div class="col-sm-8">
                            <input name="expiry_date" type="text" class="datepicker"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="file[]" class="col-sm-4 control-label">Upload Document:
                        <span class="popover-examples list-inline">&nbsp;
                        <a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="img/info.png" width="20"></a>
                        </span>
                        </label>
                        <div class="col-sm-8">
                            <input name="file" type="file" id="file" data-filename-placement="inside" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <label for="site_name"><input type="checkbox" name="accept_policy" required value=1>&nbsp; I agree to the terms & conditions.</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <p><span class="text-red pull-right"><em>Required Fields *</em></span></p>
                        </div>
                        <div class="col-sm-8"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
						<?php if(!empty($_GET['from'])) {
                            echo '<a href="eis.php" class="btn brand-btn btn-lg pull-right">Back</a>';
						} else {
							echo '<a href="orientation.php" class="btn brand-btn btn-lg pull-right">Back</a>';
							//echo '<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>';
						} ?>
                        </div>
                        <div class="col-sm-8">
                            <button type="submit" name="submit_copy_of_driver_lic_safety_tickets" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                        </div>
                    </div>

                    <br/>
                </form>
            <?php } ?>

            <?php if($form_name == 'emp_trained_in_ppe_req') { ?>
                <form id="form1" name="emp_trained_in_ppe_req_form" method="post" action="orientation_forms.php" enctype="multipart/form-data" class="form-horizontal" role="form">

                    <h1 class="double-pad-bottom">Washtech PPE Requirements</h1>
					<div class="pad-left double-gap-bottom"><a href="orientation.php" class="btn config-btn">Back to Dashboard</a></div>
                    <input type="hidden" name="contactid" value="<?php echo $contactid; ?>">

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <p>All workers on work sites wear:
                            <ul>
                                <li>CSA Grade 1 safety footwear,</li>
                                <li>CSA Class G or E hard hats,</li>
                                <li>CSA-approved safety glasses Note: Our Company provides hard hats and non-prescription safety glasses.</li>
                            </ul>
                            </p>
                            <p>Other PPE (harnesses, respirators, hearing protection, etc.) is available and is used when needed.</p>
                            <p>Workers are trained in the use and care of the PPE they are using. </p>
                            <p>PPE is inspected regularly for defects/damage and any defective equipment is removed from service.</p>
                            <p>PPE requirements are communicated to all new hires and to all subcontractors/visitors on site.</p>
                            <p>Workers use the PPE required for the task(s) they are performing including the use of elevated work platforms.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <label for="site_name"><input type="checkbox" name="accept_policy" required value=1>&nbsp; Information which I have provided here is true and correct.  I have read and understand the content of this policy.</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            Note that employees will be required to know and conform to different PPE and Safety requirements when working on a job site that is managed and supervised by a General Contractor.  The General Contractor's PPE and Safety requirements will supersede Washtech's requirements.
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <p><span class="text-red pull-right"><em>Required Fields *</em></span></p>
                        </div>
                        <div class="col-sm-8"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
                            <a href="orientation.php" class="btn brand-btn btn-lg pull-right">Back</a>
							<!--<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
                        </div>
                        <div class="col-sm-8">
                            <button type="submit" name="submit_emp_trained_in_ppe_req" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                        </div>
                    </div>

                    <br/>
                </form>

            <?php } ?>

            <?php if($form_name == 'verbal_training_in_eme_res_plan') { ?>
                <form id="form1" name="verbal_training_in_eme_res_plan_form" method="post" action="orientation_forms.php" enctype="multipart/form-data" class="form-horizontal" role="form">

                    <h1 class="double-pad-bottom">Verbal Training In Emergency Response Plan</h1>
					<div class="pad-left double-gap-bottom"><a href="orientation.php" class="btn config-btn">Back to Dashboard</a></div>
                    <input type="hidden" name="contactid" value="<?php echo $contactid; ?>">

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            For Shop/Office only (various sites will have different ERP)
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="file[]" class="col-sm-4 control-label">Emergency Response Plan:</label>
                        <div class="col-sm-8">
                            <a href="download/Washtech_ERP.pdf" target="_blank">Click to View Plan</a>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="file[]" class="col-sm-4 control-label">Building Layout:</label>
                        <div class="col-sm-8">
                            <a href="download/Building Layout - Peigan Crossing.pdf" target="_blank">Click to View Layout</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="file[]" class="col-sm-4 control-label">Aerial Photo:</label>
                        <div class="col-sm-8">
                            <a href="download/Aerial_Map.jpg" target="_blank">Click to View Photo</a>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <label for="site_name"><input type="checkbox" name="accept_policy" required value=1>&nbsp; I agree to the terms & conditions.</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <p><span class="text-red pull-right"><em>Required Fields *</em></span></p>
                        </div>
                        <div class="col-sm-8"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
                            <a href="orientation.php" class="btn brand-btn btn-lg pull-right">Back</a>
							<!--<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
                        </div>
                        <div class="col-sm-8">
                            <button type="submit" name="submit_verbal_training_in_eme_res_plan" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                        </div>
                    </div>

                    <br/>
                </form>

            <?php } ?>

	</div>
</div>

<?php include ('../footer.php'); ?>