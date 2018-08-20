<?php
if (isset($_POST['add_unbooked'])) {
    $ticket_filters = implode(',', $_POST['ticket_filters']);
    mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`) SELECT 'unbooked_ticket_filters' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name`='unbooked_ticket_filters') num WHERE num.rows=0");
    mysqli_query($dbc, "UPDATE `general_configuration` SET `value`='".$ticket_filters."' WHERE `name`='unbooked_ticket_filters'");

    $appt_filters = implode(',', $_POST['appt_filters']);
    mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`) SELECT 'unbooked_appt_filters' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name`='unbooked_appt_filters') num WHERE num.rows=0");
    mysqli_query($dbc, "UPDATE `general_configuration` SET `value`='".$appt_filters."' WHERE `name`='unbooked_appt_filters'");
}
?>
<h3>Security Settings</h3>
<div class="panel-group" id="accordion2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_dispatch">Dispatch Calendar - Allowed Security Levels/<?= TICKET_NOUN ?> Types</a>
            </h4>
        </div>
        <div id="collapse_dispatch" class="panel-collapse collapse">
            <div class="panel-body">
            </div>
        </div>
    </div>
</div>

<div class="form-group clearfix">
	<div class="col-sm-6">
		<a href="calendars.php" class="btn brand-btn btn-lg">Back</a>
	</div>
	<div class="col-sm-6">
		<button	type="submit" name="add_unbooked" value="active_unbooked" class="btn brand-btn btn-lg pull-right">Submit</button>
	</div>
</div>