<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('hr');
error_reporting(0);

if (isset($_POST['send_follow_up_email'])) {
    for($i = 0; $i < count($_POST['check_send_email']); $i++) {
        $check_send_email = explode('_', $_POST['check_send_email'][$i]);
        $staffid = $check_send_email[0];
        $manualtypeid = $check_send_email[1];

        $email = get_email($dbc, $staffid);

        $manual_type = get_manual($dbc, $manualtypeid, 'manual_type');

        $email_body = "Please login through the software and click on the link below. Sign in the signature box to confirm you understand and will adhere to this policy. If you have any questions or concerns, add them in the comment section.<br><br>";
        $email_body .= 'Manual : <a target="_blank" href="'.$_SERVER['SERVER_NAME'].'/Manuals/add_manual.php?manualtypeid='.$manualtypeid.'&type='.$manual_type.'&action=view">Click Here</a><br>';

        $subject = 'Follow Up : Manual Assigned to you for Review';

        //Mail
        send_email('', $email, '', '', $subject, $email_body, '');
        //Mail
    }

    echo '<script type="text/javascript"> alert("Follow up Sent to staff"); window.location.replace("manual_reporting.php?type='.$manual_type.'"); </script>';
}

if((!empty($_GET['action'])) && ($_GET['action'] == 'send_followup_email')) {
    $manualtypeid = $_GET['manualtypeid'];
    $manual_type = $_GET['manual_type'];
    $staffid = $_GET['staffid'];

    //Mail
    $to = get_email($dbc, $staffid);
    $subject = 'Follow Up : Manual Assigned to you for Review';
    $message = "Please login through the software and click on the link below. Sign in the signature box to confirm you understand and will adhere to this policy. If you have any questions or concerns, add them in the comment section. <br><br>";
    $message .= 'Manual : <a target="_blank" href="'.$_SERVER['SERVER_NAME'].'/Manuals/add_manual.php?manualtypeid='.$manualtypeid.'&type='.$manual_type.'&action=view">Click Here</a><br>';
    send_email('', $to, '', '', $subject, $message, '');

    //Mail
    echo '<script type="text/javascript"> alert("Follow up Sent to staff"); window.location.replace("manual_reporting.php?type='.$manual_type.'"); </script>';
}

$hr_tabs = get_config($dbc, 'hr_tabs');
?>
</head>
<body>
<style>
@media (min-width:801px) {
.hide-me {
display:none;

}
}

</style>
<?php include_once ('../navigation.php'); ?>


