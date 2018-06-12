<?php
/*
NEW PATIENT HISTORY FORM
*/
include ('../include.php');
checkAuthorised('treatment_charts');
error_reporting(0);

if (isset($_POST['submit'])) {
    $injuryid = $_POST['injuryid'];
    $discharge_date = date('Y-m-d');
    $discharge_comment = htmlentities($_POST['discharge_comment']);
    $discharge_comment = filter_var($discharge_comment,FILTER_SANITIZE_STRING);
    $discharge_stat = $_POST['discharge_stat'];

    $query_update_inventory = "UPDATE `patient_injury` SET `discharge_comment` = '$discharge_comment', `discharge_date` = '$discharge_date', `discharge_stat` = '$discharge_stat' WHERE `injuryid` = '$injuryid'";
    $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);

    $query_update_inventory = "UPDATE `assessment` SET `deleted` = 1 WHERE `injuryid` = '$injuryid'";
    $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
    $query_update_inventory = "UPDATE `treatment` SET `deleted` = 1 WHERE `injuryid` = '$injuryid'";
    $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
    $query_update_inventory = "UPDATE `treatment_exercise_plan` SET `deleted` = 1 WHERE `injuryid` = '$injuryid'";
    $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
    $query_update_inventory = "UPDATE `treatment_plan` SET `deleted` = 1 WHERE `injuryid` = '$injuryid'";
    $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);


    if($_POST['send_email'] == 1) {
        $patientid = get_all_from_injury($dbc, $injuryid, 'contactid');
        $email = get_email($dbc, $patientid);
        if($email != '') {
            $patient_name = get_contact($dbc, $patientid);

            $promo = html_entity_decode(get_config($dbc, 'discharge_patient_email'));
            $email_body = str_replace("[Patient Name]", $patient_name, $promo);
            $subject = 'Recent Recovery at Clinic';

            send_email('', $email, '', '', $subject, $email_body, '');
        }
    }

    echo '<script type="text/javascript"> window.top.close(); window.opener.location.reload(); </script>';

    mysqli_close($dbc); //Close the DB Connection
}

?>
<script type="text/javascript">
</script>
</head>

<body>
<?php //include_once ('../navigation.php');
?>
<div class="container">
  <div class="row">

		<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

            <?php
            $injuryid = $_GET['injuryid'];
			$get_injury =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT discharge_comment FROM	patient_injury WHERE	injuryid='$injuryid'"));
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
                <input type="checkbox" value="1" style="height: 20px; width: 20px;" name="send_email">
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
            </div>
            <div class="col-sm-8">
                <button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
            </div>
          </div>

        </form>

    </div>
  </div>
<?php include ('../footer.php'); ?>