<?php
/*
Add	Inventory
*/
include ('../include.php');
error_reporting(0);

if (isset($_POST['submit'])) {
    //Injury
    $contactid = $_POST['contactid'];
    $serviceid = $_POST['serviceid'];
    $injury_therapistsid = $_POST['injury_therapistsid'];
    $injury_name = filter_var($_POST['injury_name'],FILTER_SANITIZE_STRING);
    $injury_date = filter_var($_POST['injury_date'],FILTER_SANITIZE_STRING);
    $injury_type = filter_var($_POST['injury_type'],FILTER_SANITIZE_STRING);

    $insurer = filter_var($_POST['insurer'],FILTER_SANITIZE_STRING);
    $adjusterid = $_POST['adjusterid'];
    $today_date = date('Y-m-d');
    $treatment_plan = $_POST['treatment_plan'];

    move_uploaded_file($_FILES["injury_reg_form"]["tmp_name"], "Download/".$_FILES["injury_reg_form"]["name"]) ;

    $injury_reg_form = htmlspecialchars($_FILES["injury_reg_form"]["name"], ENT_QUOTES);

    if($_FILES["injury_other_form"]["name"] != '') {
        $injury_other_form = '#$#'.implode('#$#', $_FILES["injury_other_form"]["name"]);
    } else {
        $injury_other_form = '';
    }

    for($i = 0; $i < count($_FILES['injury_other_form']['name']); $i++) {
        move_uploaded_file($_FILES["injury_other_form"]["tmp_name"][$i], "Download/".$_FILES["injury_other_form"]["name"][$i]) ;
    }

    if(empty($_POST['injuryid'])) {
        $query_insert_injury = "INSERT INTO `patient_injury` (`contactid`, `injury_therapistsid`, `injury_name`, `injury_type`, `insurer`, `adjusterid`, `injury_date`, `injury_reg_form`, `injury_other_form`, `today_date`, `treatment_plan`) VALUES ('$contactid', '$injury_therapistsid', '$injury_name', '$injury_type', '$insurer', '$adjusterid', '$injury_date', '$injury_reg_form', '$injury_other_form', '$today_date', '$treatment_plan')";
        $result_insert_injury = mysqli_query($dbc, $query_insert_injury);

    } else {
        $injuryid = $_POST['injuryid'];
        $query_update_staff = "UPDATE `patient_injury` SET `treatment_plan` = '$treatment_plan', `injury_therapistsid` = '$injury_therapistsid', `injury_name` = '$injury_name', `injury_type` = '$injury_type', `insurer` = '$insurer', `adjusterid` = '$adjusterid', `injury_date` = '$injury_date', `injury_reg_form` = CONCAT(injury_reg_form,'$injury_reg_form'), `injury_other_form` = CONCAT(injury_other_form,'$injury_other_form') WHERE `injuryid` = '$injuryid'";
        $result_update_staff = mysqli_query($dbc, $query_update_staff);
    }

    //Injury

    echo '<script type="text/javascript"> window.location.replace("injury.php?category=active"); </script>';

    mysqli_close($dbc); //Close the DB Connection
}

