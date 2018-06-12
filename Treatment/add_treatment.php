<?php
/*
NEW PATIENT HISTORY FORM
*/
include_once('../tcpdf/tcpdf.php');
include ('../include.php');
checkAuthorised('treatment_charts');
error_reporting(0);

if (isset($_POST['submit'])) {
	$patientid = $_POST['patientid'];
    $injuryid = $_POST['injuryid'];
    $therapistsid = get_all_from_injury($dbc, $injuryid, 'injury_therapistsid');

    $patient_note = htmlentities($_POST['patient_note']);
    $patient_note = filter_var($patient_note,FILTER_SANITIZE_STRING);
    $subjective = htmlentities($_POST['subjective']);
    $subjective = filter_var($subjective,FILTER_SANITIZE_STRING);
    $objective = htmlentities($_POST['objective']);
    $objective = filter_var($objective,FILTER_SANITIZE_STRING);
    $assessment = htmlentities($_POST['assessment']);
    $assessment = filter_var($assessment,FILTER_SANITIZE_STRING);
    $plan = htmlentities($_POST['plan']);
    $plan = filter_var($plan,FILTER_SANITIZE_STRING);

    $treatment_date = $_POST['treatment_date'];
    $send_note = $_POST['send_note'];
    $updated_at = date('Y-m-d');
    if(empty($_POST['treatmentid'])) {
		$query_insert_form = "INSERT INTO `treatment` (`patientid`, `therapistsid`, `injuryid`, `subjective`, `objective`, `assessment`, `plan`, `patient_note`, `treatment_date`, `updated_at`) VALUES ('$patientid', '$therapistsid', '$injuryid', '$subjective', '$objective', '$assessment', '$plan', '$patient_note', '$treatment_date', '$updated_at')";
		$result_insert_form = mysqli_query($dbc, $query_insert_form);
        $url = 'Added';
    } else {
        $treatmentid = $_POST['treatmentid'];
        $query_update_inventory = "UPDATE `treatment` SET `subjective` = '$subjective', `objective` = '$objective', `assessment` = '$assessment', `plan` = '$plan', `patient_note` = '$patient_note', `treatment_date` = '$treatment_date', `updated_at` = '$updated_at' WHERE `treatmentid` = '$treatmentid'";
        $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
        $url = 'Updated';
    }

    if($send_note == 1) {
        $to = $_POST['patient_email'];
        $subject = 'Clinic Ace Patient Note';
        send_email('', $to, '', '', $subject, $_POST['patient_note'], '');
    }

    echo '<script type="text/javascript"> window.location.replace("treatment.php"); </script>';

    mysqli_close($dbc); //Close the DB Connection
}

