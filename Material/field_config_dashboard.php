<?php
if (isset($_POST['submit'])) {
    $material_dashboard = implode(',',$_POST['material_dashboard']);
    if (strpos(','.$material_dashboard.',',','.'Category,Material Name'.',') === false) {
        $material_dashboard = 'Category,Material Name,'.$material_dashboard;
    }
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config"));
    if($get_field_config['fieldconfigid'] > 0) {
        $query_update_employee = "UPDATE `field_config` SET material_dashboard = '$material_dashboard' WHERE `fieldconfigid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config` (`material_dashboard`) VALUES ('$material_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
}
?>

<div class="gap-top">
    <?php
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT material_dashboard FROM field_config"));
    $value_config = ','.$get_field_config['material_dashboard'].',';
    ?>

    <table cellpadding='10' class='table table-bordered'>
        <tr>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Code".',') !== FALSE) { echo " checked"; } ?> value="Code" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Code
            </td>
            <td>
                <input type="checkbox" disabled <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Category
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Sub-Category".',') !== FALSE) { echo " checked"; } ?> value="Sub-Category" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Sub-Category
            </td>
            <td>
                <input type="checkbox" disabled <?php if (strpos($value_config, ','."Material Name".',') !== FALSE) { echo " checked"; } ?> value="Material Name" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Material Name
            </td>

            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Description
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Quote Description
            </td>

            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Vendor".',') !== FALSE) { echo " checked"; } ?> value="Vendor" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Vendor
            </td>

            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Width".',') !== FALSE) { echo " checked"; } ?> value="Width" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Width
            </td>

            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Length".',') !== FALSE) { echo " checked"; } ?> value="Length" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Length
            </td>
        </tr>

        <tr>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Units".',') !== FALSE) { echo " checked"; } ?> value="Units" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Units
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Unit Weight".',') !== FALSE) { echo " checked"; } ?> value="Unit Weight" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Unit Weight
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Weight Per Feet".',') !== FALSE) { echo " checked"; } ?> value="Weight Per Feet" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Weight Per Foot
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Quantity".',') !== FALSE) { echo " checked"; } ?> value="Quantity" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Quantity
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Price".',') !== FALSE) { echo " checked"; } ?> value="Price" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Price
            </td>
        </tr>
    </table>
</div>