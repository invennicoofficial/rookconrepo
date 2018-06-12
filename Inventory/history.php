<?php include("../include.php");
checkAuthorised('inventory');
error_reporting(0); ?>
<style>
    table { width:100%; }
        table th, table td { padding:5px; }
</style>
</head>
<body>
<div class="container"><div class="row">
<h1>Inventory History</h1>
<?php $inventoryid = $_GET['inventoryid'];
$result = mysqli_query($dbc, "SELECT * FROM `inventory_change_log` WHERE `inventoryid`='$inventoryid' ORDER BY `date_time` DESC");
if(mysqli_num_rows($result) > 0 && $_GET['detail'] == 'cost') {
	while($row = mysqli_fetch_array($result)) {
		echo '<table border="1">';
            echo '
                <tr>
                    <th width="20%">Old Cost</th>
                    <th width="20%">New Cost</th>
                </tr>';
            echo '
                <tr>
                    <td>'. $row['old_cost'] .'</td>
                    <td>'. $row['new_cost'] .'</td>
                </tr>';
        echo '</table><br />';
	}
} else if(mysqli_num_rows($result) > 0 && $_GET['detail'] == 'qty') {
	while($row = mysqli_fetch_array($result)) {
		echo '<table border="1">';
            echo '
                <tr>
                    <th width="20%">Old Inventory</th>
                    <th width="20%">New Inventory</th>
                    <th width="20%">Changed Quantity</th>
                </tr>';
            echo '
                <tr>
                    <td>'. $row['old_inventory'] .'</td>
                    <td>'. $row['new_inventory'] .'</td>
                    <td>'. $row['changed_quantity'] .'</td>
                </tr>';
        echo '</table><br />';
	}
} else if(mysqli_num_rows($result) > 0 && $_GET['detail'] == 'comment') {
	while($row = mysqli_fetch_array($result)) {
		echo $row['change_comment'].'<br />';
	}
} else if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_array($result)) {
		echo '<b>' . ($row['location_of_change'] == 'Remote Software Transfer' ? $row['contactid'] : get_contact($dbc, $row['contactid']))." at ".date('Y-m-d g:i A', strtotime($row['date_time'])).", ".$row['change_comment']." From ".$row['location_of_change']."</b><br />\n";
        echo '<table border="1">';
            echo '
                <tr>
                    <th width="20%">Old Inventory</th>
                    <th width="20%">New Inventory</th>
                    <th width="20%">Changed Quantity</th>
                    <th width="20%">Old Cost</th>
                    <th width="20%">New Cost</th>
                </tr>';
            echo '
                <tr>
                    <td>'. $row['old_inventory'] .'</td>
                    <td>'. $row['new_inventory'] .'</td>
                    <td>'. $row['changed_quantity'] .'</td>
                    <td>'. $row['old_cost'] .'</td>
                    <td>'. $row['new_cost'] .'</td>
                </tr>';
        echo '</table><br />';
	}
} else {
	echo "<h2>No history has been recorded.</h2>";
} ?>
</div></div>
</body>