?>
<script type="text/javascript">
$(document).ready(function() {

    $("#form1").submit(function( event ) {
        var patientid = $("#patientid").val();
        var injury = $("#injury").val();
        if (patientid == '' || injury == '') {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
    });

	$("#patientid").change(function() {
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=patient&patientid="+this.value,
			dataType: "html",   //expect html to be returned
			success: function(response){
				$('#injury').html(response);
				$("#injury").trigger("change.select2");
                tinymce.get('notes').getBody().innerHTML = '';
                tinymce.get('subjective').getBody().innerHTML = '';
                tinymce.get('objective').getBody().innerHTML = '';
                tinymce.get('assessment').getBody().innerHTML = '';
                tinymce.get('plan').getBody().innerHTML = '';
			}
		});

	});

});
$(document).on('change', 'select[name="patientid"]', function() { changePatient(this); });
function outputUpdate(vol) {
    document.querySelector('#volume').value = vol;
}
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
<div class="container">
  <div class="row">

        <h1 class="triple-pad-bottom">Treatment</h1>

		<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
		<?php

        $patientid = '';
        $therapistsid = '';
        $injuryid = '';
		$patient_note = '';
        $subjective = '';
        $objective = '';
        $assessment = '';
        $plan = '';
        $treatment_date = '';
        $patient_email = '';

		if(!empty($_GET['treatmentid']))	{
			$treatmentid = $_GET['treatmentid'];
			$get_treatment =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	treatment WHERE	treatmentid='$treatmentid'"));
            $patientid = $get_treatment['patientid'];
            $therapistsid = $get_treatment['therapistsid'];
            $injuryid = $get_treatment['injuryid'];
			$patient_note = $get_treatment['patient_note'];
            $subjective = $get_treatment['subjective'];
            $objective = $get_treatment['objective'];
            $assessment = $get_treatment['assessment'];
            $plan = $get_treatment['plan'];
            $treatment_date = $get_treatment['treatment_date'];
            $patient_email = get_email($dbc, $patientid);

		?>
		<input type="hidden" id="treatmentid"	name="treatmentid" value="<?php echo $treatmentid ?>" />
		<?php	}	   ?>

        <div class="panel-group" id="accordion">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info" >
                            Information<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_info" class="panel-collapse collapse">
                    <div class="panel-body">

                      <?php if(empty($_GET['treatmentid'])) { ?>

                      <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Patient<span class="empire-red">*</span>:</label>
                        <div class="col-sm-8">
                            <select id="patientid" data-placeholder="Choose a Patient..." name="patientid" class="chosen-select-deselect form-control" width="380">
                                <option value=""></option>
                                <?php
                                $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Patient' AND status>0 AND deleted=0");
                                while($row = mysqli_fetch_array($query)) {
                                    if ($patientid == $row['contactid']) {
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
                        <label for="site_name" class="col-sm-4 control-label">Injury:</label>
                        <div class="col-sm-8">
                            <select id="injuryid" data-placeholder="Choose a Injury..." name="injuryid" class="chosen-select-deselect form-control" width="380">
                                <option value=""></option>
                            </select>
                        </div>
                      </div>

                      <?php } else {
                      ?>
                      <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Patient:</label>
                        <div class="col-sm-8">
                            <?php echo get_contact($dbc, $patientid); ?>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Injury:</label>
                        <div class="col-sm-8">
                            <?php echo get_all_from_injury($dbc, $injuryid, 'injury_name').' - '.                  get_all_from_injury($dbc, $injuryid, 'injury_type').' : '.
                                get_all_from_injury($dbc, $injuryid, 'injury_date'); ?>
                        </div>
                      </div>

                      <?php } ?>
                        <!--
                          <div class="form-group">
                            <label for="site_name" class="col-sm-4 control-label">Therapists:</label>
                            <div class="col-sm-8">
                                <select id="therapistsid" data-placeholder="Choose a Therapists..." name="therapistsid" class="chosen-select-deselect form-control" width="380">
                                    <option value=""></option>
                                    <?php
                                    $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0");
                                    while($row = mysqli_fetch_array($query)) {
                                        if ($therapistsid == $row['contactid']) {
                                            $selected = 'selected="selected"';
                                        } else {
                                            $selected = '';
                                        }
                                        echo "<option ".$selected." value='". $row['contactid']."'>".$row['first_name'].' '.$row['last_name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                          </div>
                        -->


                        <div class="form-group clearfix orientation_date">
                            <label for="first_name" class="col-sm-4 control-label text-right">Treatment Date :</label>
                            <div class="col-sm-8">
                                <input name="treatment_date" type="text" class="datepicker" value="<?php echo $treatment_date; ?>"></p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_2" >
                            Subjective<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_2" class="panel-collapse collapse">
                    <div class="panel-body">

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Subjective:</label>
                        <div class="col-sm-8">
                            <textarea name="subjective" id="subjective" rows="5" cols="50" class="form-control"><?php echo $subjective; ?></textarea>
                        </div>
                      </div>

                    </div>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_3" >
                            Objective<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_3" class="panel-collapse collapse">
                    <div class="panel-body">

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Objective:</label>
                        <div class="col-sm-8">
                            <textarea name="objective" id="objective" rows="5" cols="50" class="form-control"><?php echo $objective; ?></textarea>
                        </div>
                      </div>

                    </div>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_4" >
                            Assessment<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_4" class="panel-collapse collapse">
                    <div class="panel-body">

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Assessment:</label>
                        <div class="col-sm-8">
                            <textarea name="assessment" id="assessment" rows="5" cols="50" class="form-control"><?php echo $assessment; ?></textarea>
                        </div>
                      </div>


                    </div>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_5" >
                            Plan<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_5" class="panel-collapse collapse">
                    <div class="panel-body">

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Plan:</label>
                        <div class="col-sm-8">
                            <textarea name="plan" id="plan" rows="5" cols="50" class="form-control"><?php echo $plan; ?></textarea>
                        </div>
                      </div>

                    </div>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_7" >
                            Patient Notes/ Take Home<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_7" class="panel-collapse collapse">
                    <div class="panel-body">

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Patient Notes/ Take Home:</label>
                        <div class="col-sm-8">
                            <textarea name="patient_note" rows="5" cols="50" class="form-control"><?php echo $patient_note; ?></textarea>
                        </div>
                      </div>

					  <div class="form-group">
						<div class="col-sm-offset-4 col-sm-8">
						  <div class="checkbox">
							<label>
							  <input type="checkbox" value="1" name="send_note">Send Notes to Patient
							</label>
						  </div>
						</div>
					  </div>

                        <div class="form-group clearfix orientation_date">
                            <label for="first_name" class="col-sm-4 control-label text-right">Patient Email:<br><em>(separate multiple email addresses with a comma)</em></label>
                            <div class="col-sm-8">
                                <input name="patient_email" value="<?php echo $patient_email; ?>" id="patient_email" type="text" class="form-control"></p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>

            <div class="form-group">
                <div class="col-sm-8 col-sm-offset-4">
                    <label for="site_name"><input type="checkbox" name="accept_policy" required value=1>&nbsp; I, <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);  ?>, practicing with clinic confirm that which I have provided here is true and correct to the best of my knowledge.</label>
                </div>
            </div>

             <div class="form-group">
                <div class="col-sm-4">
                    <p><span class="empire-red pull-right"><em>Required Fields *</em></span></p>
                </div>
                <div class="col-sm-8"></div>
            </div>

        <?php
            $discharge = '';
            if(!empty($_GET['discharge'])) {
                $discharge = $_GET['discharge'];
            }
            if($discharge != 1) {
        ?>
        <div class="form-group">
            <div class="col-sm-4 clearfix">
                <a href="treatment.php" class="btn brand-btn pull-right">Back</a>
            </div>
            <div class="col-sm-8">
                <button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
            </div>
        </div>
        <?php }
         ?>

        

        </form>

    </div>
  </div>
<?php include ('../footer.php'); ?>