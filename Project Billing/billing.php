<?php $subtab = (empty($_GET['subtab']) ? '' : $_GET['subtab']); ?>

<?php if($subtab == 'project') {
	include('project_view_billing.php');
} else {
	include('billing_list.php');
} ?>