<?php
/*
NEW PATIENT HISTORY FORM
*/
include ('../include.php');
checkAuthorised('crm');
error_reporting(0);

if (isset($_POST['submit'])) {
    $type = $_POST['type'];
    $referral_date = $_POST['referral_date'];
    $referrer_name = filter_var($_POST['referrer_name'],FILTER_SANITIZE_STRING);
    $referral_name = filter_var($_POST['referral_name'],FILTER_SANITIZE_STRING);
    $referral_email = filter_var($_POST['referral_email'],FILTER_SANITIZE_STRING);

    $query_insert_injury = "INSERT INTO `crm_referrals` (`type`, `referral_date`, `referrer_name`, `referral_name`, `referral_email`) VALUES ('$type', '$referral_date', '$referrer_name', '$referral_name', '$referral_email')";
     $result_insert_injury = mysqli_query($dbc, $query_insert_injury);

    echo '<script type="text/javascript"> window.location.replace("referral.php?"); </script>';

    mysqli_close($dbc); //Close the DB Connection
}

?>
<script type="text/javascript">
$(document).ready(function() {

    $("#form1").submit(function( event ) {
        var therapistid = $("#therapistid").val();
        //var therapistsid = $("#therapistsid").val();
        if (therapistid == '') {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
    });

});

</script>
</head>

<body>
<?php include_once ('../navigation.php');
?>
<div class="container">
  <div class="row">

        <h1 class="triple-pad-bottom">Referral</h1>

		<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

            <div class="form-group">
              <label for="ship_zip" class="col-sm-4 control-label">Referrer:</label>
              <div class="col-sm-8">
                <input name="referrer_name" type="text" class="form-control" />
              </div>
            </div>

            <div class="form-group">
              <label for="ship_zip" class="col-sm-4 control-label">Referrer Email:</label>
              <div class="col-sm-8">
                <input name="referral_email" type="text" class="form-control" />
              </div>
            </div>

            <div class="form-group">
                <label for="phone_number" class="col-sm-4 control-label">Type:</label>
                <div class="col-sm-8">
                    <select data-placeholder="Select How Referred..."  name="type" id="equipmentid" class="chosen-select-deselect form-control" width="380">
                        <option value=""></option>
                        <option value="Referral">Referral</option>
                        <option value="Doctor">Doctor</option>
                        <option value="Friend">Friend</option>
                        <option value="Patient">Patient</option>
                        <option value="Insurance">Insurance</option>
                        <option value="Staff">Staff</option>
                        <option value="Business Lead">Business Lead</option>
                        <option value="Cold Call">Cold Call</option>
                        <option value="Tradeshow">Tradeshow</option>
                        <option value="Website">Website</option>
                        <option value="Social Media">Social Media</option>
                        <option value="Print Media">Print Media</option>
                        <option value="Radio">Radio</option>
                        <option value="Online">Online</option>
                        <option value="Mail Out">Mail Out</option>
                        <option value="NP - Non-specific">NP - Non-specific</option>
                        <option value="NP - Specific">NP - Specific</option>
                        <option value="RP - Non-Specific">RP - Non-Specific</option>
                        <option value="RP - Specific">RP - Specific</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
              <label for="ship_zip" class="col-sm-4 control-label">Referral:</label>
              <div class="col-sm-8">
                <input name="referral_name" type="text" class="form-control" />
              </div>
            </div>

          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Referral Date:</label>
            <div class="col-sm-8">
              <input name="referral_date" type="text" class="datepicker">
            </div>
          </div>

         <div class="form-group">
            <div class="col-sm-4 clearfix">
                <a href="referral.php" class="btn brand-btn pull-right">Back</a>
            </div>
            <div class="col-sm-8">
                <button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
            </div>
        </div>

        

        </form>

    </div>
  </div>
<?php include ('../footer.php'); ?>