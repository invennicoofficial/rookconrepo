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
    $therapistsid = $therapistsid = get_all_from_injury($dbc, $injuryid, 'injury_therapistsid');
    $exerciseid = implode(',',$_POST['exerciseid']);
    $updated_at = date('Y-m-d');
    if(empty($_POST['treatmentexerciseid'])) {
		$query_insert_form = "INSERT INTO `treatment_exercise_plan` (`patientid`, `therapistsid`, `injuryid`, `exerciseid`, `updated_at`) VALUES ('$patientid', '$therapistsid', '$injuryid', '$exerciseid', '$updated_at')";
		$result_insert_form = mysqli_query($dbc, $query_insert_form);
        $treatmentexerciseid = mysqli_insert_id($dbc);
        $url = 'Added';
    } else {
        $treatmentexerciseid = $_POST['treatmentexerciseid'];
        $query_update_inventory = "UPDATE `treatment_exercise_plan` SET `exerciseid` = '$exerciseid', `updated_at` = '$updated_at' WHERE `treatmentexerciseid` = '$treatmentexerciseid'";
        $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
        $url = 'Updated';
    }

    $link = '<a target="_blank" href="' . WEBSITE_URL.'/treatment_exercise_plan.php?id='.$treatmentexerciseid.'">Link</a>';

    $subject = 'Exercise plan for your treatment from Clinic Ace';
    $message = "Please find below links for your treatment Exercise Plan.<br>";
    $message .= $link;

    $to_email = get_email($dbc, $patientid);
    // $to_email = 'dayanapatel@freshfocusmedia.com';

    send_email('', $to_email, '', '', $subject, $message, '');

    echo '<script type="text/javascript"> window.location.replace("exercise_plan.php"); </script>';

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

                      <?php if(empty($_GET['treatmentexerciseid'])) { ?>

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
                            Exercise Plan - Private<span class="glyphicon glyphicon-plus"></span>
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
                            <th>Category</th>
                            <th>Title</th>
                            <th>Function</th>
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
                            Exercise Plan - Public<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_3" class="panel-collapse collapse">
                    <div class="panel-body">

                    <?php
                    $contactid = $_SESSION['contactid'];
                    $query_check_credentials = "SELECT * FROM exercise_config WHERE type='Public' ORDER BY category";

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
                            <th>Category</th>
                            <th>Title</th>
                            <th>Function</th>
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
                <a href="exercise_plan.php" class="btn brand-btn pull-right">Back</a>
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