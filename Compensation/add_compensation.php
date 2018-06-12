<?php
/*
Add Vendor
*/
include ('../include.php');
checkAuthorised('goals_compensation');
error_reporting(0);

if (isset($_POST['add_pay'])) {
    $contactid = $_POST['contactid'];

    $pp = implode('*#*',$_POST['performance_pay']);
    $performance_pay_perc = filter_var($pp,FILTER_SANITIZE_STRING);

    $sbp = implode('*#*',$_POST['base_pay']);
    $base_pay = filter_var($sbp,FILTER_SANITIZE_STRING);

    $today_date = date('Y-m-d');
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if(empty($_POST['compensationid'])) {
        $query_insert_vendor = "INSERT INTO `compensation` (`contactid`, `base_pay`, `performance_pay_dollor`, `performance_pay_perc`, `today_date`, `start_date`, `end_date`) VALUES ('$contactid', '$base_pay', '$performance_pay_dollor', '$performance_pay_perc', '$today_date', '$start_date', '$end_date')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $url = 'Added';
    } else {
        $compensationid = $_POST['compensationid'];
        $query_update_vendor = "UPDATE `compensation` SET `contactid` = '$contactid', `base_pay` = '$base_pay', `performance_pay_dollor` = '$performance_pay_dollor', `performance_pay_perc` = '$performance_pay_perc',`today_date` = '$today_date', `start_date` = '$start_date', `end_date` = '$end_date' WHERE `compensationid` = '$compensationid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    /*$result_delete_client = mysqli_query($dbc, "DELETE FROM `compensation_pay_therapist` WHERE `therapistid` = '$contactid'");

    for($i = 0; $i < count($_POST['performance_pay_dollor']); $i++) {
        $serviceid = $_POST['serviceid'][$i];
        $performance_pay_dollor = $_POST['performance_pay_dollor'][$i];
        if($performance_pay_dollor != '') {
            $query_insert_vendor = "INSERT INTO `compensation_pay_therapist` (`therapistid`, `serviceid`, `pay_dollor`) VALUES ('$contactid', '$serviceid', '$performance_pay_dollor')";
            $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        }
    }

    for($i = 0; $i < count($_POST['performance_pay_perc']); $i++) {
        $serviceid = $_POST['serviceid'][$i];
        $performance_pay_perc = $_POST['performance_pay_perc'][$i];
        if($performance_pay_perc != '') {
            $query_insert_vendor = "INSERT INTO `compensation_pay_therapist` (`therapistid`, `serviceid`, `pay_perc`) VALUES ('$contactid', '$serviceid', '$performance_pay_perc')";
            $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        }
    }
    */

    echo '<script type="text/javascript"> window.location.replace("compensation.php"); </script>';

 //   mysqli_close($dbc);//Close the DB Connection
}

?>
<script type="text/javascript">
$(document).ready(function() {
    $("#contactid").change(function() {
        window.location = 'add_compensation.php?compensationid=<?= $_GET['compensationid'] ?>&tid='+this.value;
    });

    $("#all_services").change(function() {
        var all_services = this.value;
        if (all_services.indexOf("%") >= 0) {
            var final_service = all_services.replace('%', '');
            $(".all_perc").val(final_service);
            $(".all_dollor").val('');
        }
        if (all_services.indexOf("$") >= 0) {
            var final_service = all_services.replace('$', '');
            $(".all_dollor").val(final_service);
            $(".all_perc").val('');
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

    <h1 class="triple-pad-bottom">Compensation</h1>

    <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
        <?php
        $contactid = '';
        $base_pay = '';
        $performance_pay_dollor = '';
        $performance_pay_perc = '';
        $start_date = '';
        $end_date = '';

        if(!empty($_GET['compensationid'])) {
            $compensationid = $_GET['compensationid'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM compensation WHERE compensationid='$compensationid'"));

            $contactid = $get_contact['contactid'];
            $start_date = $get_contact['start_date'];
            $end_date = $get_contact['end_date'];

            $base_pay = explode('*#*',$get_contact['base_pay']);
            $performance_pay_dollor = explode('*#*',$get_contact['performance_pay_dollor']);
            $performance_pay_perc = explode('*#*',$get_contact['performance_pay_perc']); ?>
			<input type="hidden" id="compensationid" name="compensationid" value="<?php echo $compensationid ?>" />
        <?php }
		if(!empty($_GET['tid'])) {
            $contactid = $_GET['tid'];
        } ?>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Staff:</label>
            <div class="col-sm-8">
				<select data-placeholder="Select Staff..." name="contactid" id="contactid" class="chosen-select-deselect form-control" width="380">
					<option></option>
					<?php $staff_groups = explode(',',get_config($dbc, 'comp_staff_groups'));
					$query = sort_contacts_query(mysqli_query($dbc,"SELECT contactid, first_name, last_name, category_contact FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `status`>0"));
					foreach($query as $staff) {
						if(in_array('ALL',$staff_groups) || in_array($staff['category_contact'],$staff_groups)) {
							echo "<option " . ($staff['contactid'] == $contactid ? 'selected' : '') . " value='". $staff['contactid']."'>".$staff['first_name'].' '.$staff['last_name'].'</option>';
						}
					} ?>
				</select>
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Effective Start Date:</label>
            <div class="col-sm-8">
				<input class='datepicker' type='text' name='start_date' value='<?php echo $start_date; ?>'>
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Effective End Date:</label>
            <div class="col-sm-8">
				<input class='datepicker' type='text' name='end_date' value='<?php echo $end_date; ?>'>
            </div>
          </div>

        <h3>Performance Pay (%)</h3>
        <br>Don't add $ or % sign, just add the value. [e.g. - 50]
        <?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT value FROM general_configuration WHERE name='staff_performance_pay'"));
        $value_config_base = $get_field_config['value'];

        $staff_performance_pay = explode('*#*',$value_config_base);
        $total_count = mb_substr_count($value_config_base,'*#*');

        $goal = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM goal WHERE therapistid='$contactid'"));

        for($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
            if($staff_performance_pay[$eq_loop] != '') {
                $target = '';
                if($staff_performance_pay[$eq_loop] == 'Arrival Rate %') {
                    $target = $goal['arrival_rate'];
                }
                if($staff_performance_pay[$eq_loop] == 'Average Visits to Discharge') {
                    $target = $goal['average_visit_discharge'];
                }
                if($staff_performance_pay[$eq_loop] == '% of available hours scheduled') {
                    $target = $goal['hours_scheduled'];
                }
                if($staff_performance_pay[$eq_loop] == '# of New Clients') {
                    $target = $goal['new_client'];
                }
                if($staff_performance_pay[$eq_loop] == '# of Assessments') {
                    $target = $goal['assessment'];
                }
                if($staff_performance_pay[$eq_loop] == 'Block Booking') {
                    $target = $goal['block_booking'];
                }
                if($staff_performance_pay[$eq_loop] == 'Testimonials submitted') {
                    $target = $goal['testimonials_submitted'];
                }
                if($staff_performance_pay[$eq_loop] == 'Manual Therapy Intermediate certification') {
                    $target = $goal['manual_intermediate'];
                }
                if($staff_performance_pay[$eq_loop] == 'Manual Therapy Advanced Diploma certification') {
                    $target = $goal['manual_advanced'];
                }

                if($performance_pay_perc[$eq_loop] == '') {
                    $performance_pay_perc[$eq_loop] = 0;
                }
                ?>
          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label"><?php echo $staff_performance_pay[$eq_loop];?>:<br><em style="color: green;">[Goal : <?php echo $target; ?>]</em></label>
            <div class="col-sm-8">
              <input name="performance_pay[]" type="text" value="<?php echo $performance_pay_perc[$eq_loop]; ?>" class="form-control" />
            </div>
          </div>
                <?php
            }
        }
        ?>

        <h3>Base Pay (%)</h3>
        <br>Don't add % sign, just add the value. [e.g. - 5,10]
        <?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT value FROM general_configuration WHERE name='staff_base_pay'"));
        $value_config_base = $get_field_config['value'];

        $staff_base_pay = explode(',',$value_config_base);
        $total_count = mb_substr_count($value_config_base,',');

        $goal = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM goal WHERE therapistid='$contactid'"));

        for($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
            if($staff_base_pay[$eq_loop] != '') {
                $target = '';
                if($base_pay[$eq_loop] == '') {
                    $base_pay[$eq_loop] = 0;
                }
            ?>
          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label"><?php echo $staff_base_pay[$eq_loop];?>(%)<br><em>[Ex: 5,10]</em></label>
            <div class="col-sm-8">
              <input name="base_pay[]" id="all_services" type="text" value="<?php echo $base_pay[$eq_loop]; ?>" class="form-control" />
            </div>
          </div>

        <?php
            }
        }
        ?>

        <div class="form-group">
            <div class="col-sm-4 clearfix">
                <a href="compensation.php" class="btn brand-btn pull-right">Back</a>
            </div>
          <div class="col-sm-8">
            <button type="submit" name="add_pay" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
		  </div>
        </div>

        

    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>