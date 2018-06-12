<?php
include ('../include.php');

error_reporting(0);

if (isset($_POST['submit'])) {
    $safety = implode(',',$_POST['safety']);

    if (strpos(','.$safety.',', ','.'Heading/Section Number,Heading/Section,Sub Heading Number,Sub Heading'.',') === false) {
        $safety = $safety.',Heading/Section Number,Heading/Section,Sub Heading Number,Sub Heading';
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(manualsid) AS manualsid FROM field_config_manuals"));
    if($get_field_config['manualsid'] > 0) {
        $query_update_employee = "UPDATE `field_config_manuals` SET safety = '$safety' WHERE `manualsid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config_manuals` (`safety`) VALUES ('$safety')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $manual_safety_email = filter_var($_POST['manual_safety_email'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='manual_safety_email'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$manual_safety_email' WHERE name='manual_safety_email'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('manual_safety_email', '$manual_safety_email')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    echo '<script type="text/javascript"> window.location.replace("field_config_safety.php"); </script>';
}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<!--<a href="safety.php?contactid=<?php //echo $_SESSION['contactid']; ?>" class="btn config-btn">Back</a>-->
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
<a href='field_config_emp_handbook.php'><button type="button" class="btn brand-btn mobile-block" >Employee Handbook</button></a>
<?php } ?>
<?php if (strpos($value_config, ','."How to Guide".',') !== FALSE) { ?>
<button type="button" class="btn brand-btn mobile-block" >How to Guide</button>
<?php } ?>
<?php if (strpos($value_config, ','."Safety".',') !== FALSE) { ?>
<a href='field_config_safety.php'><button type="button" class="btn brand-btn mobile-block active_tab" >Safety</button></a>
<?php } ?>

<?php
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT safety FROM field_config_manuals"));
$value_config = ','.$get_field_config['safety'].',';
?>

<h2>Choose Fields for How to Guide</h2>
<table border='2' cellpadding='10' class='table'>
    <tr>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="safety[]">&nbsp;&nbsp;Category
        </td>
        <td>
            <input disabled type="checkbox" <?php if (strpos($value_config, ','."Heading/Section Number".',') !== FALSE) { echo " checked"; } ?> value="Heading/Section Number" style="height: 20px; width: 20px;" name="safety[]">&nbsp;&nbsp;Heading/Section Number
        </td>
        <td>
            <input disabled type="checkbox" <?php if (strpos($value_config, ','."Heading/Section".',') !== FALSE) { echo " checked"; } ?> value="Heading/Section" style="height: 20px; width: 20px;" name="safety[]">&nbsp;&nbsp;Heading/Section
        </td>
        <td>
            <input disabled type="checkbox" <?php if (strpos($value_config, ','."Sub Heading Number".',') !== FALSE) { echo " checked"; } ?> value="Sub Heading Number" style="height: 20px; width: 20px;" name="safety[]">&nbsp;&nbsp;Sub Heading Number
        </td>
        <td>
            <input disabled type="checkbox" <?php if (strpos($value_config, ','."Sub Heading".',') !== FALSE) { echo " checked"; } ?> value="Sub Heading" style="height: 20px; width: 20px;" name="safety[]">&nbsp;&nbsp;Sub Heading
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="safety[]">&nbsp;&nbsp;Description
        </td>

        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Document".',') !== FALSE) { echo " checked"; } ?> value="Document" style="height: 20px; width: 20px;" name="safety[]">&nbsp;&nbsp;Document
        </td>
    </tr>
    <tr>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Link".',') !== FALSE) { echo " checked"; } ?> value="Link" style="height: 20px; width: 20px;" name="safety[]">&nbsp;&nbsp;Link
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Videos".',') !== FALSE) { echo " checked"; } ?> value="Videos" style="height: 20px; width: 20px;" name="safety[]">&nbsp;&nbsp;Videos
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Signature box".',') !== FALSE) { echo " checked"; } ?> value="Signature box" style="height: 20px; width: 20px;" name="safety[]">&nbsp;&nbsp;Signature box
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Comments".',') !== FALSE) { echo " checked"; } ?> value="Comments" style="height: 20px; width: 20px;" name="safety[]">&nbsp;&nbsp;Comments
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="safety[]">&nbsp;&nbsp;Staff
        </td>

        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Review Deadline".',') !== FALSE) { echo " checked"; } ?> value="Review Deadline" style="height: 20px; width: 20px;" name="safety[]">&nbsp;&nbsp;Review Deadline
        </td>
    </tr>
</table>

<div class="form-group">
<label for="company_name" class="col-sm-4 control-label"><h4>Send Email on Comment:</h4></label>
<div class="col-sm-8">
  <input name="manual_safety_email" value="<?php echo get_config($dbc, 'manual_safety_email'); ?>" type="text" class="form-control">
</div>
</div>

<div class="form-group">
    <div class="col-sm-4 clearfix">
        <!--<a href="safety.php?contactid=<?php //echo $_SESSION['contactid']; ?>" class="btn brand-btn pull-right">Back</a>-->
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