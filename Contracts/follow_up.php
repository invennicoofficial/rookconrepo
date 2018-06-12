<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('contracts');
error_reporting(0);

if (isset($_POST['send_follow_up_email'])) {
    for($i = 0; $i < count($_POST['check_send_email']); $i++) {
        $check_send_email = explode('_', $_POST['check_send_email'][$i]);
        $staffid = $check_send_email[0];
        $contractid = $check_send_email[1];
        $contactid = $check_send_email[2];

        $email = get_email($dbc, $staffid);
        $email_body = str_replace(['[CONTRACTID]','[CONTACTID]'],[$contractid,$contactid],$_POST['email_body']);

        //Mail
		try {
			send_email([$_POST['email_sender']=>$_POST['email_name']], $email, '', '', $_POST['email_subject'], $email_body, '');
		} catch(Exception $e) { }
        //Mail
    }

    echo '<script type="text/javascript"> alert("Follow up Sent to staff"); window.location.replace("follow_up.php"); </script>';
}

if((!empty($_GET['action'])) && ($_GET['action'] == 'send_followup_email')) {
    $contractid = $_GET['contractid'];
    $contactid = $_GET['contactid'];
    $staffid = $_GET['staffid'];

    //Mail
    $to = get_email($dbc, $staffid);
    $subject = 'Follow Up : Contract Assigned to you to Complete';
    $message = "Please login through the software and click on the link below. This contract needs to be completed.<br><br>
			Contract : <a target='_blank' href='".$_SERVER['SERVER_NAME']."/Contracts/fill_contract.php?contractid=$contractid&contactid=$contactid'>Click Here</a><br>";
	try {
		send_email([get_email($dbc, $_SESSION['contactid'])=>get_contact($dbc, $_SESSION['contactid'])], $email, '', '', $subject, $message, '');
	} catch(Exception $e) { }

    //Mail
    echo '<script type="text/javascript"> alert("Follow up Sent to staff"); window.location.replace("follow_up.php"); </script>';
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
<?php include_once ('../navigation.php');
$config = mysqli_fetch_array(mysqli_query($dbc, "SELECT `contract_tabs`, `header_logo`, `header_text`, `footer_logo`, `footer_text` FROM `field_config_contracts`
	UNION SELECT 'Follow Up#*#Reporting#*#Customer', '', '', '', ''"));
$contract_tabs = explode('#*#', $config['contract_tabs']);
$tab_name = 'Follow Up'; ?>

<script type="text/javascript">
$(document).on('change', 'select[name="staffid"]', function() { submitForm(); });
$(document).on('change', 'select[name="contactid"]', function() { submitForm(); });
$(document).on('change', 'select[name="category"]', function() { submitForm(); });
$(document).on('change', 'select[name="heading"]', function() { submitForm(); });
$(document).on('change', 'select[name="status"]', function() { submitForm(); });
</script>

<div class="container triple-pad-bottom">
    <div class="row">
		<h1>Contracts: Follow Up Dashboard
			<?php if(config_visible_function($dbc, 'contracts') == 1) {
				echo '<a href="field_config.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
			} ?></h1>
		<div class="clearfix"></div><br />

		<?php foreach(array_filter($contract_tabs, function($val) { return ($val != 'Follow Up' && $val != 'Reporting'); }) as $tab) {
			if(check_subtab_persmission($dbc, 'contracts', ROLE, $tab) === TRUE) {
				echo "<a href='contracts.php?tab=$tab' class='btn brand-btn mobile-block mobile-100 '>$tab</a>";
			}
		}
		if(in_array('Follow Up',$contract_tabs) && check_subtab_persmission($dbc, 'contracts', ROLE, 'Follow Up') === TRUE) {
			echo "<a href='follow_up.php' class='btn brand-btn mobile-block mobile-100 active_tab'>Follow Up</a>";
		}
		if(in_array('Reporting',$contract_tabs) && check_subtab_persmission($dbc, 'contracts', ROLE, 'Reporting') === TRUE) {
			echo "<a href='reporting.php' class='btn brand-btn mobile-block mobile-100 '>Reporting</a>";
		} ?>

        <form name="form_sites" method="post" action="" class="form-inline" role="form">
		<div class="notice triple-gap-bottom popover-examples">
			<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
			<div class="col-sm-11"><span class="notice-name">NOTE:</span>
			Following up can be an essential element in protecting your business against staff neglect. Through this section you can schedule follow ups on all aspects, track those follow ups and hold all parties accountable to ensure the company is protected at all times.</div>
			<div class="clearfix"></div>
		</div>

        <?php
        $staffid = '';
        $contactid = '';
        $category = '';
        $heading = '';
        $status = 'Deadline Passed';
        if(!empty($_POST['staffid'])) {
            $staffid = $_POST['staffid'];
        }
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
        if (isset($_POST['display_all_asset'])) {
            $staffid = '';
            $contactid = '';
            $category = '';
            $heading = '';
            $status = '';
        } ?>

        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
          <label for="ship_country" class="control-label">Search by Staff:</label>
        </div>
          <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <select data-placeholder="Select a Staff Member..." name="staffid" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE deleted=0 AND category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY.""),MYSQLI_ASSOC));
                    foreach($query as $id) { ?>
                        <option <?php echo ($staffid == $id ? 'selected' : ''); ?> value='<?php echo $id; ?>' ><?php echo get_contact($dbc, $id); ?></option>
                    <?php }
                  ?>
                </select>

          </div>

        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
          <label for="ship_country" class="control-label">Search by Individual:</label>
        </div>
          <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <select data-placeholder="Select an Individual..." name="contactid" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT DISTINCT staff.`contactid`, `name`, `first_name`, `last_name` FROM `contracts_staff` staff LEFT JOIN `contacts` ON staff.`contactid`=`contacts`.`contactid` WHERE staff.`deleted`=0"),MYSQLI_ASSOC));
                    foreach($query as $id) {
						$name = get_client($dbc, $id);
						if($name == '') {
							$name = get_contact($dbc, $id);
						} ?>
                        <option <?php echo ($contactid == $id ? 'selected' : ''); ?> value='<?php echo $id; ?>' ><?php echo $name; ?></option>
                    <?php }
                  ?>
                </select>

          </div>

        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
          <label for="ship_zip" class="control-label">Search by Tab:</label>
        </div>
          <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <select data-placeholder="Select a Tab" name="category" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT distinct(category) FROM `contracts` WHERE deleted=0 ORDER BY `category`");
                    while($row = mysqli_fetch_array($query)) { ?>
                        <option <?php echo ($category == $row['category'] ? 'selected' : ''); ?> value='<?php echo $row['category']; ?>' ><?php echo $row['category']; ?></option>
                    <?php }
                  ?>
                </select>
          </div>

        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
          <label for="ship_zip" class="control-label">Heading:</label>
        </div>
          <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <select data-placeholder="Select a Heading" name="heading" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT distinct(heading) FROM `contracts` WHERE deleted=0 ORDER BY heading");
                    while($row = mysqli_fetch_array($query)) { ?>
                        <option <?php echo ($heading == $row['heading'] ? 'selected' : ''); ?> value='<?php echo $row['heading']; ?>' ><?php echo $row['heading']; ?></option>
                    <?php }
                  ?>
                </select>
          </div>

        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
          <label for="ship_zip" class="control-label">Status:</label>
        </div>
          <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                <select data-placeholder="Select a Status" name="status" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <option <?php if ($status=='Deadline Passed') echo 'selected="selected"';?> value="Deadline Passed">Deadline Passed</option>
                  <option <?php if ($status=='Deadline Today') echo 'selected="selected"';?> value="Deadline Today">Deadline Today</option>
                  <option <?php if ($status=='Deadline Upcoming') echo 'selected="selected"';?> value="Deadline Upcoming">Deadline Upcoming</option>
                </select>
          </div>

        <div class="form-group pull-right double-gap-top triple-gap-bottom">
			<!--<button type="submit" name="reporting_client" value="Submit" class="btn brand-btn mobile-block">Submit</button>-->
			<button type="submit" name="display_all_asset" value="Display All" class="btn brand-btn mobile-block">Display All</button>
        </div>

		<div class="clearfix"></div>

        <span class="pull-right">
            <img src="<?php echo WEBSITE_URL;?>/img/block/red.png" width="23" height="23" border="0" alt=""> Deadline Passed
            <img src="<?php echo WEBSITE_URL;?>/img/block/green.png" width="23" height="23" border="0" alt=""> Deadline Today
        </span><br><br>
        <?php

        if(isset($_POST['reporting_client'])) {
			$search = '';
			if($staffid != '') {
				$search .= " AND staff.`recipient` LIKE '%".get_email($dbc, $staffid)."%'";
			}
			if($contactid != '') {
				$search .= " AND staff.`contactid`='$contactid'";
			}
			if($category != '') {
				$search .= " AND `contracts`.`category`='$category'";
			}
			if($heading != '') {
				$search .= " AND contracts.`heading`='$heading'";
			}
            if($status == 'Deadline Passed') {
                $query_check_credentials = "SELECT * FROM `contracts_staff` staff LEFT JOIN `contracts` ON staff.`contractid`=`contracts`.`contractid` WHERE staff.`deleted`=0 AND `contracts`.deleted=0 AND staff.done=0 AND DATE(NOW()) > DATE(staff.`due_date`) $search";
            }
            else if($status == 'Deadline Today') {
                $query_check_credentials = "SELECT * FROM `contracts_staff` staff LEFT JOIN `contracts` ON staff.`contractid`=`contracts`.`contractid` WHERE staff.`deleted`=0 AND `contracts`.deleted=0 AND staff.done=0 AND DATE(NOW()) = DATE(staff.`due_date`) $search";
            }
            else if($status == 'Deadline Upcoming') {
                $query_check_credentials = "SELECT * FROM `contracts_staff` staff LEFT JOIN `contracts` ON staff.`contractid`=`contracts`.`contractid` WHERE staff.`deleted`=0 AND `contracts`.deleted=0 AND staff.done=0 AND DATE(NOW()) < DATE(staff.`due_date`) $search";
            }
            else {
                $query_check_credentials = "SELECT * FROM `contracts_staff` staff LEFT JOIN `contracts` ON staff.`contractid`=`contracts`.`contractid` WHERE staff.`deleted`=0 AND `contracts`.deleted=0 $search";
            }
        } else {
            $query_check_credentials = "SELECT * FROM `contracts_staff` staff LEFT JOIN `contracts` ON staff.`contractid`=`contracts`.`contractid` WHERE staff.`deleted`=0 AND `contracts`.deleted=0";
        }

        $result = mysqli_query($dbc, $query_check_credentials);

        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {
            echo "<div id='no-more-tables'><button type='submit' name='send_follow_up_email' value='Submit' class='btn brand-btn hide-me'>Send</button><table class='table table-bordered'>";
            echo '<tr class="hidden-xs hidden-sm">
                <th>Staff</th>
                <th>Email</th>
                <th>Tab</th>
                <th>Individual</th>
                <th>Heading</th>
                <th>Sub Section Heading</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Completed&nbsp;&nbsp;<button type="submit" name="send_follow_up_email" value="Submit" class="btn brand-btn">Send</button></th>
                </tr>';
        } else {
            echo "<h2>No Record Found.</h2>";
        }

        while($row = mysqli_fetch_array( $result ))
        {
            $deadline = $row['deadline'];
            $today = date('Y-m-d');
            $color = '';

            if(($today > $deadline) && ($row['done'] == 0)) {
                $color = 'style="background-color: lightcoral;"';
            }
            if(($today == $deadline) && ($row['done'] == 0)) {
                $color = 'style="background-color: lightgreen;"';
            }
            echo "<tr>";
			$name = get_client($dbc, $row['staffid']);
			if($name == '') {
				$name = get_contact($dbc, $row['staffid']);
			}
            echo '<td data-title="Staff">' . get_staff($dbc, $row['staffid']) . '</td>';
            echo '<td data-title="Email">' . get_email($dbc, $row['staffid']) . '</td>';
            echo '<td data-title="Tab">' . $row['category'] . '</td>';
            echo '<td data-title="Individual">' . $name . '</td>';
            echo '<td data-title="Heading">' . $row['heading'] . '</td>';
            echo '<td data-title="Sub Section Heading">' . $row['sub_heading'] . '</td>';
            echo '<td data-title="Deadline">' . $row['due_date'] . '</td>';

            echo '<td data-title="Status">';
            if(($today > $deadline) && ($row['done'] == 0)) {
                echo '<img src="'.WEBSITE_URL.'/img/block/red.png" width="22" height="22" border="0" alt=""> '.$row['today_date'];
            }
            if(($today == $deadline) && ($row['done'] == 0)) {
                echo '<img src="'.WEBSITE_URL.'/img/block/green.png" width="22" height="22" border="0" alt=""> '.$row['today_date'];
            }
            if($row['done'] == 1) {
                echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="22" height="22" border="0" alt="">';
            }
            echo '</td>';

            if($row['done'] == 1) {
				$completed = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contracts_completed` WHERE `contractstaffid`='".$row['contractstaffid']."'"));
               echo '<td data-title="Completed"><a href="download/'.$completed['contract_file'].'">'.$row['today_date'].'</a></td>';
            } else {
                echo '<td data-title="Completed"><a href="fill_contract.php?contractid='.$row['contractid'].'&assignid='.$row['contractstaffid'].'">Complete Now</a> | ';
                if(get_email($dbc, $row['staffid']) != '') {
                    echo '<a href="?staffid='.$row['staffid'].'&contractid='.$row['contractid'].'&action=send_followup_email">Send</a>';
                    echo '&nbsp;&nbsp;<input name="check_send_email[]" type="checkbox" value="'.$row['staffid'].'_'.$row['manualtypeid'].'" class="form-control check_send_email" style="width:25px;"/>';
                }
                echo '</td>';
            }

            echo "</tr>";
        }

        echo '</table></div>';
        ?>
		<div class="form-group">
			<label class="col-sm-4 control-label">Sending Email Name:</label>
			<div class="col-sm-8">
				<input type="text" name="email_name" class="form-control" value="<?= get_contact($dbc, $_SESSION['contactid']) ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Sending Email Address:</label>
			<div class="col-sm-8">
				<input type="text" name="email_sender" class="form-control" value="<?= get_email($dbc, $_SESSION['contactid']) ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Email Subject:</label>
			<div class="col-sm-8">
				<input type="text" name="email_subject" class="form-control" value="Follow Up: Contract Assigned to you to Complete">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Email Body:</label>
			<div class="col-sm-8">
				<textarea name="email_body" class="form-control">Please login through the software and click on the link below. This contract needs to be completed.<br><br>
					Contract: <a target='_blank' href='<?= $_SERVER['SERVER_NAME'] ?>/Contracts/fill_contract.php?contractid=[CONTRACTID]&contactid=[CONTACTID]'>Click Here</a><br></textarea>
			</div>
		</div>

        
        </form>

    </div>
</div>
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
</script>
<?php include ('../footer.php'); ?>
