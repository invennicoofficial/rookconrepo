<script>
    function submitForm(thisForm) {
        if (!$('input[name="reporting_client"]').length) {
            var input = $("<input>")
                        .attr("type", "hidden")
                        .attr("name", "reporting_client").val("1");
            $('[name=form_sites]').append($(input));
        }

        $('[name=form_sites]').submit();
    }
	$(document).on('change', 'select[name="contactid"]', function() { submitForm(); });
	$(document).on('change', 'select[name="category"]', function() { submitForm(); });
	$(document).on('change', 'select[name="heading"]', function() { submitForm(); });
	$(document).on('change', 'select[name="status"]', function() { submitForm(); });
	$(document).on('change', 'select[name="sectionid"]', function() { submitForm(); });
</script>
<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised();
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

        $subject = $_POST['email_subject'];

        //Mail
        send_email([$_POST['email_sender'] => $_POST['email_sender_name']], $email, '', '', $subject, $email_body, '');
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
<?php
if($_GET['from_manual']) {
$active_pp = '';
$active_om = '';
$active_eh = '';
$active_htg = '';
$active_sm = '';
if(empty($_GET['maintype'])) {
    $_GET['maintype'] = 'pp';
}
if($_GET['maintype'] == 'pp') {
	$active_pp = 'active_tab';
}
if($_GET['maintype'] == 'om') {
	$active_om = 'active_tab';
}
if($_GET['maintype'] == 'eh') {
	$active_eh = 'active_tab';
}
if($_GET['maintype'] == 'htg') {
	$active_htg = 'active_tab';
}
if($_GET['maintype'] == 'sm') {
    $active_sm = 'active_tab';
}
?>
<div class="container triple-pad-bottom">
	<div class='iframe_holder' style='display:none;'>
		<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
		<span class='iframe_title' style='color:white; font-weight:bold; position: relative; left: 20px; font-size: 30px;'></span>
		<iframe id="iframe_instead_of_window" style='width: 100%;' height="1000px; border:0;" src=""></iframe>
    </div>
    <div class="row">
		<div class="col-md-12">
        <?php
            $type = $_GET['type'];
            if($type == 'policy_procedures') {
                $column = 'Policies & Procedures';
            }
            if($type == 'operations_manual') {
                $column = 'Operations Manual';
            }
            if($type == 'emp_handbook') {
                $column = 'Employee Handbook';
            }
            if($type == 'guide') {
                $column = 'How to Guide';
            }
            if($type == 'safety') {
                $column = 'Safety';
            }
            if($type == 'safety_manual') {
                $column = 'Safety Manual';
            }

            if(isset($_GET['maintype'])) {
                echo '<h2>'.$column.' Reporting</h2>';
            }
        ?>

		<div class="clearfix"></div><br />


        <span class="popover-examples list-inline" style="margin:10px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Policies & Procedures allows Admin users to create new policies and procedures and assign them to staff."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
        <a href="<?php echo addOrUpdateCurrentUrlParam(['maintype','type'],['pp','policy_procedures']); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $active_pp; ?>" type="button">Policies & Procedures</button></a>
        <span class="popover-examples list-inline" style="margin:10px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Pertains to the operations of the company. Allows Admin users to create new operation manuals or procedures and assign them to staff."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
        <a href="<?php echo addOrUpdateCurrentUrlParam(['maintype','type'],['om','operations_manual']); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $active_om; ?>" type="button">Operations Manual</button></a>
        <span class="popover-examples list-inline" style="margin:10px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Contains anything the company deems to be part of their requirements for staff."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
        <a href="<?php echo addOrUpdateCurrentUrlParam(['maintype','type'],['eh','emp_handbook']); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $active_eh; ?>" type="button">Employee Handbook</button></a>
        <!-- <span class="popover-examples list-inline" style="margin:10px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Contains the How To Guide for the software."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span> -->
        <!-- <a href="<?php echo addOrUpdateUrlParam('maintype','htg'); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $active_htg; ?>" type="button">How To Guide</button></a> -->
        </div>
    </div>
</div>
<?php } ?>
<?php
if(isset($_GET['maintype'])) {
	$maintype=$_GET['maintype'];
}
?>
<div class="container">
	<div class="row">

        <form name="form_sites" method="post" action="" class="form-inline" role="form">
        <?php

            $type = $_GET['type'];
            if($type == 'policy_procedures') {
                $column = 'Policies & Procedures';
                $notes = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT note FROM notes_setting WHERE subtab='pp_reporting'"));
                $note = $notes['note'];
            }
            if($type == 'operations_manual') {
                $column = 'Operations Manual';
            }
            if($type == 'emp_handbook') {
                $column = 'Employee Handbook';
            }
            if($type == 'guide') {
                $column = 'How to Guide';
            }
            if($type == 'safety') {
                $column = 'Safety';
            }
            if($type == 'safety_manual') {
                $column = 'Safety Manual';
            }

            if(!isset($_GET['maintype'])) {
                echo '<h2>'.$column.' Reporting</h2>';
            }

            /*
            if(config_visible_function($dbc, 'promotion') == 1) {
                echo '<a href="field_config_policy_procedures.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
            }
            */

            $category = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT category FROM manuals WHERE deleted=0 AND manual_type='$type' LIMIT 1"));
            $manual_category = $category['category'];
            if($manual_category == '') {
               $manual_category = 0;
            }
        ?>
		<div class="tab-container mobile-100-container double-gap-top triple-gap-bottom">
			<div class="pull-left tab">
				<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="See all manuals."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
				if ( $type=='operations_manual' ) {
					if ( check_subtab_persmission($dbc, 'ops_manual', ROLE, 'dashboard') === TRUE ) { ?>
						<?php if(isset($_GET['from_manual'])): ?>
							<a href="manual.php?category=<?php echo $manual_category; ?>&from_manual=1&maintype=<?php echo $maintype;?>"><button type="button" class="mobile-100 btn brand-btn mobile-block">Manuals</button></a>
						<?php else: ?>
							<a href="<?php echo $type; ?>.php?category=<?php echo $manual_category; ?>"><button type="button" class="mobile-100 btn brand-btn mobile-block">Manuals</button></a>
						<?php endif; ?>
					<?php
					} else { ?>
						<button type="button" class="btn disabled-btn mobile-block mobile-100">Manuals</button><?php
					}

                } elseif ( $type=='policy_procedures' ) {
					if ( check_subtab_persmission($dbc, 'policy_procedure', ROLE, 'manuals') === TRUE ) { ?>
						<?php if(isset($_GET['from_manual'])): ?>
							<a href="manual.php?category=<?php echo $manual_category; ?>&from_manual=1&maintype=<?php echo $maintype;?>"><button type="button" class="mobile-100 btn brand-btn mobile-block">Manuals</button></a>
						<?php else: ?>
							<a href="<?php echo $type; ?>.php?category=<?php echo $manual_category; ?>"><button type="button" class="mobile-100 btn brand-btn mobile-block">Manuals</button></a>
						<?php endif; ?>
					<?php
					} else { ?>
						<button type="button" class="btn disabled-btn mobile-block mobile-100">Manuals</button><?php
					}

                } elseif ( $type=='emp_handbook' ) {
					if ( check_subtab_persmission($dbc, 'emp_handbook', ROLE, 'manuals') === TRUE ) { ?>
						<?php if(isset($_GET['from_manual'])): ?>
							<a href="manual.php?category=<?php echo $manual_category; ?>&from_manual=1&maintype=<?php echo $maintype;?>"><button type="button" class="mobile-100 btn brand-btn mobile-block">Manuals</button></a>
						<?php else: ?>
							<a href="<?php echo $type; ?>.php?category=<?php echo $manual_category; ?>"><button type="button" class="mobile-100 btn brand-btn mobile-block">Manuals</button></a>
						<?php endif; ?>
					<?php
					} else { ?>
						<button type="button" class="btn disabled-btn mobile-block mobile-100">Manuals</button><?php
					}

                } elseif ( $type=='safety_manual' ) {
					if ( check_subtab_persmission($dbc, 'safety_manual', ROLE, 'manuals') === TRUE ) { ?>
						<?php if(isset($_GET['from_manual'])): ?>
							<a href="manual.php?category=<?php echo $manual_category; ?>&from_manual=1&maintype=<?php echo $maintype;?>"><button type="button" class="mobile-100 btn brand-btn mobile-block">Manuals</button></a>
						<?php else: ?>
							<a href="<?php echo $type; ?>.php?category=<?php echo $manual_category; ?>"><button type="button" class="mobile-100 btn brand-btn mobile-block">Manuals</button></a>
						<?php endif; ?>
					<?php
					} else { ?>
						<button type="button" class="btn disabled-btn mobile-block mobile-100">Manuals</button><?php
					}

                } else { ?>
					<?php if(isset($_GET['from_manual'])): ?>
						<a href="manual.php?category=<?php echo $manual_category; ?>&from_manual=1&maintype=<?php echo $maintype;?>"><button type="button" class="mobile-100 btn brand-btn mobile-block">Manuals</button></a>
					<?php else: ?>
						<a href="<?php echo $type; ?>.php?category=<?php echo $manual_category; ?>"><button type="button" class="mobile-100 btn brand-btn mobile-block">Manuals</button></a>
					<?php endif; ?>
				<?php
				} ?>
			</div>

			<div class="pull-left tab">
				<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="See manuals that require your attention."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
				if ( $type=='operations_manual' ) {
					if ( check_subtab_persmission($dbc, 'ops_manual', ROLE, 'followup') === TRUE ) { ?>
						<?php if(isset($_GET['from_manual'])):?>
							<a href="manual_follow_up.php?type=<?php echo $type; ?>&from_manual=1&maintype=<?php echo $maintype;?>"><button type="button" class="btn mobile-100 brand-btn mobile-block">Follow Up</button></a>
						<?php else: ?>
							<a href="manual_follow_up.php?type=<?php echo $type; ?>"><button type="button" class="btn mobile-100 brand-btn mobile-block">Follow Up</button></a>
						<?php endif; ?>
					<?php
					} else { ?>
						<button type="button" class="btn disabled-btn mobile-block mobile-100">Follow Up</button><?php
					}

                } elseif ( $type=='policy_procedures' ) {
					if ( check_subtab_persmission($dbc, 'policy_procedure', ROLE, 'follow_up') === TRUE ) { ?>
						<?php if(isset($_GET['from_manual'])):?>
							<a href="manual_follow_up.php?type=<?php echo $type; ?>&from_manual=1&maintype=<?php echo $maintype;?>"><button type="button" class="btn mobile-100 brand-btn mobile-block">Follow Up</button></a>
						<?php else: ?>
							<a href="manual_follow_up.php?type=<?php echo $type; ?>"><button type="button" class="btn mobile-100 brand-btn mobile-block">Follow Up</button></a>
						<?php endif; ?>
					<?php
					} else { ?>
						<button type="button" class="btn disabled-btn mobile-block mobile-100">Follow Up</button><?php
					}

                } elseif ( $type=='emp_handbook' ) {
					if ( check_subtab_persmission($dbc, 'emp_handbook', ROLE, 'follow_up') === TRUE ) { ?>
						<?php if(isset($_GET['from_manual'])):?>
							<a href="manual_follow_up.php?type=<?php echo $type; ?>&from_manual=1&maintype=<?php echo $maintype;?>"><button type="button" class="btn mobile-100 brand-btn mobile-block">Follow Up</button></a>
						<?php else: ?>
							<a href="manual_follow_up.php?type=<?php echo $type; ?>"><button type="button" class="btn mobile-100 brand-btn mobile-block">Follow Up</button></a>
						<?php endif; ?>
					<?php
					} else { ?>
						<button type="button" class="btn disabled-btn mobile-block mobile-100">Follow Up</button><?php
					}

                } elseif ( $type=='safety_manual' ) {
					if ( check_subtab_persmission($dbc, 'safety_manual', ROLE, 'follow_up') === TRUE ) { ?>
						<?php if(isset($_GET['from_manual'])):?>
							<a href="manual_follow_up.php?type=<?php echo $type; ?>&from_manual=1&maintype=<?php echo $maintype;?>"><button type="button" class="btn mobile-100 brand-btn mobile-block">Follow Up</button></a>
						<?php else: ?>
							<a href="manual_follow_up.php?type=<?php echo $type; ?>"><button type="button" class="btn mobile-100 brand-btn mobile-block">Follow Up</button></a>
						<?php endif; ?>
					<?php
					} else { ?>
						<button type="button" class="btn disabled-btn mobile-block mobile-100">Follow Up</button><?php
					}

                } else { ?>
					<?php if(isset($_GET['from_manual'])): ?>
						<a href="manual_follow_up.php?type=<?php echo $type; ?>&from_manual=1&maintype=<?php echo $maintype;?>"><button type="button" class="btn mobile-100 brand-btn mobile-block">Follow Up</button></a>
					<?php else: ?>
						<a href="manual_follow_up.php?type=<?php echo $type; ?>"><button type="button" class="btn mobile-100 brand-btn mobile-block">Follow Up</button></a>
					<?php endif; ?>
				<?php
				} ?>
			</div>

			<div class="pull-left tab">
				<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="See reports of the manuals."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
				if ( $type=='operations_manual' ) {
					if ( check_subtab_persmission($dbc, 'ops_manual', ROLE, 'reporting') === TRUE ) { ?>
						<?php if(isset($_GET['from_manual'])):?>
							<a href="manual_reporting.php?type=<?php echo $type; ?>&from_manual=1&maintype=<?php echo $maintype;?>"><button type="button" class="btn mobile-100 brand-btn mobile-block active_tab">Reporting</button></a>
						<?php else: ?>
							<a href="manual_reporting.php?type=<?php echo $type; ?>"><button type="button" class="btn mobile-100 brand-btn mobile-block active_tab">Reporting</button></a>
						<?php endif; ?>
					<?php
					} else { ?>
						<button type="button" class="btn disabled-btn mobile-block mobile-100">Reporting</button><?php
					}

                } elseif ( $type=='policy_procedures' ) {
					if ( check_subtab_persmission($dbc, 'policy_procedure', ROLE, 'reporting') === TRUE ) { ?>
						<?php if(isset($_GET['from_manual'])):?>
							<a href="manual_reporting.php?type=<?php echo $type; ?>&from_manual=1&maintype=<?php echo $maintype;?>"><button type="button" class="btn mobile-100 brand-btn mobile-block active_tab">Reporting</button></a>
						<?php else: ?>
							<a href="manual_reporting.php?type=<?php echo $type; ?>"><button type="button" class="btn mobile-100 brand-btn mobile-block active_tab">Reporting</button></a>
						<?php endif; ?>
					<?php
					} else { ?>
						<button type="button" class="btn disabled-btn mobile-block mobile-100">Reporting</button><?php
					}

                } elseif ( $type=='emp_handbook' ) {
					if ( check_subtab_persmission($dbc, 'emp_handbook', ROLE, 'reporting') === TRUE ) { ?>
						<?php if(isset($_GET['from_manual'])):?>
							<a href="manual_reporting.php?type=<?php echo $type; ?>&from_manual=1&maintype=<?php echo $maintype;?>"><button type="button" class="btn mobile-100 brand-btn mobile-block active_tab">Reporting</button></a>
						<?php else: ?>
							<a href="manual_reporting.php?type=<?php echo $type; ?>"><button type="button" class="btn mobile-100 brand-btn mobile-block active_tab">Reporting</button></a>
						<?php endif; ?>
					<?php
					} else { ?>
						<button type="button" class="btn disabled-btn mobile-block mobile-100">Reporting</button><?php
					}

                } elseif ( $type=='safety_manual' ) {
					if ( check_subtab_persmission($dbc, 'safety_manual', ROLE, 'reporting') === TRUE ) { ?>
						<?php if(isset($_GET['from_manual'])):?>
							<a href="manual_reporting.php?type=<?php echo $type; ?>&from_manual=1&maintype=<?php echo $maintype;?>"><button type="button" class="btn mobile-100 brand-btn mobile-block active_tab">Reporting</button></a>
						<?php else: ?>
							<a href="manual_reporting.php?type=<?php echo $type; ?>"><button type="button" class="btn mobile-100 brand-btn mobile-block active_tab">Reporting</button></a>
						<?php endif; ?>
					<?php
					} else { ?>
						<button type="button" class="btn disabled-btn mobile-block mobile-100">Reporting</button><?php
					}

                } else { ?>
					<?php if(isset($_GET['from_manual'])):?>
						<a href="manual_reporting.php?type=<?php echo $type; ?>&from_manual=1&maintype=<?php echo $maintype;?>"><button type="button" class="btn mobile-100 brand-btn mobile-block active_tab">Reporting</button></a>
					<?php else: ?>
						<a href="manual_reporting.php?type=<?php echo $type; ?>"><button type="button" class="btn mobile-100 brand-btn mobile-block active_tab">Reporting</button></a>
					<?php endif; ?>
					<?php
				} ?>
			</div>

			<div class="clearfix"></div>
		</div><?php
            
        if ( !empty($note) ) { ?>
            <div class="notice double-gap-bottom popover-examples">
                <div class="col-sm-1 notice-icon"><img src="../img/info.png" class="wiggle-me" width="25"></div>
                <div class="col-sm-11">
                    <span class="notice-name">NOTE:</span>
                    <?= $note; ?>
                </div>
                <div class="clearfix"></div>
            </div><?php
        }
        
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
                <input name="s_start_date" type="text" class="datepicker" value="<?php echo $s_start_date; ?>" onchange="submitForm()">
            </div>

        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
            <label for="first_name" class="control-label">End Date:</label>
        </div>
            <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <input name="s_end_date" type="text" class="datepicker" value="<?php echo $s_end_date; ?>" onchange="submitForm()">
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
			<!--<button type="submit" name="reporting_client" value="Submit" class="btn brand-btn mobile-block">Submit</button>-->
			<button type="submit" name="display_all_asset" value="Display All" class="btn brand-btn mobile-block">Display All</button>
        </div>

		<div class="clearfix"></div>

        <span class="pull-right">
            <img src="<?php echo WEBSITE_URL;?>/img/block/red.png" width="23" height="23" border="0" alt=""> Deadline Passed
            <img src="<?php echo WEBSITE_URL;?>/img/block/green.png" width="23" height="23" border="0" alt=""> Deadline Today
        </span><br><br>
		<div class="form-group">
			<label class="col-sm-4">Email Sender Name:</label>
			<div class="col-sm-8">
				<input type="text" name="email_sender_name" class="form-control" value="<?= get_contact($dbc, $_SESSION['contactid']) ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4">Email Sender Address:</label>
			<div class="col-sm-8">
				<input type="text" name="email_sender" class="form-control" value="<?= get_email($dbc, $_SESSION['contactid']) ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4">Email Subject:</label>
			<div class="col-sm-8">
				<input type="text" name="email_subject" class="form-control" value="Follow Up: Manual Assigned to you for Review">
			</div>
		</div>
        <?php

        if(isset($_POST['reporting_client'])) {
            $contactid = $_POST['contactid'];
            $category = $_POST['category'];
            $heading = $_POST['heading'];
            $status = $_POST['status'];
            $s_start_date = $_POST['s_start_date'];
            $s_end_date = $_POST['s_end_date'];

            $query_check_credentials = "SELECT m.*, ms.*  FROM manuals_staff ms, manuals m WHERE m.deleted=0 AND m.manual_type = '$type' AND m.manualtypeid = ms.manualtypeid AND (ms.staffid = '$contactid' OR m.category='$category' OR m.heading='$heading')";
            if($status == 'Deadline Past') {
                $query_check_credentials = "SELECT m.*, ms.*  FROM manuals_staff ms, manuals m WHERE m.deleted=0 AND m.manual_type = '$type' AND m.manualtypeid = ms.manualtypeid AND ms.done=0 AND DATE(NOW()) > DATE(m.deadline)";
            }
            if($status == 'Deadline Today') {
                $query_check_credentials = "SELECT m.*, ms.*  FROM manuals_staff ms, manuals m WHERE m.deleted=0 AND m.manual_type = '$type' AND m.manualtypeid = ms.manualtypeid AND ms.done=0 AND DATE(NOW()) = DATE(m.deadline)";
            }
            if($s_start_date != '' && $s_end_date != '') {
                $query_check_credentials = "SELECT m.*, ms.*  FROM manuals_staff ms, manuals m WHERE m.deleted=0 AND m.manual_type = '$type' AND m.manualtypeid = ms.manualtypeid AND m.deadline >= '$s_start_date' AND m.deadline <= '$s_end_date'";
            }
        } else if(empty($_GET['action'])) {
            $query_check_credentials = "SELECT m.*, ms.*  FROM manuals_staff ms, manuals m WHERE m.deleted=0 AND m.manual_type = '$type' AND m.manualtypeid = ms.manualtypeid";
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
