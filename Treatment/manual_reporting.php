<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('treatment_charts');
error_reporting(0);
?>
</head>
<body>

<?php include_once ('../navigation.php'); ?>

<div class="container">
	<div class="row">

        <form name="form_sites" method="post" action="" class="form-inline" role="form">

        <h2>Information Gathering Reporting</h2>

        <a href='patientform.php?tab=Form'><button type="button" class="btn brand-btn mobile-block" >Form</button></a>
        <a href='manual_reporting.php?type=patientform'><button type="button" class="btn brand-btn mobile-block active_tab" >Reporting</button></a>
        <br><br>
        <?php
        $contactid = '';
        $category = '';
        $heading = '';
        $status = '';
        $s_start_date = '';
        $s_end_date = '';

        if(!empty($_POST['contactid'])) {
            $contactid = $_POST['contactid'];
        }
        if(!empty($_POST['category'])) {
            $category = $_POST['category'];
        }
        if(!empty($_POST['heading'])) {
            $heading = $_POST['heading'];
        }
        if(!empty($_POST['status'])) {
            $status = $_POST['status'];
        }
        if(!empty($_POST['s_start_date'])) {
            $s_start_date = $_POST['s_start_date'];
        }
        if(!empty($_POST['s_end_date'])) {
            $s_end_date = $_POST['s_end_date'];
        }
        if (isset($_POST['display_all_asset'])) {
            $contactid = '';
            $category = '';
            $heading = '';
            $status = '';
            $s_start_date = '';
            $s_end_date = '';
        }
        ?>

        <div class="form-group">
          <label for="ship_country" class="col-sm-4 control-label">Staff:</label>
          <div class="col-sm-8">
                <select data-placeholder="Choose a Staff..." name="contactid" class="chosen-select-deselect form-control" width="380">
					<option value=""></option>
					  <?php
						$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
						foreach($query as $id) {
							$selected = '';
							$selected = $id == $contactid ? 'selected = "selected"' : '';
							echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
						}
					  ?>
                </select>

          </div>
        </div>

        <div class="form-group">
          <label for="ship_zip" class="col-sm-4 control-label">Topic:</label>
          <div class="col-sm-8">
                <select data-placeholder="Choose a Topic (Sub Tab)..." name="category" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT distinct(category) FROM patientform WHERE deleted=0 AND manual_type='$type'");
                    while($row = mysqli_fetch_array($query)) {
                        if ($category == $row['category']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        ?>
                        <option <?php echo $selected; ?> value='<?php echo $row['category']; ?>' ><?php echo $row['category']; ?></option>
                    <?php }
                  ?>
                </select>
          </div>
        </div>

        <div class="form-group">
          <label for="ship_zip" class="col-sm-4 control-label">Heading:</label>
          <div class="col-sm-8">
                <select data-placeholder="Choose a Heading..." name="heading" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT distinct(heading) FROM patientform WHERE deleted=0 AND manual_type='$type'");
                    while($row = mysqli_fetch_array($query)) {
                        if ($heading == $row['heading']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        ?>
                        <option <?php echo $selected; ?> value='<?php echo $row['heading']; ?>' ><?php echo $row['heading']; ?></option>
                    <?php }
                  ?>
                </select>
          </div>
        </div>

        <div class="form-group">
          <label for="ship_zip" class="col-sm-4 control-label">Status:</label>
          <div class="col-sm-8">
                <select data-placeholder="Choose a Status..." name="status" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <option <?php if ($status=='Deadline Past') echo 'selected="selected"';?> value="Deadline Past">Deadline Past</option>
                  <option <?php if ($status=='Deadline Today') echo 'selected="selected"';?> value="Deadline Today">Deadline Today</option>
                </select>
          </div>
        </div>

        <div class="form-group">
            <label for="site_name" class="col-sm-2 control-label">Start Date:</label>
            <div class="col-sm-4">
                <input name="s_start_date" type="text" class="datepicker" value="<?php echo $s_start_date; ?>">
            </div>

            <label for="first_name" class="col-sm-2 control-label">End Date:</label>
            <div class="col-sm-4">
                <input name="s_end_date" type="text" class="datepicker" value="<?php echo $s_end_date; ?>">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 clearfix">

            </div>
          <div class="col-sm-8">
                <button type="submit" name="reporting_client" value="Submit" class="btn brand-btn mobile-block">Submit</button>
                <button type="submit" name="display_all_asset" value="Display All" class="btn brand-btn mobile-block">Display All</button>
          </div>
        </div>

        <br><br>

        <span class="pull-right">
            <img src="<?php echo WEBSITE_URL;?>/img/red.png" width="23" height="23" border="0" alt=""> Deadline Past
            <img src="<?php echo WEBSITE_URL;?>/img/green.png" width="23" height="23" border="0" alt=""> Deadline Today
        </span><br><br>
        <?php

        if(isset($_POST['reporting_client'])) {
            $contactid = $_POST['contactid'];
            $category = $_POST['category'];
            $heading = $_POST['heading'];
            $status = $_POST['status'];
            $s_start_date = $_POST['s_start_date'];
            $s_end_date = $_POST['s_end_date'];

            $query_check_credentials = "SELECT m.*, ms.*  FROM patientform_attendance ms, patientform m WHERE m.deleted=0 AND m.patientformid = ms.patientformid AND (ms.staffid = '$contactid' OR m.category='$category' OR m.heading='$heading')";
            if($status == 'Deadline Past') {
                $query_check_credentials = "SELECT m.*, ms.*  FROM patientform_attendance ms, patientform m WHERE m.deleted=0 AND m.patientformid = ms.patientformid AND ms.done=0 AND DATE(NOW()) > DATE(m.deadline)";
            }
            if($status == 'Deadline Today') {
                $query_check_credentials = "SELECT m.*, ms.*  FROM patientform_attendance ms, patientform m WHERE m.deleted=0 AND m.patientformid = ms.patientformid AND ms.done=0 AND DATE(NOW()) = DATE(m.deadline)";
            }
            if($s_start_date != '' && $s_end_date != '') {
                $query_check_credentials = "SELECT m.*, ms.*  FROM patientform_attendance ms, patientform m WHERE m.deleted=0 AND m.patientformid = ms.patientformid AND m.deadline >= '$s_start_date' AND m.deadline <= '$s_end_date'";
            }
        } else if(empty($_GET['action'])) {
            $query_check_credentials = "SELECT i.*, p.*  FROM patientform i, patientform_pdf p WHERE deleted=0 AND i.patientformid = p.patientformid";
        }

        $result = mysqli_query($dbc, $query_check_credentials);

        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {
            echo "<table class='table table-bordered'>";
            echo '<tr class="hidden-xs hidden-sm">
                <th>Topic (Sub Tab)</th>
                <th>Heading</th>
                <th>Sub Section Heading</th>
                <th>Status</th>
                <th>Function</th>
                </tr>';
        } else {
            echo "<h2>No Record Found.</h2>";
        }

        while($row = mysqli_fetch_array( $result ))
        {
            $patientformid = $row['patientformid'];
            $fieldlevelriskid = $row['fieldlevelriskid'];
            $done = $row['done'];
            $staffid = $row['staffid'];
            $today = date('Y-m-d');
            $color = '';
            $signed_off = $row['today_date'];

            if($row['done'] == 0) {
                $color = 'style="background-color: lightgreen;"';
            }

            echo "<tr>";
            //echo '<td data-title="Contact Person">' . $row['assign_staff'] . '</td>';
            echo '<td data-title="Code">' . $row['category'] . '</td>';
            echo '<td data-title="Code">' . $row['heading'] . '</td>';
            echo '<td data-title="Code">' . $row['sub_heading'] . '</td>';
            //echo '<td data-title="Code">' . $row['deadline'] . '</td>';

            echo '<td data-title="Code">';
            echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="22" height="22" border="0" alt="">';
            echo '</td>';

            $pdf_path = patientform_pdf($dbc, $patientformid, $fieldlevelriskid);
            $edit_path = patientform_edit($dbc, $patientformid, $fieldlevelriskid);

            $pdf = '<a target="_blank" href="'.$pdf_path.'"><img src="'.WEBSITE_URL.'/img/pdf.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt=""></a>';
            echo '<td data-title="Code">'.$row['today_date'] .'&nbsp;'.$pdf.' '. $edit_path.'</td>';

            echo "</tr>";
        }

        echo '</table></div>';
        ?>

        </form>

    </div>
</div>

<?php include ('../footer.php');

function patientform_pdf($dbc, $patientformid, $fieldlevelriskid) {
    $form = get_patientform($dbc, $patientformid, 'form');

    if($form == 'Client Business Introduction') {
        $pdf_path = 'client_business_introduction/download/patientform_'.$fieldlevelriskid.'.pdf';
        return $pdf_path;
    }
    if($form == 'Branding Questionnaire') {
        $pdf_path = 'branding_questionnaire/download/patientform_'.$fieldlevelriskid.'.pdf';
        return $pdf_path;
    }
    if($form == 'Website Information Gathering') {
        $pdf_path = 'website_information_gathering_form/download/patientform_'.$fieldlevelriskid.'.pdf';
        return $pdf_path;
    }
    if($form == 'Blog') {
        $pdf_path = 'blog/download/patientform_'.$fieldlevelriskid.'.pdf';
        return $pdf_path;
    }
    if($form == 'Social Media Info Gathering') {
        $pdf_path = 'social_media_info_gathering/download/patientform_'.$fieldlevelriskid.'.pdf';
        return $pdf_path;
    }
    if($form == 'Social Media Start Up Questionnaire') {
        $pdf_path = 'social_media_start_up_questionnaire/download/patientform_'.$fieldlevelriskid.'.pdf';
        return $pdf_path;
    }

}

function patientform_edit($dbc, $patientformid, $fieldlevelriskid) {
    $form = get_patientform($dbc, $patientformid, 'form');

    //if($form == 'Client Business Introduction') {
        $edit_path = '
        <a href ="add_manual.php?patientformid='.$patientformid.'&action=view&formid='.$fieldlevelriskid.'">Edit</a>';
        return $edit_path;
   // }

}
?>