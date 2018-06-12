<?php
include ('../include.php');
error_reporting(0);

if (isset($_POST['submit'])) {
    $emp_handbook = implode(',',$_POST['emp_handbook']);

    if (strpos(','.$emp_handbook.',', ','.'Heading/Section Number,Heading/Section,Sub Heading Number,Sub Heading'.',') === false) {
        $emp_handbook = $emp_handbook.',Heading/Section Number,Heading/Section,Sub Heading Number,Sub Heading';
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(manualsid) AS manualsid FROM field_config_manuals"));
    if($get_field_config['manualsid'] > 0) {
        $query_update_employee = "UPDATE `field_config_manuals` SET emp_handbook = '$emp_handbook' WHERE `manualsid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config_manuals` (`emp_handbook`) VALUES ('$emp_handbook')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $manual_emp_handbook_email = filter_var($_POST['manual_emp_handbook_email'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='manual_emp_handbook_email'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$manual_emp_handbook_email' WHERE name='manual_emp_handbook_email'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('manual_emp_handbook_email', '$manual_emp_handbook_email')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    echo '<script type="text/javascript"> window.location.replace("field_config_emp_handbook.php"); </script>';
}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<!--<a href="emp_handbook.php?contactid=<?php //echo $_SESSION['contactid']; ?>" class="btn config-btn">Back</a>-->
<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>

<?php include ('field_config_manual.php'); ?>
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<?php
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT manual FROM field_config_manuals"));
$value_config = ','.$get_field_config['manual'].',';
?>
<?php if (strpos($value_config, ','."Policy & Procedures".',') !== FALSE) { ?>
<a href='field_config_policy_procedures.php'><button type="button" class="btn brand-btn mobile-block" >Policy & Procedures</button></a>
<?php } ?>
<?php if (strpos($value_config, ','."Operations Manual".',') !== FALSE) { ?>
<a href='field_config_operations_manual.php'><button type="button" class="btn brand-btn mobile-block" >Operations Manual</button></a>
<?php } ?>
<?php if (strpos($value_config, ','."Employee Handbook".',') !== FALSE) { ?>
<button type="button" class="btn brand-btn mobile-block active_tab" >Employee Handbook</button>
<?php } ?>
<?php if (strpos($value_config, ','."How to Guide".',') !== FALSE) { ?>
<a href='field_config_guide.php'><button type="button" class="btn brand-btn mobile-block" >How to Guide</button></a>
<?php } ?>
<?php if (strpos($value_config, ','."Safety".',') !== FALSE) { ?>
<a href='field_config_safety.php'><button type="button" class="btn brand-btn mobile-block" >Safety</button></a>
<?php } ?>

<?php
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT emp_handbook FROM field_config_manuals"));
$value_config = ','.$get_field_config['emp_handbook'].',';
?>

<h2>Choose Fields for Employee Handbook</h2>
<table border='2' cellpadding='10' class='table'>
    <tr>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="emp_handbook[]">&nbsp;&nbsp;Category
        </td>
        <td>
            <input disabled type="checkbox" <?php if (strpos($value_config, ','."Heading/Section Number".',') !== FALSE) { echo " checked"; } ?> value="Heading/Section Number" style="height: 20px; width: 20px;" name="emp_handbook[]">&nbsp;&nbsp;Heading/Section Number
        </td>
        <td>
            <input disabled type="checkbox" <?php if (strpos($value_config, ','."Heading/Section".',') !== FALSE) { echo " checked"; } ?> value="Heading/Section" style="height: 20px; width: 20px;" name="emp_handbook[]">&nbsp;&nbsp;Heading/Section
        </td>
        <td>
            <input disabled type="checkbox" <?php if (strpos($value_config, ','."Sub Heading Number".',') !== FALSE) { echo " checked"; } ?> value="Sub Heading Number" style="height: 20px; width: 20px;" name="emp_handbook[]">&nbsp;&nbsp;Sub Heading Number
        </td>
        <td>
            <input disabled type="checkbox" <?php if (strpos($value_config, ','."Sub Heading".',') !== FALSE) { echo " checked"; } ?> value="Sub Heading" style="height: 20px; width: 20px;" name="emp_handbook[]">&nbsp;&nbsp;Sub Heading
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="emp_handbook[]">&nbsp;&nbsp;Description
        </td>

        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Document".',') !== FALSE) { echo " checked"; } ?> value="Document" style="height: 20px; width: 20px;" name="emp_handbook[]">&nbsp;&nbsp;Document
        </td>
    </tr>
    <tr>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Link".',') !== FALSE) { echo " checked"; } ?> value="Link" style="height: 20px; width: 20px;" name="emp_handbook[]">&nbsp;&nbsp;Link
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Videos".',') !== FALSE) { echo " checked"; } ?> value="Videos" style="height: 20px; width: 20px;" name="emp_handbook[]">&nbsp;&nbsp;Videos
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Signature box".',') !== FALSE) { echo " checked"; } ?> value="Signature box" style="height: 20px; width: 20px;" name="emp_handbook[]">&nbsp;&nbsp;Signature box
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Comments".',') !== FALSE) { echo " checked"; } ?> value="Comments" style="height: 20px; width: 20px;" name="emp_handbook[]">&nbsp;&nbsp;Comments
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="emp_handbook[]">&nbsp;&nbsp;Staff
        </td>

        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Review Deadline".',') !== FALSE) { echo " checked"; } ?> value="Review Deadline" style="height: 20px; width: 20px;" name="emp_handbook[]">&nbsp;&nbsp;Review Deadline
        </td>
    </tr>
</table>

<div class="form-group">
<label for="company_name" class="col-sm-4 control-label"><h4>Send Email on Comment:</h4></label>
<div class="col-sm-8">
  <input name="manual_emp_handbook_email" value="<?php echo get_config($dbc, 'manual_emp_handbook_email'); ?>" type="text" class="form-control">
</div>
</div>

<div class="form-group">
    <div class="col-sm-4 clearfix">
        <!--<a href="emp_handbook.php?contactid=<?php //echo $_SESSION['contactid']; ?>" class="btn brand-btn pull-right">Back</a>-->
		<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>
    </div>
    <div class="col-sm-8">
        <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
    </div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>