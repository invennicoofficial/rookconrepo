<?php
/*
New PAtient Hidtory list
*/
include ('../include.php');
checkAuthorised('treatment_charts');
error_reporting(0);

if (isset($_POST['submit'])) {
    $injuryid = $_POST['injuryid'];
    $discharge_date = date('Y-m-d');
    $discharge_comment = htmlentities($_POST['discharge_comment']);
    $discharge_comment = filter_var($discharge_comment,FILTER_SANITIZE_STRING);

    $query_update_inventory = "UPDATE `patient_injury` SET `discharge_comment` = '$discharge_comment', `discharge_date` = '$discharge_date' WHERE `injuryid` = '$injuryid'";
    $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);

    $query_update_inventory = "UPDATE `assessment` SET `deleted` = 1 WHERE `injuryid` = '$injuryid'";
    $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
    $query_update_inventory = "UPDATE `treatment` SET `deleted` = 1 WHERE `injuryid` = '$injuryid'";
    $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
    $query_update_inventory = "UPDATE `treatment_exercise_plan` SET `deleted` = 1 WHERE `injuryid` = '$injuryid'";
    $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
    $query_update_inventory = "UPDATE `treatment_plan` SET `deleted` = 1 WHERE `injuryid` = '$injuryid'";
    $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);

    $patientid = get_all_from_injury($dbc, $injuryid, 'contactid');
    $email = get_email($dbc, $patientid);
    $patient_name = get_contact($dbc, $patientid);

    $promo = html_entity_decode(get_config($dbc, 'discharge_patient_email'));
    $email_body = str_replace("[Patient Name]", $patient_name, $promo);
    $subject = 'Recent Recovery at Clinic';

    //Mail
    send_email('', $email, '', '', $subject, $email_body, '');

    echo '<script type="text/javascript"> window.location.replace("discharge.php"); </script>';

    mysqli_close($dbc); //Close the DB Connection
}
?>
<script type="text/javascript">
$(document).ready(function() {
    $("#form1").submit(function( event ) {
        var patientid = $("#patientid").val();
        var injury = $("#injury").val();
        //var therapistsid = $("#therapistsid").val();
        if (patientid == '' || injury == '') {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
    });

});
$(document).on('change', 'select[name="patientid"]', function() { changePatient(this); });

function changePatient(sel) {
    var proValue = sel.value;
    var proId = sel.id;
    var arr = proId.split('_');

    $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=treatment&patientid="+proValue,
        dataType: "html",   //expect html to be returned
        success: function(response){
            var result = response.split('#*#');
            $("#patient_email").val(result[0]);
            $("#injuryid").html(result[1]);
			$("#injuryid").trigger("change.select2");
        }
    });
}
</script>
</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container triple-pad-bottom">
    <div class="row">

        <h1 class="double-pad-bottom">Discharge Patient
        </h1>

        <?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT treatment FROM config_treatment"));
        $value_config = ','.$get_field_config['treatment'].',';
        ?>

		<div class="mobile-100-container">
			<span class="nav-subtab">
				<?php //if (strpos($value_config, ','."Assessment".',') !== FALSE) { ?>
					<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="This is where the treatment forms are stored."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a href='patientform.php'><button type="button" class="btn brand-btn mobile-block mobile-100">Forms</button></a>
				<?php //} ?>
			</span>
			<span class="nav-subtab">
				<?php if (strpos($value_config, ','."Assessment".',') !== FALSE) { ?>
					<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="This is where the assessment information is stored."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a href='assessment.php'><button type="button" class="btn brand-btn mobile-block mobile-100">My Assessment</button></a>
				<?php } ?>
			</span>
			<span class="nav-subtab">
				<?php if (strpos($value_config, ','."Treatment".',') !== FALSE) { ?>
					<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="This is where the treatment information is stored."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a href='treatment.php'><button type="button" class="btn brand-btn mobile-block mobile-100">Treatment</button></a>
				<?php } ?>
			</span>
			<span class="nav-subtab">
				<?php if (strpos($value_config, ','."Exercise Plan".',') !== FALSE) { ?>
					<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="This is where the exercise plan information is stored."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a href='exercise_plan.php'><button type="button" class="btn brand-btn mobile-block mobile-100">Exercise Plan</button></a>
				<?php } ?>
			</span>
			<span class="nav-subtab">
				<?php if (strpos($value_config, ','."Treatment Plan".',') !== FALSE) { ?>
					<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="This is where the treatment plan information is stored."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a href='treatment_plan.php'><button type="button" class="btn brand-btn mobile-block mobile-100">Treatment Plan</button></a>
				<?php } ?>
			</span>
			<span class="nav-subtab">
				<?php if (strpos($value_config, ','."Discharge".',') !== FALSE) { ?>
					<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="This is where the discharge results are stored."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a href='discharge.php'><button type="button" class="btn brand-btn mobile-block mobile-100 active_tab">Discharge</button></a>
				<?php } ?>
			</span>
		</div>

		<br clear="all" />

        <div class="table-responsive">

		<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Patient<span class="empire-red">*</span>:</label>
            <div class="col-sm-8">
                <select id="patientid" data-placeholder="Choose a Patient..." name="patientid" class="chosen-select-deselect form-control" width="380">
					<option value=""></option>
					  <?php
						$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Patient' AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
						foreach($query as $id) {
							$selected = '';
							$selected = $id == $patientid ? 'selected = "selected"' : '';
							echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
						}
					  ?>
                </select>
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Injury:</label>
            <div class="col-sm-8">
                <select id="injuryid" data-placeholder="Choose a Injury..." name="injuryid" class="chosen-select-deselect form-control" width="380">
                    <option value=""></option>
                </select>
            </div>
          </div>

          <div class="form-group">
            <label for="fax_number"	class="col-sm-4	control-label">Discharge Comment:</label>
            <div class="col-sm-8">
                <a name="exactline">
                <textarea name="discharge_comment" rows="5" cols="50" class="form-control"></textarea>
                </a>
            </div>
          </div>

        <div class="form-group">
            <div class="col-sm-4">
                <p><span class="empire-red pull-right"><em>Required Fields *</em></span></p>
            </div>
            <div class="col-sm-8"></div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
            </div>
        </div>

        

        </form>

        </div>

    </div>

<?php include ('../footer.php'); ?>