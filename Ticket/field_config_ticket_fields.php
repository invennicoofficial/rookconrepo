<?php
/*
Dashboard
*/
include_once ('../include.php');
error_reporting(0);

$from_url = 'tickets.php';
if(!empty($_GET['from_url'])) {
	$from_url = urldecode($_GET['from_url']);
}
$tab = filter_var($_GET['tab'],FILTER_SANITIZE_STRING);
if (isset($_POST['submit'])) {
    //Ticket Fields
    $tickets  = implode(',',$_POST['tickets']);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='ticket_fields_$tab'"));
    if($get_config['configid'] > 0) {
        $result = mysqli_query($dbc, "UPDATE `general_configuration` SET value = '$tickets' WHERE name='ticket_fields_$tab'");
    } else {
        $result = mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('ticket_fields_$tab', '$tickets')");
    }
    //Ticket Fields

    //Multiple Ticket Labels
    $ticket_multiple_labels  = filter_var($_POST['ticket_multiple_labels'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='ticket_multiple_labels'"));
    if($get_config['configid'] > 0) {
        $result = mysqli_query($dbc, "UPDATE `general_configuration` SET value = '$ticket_multiple_labels' WHERE name='ticket_multiple_labels'");
    } else {
        $result = mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('ticket_multiple_labels', '$ticket_multiple_labels')");
    }
    //Multiple Ticket Labels

	//Staff Tasks
	$tasks = filter_var($_POST['ticket_'.$tab.'_staff_tasks'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='ticket_".$tab."_staff_tasks'"));
    if($get_config['configid'] > 0) {
        $result = mysqli_query($dbc, "UPDATE `general_configuration` SET value = '$tasks' WHERE name='ticket_".$tab."_staff_tasks'");
    } else {
        $result = mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('ticket_".$tab."_staff_tasks', '$tasks')");
    }
	//Staff Tasks

	//Extra Billing Notice
	$ticket_extra_billing_email = filter_var($_POST['ticket_extra_billing_email'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='ticket_extra_billing_email'"));
    if($get_config['configid'] > 0) {
        $result = mysqli_query($dbc, "UPDATE `general_configuration` SET value = '$ticket_extra_billing_email' WHERE name='ticket_extra_billing_email'");
    } else {
        $result = mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('ticket_extra_billing_email', '$ticket_extra_billing_email')");
    }
	//Extra Billing Notice

    echo '<script type="text/javascript"> window.location.replace("'.$from_url.'"); </script>';
}
$tab_label = ''; ?>
</head>
<body>
	<?php include_once('../navigation.php'); ?>
	<div class="container">
		<div class="row">
			<h1><?= TICKET_TILE ?></h1>
			<div class="pad-left gap-top double-gap-bottom">
				<a href="<?php echo $from_url; ?>" class="btn brand-btn">Back to Dashboard</a>
				<a href="field_config_tickets.php?from_url=<?= urlencode($from_url) ?>" class="btn brand-btn">Ticket Settings</a>
				<?php foreach(array_filter(explode(',',get_config($dbc, 'ticket_tabs'))) as $ticket_tab) {
					if(config_safe_str($ticket_tab) == $_GET['tab']) {
						$tab_label = $ticket_tab;
					} ?>
					<a href="field_config_ticket_fields.php?tab=<?= config_safe_str($ticket_tab) ?>&from_url=<?= urlencode($from_url) ?>" class="btn brand-btn <?= config_safe_str($ticket_tab) == $_GET['tab'] ? 'active_tab' : '' ?>"><?= $ticket_tab ?> Fields</a>
				<?php } ?>
			</div>
			<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->

			<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
				<?php $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT `tickets` FROM `field_config`"));
				$all_config = explode(',',$get_field_config['tickets']);
				$value_config = explode(',',get_config($dbc, 'ticket_fields_'.$tab)); ?>

				<div class="panel-group" id="accordion2">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<span class="popover-examples list-inline" style="margin:0 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add or remove the fields."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
									Choose Fields for <?= $tab_label ?> <?= TICKET_TILE ?><span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_field" class="panel-collapse collapse">
							<div class="panel-body">
								<?php include('field_config_field_list.php'); ?>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-4">
						<!--<a href="" class="btn brand-btn">Back</a>-->
						<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;">Back</a>
					</div>
					<div class="col-sm-8">
						<span class="popover-examples list-inline pull-right" style="margin-left:-6em;"><a data-toggle="tooltip" data-placement="top" title="" data-original-title="The entire form will submit and close if this submit button is pressed.">
							<button type="submit" name="submit" value="submit" class="btn brand-btn">Submit</button></a></span>
						<button type="submit" name="submit" value="submit" class="btn brand-btn pull-right">Submit</button>
					</div>
				</div>

			</form>
		</div>
	</div>
	<?php include_once('../footer.php'); ?>
