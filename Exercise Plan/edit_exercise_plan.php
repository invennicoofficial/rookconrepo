<?php
/*
NEW PATIENT HISTORY FORM
*/
include_once('../tcpdf/tcpdf.php');
include ('../include.php');
checkAuthorised('exercise_library');
error_reporting(0);

if(isset($_GET['action'])) {
	if($_GET['action'] == 'archive') {
		mysqli_query($dbc, "UPDATE `treatment_exercise_plan` SET `deleted`=1, `updated_at`='".date('Y-m-d')."' WHERE `treatmentexerciseid`='".$_GET['treatmentexerciseid']."'");
		echo '<script type="text/javascript"> window.location.replace("exercise_config.php?view=exercise"); </script>';
	}
}
if (isset($_POST['submit'])) {
	ob_clean();
	$patientid = $_POST['patientid'];
    $injuryid = $_POST['injuryid'];
	if(!empty($_POST['treatmentexerciseid']) && (empty($patientid) || empty($injuryid))) {
		$result = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `treatment_exercise_plan` WHERE `treatmentexerciseid`='".$_POST['treatmentexerciseid']."'"));
		$patientid = $result['patientid'];
		$injuryid = $result['injuryid'];
	}
    $therapistsid = $therapistsid = get_all_from_injury($dbc, $injuryid, 'injury_therapistsid');
    $exerciseid = implode(',',$_POST['exerciseid']);
    $updated_at = date('Y-m-d');

	if(!file_exists('Download')) {
		mkdir('Download');
	}
	$file_name = "Download/exercise_plan_".preg_replace('/[^a-z_]/','',str_replace(' ','_',strtolower(get_contact($dbc, $patientid))))."_".date('Y_m_d').".pdf";

	// Generate PDF
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->setFooterData(array(0,64,0), array(0,64,128));

	$html = "<h3>Patient: ".get_contact($dbc, $patientid)."</h3>";
	$html .= "<h3>Injury: ".get_all_from_injury($dbc, $injuryid, 'injury_name').' - '.get_all_from_injury($dbc, $injuryid, 'injury_type').' ('.get_all_from_injury($dbc, $injuryid, 'injury_date').")</h3>";
	$html .= "<table border='2' cellpadding='10' class='table'>";
	$html .= "<tr>
	<th>Category</th>
	<th>Title</th>
	<th>Description</th>
	<th>Document(s)</th>
	<th>Link(s)</th>
	<th>Video(s)</th>
	</tr>";

	$exerciseid_all = explode(',', $exerciseid);

	foreach($exerciseid_all as $exercise) {
		$exercise_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM exercise_config WHERE exerciseid='$exercise'"));
		$html .= "<tr>";
		$html .= '<td>' . $exercise_config['category'] . '</td>';
		$html .= '<td>' . $exercise_config['title'] . '</td>';
		$html .= '<td>' . $exercise_config['description'] . '</td>';

		$result = mysqli_query($dbc, "SELECT upload, exlibraryuploadid FROM exercise_library_upload WHERE type='document' AND exerciseid='$exercise'");

		$html .= '<td><ul>';
		$i=0;
		while($row = mysqli_fetch_array($result)) {
			$document = $row['upload'];
			if($document != '') {
				$html .= '<li><a href="'.WEBSITE_URL.'/Exercise Plan/Download/'.$document.'">'.$document.'</a></li>';
			}
		}
		$html .= '</ul></td>';

		$result = mysqli_query($dbc, "SELECT upload, exlibraryuploadid FROM exercise_library_upload WHERE type='link' AND exerciseid='$exercise'");

		$html .= '<td><ul>';
		$i=0;
		while($row = mysqli_fetch_array($result)) {
			$link = $row['upload'];
			if($link != '') {
				$html .= '<li><a href="'.$link.'">'.$link.'</a></li>';
			}
		}
		$html .= '</ul></td>';

		$result = mysqli_query($dbc, "SELECT upload, exlibraryuploadid FROM exercise_library_upload WHERE type='video' AND exerciseid='$exercise'");

		$html .= '<td><ul>';
		$i=0;
		while($row = mysqli_fetch_array($result)) {
			$video = $row['upload'];
			if($video != '') {
				$html .= '<li><a href="'.WEBSITE_URL.'/Exercise Plan/Download/'.$video.'">'.$video.'</a></li>';
			}
		}
		$html .= '</ul></td>';
		$html .= "</tr>";
	}
	$html .= '</table>';

	$pdf->SetFont('dejavusans', '', 10);
	$pdf->AddPage();
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output($file_name, 'F');

    if(empty($_POST['treatmentexerciseid'])) {
		$query_insert_form = "INSERT INTO `treatment_exercise_plan` (`patientid`, `therapistsid`, `injuryid`, `exerciseid`, `updated_at`, `file_name`) VALUES ('$patientid', '$therapistsid', '$injuryid', '$exerciseid', '$updated_at', '$file_name')";
		$result_insert_form = mysqli_query($dbc, $query_insert_form);
        $treatmentexerciseid = mysqli_insert_id($dbc);
        $url = 'Added';
    } else {
        $treatmentexerciseid = $_POST['treatmentexerciseid'];
        $query_update_inventory = "UPDATE `treatment_exercise_plan` SET `exerciseid` = '$exerciseid', `updated_at` = '$updated_at', `file_name`='$file_name' WHERE `treatmentexerciseid` = '$treatmentexerciseid'";
        $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
        $url = 'Updated';
    }

	if($_POST['submit'] == 'email') {
		$to_email = array_filter(explode(',',$_POST['patient_email']));
		$sender_name = filter_var($_POST['sender_name'],FILTER_SANITIZE_STRING);
		$sender = filter_var($_POST['sender'],FILTER_SANITIZE_STRING);
		$subject = filter_var($_POST['subject'],FILTER_SANITIZE_STRING);
		$message = html_entity_decode(filter_var(htmlentities($_POST['body']),FILTER_SANITIZE_STRING));

		foreach($to_email as $email) {
			send_email([$sender=>$sender_name], $email, '', '', $subject, $message, $file_name);
		}
	}

	if($_POST['submit'] == 'pdf') {
		echo '<script type="text/javascript"> window.location.replace("'.$file_name.'"); </script>';
	} else {
		echo '<script type="text/javascript"> window.location.replace("exercise_config.php?view=exercise"); </script>';
	}

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
});
$(document).on('change', 'select[name="patientid"]', function() { changePatient(this.value); });

