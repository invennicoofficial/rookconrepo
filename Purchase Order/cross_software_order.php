<?php
error_reporting(0);
include_once('../include.php');
checkAuthorised('purchase_order');
if(isset($number_of_connections) && $number_of_connections > 0) {
	foreach (range(1, $number_of_connections) as $i) {
		$dbc_cross = ${'dbc_cross_'.$i}; 
		$resultt = mysqli_query($dbc_cross, $query_check_credentialss);
		$num_rowss = mysqli_num_rows($resultt);
		if($num_rowss > 0) {
			$num_of_rows = $num_of_rows+$num_rowss;
		}
	}
	echo "<h1>Order Details</h1>\n";
	$query_poproduct = "SELECT * FROM purchase_orders_product WHERE posid = '".$_GET['posid']."' AND type_category = 'inventory'";
	$resultx = mysqli_query($dbc_cross, $query_poproduct);
	$nummyrows = mysqli_num_rows($resultx);
	if($nummyrows > 0) {
		echo '<table  class="table table-bordered" style="padding:2px;margin:0;"><tr><th>Name</th><th>Quantity Ordered</th><th>Quantity Received</th><th>Total Paid</th></tr>';
		while($rowxw = mysqli_fetch_array( $resultx ))
		{
			$resultz = mysqli_query($dbc, "SELECT * FROM inventory WHERE inventoryid= '".$rowxw['inventoryid']."'");
			$name = 'No name given';
			while($rowz = mysqli_fetch_assoc($resultz)) {
				if($rowz['name'] !== '' && $rowz['name'] !== NULL) {
					$name = $rowz['name'];
				} else if($rowz['product_name'] !== '' && $rowz['product_name'] !== NULL) {
					$name = $rowz['product_name'];
				}
				if($rowxw['total_paid'] == '' || $rowxw['total_paid'] == NULL  || $rowxw['total_paid'] == 0) {
					$total_paid = 0.00;
				} else {
					$total_paid = $rowxw['total_paid'];
				}
				if($rowxw['qty_received'] == '' || $rowxw['qty_received'] == NULL || $rowxw['qty_received'] == 0) {
					$qty_rec = 0.00;
				}
				echo '<tr><td><span title="Category: '.$rowz['category'].'">'.$name.' (ID: <a href="../Inventory/add_inventory.php?inventoryid='.$rowxw['inventoryid'].'">'.$rowxw['inventoryid'].'</a>)</span></td><td>'.$rowxw['quantity'].'</td><td>'.$qty_rec.'</td><td>$'.$total_paid.'</td></tr>';
			}
		}
		echo '</table>';
	} else { echo "That's weird... It seems nothing was ordered!"; }
} else {
	echo "You currently don't have any connections set up to any other software, please talk to your software administrator if you would like to set this functionality up.";
	$number_of_connections = 0;
}
?>