<?php
include ('../database_connection.php');

if (isset($_POST['submit_tabs'])) {
    $manual = implode(',',$_POST['manual']);

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(manualsid) AS manualsid FROM field_config_manuals"));
    if($get_field_config['manualsid'] > 0) {
        $query_update_employee = "UPDATE `field_config_manuals` SET manual = '$manual' WHERE `manualsid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config_manuals` (`manual`) VALUES ('$manual')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    if (strpos($manual, 'Client') !== false) {
        $url = 'field_config_clients.php';
    } else if(strpos($manual, 'Contractor') !== false) {
        $url = 'field_config_contractor.php';
    } else if(strpos($manual, 'Vendor') !== false) {
        $url = 'field_config_vendor.php';
    } else if(strpos($manual, 'Customer') !== false){
        $url = 'field_config_customer.php';
    }
    echo '<script type="text/javascript"> window.location.replace("'.$url.'"); </script>';
}
?>
</head>
<body>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
<?php
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT manual FROM field_config_manuals"));
$value_config = ','.$get_field_config['manual'].',';
?>

<h2>Choose Manuals</h2>
<table border='2' cellpadding='10' class='table'>
    <tr>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Policies & Procedures".',') !== FALSE) { echo " checked"; } ?> value="Policies & Procedures" style="height: 20px; width: 20px;" name="manual[]">&nbsp;&nbsp;Policies & Procedures
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Operations Manual".',') !== FALSE) { echo " checked"; } ?> value="Operations Manual" style="height: 20px; width: 20px;" name="manual[]">&nbsp;&nbsp;Operations Manual
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Employee Handbook".',') !== FALSE) { echo " checked"; } ?> value="Employee Handbook" style="height: 20px; width: 20px;" name="manual[]">&nbsp;&nbsp;Employee Handbook
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."How to Guide".',') !== FALSE) { echo " checked"; } ?> value="How to Guide" style="height: 20px; width: 20px;" name="manual[]">&nbsp;&nbsp;How to Guide
        </td>
        <td>
            <input type="checkbox" <?php if (strpos($value_config, ','."Safety".',') !== FALSE) { echo " checked"; } ?> value="Safety" style="height: 20px; width: 20px;" name="manual[]">&nbsp;&nbsp;Safety
        </td>
    </tr>

</table>

<div class="form-group">
    <div class="col-sm-8">
        <button	type="submit" name="submit_tabs"	value="submit_tabs" class="btn config-btn pull-right">Submit</button>
    </div>
</div>

</form>