function changePatient(patient) {
	if(patient == '') {
		return false;
	}
    $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=treatment&patientid="+patient+"&injuryid="+$('#injuryid').data('value'),
        dataType: "html",   //expect html to be returned
        success: function(response){
            var result = response.split('#*#');
			var email = result[0];
			if(email == '') {
				$("#patient_email").html('N/A');
			} else {
				$("#patient_email").html(email);
			}
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

        <h1 class="triple-pad-bottom">Exercise Plan</h1>

		<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
		<?php

        $patientid = '';
        $therapistsid = '';
        $injuryid = '';
        $exerciseid = '';

		if(!empty($_GET['treatmentexerciseid']))	{
			$treatmentexerciseid = $_GET['treatmentexerciseid'];
			$get_treatment =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	treatment_exercise_plan WHERE	treatmentexerciseid='$treatmentexerciseid'"));
            $patientid = $get_treatment['patientid'];
            $therapistsid = $get_treatment['therapistsid'];
            $injuryid = $get_treatment['injuryid'];
            $exerciseid = $get_treatment['exerciseid'];

		?>
		<input type="hidden" name="treatmentexerciseid" value="<?php echo $treatmentexerciseid ?>" />
		<?php	} else if(!empty($_GET['patientid'])) {
			$patientid = $_GET['patientid'];
			$injuryid = $_GET['injuryid'];
		}	   ?>

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

                      <?php if(empty($_GET['treatmentexerciseid'])) { ?>

                      <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Patient<span class="empire-red">*</span>:</label>
                        <div class="col-sm-8">
                            <select id="patientid" onchange="changePatient(this.value)" data-placeholder="Choose a Patient..." name="patientid" class="chosen-select-deselect form-control" width="380">
                                <option value=""></option>
                                <?php
                                $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Patient' AND status>0 AND deleted=0");
                                while($row = mysqli_fetch_array($query)) {
                                    if ($patientid == $row['contactid']) {
                                        $selected = 'selected="selected"';
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option ".$selected." value='". $row['contactid']."'>".get_contact($dbc, $row['contactid']).'</option>';
                                }
                                ?>
                            </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Injury:</label>
                        <div class="col-sm-8">
                            <select id="injuryid" data-placeholder="Choose a Injury..." data-value="<?php echo $injuryid; ?>" name="injuryid" class="chosen-select-deselect form-control" width="380">
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
                            <?php echo get_all_from_injury($dbc, $injuryid, 'injury_name').' - '.get_all_from_injury($dbc, $injuryid, 'injury_type').' : '.
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
                            My Private Library<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_2" class="panel-collapse collapse">
                    <div class="panel-body">

                    <?php
                    $contactid = $_SESSION['contactid'];
                    $query_check_credentials = "SELECT * FROM exercise_config WHERE type='$contactid' ORDER BY category";
                    $result = mysqli_query($dbc, $query_check_credentials);

                    $num_rows = mysqli_num_rows($result);
                    if($num_rows > 0) {

                    }
                    $cat = '';
                    while($row = mysqli_fetch_array( $result ))
                    {
                        if($row['category'] != $cat) {
                            echo "<table border='2' cellpadding='10' class='table'>";
                            echo "<tr>
                            <th style='max-width: 30%; width: 30em;'>Category</th>
                            <th>Title</th>
                            <th style='max-width: 30%; width: 15em;'>Include in Plan</th>
                            </tr>";
                            $cat = $row['category'];
                            echo '<h3>'.$row['category'].'</h3>';
                        }
                        echo "<tr>";
                        echo '<td>' . $row['category'] . '</td>';
                        echo '<td>' . $row['title'] . '</td>';
                        ?>
                        <td><input type="checkbox" <?php if (strpos(','.$exerciseid.',', ','.$row['exerciseid'].',') !== FALSE) { echo " checked"; } ?> value="<?php echo $row['exerciseid'];?>" name="exerciseid[]"></td>
                        <?php
                        echo "</tr>";
                    }

                    echo '</table>';
                    ?>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_3" >
                            Company Library<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_3" class="panel-collapse collapse">
                    <div class="panel-body">

                    <?php
                    $contactid = $_SESSION['contactid'];
                    $query_check_credentials = "SELECT * FROM exercise_config WHERE type='Common' ORDER BY category";

                    $result = mysqli_query($dbc, $query_check_credentials);

                    $num_rows = mysqli_num_rows($result);
                    if($num_rows > 0) {

                    }
                    $cat = '';
                    while($row = mysqli_fetch_array( $result ))
                    {
                        if($row['category'] != $cat) {
                            echo "<table border='2' cellpadding='10' class='table'>";
                            echo "<tr>
                            <th style='max-width: 30%; width: 30em;'>Category</th>
                            <th>Title</th>
                            <th style='max-width: 30%; width: 15em;'>Include in Plan</th>
                            </tr>";
                            $cat = $row['category'];
                            echo '<h3>'.$row['category'].'</h3>';
                        }
                        echo "<tr>";
                        echo '<td>' . $row['category'] . '</td>';
                        echo '<td>' . $row['title'] . '</td>';
                        ?>
                        <td><input type="checkbox" <?php if (strpos(','.$exerciseid.',', ','.$row['exerciseid'].',') !== FALSE) { echo " checked"; } ?> value="<?php echo $row['exerciseid'];?>" name="exerciseid[]"></td>
                        <?php
                        echo "</tr>";
                    }
                    echo '</table>';
                    ?>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_email" >
                            Send to Patient<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_email" class="panel-collapse collapse">
                    <div class="panel-body">
						<?php $sender = get_email($dbc, $_SESSION['contactid']);
						$subject = "Attached is your Exercise Plan";
						$body = "Attached is the Exercise Plan that was prepared by your therapist. Please review it, and contact your therapist with any questions."; ?>
						<div class="form-group">
							<label class="col-sm-4 control-label">Patient Email Address:</label>
							<div class="col-sm-8">
								<input type="text" name="patient_email" class="form-control" value="">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Sending Email Name:</label>
							<div class="col-sm-8">
								<input type="text" name="sender_name" class="form-control" value="<?= get_contact($dbc, $_SESSION['contactid']) ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Sending Email Address:</label>
							<div class="col-sm-8">
								<input type="text" name="sender" class="form-control" value="<?php echo $sender; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Email Subject:</label>
							<div class="col-sm-8">
								<input type="text" name="subject" class="form-control" value="<?php echo $subject; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Email Body:</label>
							<div class="col-sm-8">
								<textarea name="body" class="form-control"><?php echo $body; ?></textarea>
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

        <?php
            $discharge = '';
            if(!empty($_GET['discharge'])) {
                $discharge = $_GET['discharge'];
            }
            if($discharge != 1) {
        ?>
        <div class="form-group">
            <div class="col-sm-4 clearfix">
                <a href="exercise_config.php?view=exercise" class="btn brand-btn pull-right">Back</a>
            </div>
            <div class="col-sm-8">
                <button type="submit" name="submit" value="email" class="btn brand-btn btn-lg pull-right">Send Email</button>
                <button type="submit" name="submit" value="pdf" class="btn brand-btn btn-lg pull-right">View PDF</button>
                <button type="submit" name="submit" value="save" class="btn brand-btn btn-lg pull-right">Save</button>
            </div>
        </div>
        <?php }
         ?>

        </form>

    </div>
  </div>
<?php include ('../footer.php'); ?>