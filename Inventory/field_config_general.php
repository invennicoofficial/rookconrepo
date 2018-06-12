<?php
if (isset($_POST['submit'])) {
    $inventory_minbin_email     = $_POST['inventory_minbin_email'];
    $inventory_minbin_subject   = $_POST['inventory_minbin_subject'];
    $inventory_minbin_body      = $_POST['inventory_minbin_body'];

    if (!filter_var(trim($inventory_minbin_email), FILTER_VALIDATE_EMAIL) === false) {

    } else {
        echo '
            <script type="text/javascript">
                alert("The email address you have provided appears to be not valid. Please add a valid email address.");
                window.location.replace("field_config.php?type=general");
            </script>';
        exit();
    }

    $get_config = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT COUNT(`configid`) AS `configid` FROM `general_configuration` WHERE `name`='inventory_minbin_email'" ) );
    if ( $get_config['configid'] > 0 ) {
        $query_update_employee = "UPDATE `general_configuration` SET `value`='$inventory_minbin_email' WHERE `name`='inventory_minbin_email'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('inventory_minbin_email', '$inventory_minbin_email')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT COUNT(`configid`) AS `configid` FROM `general_configuration` WHERE `name`='inventory_minbin_subject'" ) );
    if ( $get_config['configid'] > 0 ) {
        $query_update_employee = "UPDATE `general_configuration` SET `value`='$inventory_minbin_subject' WHERE `name`='inventory_minbin_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('inventory_minbin_subject', '$inventory_minbin_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT COUNT(`configid`) AS `configid` FROM `general_configuration` WHERE `name`='inventory_minbin_body'" ) );
    if ( $get_config['configid'] > 0 ) {
        $query_update_employee = "UPDATE `general_configuration` SET `value`='$inventory_minbin_body' WHERE `name`='inventory_minbin_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('inventory_minbin_body', '$inventory_minbin_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

}
?>

<div class="gap-top">

    <div class="notice popover-examples double-gap-top triple-gap-bottom">
        <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
        <div class="col-sm-11"><span class="notice-name">NOTE:</span> Adding and configuring the email below will tell the software who to alert when an inventory item hits the minimum quantity you want.</div>
        <div class="clearfix"></div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label"><span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Add a verified email here. Once the inventory has reached the Min Bin, a notification will be sent."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span> Email Address:</label>
        <div class="col-sm-8">
            <input name="inventory_minbin_email" value="<?php echo get_config($dbc, 'inventory_minbin_email'); ?>" placeholder="Add an email address" type="text" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Min Bin Email Subject:</label>
        <div class="col-sm-8"><?php
            $inventory_minbin_subject = get_config($dbc, 'inventory_minbin_subject');
            if ( empty ($inventory_minbin_subject) ) {
                $inventory_minbin_subject = 'Inventory Min Bin Alert';
            } ?>
            <input type="text" name="inventory_minbin_subject" class="form-control" value="<?php echo $inventory_minbin_subject; ?>" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Min Bin Email Body:</label>
        <div class="col-sm-8"><?php
            $inventory_minbin_body = get_config($dbc, 'inventory_minbin_body');
            if ( empty ($inventory_minbin_body) ) {
                $inventory_minbin_body = '
                    <h1>Inventory Min Bin Alert</h1>
                    <p>Below inventory item(s) have reached the minimum quantity. Please re-order as necessary.</p>';
            } ?>
            <textarea name="inventory_minbin_body" class="form-control"><?php echo $inventory_minbin_body; ?></textarea>
        </div>
    </div>
</div>