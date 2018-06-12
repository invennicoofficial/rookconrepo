<?php 
for($i = 0; $i < count($_POST['item_soptid']); $i++) {
    $item_contactid = $_POST['item_contactid'][$i];
    $item_soptid = $_POST['item_soptid'][$i];
    $item_quantity = $_POST['item_quantity'][$i];

    $result = mysqli_query($dbc, "SELECT * FROM `sales_order_product_details_temp` WHERE `contactid` = '$item_contactid' AND `parentsotid` = '$item_soptid'");
    if($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        if($item_quantity != $row['quantity']) {
            mysqli_query($dbc, "UPDATE `sales_order_product_details_temp` SET `quantity` = '$item_quantity', `last_updated_by` = '$last_updated_by' WHERE `contactid` = '$item_contactid' AND `parentsotid` = '$item_soptid'");
			$item = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `sales_order_product_temp` WHERE `sotid` = '".$row['parentsotid']."'"));
			$history .= 'Updated Quantity for '.$item['item_name'].' for '.get_contact($dbc, $row['contactid']).' to '.$item_quantity.'<br />';
        }
    } else if($item_quantity > 0) {
        mysqli_query($dbc, "INSERT INTO `sales_order_product_details_temp` (`contactid`, `parentsotid`, `quantity`, `last_updated_by`) VALUES ('$item_contactid', '$item_soptid', '$item_quantity', '$last_updated_by')");
        $history .= 'Added '.$item['item_name'].' for '.get_contact($dbc, $row['contactid']).', Quantity of '.$item_quantity.'<br />';
    }
}
?>