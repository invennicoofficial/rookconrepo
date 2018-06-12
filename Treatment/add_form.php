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
    $assessment = htmlentities($_POST['assessment']);
    $assessment = filter_var($assessment,FILTER_SANITIZE_STRING);
    $updated_at = date('Y-m-d');
    if(empty($_POST['assessmentid'])) {
		$query_insert_form = "INSERT INTO `assessment` (`patientid`, `therapistsid`, `injuryid`, `assessment`, `updated_at`) VALUES ('$patientid', '$therapistsid', '$injuryid', '$assessment', '$updated_at')";
		$result_insert_form = mysqli_query($dbc, $query_insert_form);
        $url = 'Added';
    } else {
        $assessmentid = $_POST['assessmentid'];
        $query_update_inventory = "UPDATE `assessment` SET `assessment` = '$assessment', `updated_at` = '$updated_at' WHERE `assessmentid` = '$assessmentid'";
        $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
        $url = 'Updated';
    }

    echo '<script type="text/javascript"> window.location.replace("assessment.php"); </script>';

    mysqli_close($dbc); //Close the DB Connection
}

?>
<script type="text/javascript">
$(document).ready(function() {

    $("#treatment_form").change(function() {
		window.location = 'add_form.php?form='+this.value;
	});

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
<div class="container">
  <div class="row">

        <h1 class="triple-pad-bottom">Assessment</h1>

		<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
		<?php

        $patientid = '';
        $therapistsid = '';
        $injuryid = '';
        $assessment = '';

		if(!empty($_GET['assessmentid']))	{
			$assessmentid = $_GET['assessmentid'];
			$get_treatment =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	assessment WHERE	assessmentid='$assessmentid'"));
            $patientid = $get_treatment['patientid'];
            $therapistsid = $get_treatment['therapistsid'];
            $injuryid = $get_treatment['injuryid'];
            $assessment = $get_treatment['assessment'];

		?>
		<input type="hidden" id="assessmentid"	name="assessmentid" value="<?php echo $assessmentid ?>" />
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

                      <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Form:</label>
                        <div class="col-sm-8">
                            <select id="treatment_form" data-placeholder="Choose a Form..." name="treatment_form" class="chosen-select-deselect form-control" width="380">
                                <option value=""></option>
                                <option value="Neck Pain">Neck Pain</option>
                            </select>
                        </div>
                      </div>

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

                     </div>

                </div>
            </div>

            <?php if($form == '') { ?>



            <?php } ?>

        </div>

         <div class="form-group">
            <div class="col-sm-4">
                <p><span class="empire-red pull-right"><em>Required Fields *</em></span></p>
            </div>
            <div class="col-sm-8"></div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 clearfix">
                <a href="assessment.php" class="btn brand-btn pull-right">Back</a>
            </div>
            <div class="col-sm-8">
                <button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
            </div>
        </div>

        

        </form>

    </div>
  </div>
<?php include ('../footer.php'); ?>