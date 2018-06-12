<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('treatment_charts');
error_reporting(0);

if (isset($_POST['submit_tabs'])) {
    $treatment = implode(',',$_POST['treatment']);

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configtreatmentid) AS configtreatmentid FROM config_treatment"));
    if($get_field_config['configtreatmentid'] > 0) {
        $query_update_employee = "UPDATE `config_treatment` SET treatment = '$treatment' WHERE `configtreatmentid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `config_treatment` (`treatment`) VALUES ('$treatment')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    // Front desk email
    $treatment_frontend_email = $_POST['treatment_frontend_email'];
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='treatment_frontend_email'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$treatment_frontend_email' WHERE name='treatment_frontend_email'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('treatment_frontend_email', '$treatment_frontend_email')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    // Front desk email

    // Discharge Summary Email to Patient
    $discharge_patient_email = htmlentities($_POST['discharge_patient_email']);
    $discharge_patient_email = filter_var($discharge_patient_email,FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='discharge_patient_email'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$discharge_patient_email' WHERE name='discharge_patient_email'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('discharge_patient_email', '$discharge_patient_email')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    // Discharge Summary Email to Patient

    if (strpos($treatment, 'Assessment') !== false) {
        $url = 'config_treatment.php';
    } else if(strpos($treatment, 'Treatment') !== false) {
        $url = 'config_treatment.php';
    } else if(strpos($treatment, 'Exercise Plan') !== false) {
        $url = 'config_treatment.php';
    } else if(strpos($treatment, 'Treatment Plan') !== false){
        $url = 'config_treatment.php';
    }
    echo '<script type="text/javascript"> window.location.replace("'.$url.'"); </script>';
}
?>
<script type="text/javascript">
$(document).ready(function() {
});
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<?php
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT treatment FROM config_treatment"));
$value_config = ','.$get_field_config['treatment'].',';
?>

<h2>Choose Tabs</h2>
<table border='2' cellpadding='10' class='table'>
    <tr>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Assessment".',') !== FALSE) { echo " checked"; } ?> value="Assessment" style="height: 20px; width: 20px;" name="treatment[]">&nbsp;&nbsp;Assessment
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Treatment".',') !== FALSE) { echo " checked"; } ?> value="Treatment" style="height: 20px; width: 20px;" name="treatment[]">&nbsp;&nbsp;Treatment
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Exercise Plan".',') !== FALSE) { echo " checked"; } ?> value="Exercise Plan" style="height: 20px; width: 20px;" name="treatment[]">&nbsp;&nbsp;Exercise Plan
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Treatment Plan".',') !== FALSE) { echo " checked"; } ?> value="Treatment Plan" style="height: 20px; width: 20px;" name="treatment[]">&nbsp;&nbsp;Treatment Plan
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Discharge".',') !== FALSE) { echo " checked"; } ?> value="Discharge" style="height: 20px; width: 20px;" name="treatment[]">&nbsp;&nbsp;Discharge
        </td>
    </tr>

</table>

<?php
    $treatment_frontend_email = get_config($dbc, 'treatment_frontend_email');
?>
<div class="form-group">
    <label for="first_name" class="col-sm-4 control-label text-right">Email:</label>
    <div class="col-sm-8">
        <input name="treatment_frontend_email" type="text" class="form-control" value="<?php echo $treatment_frontend_email; ?>"></p>
    </div>
</div>

<?php
    $discharge_patient_email = get_config($dbc, 'discharge_patient_email');
?>
<div class="form-group">
    <label for="first_name" class="col-sm-4 control-label text-right">Discharge Summary Email to Patient:<br>Use Below tags <br> [Patient Name] </label>
    <div class="col-sm-8">
        <textarea name="discharge_patient_email" rows="5" cols="50" class="form-control"><?php echo $discharge_patient_email; ?></textarea>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-4 clearfix">
        <a href="treatment.php" class="btn config-btn pull-right">Back</a>
    </div>
    <div class="col-sm-8">
        <button	type="submit" name="submit_tabs"	value="submit_tabs" class="btn config-btn pull-right">Submit</button>
    </div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>