<?php
include ('../include.php');
checkAuthorised('policy_procedure');
error_reporting(0);

if (isset($_POST['submit'])) {
    $policy_procedures = implode(',',$_POST['policy_procedures']);

    if (strpos(','.$policy_procedures.',', ','.'Section #,Section Heading,Sub Section #,Sub Section Heading'.',') === false) {
        $policy_procedures = $policy_procedures.',Section #,Section Heading,Sub Section #,Sub Section Heading';
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(manualsid) AS manualsid FROM field_config_manuals"));
    if($get_field_config['manualsid'] > 0) {
        $query_update_employee = "UPDATE `field_config_manuals` SET policy_procedures = '$policy_procedures' WHERE `manualsid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config_manuals` (`policy_procedures`) VALUES ('$policy_procedures')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $manual_policy_header = filter_var(htmlentities($_POST['manual_policy_header']),FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='manual_policy_header'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$manual_policy_header' WHERE name='manual_policy_header'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('manual_policy_header', '$manual_policy_header')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $manual_policy_footer = filter_var(htmlentities($_POST['manual_policy_footer']),FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='manual_policy_footer'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$manual_policy_footer' WHERE name='manual_policy_footer'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('manual_policy_footer', '$manual_policy_footer')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $manual_policy_pro_email = filter_var($_POST['manual_policy_pro_email'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='manual_policy_pro_email'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$manual_policy_pro_email' WHERE name='manual_policy_pro_email'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('manual_policy_pro_email', '$manual_policy_pro_email')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $policy_pro_max_section = filter_var($_POST['policy_pro_max_section'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='policy_pro_max_section'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$policy_pro_max_section' WHERE name='policy_pro_max_section'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('policy_pro_max_section', '$policy_pro_max_section')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $policy_pro_max_subsection = filter_var($_POST['policy_pro_max_subsection'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='policy_pro_max_subsection'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$policy_pro_max_subsection' WHERE name='policy_pro_max_subsection'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('policy_pro_max_subsection', '$policy_pro_max_subsection')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $policy_pro_max_thirdsection = filter_var($_POST['policy_pro_max_thirdsection'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='policy_pro_max_thirdsection'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$policy_pro_max_thirdsection' WHERE name='policy_pro_max_thirdsection'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('policy_pro_max_thirdsection', '$policy_pro_max_thirdsection')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

	if(isset($_GET['maintype'])) {
		echo '<script type="text/javascript"> window.location.replace("manual.php?maintype=pp"); </script>';
	}
	else {
		echo '<script type="text/javascript"> window.location.replace("field_config_policy_procedures.php"); </script>';
	}
}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">

<?php //include ('field_config_manual.php'); ?>
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<?php
/*
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT manual FROM field_config_manuals"));
$value_config = ','.$get_field_config['manual'].',';
?>
<?php if (strpos($value_config, ','."Policies & Procedures".',') !== FALSE) { ?>
<button type="button" class="btn brand-btn mobile-block active_tab" >Policies & Procedures</button>
<?php } ?>
<?php if (strpos($value_config, ','."Operations Manual".',') !== FALSE) { ?>
<a href='field_config_operations_manual.php'><button type="button" class="btn brand-btn mobile-block" >Operations Manual</button></a>
<?php } ?>
<?php if (strpos($value_config, ','."Employee Handbook".',') !== FALSE) { ?>
<a href='field_config_emp_handbook.php'><button type="button" class="btn brand-btn mobile-block" >Employee Handbook</button></a>
<?php } ?>
<?php if (strpos($value_config, ','."How to Guide".',') !== FALSE) { ?>
<a href='field_config_guide.php'><button type="button" class="btn brand-btn mobile-block" >How to Guide</button></a>
<?php } ?>
<?php if (strpos($value_config, ','."Safety".',') !== FALSE) { ?>
<a href='field_config_safety.php'><button type="button" class="btn brand-btn mobile-block" >Safety</button></a>
<?php }
*/
?>

<?php
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT policy_procedures FROM field_config_manuals"));
$value_config = ','.$get_field_config['policy_procedures'].',';
$category = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT category FROM manuals WHERE deleted=0 AND manual_type='policy_procedures' LIMIT 1"));
$manual_category = $category['category'];
if($manual_category == '') {
   $manual_category = 0;
}
?>

<h2>Choose Fields for Policies &amp; Procedures</h2>
<?php if(isset($_GET['maintype'])): ?>
	<div class="double-gap-top double-gap-bottom"><a href="manual.php?category=<?php echo $manual_category; ?>&maintype=pp" class="btn config-btn">Back to Dashboard</a></div>
<?php else: ?>
	<div class="double-gap-top double-gap-bottom"><a href="policy_procedures.php?category=<?php echo $manual_category; ?>" class="btn config-btn">Back to Dashboard</a></div>
<?php endif; ?>
<!--<a href="manual_reporting.php?type=policy_procedures" class="btn config-btn">Back</a>
<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->
<div id='no-more-tables'>
<table border='2' cellpadding='10' class='table'>
    <tr>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Topic (Sub Tab)".',') !== FALSE) { echo " checked"; } ?> value="Topic (Sub Tab)" style="height: 20px; width: 20px;" name="policy_procedures[]">&nbsp;&nbsp;Topic (Sub Tab)
        </td>
        <td>
            <input disabled type="checkbox" <?php if (strpos($value_config, ','."Section #".',') !== FALSE) { echo " checked"; } ?> value="Section #" style="height: 20px; width: 20px;" name="policy_procedures[]">&nbsp;&nbsp;Section #
        </td>
        <td>
            <input disabled type="checkbox" <?php if (strpos($value_config, ','."Section Heading".',') !== FALSE) { echo " checked"; } ?> value="Section Heading" style="height: 20px; width: 20px;" name="policy_procedures[]">&nbsp;&nbsp;Section Heading
        </td>
        <td>
            <input disabled type="checkbox" <?php if (strpos($value_config, ','."Sub Section #".',') !== FALSE) { echo " checked"; } ?> value="Sub Section #" style="height: 20px; width: 20px;" name="policy_procedures[]">&nbsp;&nbsp;Sub Section #
        </td>
        <td>
            <input disabled type="checkbox" <?php if (strpos($value_config, ','."Sub Section Heading".',') !== FALSE) { echo " checked"; } ?> value="Sub Section Heading" style="height: 20px; width: 20px;" name="policy_procedures[]">&nbsp;&nbsp;Sub Section Heading
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Third Tier Section #".',') !== FALSE) { echo " checked"; } ?> value="Third Tier Section #" style="height: 20px; width: 20px;" name="policy_procedures[]">&nbsp;&nbsp;Third Tier Section #
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Third Tier Heading".',') !== FALSE) { echo " checked"; } ?> value="Third Tier Heading" style="height: 20px; width: 20px;" name="policy_procedures[]">&nbsp;&nbsp;Third Tier Heading
        </td>
    </tr>
    <tr>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Detail".',') !== FALSE) { echo " checked"; } ?> value="Detail" style="height: 20px; width: 20px;" name="policy_procedures[]">&nbsp;&nbsp;Detail
        </td>

        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Document".',') !== FALSE) { echo " checked"; } ?> value="Document" style="height: 20px; width: 20px;" name="policy_procedures[]">&nbsp;&nbsp;Document
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Link".',') !== FALSE) { echo " checked"; } ?> value="Link" style="height: 20px; width: 20px;" name="policy_procedures[]">&nbsp;&nbsp;Link
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Videos".',') !== FALSE) { echo " checked"; } ?> value="Videos" style="height: 20px; width: 20px;" name="policy_procedures[]">&nbsp;&nbsp;Videos
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Signature box".',') !== FALSE) { echo " checked"; } ?> value="Signature box" style="height: 20px; width: 20px;" name="policy_procedures[]">&nbsp;&nbsp;Signature box
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Comments".',') !== FALSE) { echo " checked"; } ?> value="Comments" style="height: 20px; width: 20px;" name="policy_procedures[]">&nbsp;&nbsp;Comments
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="policy_procedures[]">&nbsp;&nbsp;Staff
        </td>
    </tr>
    <tr>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Review Deadline".',') !== FALSE) { echo " checked"; } ?> value="Review Deadline" style="height: 20px; width: 20px;" name="policy_procedures[]">&nbsp;&nbsp;Review Deadline
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Status".',') !== FALSE) { echo " checked"; } ?> value="Status" style="height: 20px; width: 20px;" name="policy_procedures[]">&nbsp;&nbsp;Status
        </td>
        <td>
            <input type="checkbox" <?php if ( strpos ( $value_config, ',' . "Configure Email" . ',' ) !== FALSE) { echo " checked"; } ?> value="Configure Email" name="policy_procedures[]">&nbsp;&nbsp;Configure Email
        </td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
    </tr>
</table>
</div>

<div class="form-group">
<label for="company_name" class="col-sm-4 control-label">Send Email on Comment:</label>
<div class="col-sm-8">
  <input name="manual_policy_pro_email" value="<?php echo get_config($dbc, 'manual_policy_pro_email'); ?>" type="text" class="form-control">
</div>
</div>

<div class="form-group">
<label for="company_name" class="col-sm-4 control-label">Max Section #:<br><em>(add only digits)</em></label>
<div class="col-sm-8">
  <input name="policy_pro_max_section" value="<?php echo get_config($dbc, 'policy_pro_max_section'); ?>" type="text" class="form-control">
</div>
</div>

<div class="form-group">
<label for="company_name" class="col-sm-4 control-label">Max Sub Section #:<br><em>(add only digits)</em></label>
<div class="col-sm-8">
  <input name="policy_pro_max_subsection" value="<?php echo get_config($dbc, 'policy_pro_max_subsection'); ?>" type="text" class="form-control">
</div>
</div>

<div class="form-group">
<label for="company_name" class="col-sm-4 control-label">Max Third Tier Section #:<br><em>(add only digits)</em></label>
<div class="col-sm-8">
  <input name="policy_pro_max_thirdsection" value="<?php echo get_config($dbc, 'policy_pro_max_thirdsection'); ?>" type="text" class="form-control">
</div>
</div>

<div class="form-group">
<label for="company_name" class="col-sm-4 control-label">PDF Header:</label>
<div class="col-sm-8">
  <textarea name="manual_policy_header" class="form-control"><?= html_entity_decode(get_config($dbc, 'manual_policy_header')) ?></textarea>
</div>
</div>

<div class="form-group">
<label for="company_name" class="col-sm-4 control-label">PDF Footer:</label>
<div class="col-sm-8">
  <textarea name="manual_policy_footer" class="form-control"><?= html_entity_decode(get_config($dbc, 'manual_policy_footer')) ?></textarea>
</div>
</div>

<div class="form-group">
    <div class="col-sm-6">
		<?php if(isset($_GET['maintype'])): ?>
			<a href="manual.php?category=<?php echo $manual_category; ?>&maintype=pp" class="btn config-btn btn-lg">Back</a>
		<?php else: ?>
			<a href="policy_procedures.php?category=<?php echo $manual_category; ?>" class="btn config-btn">Back</a>
		<?php endif; ?>
		<!--<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
    </div>
    <div class="col-sm-6">
        <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
    </div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>