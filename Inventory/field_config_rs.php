<?php
if (isset($_POST['submit'])) {
    $inventory_dashboard = implode(',',$_POST['inventory_dashboard']);

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configinvid) AS configinvid FROM field_config_inventory WHERE tab='receive_shipment' AND accordion='receive_shipment'"));
    if($get_field_config['configinvid'] > 0) {
        $query_update_employee = "UPDATE `field_config_inventory` SET `receive_shipment` = '$inventory_dashboard' WHERE tab='receive_shipment' AND accordion='receive_shipment'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config_inventory` (`tab`, `accordion`, `receive_shipment`) VALUES ('receive_shipment', 'receive_shipment', '$inventory_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
}
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT receive_shipment FROM field_config_inventory WHERE tab='receive_shipment' AND accordion='receive_shipment'"));
$inventory_dashboard_config = ','.$get_field_config['receive_shipment'].',';

?>

<div class="gap-top">
    <table class="table table-bordered">
        <tr>
            <td>
                <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Inventory".',') !== FALSE) { echo " checked"; } ?> value="Inventory" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Inventory
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Quantity".',') !== FALSE) { echo " checked"; } ?> value="Quantity" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Quantity
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Sell Price".',') !== FALSE) { echo " checked"; } ?> value="Sell Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Sell Price
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Final Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Final Retail Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Final Retail Price
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Wholesale Price".',') !== FALSE) { echo " checked"; } ?> value="Wholesale Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Wholesale Price
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Commercial Price".',') !== FALSE) { echo " checked"; } ?> value="Commercial Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Commercial Price
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Client Price".',') !== FALSE) { echo " checked"; } ?> value="Client Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Client Price
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Preferred Price".',') !== FALSE) { echo " checked"; } ?> value="Preferred Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Preferred Price
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Admin Price".',') !== FALSE) { echo " checked"; } ?> value="Admin Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Admin Price
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Web Price".',') !== FALSE) { echo " checked"; } ?> value="Web Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Web Price
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Commission Price".',') !== FALSE) { echo " checked"; } ?> value="Commission Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Commission Price
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."MSRP".',') !== FALSE) { echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;MSRP
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Unit Price".',') !== FALSE) { echo " checked"; } ?> value="Unit Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Unit Price
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Unit Cost".',') !== FALSE) { echo " checked"; } ?> value="Unit Cost" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Unit Cost
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Purchase Order Price".',') !== FALSE) { echo " checked"; } ?> value="Purchase Order Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Purchase Order Price
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Sales Order Price".',') !== FALSE) { echo " checked"; } ?> value="Sales Order Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;<?= SALES_ORDER_NOUN ?> Price
            </td>
        </tr>
    </table>
</div>