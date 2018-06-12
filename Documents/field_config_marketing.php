<?php include_once('../include.php');
if(!empty($_GET['tile_name'])) {
    checkAuthorised(false,false,'documents_all_'.$_GET['tile_name']);
} else {
    checkAuthorised('documents_all');
}
include_once('document_settings.php');

if (isset($_POST['submit_marketing'])) {
    $marketing_material = implode(',',$_POST['marketing_material']);
    $marketing_material_dashboard = implode(',',$_POST['marketing_material_dashboard']);

    if (strpos(','.$marketing_material.',',','.'Marketing Material Type,Category,Heading'.',') === false) {
        $marketing_material = 'Marketing Material Type,Category,Heading,'.$marketing_material;
    }
    if (strpos(','.$marketing_material_dashboard.',',','.'Marketing Material Type,Category,Heading'.',') === false) {
        $marketing_material_dashboard = 'Marketing Material Type,Category,Heading,'.$marketing_material_dashboard;
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config"));
    if($get_field_config['fieldconfigid'] > 0) {
        $query_update_employee = "UPDATE `field_config` SET marketing_material = '$marketing_material', marketing_material_dashboard = '$marketing_material_dashboard' WHERE `fieldconfigid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config` (`marketing_material`, `marketing_material_dashboard`) VALUES ('$marketing_material', '$marketing_material_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    echo '<script type="text/javascript"> window.location.replace("?tile_name='.$tile_name.'&settings='.$_GET['settings'].'"); </script>';

}
?>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
	<h3>Fields</h3>
    <?php
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT marketing_material FROM field_config"));
    $value_config = ','.$get_field_config['marketing_material'].',';
    ?>

    <table class='table table-bordered'>
        <tr>
            <td>
                <input type="checkbox" disabled <?php if (strpos($value_config, ','."Marketing Material Type".',') !== FALSE) { echo " checked"; } ?> value="Marketing Material Type" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Marketing Material Type
            </td>
            <td>
                <input type="checkbox" disabled <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Category
            </td>
            <td>
                <input type="checkbox" disabled <?php if (strpos($value_config, ','."Heading".',') !== FALSE) { echo " checked"; } ?> value="Heading" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Heading
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Cost
            </td>

            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Description
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Quote Description
            </td>

        </tr>

        <tr>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Final Retail Price" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Final Retail Price
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { echo " checked"; } ?> value="Admin Price" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Admin Price
            </td>
            <td>
               <input type="checkbox" <?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { echo " checked"; } ?> value="Wholesale Price" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Wholesale Price
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { echo " checked"; } ?> value="Commercial Price" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Commercial Price
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Client Price".',') !== FALSE) { echo " checked"; } ?> value="Client Price" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Client Price
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Minimum Billable
            </td>
        </tr>

        <tr>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Estimated Hours
            </td>

            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Actual Hours
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."MSRP".',') !== FALSE) {
                echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;MSRP
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Marketing Material Code".',') !== FALSE) {
                echo " checked"; } ?> value="Marketing Material Code" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Marketing Material Code
            </td>

            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
                echo " checked"; } ?> value="Invoice Description" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Invoice Description
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
                echo " checked"; } ?> value="Ticket Description" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;<?= TICKET_NOUN ?> Description
            </td>
        </tr>

        <tr>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Name".',') !== FALSE) {
                echo " checked"; } ?> value="Name" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Name
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Fee".',') !== FALSE) {
                echo " checked"; } ?> value="Fee" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Fee
            </td>

            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Unit Price".',') !== FALSE) { echo " checked"; } ?> value="Unit Price" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Unit Price
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Unit Cost".',') !== FALSE) { echo " checked"; } ?> value="Unit Cost" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Unit Cost
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Rent Price".',') !== FALSE) { echo " checked"; } ?> value="Rent Price" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Rent Price
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Rental Days".',') !== FALSE) { echo " checked"; } ?> value="Rental Days" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Rental Days
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Rental Weeks".',') !== FALSE) { echo " checked"; } ?> value="Rental Weeks" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Rental Weeks
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Rental Months".',') !== FALSE) { echo " checked"; } ?> value="Rental Months" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Rental Months
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Rental Years".',') !== FALSE) { echo " checked"; } ?> value="Rental Years" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Rental Years
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Reminder/Alert".',') !== FALSE) { echo " checked"; } ?> value="Reminder/Alert" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Reminder/Alert
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Daily".',') !== FALSE) { echo " checked"; } ?> value="Daily" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Daily
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Weekly".',') !== FALSE) { echo " checked"; } ?> value="Weekly" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Weekly
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Monthly".',') !== FALSE) { echo " checked"; } ?> value="Monthly" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Monthly
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Annually".',') !== FALSE) { echo " checked"; } ?> value="Annually" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Annually
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."#Of Days".',') !== FALSE) { echo " checked"; } ?> value="#Of Days" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;#Of Days
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."#Of Hours".',') !== FALSE) { echo " checked"; } ?> value="#Of Hours" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;#Of Hours
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."#Of Kilometers".',') !== FALSE) { echo " checked"; } ?> value="#Of Kilometers" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;#Of Kilometers
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."#Of Miles".',') !== FALSE) { echo " checked"; } ?> value="#Of Miles" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;#Of Miles
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Title".',') !== FALSE) { echo " checked"; } ?> value="Title" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Title
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Uploader".',') !== FALSE) { echo " checked"; } ?> value="Uploader" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Uploader
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Link".',') !== FALSE) { echo " checked"; } ?> value="Link" style="height: 20px; width: 20px;" name="marketing_material[]">&nbsp;&nbsp;Link
            </td>
        </tr>
    </table>

    <h3>Dashboard Fields</h3>
    <?php
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT marketing_material_dashboard FROM field_config"));
    $value_config = ','.$get_field_config['marketing_material_dashboard'].',';
    ?>

    <table class='table table-bordered'>
        <tr>
            <td>
                <input type="checkbox" disabled <?php if (strpos($value_config, ','."Marketing Material Type".',') !== FALSE) { echo " checked"; } ?> value="Marketing Material Type" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Marketing Material Type
            </td>
            <td>
                <input type="checkbox" disabled <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Category
            </td>
            <td>
                <input type="checkbox" disabled <?php if (strpos($value_config, ','."Heading".',') !== FALSE) { echo " checked"; } ?> value="Heading" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Heading
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Cost
            </td>

            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Description
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Quote Description
            </td>

        </tr>

        <tr>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Final Retail Price" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Final Retail Price
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { echo " checked"; } ?> value="Admin Price" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Admin Price
            </td>
            <td>
               <input type="checkbox" <?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { echo " checked"; } ?> value="Wholesale Price" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Wholesale Price
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { echo " checked"; } ?> value="Commercial Price" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Commercial Price
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Client Price".',') !== FALSE) { echo " checked"; } ?> value="Client Price" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Client Price
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Minimum Billable
            </td>
        </tr>

        <tr>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Estimated Hours
            </td>

            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Actual Hours
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."MSRP".',') !== FALSE) {
                echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;MSRP
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Marketing Material Code".',') !== FALSE) {
                echo " checked"; } ?> value="Marketing Material Code" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Marketing Material Code
            </td>

            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
                echo " checked"; } ?> value="Invoice Description" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Invoice Description
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
                echo " checked"; } ?> value="Ticket Description" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;<?= TICKET_NOUN ?> Description
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Name".',') !== FALSE) {
                echo " checked"; } ?> value="Name" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Name
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Fee".',') !== FALSE) {
                echo " checked"; } ?> value="Fee" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Fee
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Title".',') !== FALSE) { echo " checked"; } ?> value="Title" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Title
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Uploader".',') !== FALSE) { echo " checked"; } ?> value="Uploader" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Uploader
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Link".',') !== FALSE) { echo " checked"; } ?> value="Link" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Link
            </td>
            <td>
                <input type="checkbox" <?php if (strpos($value_config, ','."Send Email".',') !== FALSE) { echo " checked"; } ?> value="Send Email" style="height: 20px; width: 20px;" name="marketing_material_dashboard[]">&nbsp;&nbsp;Send Email
            </td>
        </tr>
    </table>

	<div class="form-group">
	    <div class="col-sm-6">
	        <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Clicking this will discard your Internal Document settings."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
	        <a href="?tile_name=<?= $tab_name ?>&tab=<?= $_GET['settings'] ?>" class="btn brand-btn btn-lg">Back</a>
			<!--<a href="#" class="btn config-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
		</div>
		<div class="col-sm-6">
	        <button	type="submit" name="submit_marketing" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
			<span class="popover-examples list-inline pull-right" style="margin:15px 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click this to finalize your Internal Document settings."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
	    </div>
	</div>

</form>