<?php
/*
NEW PATIENT HISTORY FORM
*/
if (isset($_POST['manual_btn'])) {
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

    echo '<script type="text/javascript"> window.location.replace("index.php?tab='.$tab_name.'&subtab='.$category.'"); </script>';

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
	$('.iframe_open').click(function(){

			var id = $(this).attr('id');
		   $('#iframe_instead_of_window').attr('src', '<?php echo WEBSITE_URL; ?>/Contacts/add_injury.php?type=contact&contactid='+id);
		   $('.iframe_title').text('Add New Injury');

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
				<label for="first_name" class="col-sm-4 control-label"></label>
				<div class="col-sm-8">
				<?php
					//echo '<a class="btn brand-btn pull-right iframe_open" id="'.$patientid.'">Add New Injury</a>';
					//echo '<a class="btn brand-btn pull-right" href="#"  onclick=" window.open(\''.WEBSITE_URL.'/Contact/add_injury.php?type=contact&contactid='.$patientid.'\', \'newwindow\', \'width=900, height=900\'); return false;">Add New Injury</a>';
				?>
				</div>
			  </div>

			  <?php if(empty($_GET['assessmentid'])) { ?>

			  <div class="form-group">
				<label for="site_name" class="col-sm-4 control-label">Patient<span class="empire-red">*</span>:</label>
				<div class="col-sm-8">
					<select id="patientid" data-placeholder="Select a Patient..." name="patientid" class="chosen-select-deselect form-control" width="380">
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
					<select id="injuryid" data-placeholder="Select an Injury..." name="injuryid" class="chosen-select-deselect form-control" width="380">
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
					<label for="site_name" class="col-sm-4 control-label">Therapists<span class="empire-red">*</span>:</label>
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

			</div>

		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse_2" >
					Assessment Plan<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_2" class="panel-collapse collapse">
			<div class="panel-body">

			  <div class="form-group">
				<label for="fax_number"	class="col-sm-4	control-label">Assessment Plan:</label>
				<div class="col-sm-8">
					<textarea name="assessment" rows="5" cols="50" class="form-control"><?php echo $assessment; ?></textarea>
				</div>
			  </div>

			</div>
		</div>
	</div>

</div>

 <div class="form-group">
	<div class="col-sm-4">
		<p><span class="empire-red pull-right"><em>Required Fields *</em></span></p>
	</div>
	<div class="col-sm-8"></div>
</div>