?>
<script type="text/javascript">
    $(document).ready(function () {

     $("#form1").submit(function( event ) {
        var therapistid = $("#therapistid").val();
        var injury_name = $("#injury_name").val();
        var category = $("#category").val();
        //var therapistsid = $("#therapistsid").val();
        if (category == '' || injury_name =='') {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
    });
});
$(document).on('change', 'select[name="injury_type"]', function() { selectType(this); });

</script>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('injury');
?>
<div class="container">
  <div class="row">

        <h1 class="triple-pad-bottom">Injury</h1>

		<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
        <?php
        $contactid = '';
        $serviceid = '';
        $injury_therapistsid = '';
        $injury_name = '';
        $injury_date = '';
        $injury_type = '';
        $injury_reg_form = '';
        $injury_other_form = '';
        $insurer = '';
        $adjusterid = '';
        $treatment_plan = '';
        if(!empty($_GET['injuryid'])) {
            $injuryid = $_GET['injuryid'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM patient_injury WHERE injuryid='$injuryid'"));
            $contactid = $get_contact['contactid'];
            $serviceid = $get_contact['serviceid'];
            $injury_therapistsid = $get_contact['injury_therapistsid'];
            $injury_name = $get_contact['injury_name'];
            $injury_date = $get_contact['injury_date'];
            $injury_type = $get_contact['injury_type'];
            $insurer = $get_contact['insurer'];
            $adjusterid = $get_contact['adjusterid'];
            $injury_reg_form = $get_contact['injury_reg_form'];
            $injury_other_form = $get_contact['injury_other_form'];
            $treatment_plan = $get_contact['treatment_plan'];
            echo '<input type="hidden" name="injuryid" value="'.$_GET['injuryid'].'" />';
        } else if(!empty($_GET['contactid'])) {
            $contactid = $_GET['contactid'];
        }
        ?>

         <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Therapist:</label>
            <div class="col-sm-8">
                <select data-placeholder="Choose a Therapist..." name="injury_therapistsid" class="chosen-select-deselect form-control" width="380">
                    <option value=""></option>
                    <?php
                    $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `status` > 0");

					while($row = mysqli_fetch_array($query)) {
						$patients[$row['contactid']] = decryptit($row['first_name']) . ' ' . decryptit($row['last_name']);
					}
	
					$patients = sortByLastName($patients);
					foreach($patients as $patientid => $patientp) {
						if ($injury_therapistsid == $patientid) {
							$selected = 'selected="selected"';
						} else {
							$selected = '';
						}
						echo "<option ".$selected." value='". $patientid."'>".$patientp.'</option>';
					}
                    ?>
                </select>
            </div>
          </div>

          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Patient<span class="empire-red">*</span>:</label>
            <div class="col-sm-8">
                <select id="contactid" data-placeholder="Choose a Patient..." name="contactid" class="chosen-select-deselect form-control" width="380">
                    <option value=""></option>
                    <?php
                    $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Patient' AND status>0 AND deleted=0");
            					while($row = mysqli_fetch_array($query)) {
            						$patients[$row['contactid']] = decryptit($row['first_name']) . ' ' . decryptit($row['last_name']);
            					}
            	
            					$patients = sortByLastName($patients);
            					foreach($patients as $patientid => $patientp) {
            						if ($contactid == $patientid) {
            							$selected = 'selected="selected"';
                          $contact_found = true;
            						} else {
            							$selected = '';
            						}
            						echo "<option ".$selected." value='". $patientid."'>".$patientp.'</option>';
            					}
                      if(!$contact_found && $contactid > 0) {
                        echo "<option selected value='". $contactid."'>".get_contact($dbc, $contactid).'</option>';
                      }
                    ?>
                </select>
            </div>
          </div>

            <div class="form-group">
              <label for="ship_zip" class="col-sm-4 control-label">Injury Name<span class="empire-red">*</span>:</label>
              <div class="col-sm-8">
				<?php
					$injury_array = array('Thumb','Knee','Hand','Forearm','Arm','Shoulder','Back','Chest','Neck','Thigh','Elbow','Wrist','Hamstring','Calf','Heel','Ankle','Foot','Abdominals','Groin','Head','Hip','Jaw','Leg','Low back','Lumbar Spine','Massage','Rib','Tail bone','Thoracic','Toe','Vertigo','Pelvis','Buttocks','Clavicle','Rib','Quad','Rotator Cuff','Scapula','Shin','Sl Joint','Thoracic','Achilles','ACL','Sciatic Nerve','Concussion','Glute','Groin','Hamstring','Humorous','Lumbar Spine','MCL','Concusion','Face','Foot/Feet','Full Body','Hand-Finger','Lower Back','Massage','Sciatica','Spine','Upperback','Clinic Forms');
					sort($injury_array);
				?>
                <select data-placeholder="Choose a Name..." name="injury_name" id="injury_name" class="chosen-select-deselect form-control" width="380">
                    <option value=""></option>
					<?php foreach($injury_array as $indinjury): ?>
						<option <?php if ($injury_name==$indinjury) echo 'selected="selected"';?> value="<?php echo $indinjury; ?>"><?php echo $indinjury; ?></option>
                    <?php endforeach; ?>
                </select>

              </div>
            </div>

          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Injury Date:</label>
            <div class="col-sm-8">
              <input name="injury_date" type="text" class="datepicker" value="<?php echo $injury_date; ?>">
            </div>
          </div>

          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Injury File Type<span class="empire-red">*</span>:</label>
            <div class="col-sm-8">
                <select id="category" name="injury_type" class="chosen-select-deselect form-control" width="380">
                    <option value=''></option>
                    <option <?php if ($injury_type=='AHS') echo 'selected="selected"';?> value='AHS'>AHS</option>
                    <option <?php if ($injury_type=='MVA/MVC - Out of Protocol') echo 'selected="selected"';?> value='MVA/MVC - Out of Protocol'>MVA/MVC - Out of Protocol</option>
                    <option <?php if ($injury_type=='MVA/MVC - In Protocol') echo 'selected="selected"';?> value='MVA/MVC - In Protocol'>MVA/MVC - In Protocol</option>
                    <option <?php if ($injury_type=='Orthotics') echo 'selected="selected"';?> value='Orthotics'>Orthotics</option>
                    <option <?php if ($injury_type=='Osteopathic') echo 'selected="selected"';?> value='Osteopathic'>Osteopathic</option>
                    <option <?php if ($injury_type=='Private Massage') echo 'selected="selected"';?> value='Private Massage'>Private Massage</option>
                    <option <?php if ($injury_type=='Private Physio') echo 'selected="selected"';?> value='Private Physio'>Private Physio</option>
                    <option <?php if ($injury_type=='WCB') echo 'selected="selected"';?> value='WCB'>WCB</option>
                </select>
            </div>
          </div>

           <div class="form-group treatment_plan">
            <label for="site_name" class="col-sm-4 control-label">Treatment Plan:</label>
            <div class="col-sm-8">
              <select name="treatment_plan" data-placeholder="Choose a Plan..." class="chosen-select-deselect form-control" width="380">
					<option value=''></option>
                    <?php
                    for($i = 1; $i <= 50; $i++) { ?>
                        <option <?php if ($treatment_plan == $i) { echo " selected"; } ?> value = '<?php echo $i; ?>'><?php echo $i; ?></option>
                    <?php } ?>
              </select>
            </div>
          </div>

          <!--
          <div class="form-group">
            <label for="site_name" class="col-sm-4 control-label">Service:</label>
            <div class="col-sm-8">
                <select id="contactid" data-placeholder="Choose a Service..." name="serviceid" class="chosen-select-deselect form-control" width="380">
                    <option value=""></option>
                    <?php
                    $query = mysqli_query($dbc,"SELECT serviceid, service_code, service_type, fee FROM services WHERE deleted=0");
                    while($row = mysqli_fetch_array($query)) {
                        if ($serviceid == $row['serviceid']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $row['serviceid']."'>".$row['service_code'].' : $'.$row['fee'].'</option>';
                    }
                    ?>
                </select>
            </div>
          </div>

          <?php
          $style = 'style="display:none;"';
          if(strpos($injury_type, 'MVA') !== FALSE) {
            $style = 'style="display:block;"';
          }
          ?>
          <div class="form-group mva_type" <?php echo $style; ?>>
            <label for="position[]" class="col-sm-4 control-label">Insurer:</label>
            <div class="col-sm-8">
                <select onchange="selectInsurer(this)" data-placeholder="Choose a Insurer..." name="insurer" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE category='Insurer' AND deleted=0 ORDER BY name");
                    while($row = mysqli_fetch_array($query)) {
                        if ($insurer == $row['name']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $row['name']."'>".$row['name'].'</option>';
                    }
                  ?>
                </select>
            </div>
          </div>

          <?php if(!empty($_GET['injuryid'])) { ?>
          <div class="form-group mva_type" <?php echo $style; ?>>
            <label for="position[]" class="col-sm-4 control-label">Adjuster:</label>
            <div class="col-sm-8">
                <select data-placeholder="Choose a Adjuster..." id="adjusterid" name="adjusterid" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE name='$insurer' AND category='Adjuster'");
                    while($row = mysqli_fetch_array($query)) {
                        if ($adjusterid == $row['contactid']) {
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
          <?php } else { ?>
          <div class="form-group mva_type" <?php echo $style; ?>>
            <label for="position[]" class="col-sm-4 control-label">Adjuster:</label>
            <div class="col-sm-8">
                <select data-placeholder="Choose a Adjuster..." id="adjusterid" name="adjusterid" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                </select>
            </div>
          </div>
          <?php } ?>

           <div class="form-group">
            <label for="file[]" class="col-sm-4 control-label">Registration Form:
            <span class="popover-examples list-inline">&nbsp;
            <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Remove Single/Double Quote from file name"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
            </span>
            </label>
            <div class="col-sm-8">
              <input name="injury_reg_form" type="file" id="file" data-filename-placement="inside" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label for="file[]" class="col-sm-4 control-label">Other Forms:<br><em>(Insurance, History, etc) </em>
            <span class="popover-examples list-inline">&nbsp;
            <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Remove Single/Double Quote from file name"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
            </span>
            </label>
            <div class="col-sm-8">
              <input name="injury_other_form[]" multiple type="file" id="file" data-filename-placement="inside" class="form-control" />
            </div>
          </div>
          -->

         <div class="form-group">
            <div class="col-sm-4">
                <p><span class="empire-red pull-right"><em>Required Fields *</em></span></p>
            </div>
            <div class="col-sm-8"></div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 clearfix">
                <a href="injury.php?category=active" class="btn brand-btn pull-right">Back</a>
            </div>
            <div class="col-sm-8">
                <button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
            </div>
        </div>

        

        </form>

    </div>
  </div>

<?php include ('../footer.php'); ?>
