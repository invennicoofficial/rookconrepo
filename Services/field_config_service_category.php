<?php
/*
Dashboard
*/
include ('../database_connection.php');

if (isset($_POST['submit_tabs'])) {
    $service_category = implode(',',$_POST['service_category']);
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigserviceid) AS fieldconfigserviceid FROM field_config_services WHERE category='services'"));
    if($get_field_config['fieldconfigserviceid'] > 0) {
        $query_update_employee = "UPDATE `field_config_services` SET services = '$service_category' WHERE `category` = 'services'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config_services` (`category`, `services`) VALUES ('services', '$service_category')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $cat = $_POST['hidden_service_category'];
    /*
    if (strpos($service_category, 'Client') !== false) {
        $url = 'field_config_clients.php';
    } else if(strpos($service_category, 'Contractor') !== false) {
        $url = 'field_config_contractor.php';
    } else if(strpos($service_category, 'Vendor') !== false) {
        $url = 'field_config_vendor.php';
    } else if(strpos($service_category, 'Customer') !== false){
        $url = 'field_config_customer.php';
    }
    */
    echo '<script type="text/javascript"> window.location.replace("field_config_services.php?category='.$cat.'"); </script>';
}
?>
</head>
<body>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<?php
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT services FROM field_config_services WHERE category='services'"));
$value_config = ','.$get_field_config['services'].',';
?>
<input type="hidden" name="hidden_service_category" value="<?php echo $_GET['category'];?>">
<h2>Choose Category</h2>
<table border='2' cellpadding='10' class='table'>
    <tr>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Cancellation Fee".',') !== FALSE) { echo " checked"; } ?> value="Cancellation Fee" style="height: 20px; width: 20px;" name="service_category[]">&nbsp;&nbsp;Cancellation Fee
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Physical Therapy".',') !== FALSE) { echo " checked"; } ?> value="Physical Therapy" style="height: 20px; width: 20px;" name="service_category[]">&nbsp;&nbsp;Physical Therapy
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Chiropractic".',') !== FALSE) { echo " checked"; } ?> value="Chiropractic" style="height: 20px; width: 20px;" name="service_category[]">&nbsp;&nbsp;Chiropractic
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Massage Therapy".',') !== FALSE) { echo " checked"; } ?> value="Massage Therapy" style="height: 20px; width: 20px;" name="service_category[]">&nbsp;&nbsp;Massage Therapy
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Speech Language Pathologists".',') !== FALSE) { echo " checked"; } ?> value="Speech Language Pathologists" style="height: 20px; width: 20px;" name="service_category[]">&nbsp;&nbsp;Speech Language Pathologists
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Occupational Therapists".',') !== FALSE) { echo " checked"; } ?> value="Occupational Therapists" style="height: 20px; width: 20px;" name="service_category[]">&nbsp;&nbsp;Occupational Therapists
        </td>
    </tr>
    <tr>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Supportive Roommate".',') !== FALSE) { echo " checked"; } ?> value="Supportive Roommate" style="height: 20px; width: 20px;" name="service_category[]">&nbsp;&nbsp;Supportive Roommate
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Psychologists".',') !== FALSE) { echo " checked"; } ?> value="Psychologists" style="height: 20px; width: 20px;" name="service_category[]">&nbsp;&nbsp;Psychologists
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Support Agencies".',') !== FALSE) { echo " checked"; } ?> value="Support Agencies" style="height: 20px; width: 20px;" name="service_category[]">&nbsp;&nbsp;Support Agencies
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Naturopaths".',') !== FALSE) { echo " checked"; } ?> value="Naturopaths" style="height: 20px; width: 20px;" name="service_category[]">&nbsp;&nbsp;Naturopaths
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Social Worker".',') !== FALSE) { echo " checked"; } ?> value="Social Worker" style="height: 20px; width: 20px;" name="service_category[]">&nbsp;&nbsp;Social Worker
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Special/Extra".',') !== FALSE) { echo " checked"; } ?> value="Special/Extra" style="height: 20px; width: 20px;" name="service_category[]">&nbsp;&nbsp;Special/Extra
        </td>
    </tr>
    <tr>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."General".',') !== FALSE) { echo " checked"; } ?> value="General" style="height: 20px; width: 20px;" name="service_category[]">&nbsp;&nbsp;General
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Promotion".',') !== FALSE) { echo " checked"; } ?> value="Promotion" style="height: 20px; width: 20px;" name="service_category[]">&nbsp;&nbsp;Promotion
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Package".',') !== FALSE) { echo " checked"; } ?> value="Package" style="height: 20px; width: 20px;" name="service_category[]">&nbsp;&nbsp;Package
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Referral".',') !== FALSE) { echo " checked"; } ?> value="Referral" style="height: 20px; width: 20px;" name="service_category[]">&nbsp;&nbsp;Referral
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Membership".',') !== FALSE) { echo " checked"; } ?> value="Membership" style="height: 20px; width: 20px;" name="service_category[]">&nbsp;&nbsp;Membership
        </td>
    </tr>

</table>

<div class="form-group">
    <div class="col-sm-8">
        <button	type="submit" name="submit_tabs"	value="submit_tabs" class="btn config-btn pull-right">Submit</button>
    </div>
</div>

</form>