<div class="container">
    <div class="row">

        <div class="col-md-10">
            <h1>HR Reporting</h1>
        </div>
        <div class="col-md-2 double-gap-top">
            <?php
                if(config_visible_function($dbc, 'hr') == 1) {
                    echo '<a href="field_config_hr.php" class="mobile-block pull-right"><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
                    echo '<span class="popover-examples list-inline pull-right" style="margin:15px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
                }
            ?>
        </div>
        <div class="clearfix double-gap-bottom"></div>

        <div class="gap-left tab-container mobile-100-container double-gap-bottom">
            <!--
            <a href='hr.php?tab=Toolbox'><button type="button" class="btn brand-btn mobile-block <?php echo $active_toolbox; ?>" >Toolbox</button></a>
            <a href='hr.php?tab=Tailgate'><button type="button" class="btn brand-btn mobile-block <?php echo $active_taligate; ?>" >Tailgate</button></a>-->
            <?php foreach (explode(',', $hr_tabs) as $hr_tab) { ?>
            <div class="pull-left tab">
                <span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to see all Forms and Manuals for the <?= $hr_tab ?> tab."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <?php if ( check_subtab_persmission($dbc, 'hr', ROLE, '$hr_tab') === TRUE ) { ?>
                    <a href='hr.php?tab=<?= $hr_tab ?>'><button type="button" class="btn brand-btn mobile-block mobile-100 <?= ($tab == $hr_tab ? 'active_tab' : '') ?>"><?= $hr_tab ?></button></a>
                <?php } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100"><?= $hr_tab ?></button>
                <?php } ?>
            </div>
            <?php } ?>
            <!-- <div class="pull-left tab">
                <span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to see all Forms you have created."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <?php if ( check_subtab_persmission($dbc, 'hr', ROLE, 'forms') === TRUE ) { ?>
                    <a href='hr.php?tab=Form'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_form; ?>" >Forms</button></a>
                <?php } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100 <?php echo $active_form; ?>">Forms</button>
                <?php } ?>
            </div>
            <div class="pull-left tab">
                <span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to see all the Manuals you have created."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <?php if ( check_subtab_persmission($dbc, 'hr', ROLE, 'manuals') === TRUE ) { ?>
                    <a href='hr.php?tab=Manual'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_manual; ?>" >Manuals</button></a>
                <?php } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100 <?php echo $active_manual; ?>">Manuals</button>
                <?php } ?>
            </div>
            <div class="pull-left tab">
                <span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to see all the Onboarding Forms and Templates you have created."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <?php if ( check_subtab_persmission($dbc, 'hr', ROLE, 'onboarding') === TRUE ) { ?>
                    <a href='hr.php?tab=Onboarding'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_onboarding; ?>">Onboarding</button></a>
                <?php } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100 <?php echo $active_onboarding; ?>">Onboarding</button>
                <?php } ?>
            </div>
            <div class="pull-left tab">
                <span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to see all the Orientation Forms and Templates you have created."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <?php if ( check_subtab_persmission($dbc, 'hr', ROLE, 'orientation') === TRUE ) { ?>
                    <a href='hr.php?tab=Orientation'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_orientation; ?>">Orientation</button></a>
                <?php } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100 <?php echo $active_orientation; ?>">Orientation</button>
                <?php } ?>
            </div> -->
            <!-- <a href='<?php echo $type; ?>.php?category=<?php echo $manual_category; ?>'><button type="button" class="btn brand-btn mobile-block" >Dashboard</button></a>
            <a href='manual_follow_up.php?type=<?php echo $type; ?>'><button type="button" class="btn brand-btn mobile-block" >Follow Up</button></a>-->
            <div class="pull-left tab">
                <span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to search through the HR Reports."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <?php if ( check_subtab_persmission($dbc, 'hr', ROLE, 'reporting') === TRUE ) { ?>
                    <a href='manual_reporting.php?type=<?php echo $type; ?>'><button type="button" class="btn mobile-100 brand-btn mobile-block active_tab" >Reporting</button></a>
                <?php } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100">Reporting</button>
                <?php } ?>
            </div>
            <!-- <div class="pull-left tab">
                <a href='policy_procedures.php?category=Policies and Procedures&source=hr'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_manual; ?>">Policies & Procedures</button></a>
            </div> -->
            <div class="clearfix"></div>
        </div>

        <div class="mobile-100-container">
                <a href="manual_reporting.php"><button type="button" style="margin-right:3px;" class="btn brand-btn mobile-block mobile-100">Forms</button></a>
                <a href="manual_reporting_manuals.php"><button type="button" style="margin-right:3px;" class="btn brand-btn mobile-block mobile-100 active_tab">Manuals</button></a>
        </div>

        <div class="notice double-gap-bottom popover-examples">
            <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
            <div class="col-sm-11"><span class="notice-name">NOTE:</span>
              <?php
                $notes = mysqli_fetch_assoc(mysqli_query($dbc, "select note from notes_setting where subtab = 'hr_reporting'"));
                $note = $notes['note'];
              ?>
              <?php echo $note; ?></div>            <div class="clearfix"></div>
        </div>

        <form name="form_sites" method="post" action="" class="form-inline" role="form">
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

        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
          <label for="ship_country" class="control-label">Staff:</label>
        </div>
          <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <select data-placeholder="Select a Staff Member..." name="contactid" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
				  <?php $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0"),MYSQLI_ASSOC));
					foreach($query as $id) {
						echo "<option ".($contactid == $id ? 'selected' : '')." value='". $id."'>".get_contact($dbc, $id).'</option>';
					} ?>
                </select>

          </div>

        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
          <label for="ship_zip" class="control-label">Topic:</label>
        </div>
          <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <select data-placeholder="Select a Topic (Sub Tab)..." name="category" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT distinct(category) FROM manuals WHERE deleted=0 AND manual_type='$type' order by category");
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

        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
          <label for="ship_zip" class="control-label">Heading:</label>
        </div>
          <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <select data-placeholder="Select a Heading..." name="heading" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT distinct(heading) FROM manuals WHERE deleted=0 AND manual_type='$type' order by heading");
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

        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
          <label for="ship_zip" class="control-label">Status:</label>
        </div>
          <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <select data-placeholder="Select a Status..." name="status" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <option <?php if ($status=='Deadline Past') echo 'selected="selected"';?> value="Deadline Past">Deadline Passed</option>
                  <option <?php if ($status=='Deadline Today') echo 'selected="selected"';?> value="Deadline Today">Deadline Today</option>
                </select>
          </div>

        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
            <label for="site_name" class="control-label">Start Date:</label>
        </div>
            <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <input name="s_start_date" type="text" class="datepicker" value="<?php echo $s_start_date; ?>">
            </div>

        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
            <label for="first_name" class="control-label">End Date:</label>
        </div>
            <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <input name="s_end_date" type="text" class="datepicker" value="<?php echo $s_end_date; ?>">
            </div>

        <?php if($type == 'safety') { ?>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
          <label for="ship_zip" class="control-label">Job#:</label>
        </div>
          <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <select data-placeholder="Select a Job..." name="sectionid" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT distinct(sectionid) FROM bid_section WHERE deleted=0 order by sectionid");
                    while($row = mysqli_fetch_array($query)) {
                        if ($sectionid == $row['sectionid']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        ?>
                        <option <?php echo $selected; ?> value='<?php echo $row['sectionid']; ?>' ><?php echo $row['sectionid']; ?></option>
                    <?php }
                  ?>
                </select>
          </div>
        <?php } ?>

        <div class="form-group pull-right double-gap-top triple-gap-bottom">
			<button type="submit" name="reporting_client" value="Submit" class="btn brand-btn mobile-block">Submit</button>
			<button type="submit" name="display_all_asset" value="Display All" class="btn brand-btn mobile-block">Display All</button>
        </div>

		<div class="clearfix"></div>

        <span class="pull-right">
            <img src="<?php echo WEBSITE_URL;?>/img/block/red.png" width="23" height="23" border="0" alt=""> Deadline Passed
            <img src="<?php echo WEBSITE_URL;?>/img/block/green.png" width="23" height="23" border="0" alt=""> Deadline Today
        </span><br><br>
        <?php

        if(isset($_POST['reporting_client'])) {
            $contactid = $_POST['contactid'];
            $category = $_POST['category'];
            $heading = $_POST['heading'];
            $status = $_POST['status'];
            $s_start_date = $_POST['s_start_date'];
            $s_end_date = $_POST['s_end_date'];

            $query_check_credentials = "SELECT m.*, ms.*  FROM manuals_staff ms, manuals m WHERE m.deleted=0 AND m.manualtypeid = ms.manualtypeid AND (ms.staffid = '$contactid' OR m.category='$category' OR m.heading='$heading')";
            if($status == 'Deadline Past') {
                $query_check_credentials = "SELECT m.*, ms.*  FROM manuals_staff ms, manuals m WHERE m.deleted=0 AND m.manualtypeid = ms.manualtypeid AND ms.done=0 AND DATE(NOW()) > DATE(m.deadline)";
            }
            if($status == 'Deadline Today') {
                $query_check_credentials = "SELECT m.*, ms.*  FROM manuals_staff ms, manuals m WHERE m.deleted=0 AND m.manualtypeid = ms.manualtypeid AND ms.done=0 AND DATE(NOW()) = DATE(m.deadline)";
            }
            if($s_start_date != '' && $s_end_date != '') {
                $query_check_credentials = "SELECT m.*, ms.*  FROM manuals_staff ms, manuals m WHERE m.deleted=0 AND m.manualtypeid = ms.manualtypeid AND m.deadline >= '$s_start_date' AND m.deadline <= '$s_end_date'";
            }
        } else if(empty($_GET['action'])) {
            $query_check_credentials = "SELECT m.*, ms.*  FROM manuals_staff ms, manuals m WHERE m.deleted=0 AND m.manualtypeid = ms.manualtypeid";
        }

        $result = mysqli_query($dbc, $query_check_credentials);

        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {
            echo "<div id='no-more-tables'><button type='submit' name='send_follow_up_email' value='Submit' class='btn brand-btn hide-me'>Send</button><table class='table table-bordered'>";
            echo '<tr class="hidden-xs hidden-sm">
                <th>Staff</th>
                <th>Email</th>
                <th>Topic (Sub Tab)</th>
                <th>Heading</th>
                <th>Sub Section Heading</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Signed Off&nbsp;&nbsp;<button type="submit" name="send_follow_up_email" value="Submit" class="btn brand-btn">Send</button></th>
                </tr>';
        } else {
            echo "<h2>No Record Found.</h2>";
        }

        while($row = mysqli_fetch_array( $result ))
        {
            $manualtypeid = $row['manualtypeid'];
            $deadline = $row['deadline'];
            $signid = $row['manualstaffid'];
            $staffid = $row['staffid'];
            $today = date('Y-m-d');
            $color = '';
            $signed_off = $row['today_date'];

            if(($today > $deadline) && ($row['done'] == 0)) {
                $color = 'style="background-color: lightcoral;"';
            }
            if(($today == $deadline) && ($row['done'] == 0)) {
                $color = 'style="background-color: lightgreen;"';
            }

            $pdf_link = '';
            $pdf_icon = '';
            if (strpos($row['form_name'], "form_field_level_risk_assessment") !== FALSE) {
                $get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fieldlevelriskid FROM form_field_level_risk_assessment WHERE manualtypeid='$manualtypeid' AND contactid='$staffid' AND DATE(today_date) = '$signed_off'"));

                $fieldlevelriskid = $get_field_level['fieldlevelriskid'];
                $pdf_link = '<a target="_blank" href="download/hazard_'.$fieldlevelriskid.'.pdf">';
                $pdf_icon = '<img src="'.WEBSITE_URL.'/img/pdf.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt=""></a>';
            } else if(file_exists('download/manual_'.$manualtypeid.'_signed_'.$signid.'_'.$signed_off.'.pdf')) {
                $pdf_link = '<a target="_blank" href="download/manual_'.$manualtypeid.'_signed_'.$signid.'_'.$signed_off.'.pdf">';
                $pdf_icon = '<img src="'.WEBSITE_URL.'/img/pdf.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt=""></a>';
			} else if(file_exists('../Manuals/download/manual_'.$manualtypeid.'_signed_'.$signid.'_'.$signed_off.'.pdf">')) {
                $pdf_link = '<a target="_blank" href="../Manuals/download/manual_'.$manualtypeid.'_signed_'.$signid.'_'.$signed_off.'.pdf">';
                $pdf_icon = '<img src="'.WEBSITE_URL.'/img/pdf.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt=""></a>';
            }

            echo "<tr>";
            echo '<td data-title="Staff">' . get_staff($dbc, $row['staffid']) . '</td>';
            echo '<td data-title="Email">' . get_email($dbc, $row['staffid']) . '</td>';
            echo '<td data-title="Topic (Sub Tab)">' . $row['category'] . '</td>';
            echo '<td data-title="Heading">' . $row['heading'] . '</td>';
            echo '<td data-title="Sub Section Heading">' . $row['sub_heading'] . '</td>';
            echo '<td data-title="Deadline">' . $row['deadline'] . '</td>';

            echo '<td data-title="Status">';
            if($row['done'] == 1) {
                echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="22" height="22" border="0" alt="">';
            }
            else if($today > $deadline) {
                echo '<img src="'.WEBSITE_URL.'/img/block/red.png" width="22" height="22" border="0" alt=""> '.$row['today_date'];
            }
            else if($today == $deadline) {
                echo '<img src="'.WEBSITE_URL.'/img/block/green.png" width="22" height="22" border="0" alt=""> '.$row['today_date'];
            }
            echo '</td>';

            if($row['done'] == 1) {
                echo '<td data-title="Signed Off">'.$pdf_link.$row['today_date'] .'&nbsp;'.$pdf_icon.'</td>';
            } else {
                echo '<td data-title="Signed Off">';
                if(get_email($dbc, $row['staffid']) != '') {
                    echo '<a href="manual_follow_up.php?staffid='.$row['staffid'].'&manual_type='.$row['manual_type'].'&manualtypeid='.$row['manualtypeid'].'&action=send_followup_email">Send</a>';
                    echo '&nbsp;&nbsp;<input name="check_send_email[]" type="checkbox" value="'.$row['staffid'].'_'.$row['manualtypeid'].'" class="form-control check_send_email" style="width:25px;"/>';
                }
                echo '</td>';
            }

            echo "</tr>";
        }

        echo '</table></div></div>';
        ?>

        
        </form>

    </div>
</div>

<?php include ('../footer.php'); ?>
