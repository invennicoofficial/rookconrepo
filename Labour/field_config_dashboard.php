<?php
/*
Labour Field Config - Dashboard Fields
*/
include ('../include.php');
checkAuthorised('labour');
error_reporting(0);

if (isset($_POST['submit'])) {
    $labour_dashboard = implode(',',$_POST['labour_dashboard']);

    if (strpos(','.$labour_dashboard.',',','.'Labour Type'.',') === false) {
        $labour_dashboard = 'Labour Type,Heading,'.$labour_dashboard;
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config"));
    if($get_field_config['fieldconfigid'] > 0) {
        $query_update_employee = "UPDATE `field_config` SET labour_dashboard = '$labour_dashboard' WHERE `fieldconfigid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config` (`labour_dashboard`) VALUES ('$labour_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    echo '<script type="text/javascript"> window.location.replace("?settings='.$_GET['settings'].'"); </script>';

}
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT labour_dashboard FROM field_config"));
$value_config = ','.$get_field_config['labour_dashboard'].',';
?>

<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
    <div class="form-group gap-top">
        <table cellpadding='10' class='table table-bordered'>
            <tr>
                <td>
                    <input type="checkbox" disabled checked value="Labour Type" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Labour Type
                </td>
                <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Category
                </td>
                <td>
                    <input type="checkbox" disabled checked value="Heading" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Heading
                </td>
                <!-- <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Cost
                </td> -->
                <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Description
                </td>
                <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Quote Description
                </td>
                <!-- <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."Daily Rate".',') !== FALSE) { echo " checked"; } ?> value="Daily Rate" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Daily Rate
                </td> -->
                <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."WCB".',') !== FALSE) { echo " checked"; } ?> value="WCB" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;WCB
                </td>
            </tr>

            <tr>
                <td>
                   <input type="checkbox" <?php if (strpos($value_config, ','."Benefits".',') !== FALSE) { echo " checked"; } ?> value="Benefits" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Benefits
                </td>
                <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."Salary".',') !== FALSE) { echo " checked"; } ?> value="Salary" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Salary
                </td>
                <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."Bonus".',') !== FALSE) { echo " checked"; } ?> value="Bonus" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Bonus
                </td>
                <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Minimum Billable
                </td>
                <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Estimated Hours
                </td>
                <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Actual Hours
                </td>
            </tr>

            <tr>
                <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."MSRP".',') !== FALSE) {
                    echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;MSRP
                </td>
                <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."Labour Code".',') !== FALSE) {
                    echo " checked"; } ?> value="Labour Code" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Labour Code
                </td>

                <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
                    echo " checked"; } ?> value="Invoice Description" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Invoice Description
                </td>
                <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
                    echo " checked"; } ?> value="Ticket Description" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;<?= TICKET_NOUN ?> Description
                </td>
                <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."Name".',') !== FALSE) {
                    echo " checked"; } ?> value="Name" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Name
                </td>
                <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."Rate Card".',') !== FALSE) {
                    echo " checked"; } ?> value="Rate Card" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;<span class="popover-examples"><a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="The user will need View Access to the Rate Card tile to view the Rate Card section, and Edit Access to the Rate Card tile to edit the Rate Card section."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span> Rate Card
                </td>
                <!-- <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."Hourly Rate".',') !== FALSE) {
                    echo " checked"; } ?> value="Hourly Rate" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Hourly Rate
                </td> -->
            </tr>

            <tr>
                <td>
                    <input type="checkbox" <?php if (strpos($value_config, ','."Rate Card Price".',') !== FALSE) {
                    echo " checked"; } ?> value="Rate Card Price" style="height: 20px; width: 20px;" name="labour_dashboard[]">&nbsp;&nbsp;Rate Card Price
                </td>
            </tr>
        </table>
    </div>
    <div class="pull-right gap-top gap-right gap-bottom">
        <a href="?" class="btn brand-btn">Cancel</a>
        <button type="submit" id="submit" name="submit" value="Submit" class="btn brand-btn">Submit</button>
    </div>
</form>