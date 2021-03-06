<?php
/*
NEW PATIENT HISTORY FORM
*/
include ('../include.php');
checkAuthorised('injury');
error_reporting(0);

if (isset($_POST['submit'])) {
    $injuryid = $_POST['injuryid'];
    $discharge_date = date('Y-m-d');
    $discharge_comment = htmlentities($_POST['discharge_comment']);
    $discharge_comment = filter_var($discharge_comment,FILTER_SANITIZE_STRING);
    $discharge_stat = $_POST['discharge_stat'];

    $query_update_inventory = "UPDATE `patient_injury` SET `discharge_comment` = '$discharge_comment', `discharge_date` = '$discharge_date', `discharge_stat` = '$discharge_stat' WHERE `injuryid` = '$injuryid'";
    $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
        $date_of_archival = date('Y-m-d');

    $query_update_inventory = "UPDATE `assessment` SET `deleted` = 1, `date_of_archival` = '$date_of_archival' WHERE `injuryid` = '$injuryid'";
    $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
    $query_update_inventory = "UPDATE `treatment` SET `deleted` = 1, `date_of_archival` = '$date_of_archival' WHERE `injuryid` = '$injuryid'";
    $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
    $query_update_inventory = "UPDATE `treatment_exercise_plan` SET `deleted` = 1, `date_of_archival` = '$date_of_archival' WHERE `injuryid` = '$injuryid'";
    $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
    $query_update_inventory = "UPDATE `treatment_plan` SET `deleted` = 1, `date_of_archival` = '$date_of_archival' WHERE `injuryid` = '$injuryid'";
    $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);

    if($_POST['send_email'] == 1) {
        $patientid = get_all_from_injury($dbc, $injuryid, 'contactid');
        $email = get_email($dbc, $patientid);
        if($email != '') {
            $email_body = $_POST['email_body'];
            $subject = $_POST['email_subject'];

            send_email([$_POST['email_address']=>$_POST['email_name']], $email, '', '', $subject, $email_body, '');
        }
    }

    echo '<script type="text/javascript"> window.location.replace("injury.php?category=discharged"); </script>';

    mysqli_close($dbc); //Close the DB Connection
}

?>
<script type="text/javascript">
</script>
</head>

<body>
<?php include_once ('../navigation.php');
?>
<div class="container">
  <div class="row">

		<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

            <?php
            $injuryid = $_GET['injuryid'];
			$get_injury =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT discharge_comment, contactid FROM	patient_injury WHERE	injuryid='$injuryid'"));
            ?>
            <input type="hidden" name="injuryid" value="<?php echo $injuryid ?>" />

          <div class="form-group">
            <label for="fax_number"	class="col-sm-4	control-label">Discharge Comment:</label>
            <div class="col-sm-8">
                <a name="exactline">
                <textarea name="discharge_comment" rows="5" cols="50" class="form-control"><?php echo $get_injury['discharge_comment']; ?></textarea>
                </a>
            </div>
          </div>

          <div class="form-group">
            <label for="fax_number"	class="col-sm-4	control-label">Send Email to Patient:</label>
            <div class="col-sm-8">
                <input type="checkbox" value="1" style="height: 20px; width: 20px;" name="send_email" onchange="$('.show_email').toggle();">
            </div>
          </div>

		  <div class="show_email" style="display:none;">
			  <div class="form-group">
				<label for="fax_number"	class="col-sm-4	control-label">Email Sender Name:</label>
				<div class="col-sm-8">
					<input type="text" value="<?= get_contact($dbc, $_SESSION['contactid']) ?>" class="form-control" name="email_name">
				</div>
			  </div>

			  <div class="form-group">
				<label for="fax_number"	class="col-sm-4	control-label">Email Sender Address:</label>
				<div class="col-sm-8">
					<input type="text" value="<?= get_email($dbc, $_SESSION['contactid']) ?>" class="form-control" name="email_address">
				</div>
			  </div>

			  <div class="form-group">
				<label for="fax_number"	class="col-sm-4	control-label">Email Subject:</label>
				<div class="col-sm-8">
					<input type="text" value="Recent Recovery at Clinic" class="form-control" name="email_subject">
				</div>
			  </div>

			  <div class="form-group">
				<label for="fax_number"	class="col-sm-4	control-label">Email Body:</label>
				<div class="col-sm-8">
					<textarea class="form-control" name="email_body"><?= str_replace("[Patient Name]", get_contact($dbc, $get_injury['contactid']), html_entity_decode(get_config($dbc, 'discharge_patient_email'))) ?></textarea>
				</div>
			  </div>
		  </div>

          <div class="form-group">
            <label for="fax_number"	class="col-sm-4	control-label">Add to Discharge Stat:</label>
            <div class="col-sm-8">
                <input type="checkbox" value="1" style="height: 20px; width: 20px;" name="discharge_stat">
            </div>
          </div>

         <div class="form-group">
            <div class="col-sm-4 clearfix">
                <a href="injury.php?category=active" class="btn brand-btn pull-right">Back</a>
            </div>
            <div class="col-sm-8">
                <button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
            </div>
          </div>

        </form>

    </div>
  </div>
<?php include ('../footer.php'); ?>