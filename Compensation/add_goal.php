<?php
/*
Add Vendor
*/
include ('../include.php');
checkAuthorised('goals_compensation');
error_reporting(0);

if (isset($_POST['add_pay'])) {
    $therapistid = $_POST['therapistid'];
    $arrival_rate = filter_var($_POST['arrival_rate'],FILTER_SANITIZE_STRING);
    $average_visit_discharge = filter_var($_POST['average_visit_discharge'],FILTER_SANITIZE_STRING);
    $hours_scheduled = filter_var($_POST['hours_scheduled'],FILTER_SANITIZE_STRING);
    $new_client = filter_var($_POST['new_client'],FILTER_SANITIZE_STRING);
    $assessment = filter_var($_POST['assessment'],FILTER_SANITIZE_STRING);
    $block_booking = filter_var($_POST['block_booking'],FILTER_SANITIZE_STRING);
    $testimonials_submitted = filter_var($_POST['testimonials_submitted'],FILTER_SANITIZE_STRING);
    $manual_intermediate = filter_var($_POST['manual_intermediate'],FILTER_SANITIZE_STRING);
    $manual_advanced = filter_var($_POST['manual_advanced'],FILTER_SANITIZE_STRING);
    $vacation_pay = $_POST['vacation_pay'];

    $today_date = date('Y-m-d');

    if(empty($_POST['goalid'])) {
        $query_insert_vendor = "INSERT INTO `goal` (`therapistid`, `arrival_rate`, `average_visit_discharge`, `hours_scheduled`, `new_client`, `assessment`, `block_booking`, `testimonials_submitted`, `manual_intermediate`, `manual_advanced`, `vacation_pay`, `today_date`) VALUES ('$therapistid', '$arrival_rate', '$average_visit_discharge', '$hours_scheduled', '$new_client', '$assessment', '$block_booking', '$testimonials_submitted', '$manual_intermediate', '$manual_advanced', '$vacation_pay', '$today_date')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $url = 'Added';
    } else {
        $goalid = $_POST['goalid'];
        $query_update_vendor = "UPDATE `goal` SET `therapistid` = '$therapistid', `arrival_rate` = '$arrival_rate', `average_visit_discharge` = '$average_visit_discharge', `hours_scheduled` = '$hours_scheduled', `new_client` = '$new_client', `assessment` = '$assessment', `block_booking` = '$block_booking', `testimonials_submitted` = '$testimonials_submitted', `manual_intermediate` = '$manual_intermediate', `manual_advanced` = '$manual_advanced', `vacation_pay` = '$vacation_pay', `today_date` = '$today_date' WHERE `goalid` = '$goalid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    echo '<script type="text/javascript"> window.location.replace("goals.php"); </script>';

 //   mysqli_close($dbc);//Close the DB Connection
}

?>
<script type="text/javascript">
$(document).ready(function() {
} );

</script>
</head>

<body>
<?php include_once ('../navigation.php');
?>
<div class="container">
  <div class="row">

    <h1 class="triple-pad-bottom">Set Goal</h1>

    <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
        <?php
        $therapistid = '';
        $arrival_rate = '';
        $average_visit_discharge = '';
        $hours_scheduled = '';
        $new_client = '';
        $assessment = '';
        $block_booking = '';
        $testimonials_submitted = '';
        $manual_intermediate = '';
        $manual_advanced = '';
        $vacation_pay = '';

        if(!empty($_GET['goalid'])) {

            $goalid = $_GET['goalid'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM goal WHERE goalid='$goalid'"));

            $therapistid = $get_contact['therapistid'];
            $arrival_rate = $get_contact['arrival_rate'];
            $average_visit_discharge = $get_contact['average_visit_discharge'];
            $hours_scheduled = $get_contact['hours_scheduled'];
            $new_client = $get_contact['new_client'];
            $assessment = $get_contact['assessment'];
            $block_booking = $get_contact['block_booking'];
            $testimonials_submitted = $get_contact['testimonials_submitted'];
            $manual_intermediate = $get_contact['manual_intermediate'];
            $manual_advanced = $get_contact['manual_advanced'];
            $vacation_pay = $get_contact['vacation_pay'];

        ?>
        <input type="hidden" id="goalid" name="goalid" value="<?php echo $goalid ?>" />
        <?php   }      ?>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Staff:</label>
            <div class="col-sm-8">
				<select data-placeholder="Select Staff..." name="therapistid" class="chosen-select-deselect form-control" width="380">
					<option></option>
					<?php $staff_groups = explode(',',get_config($dbc, 'comp_staff_groups'));
					$query = sort_contacts_query(mysqli_query($dbc,"SELECT contactid, first_name, last_name, category_contact FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `status`>0"));
					foreach($query as $staff) {
						if(in_array('ALL',$staff_groups) || in_array($staff['category_contact'],$staff_groups)) {
							echo "<option " . ($staff['contactid'] == $therapistid ? 'selected' : '') . "value='". $staff['contactid']."'>".$staff['first_name'].' '.$staff['last_name'].'</option>';
						}
					} ?>
				</select>
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Arrival Rate %:</label>
            <div class="col-sm-8">
              <input name="arrival_rate" type="text" value="<?php echo $arrival_rate; ?>" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Average Visits to Discharge:</label>
            <div class="col-sm-8">
              <input name="average_visit_discharge" type="text" value="<?php echo $average_visit_discharge; ?>" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">% of Available Hours Scheduled:</label>
            <div class="col-sm-8">
              <input name="hours_scheduled" type="text" value="<?php echo $hours_scheduled; ?>" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label"># of New Clients:</label>
            <div class="col-sm-8">
              <input name="new_client" type="text" value="<?php echo $new_client; ?>" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label"># of Assessments:</label>
            <div class="col-sm-8">
              <input name="assessment" type="text" value="<?php echo $assessment; ?>" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Block Booking:</label>
            <div class="col-sm-8">
              <input name="block_booking" type="text" value="<?php echo $block_booking; ?>" class="form-control" />
            </div>
          </div>

        <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Testimonials Submitted:</label>
            <div class="col-sm-8">
              <input name="testimonials_submitted" type="text" value="<?php echo $testimonials_submitted; ?>" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Manual Therapy Intermediate Certification:</label>
            <div class="col-sm-8">
                <input type="checkbox" <?php if ($manual_intermediate == '1') { echo " checked"; } ?> value="1" style="height: 20px; width: 20px;" name="manual_intermediate">
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Manual Therapy Advanced Diploma Certification:</label>
            <div class="col-sm-8">
                <input type="checkbox" <?php if ($manual_advanced == '1') { echo " checked"; } ?> value="1" style="height: 20px; width: 20px;" name="manual_advanced">
            </div>
          </div>

        <div class="form-group clearfix completion_date">
            <label for="first_name" class="col-sm-4 control-label text-right">Vacation Pay:</label>
            <div class="col-sm-8">
                <select name="vacation_pay" data-placeholder="Choose an Option..." class="chosen-select-deselect form-control" width="380">
                    <option value=''></option>
                    <option <?php if ($vacation_pay == '4') { echo  'selected="selected"'; } ?> value='4'>2 Weeks (4%)</option>
                    <option <?php if ($vacation_pay == '6') { echo  'selected="selected"'; } ?> value='6'>3 Weeks (6%)</option>
                    <option <?php if ($vacation_pay == '8') { echo  'selected="selected"'; } ?> value='8'>4 Weeks (8%)</option>
                </select>
            </div>
        </div>

          <div class="form-group">
            <div class="col-sm-4 clearfix">
                <a href="goals.php" class="btn brand-btn pull-right">Back</a>
            </div>
          <div class="col-sm-8">
            <button type="submit" name="add_pay" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
		  </div>
        </div>

        

    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>