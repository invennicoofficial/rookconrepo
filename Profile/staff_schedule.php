<?php error_reporting(0);
include_once ('../include.php');
$rookconnect = get_software_name();
if(!empty($_POST['subtab']) && $_POST['subtab'] != 'schedule') {
	$action_page = 'my_profile.php?edit_contact='.$_GET['edit_contact'];
	if($_POST['subtab'] == 'certificates') {
		$action_page = 'my_certificate.php?edit_contact='.$_GET['edit_contact'];
	}
	if($_POST['subtab'] == 'daysheet') {
		$action_page = 'daysheet.php?edit_contact='.$_GET['edit_contact'];
	}
	if($_POST['subtab'] == 'schedule') {
		$action_page = 'staff_schedule.php';
	}

	?>
	<form action="<?php echo $action_page; ?>" method="post" id="change_page">
		<input type="hidden" name="subtab" value="<?php echo $_POST['subtab']; ?>">
	</form>
	<script type="text/javascript"> document.getElementById('change_page').submit(); </script>
<?php }

include_once('../Staff/staff_schedule_include.php');
?>