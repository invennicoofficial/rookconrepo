<?php if(!isset($_GET['mobile_view'])) {
	include_once ('../include.php');
} else {
	include_once ('../database_connection.php');
	include_once ('../global.php');
	include_once ('../function.php');
	include_once ('../output_functions.php');
	include_once ('../email.php');
	include_once ('../user_font_settings.php');
}
$rookconnect = get_software_name();
error_reporting(0); ?>
</head>
<script type="text/javascript" src="profile.js"></script>
<?php include_once ('edit_contact_access.php') ?>
<body>
<?php if(!isset($_GET['mobile_view'])) { include_once ('../navigation.php'); }

if (isset($_POST['contactid'])) {
	if($_GET['edit_contact'] == 'true') {
		echo "<!--"; // Just hide the missing field warnings that will show up for submitted values
		if($_POST['contactid'] != '') {
			$contacts_prior = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid` = '{$_POST['contactid']}'"));
		}
		$user_name = filter_var($_POST['user_name'],FILTER_SANITIZE_STRING);
		$password = filter_var(encryptIt($_POST['password']),FILTER_SANITIZE_STRING);

		$contactid = $_SESSION['contactid'];
		$query_update_inventory = "UPDATE `contacts` SET `user_name` = '$user_name', `password` = '$password', `password_update`=0, `password_date`=CURRENT_TIMESTAMP WHERE `contactid` = '$contactid'";

		$result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
		$_GET['id'] = $contactid;

		$url = 'Updated';

		// Record the history of the change
		$contacts_after = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid` = '$contactid'"));
		$user = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
		$change_log = '';
		foreach($contacts_after as $name => $value) {
			if(str_replace(['0000-00-00','0'], '', $contacts_prior[$name]) != str_replace(['0000-00-00','0'], '', $value)) {
				if($name == 'password') {
					$value = '************';
				}
				$change_log .= "$name set from '{$contacts_prior[$name]}' to '$value'.\n";
			}
		}
		$change_log = filter_var($change_log,FILTER_SANITIZE_STRING);
		$query = "INSERT INTO contacts_history (`updated_by`, `description`, `contactid`) VALUES ('$user', '$change_log\nSet from the Profile tile.', '$contactid')";
		mysqli_query($dbc, $query);
		echo '-->'; 
	} ?>
	<?php if(!empty($_POST['subtab'])) {
		$action_page = 'my_profile.php?edit_contact='.$_GET['edit_contact'];
		if($_POST['subtab'] == 'certificates') {
			$action_page = 'my_certificate.php?edit_contact='.$_GET['edit_contact'];
		} else if($_POST['subtab'] == 'goals') {
			$action_page = 'gao_goal.php?edit_contact='.$_GET['edit_contact'];
		} else if($_POST['subtab'] == 'daysheet') {
			$action_page = 'daysheet.php';
		}?>
		<form action="<?php echo $action_page; ?>" method="post" id="change_page">
			<input type="hidden" name="subtab" value="<?php echo $_POST['subtab']; ?>">
		</form>
		<script type="text/javascript"> document.getElementById('change_page').submit(); </script>
	<?php }
}

$contactid = $_SESSION['contactid'];
$get_contact =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	contacts WHERE	contactid='$contactid'"));

$user_name = $get_contact['user_name'];
$password = decryptIt($get_contact['password']);
$role = $get_contact['role'];

$subtab = 'software_access';
if (!empty($_POST['subtab'])) {
    $subtab = $_POST['subtab'];
}
?>
<div class="container">
	<div class="row">
		<!-- <div id="no-more-tables" class="main-screen contacts-list"> -->
		<div class="main-screen contacts-list">
            <!-- Tile Header -->
            <div class="tile-header">
                <div class="col-xs-12 col-sm-4">
                    <h1>
                        <span class="pull-left" style="margin-top: -5px;"><a href="my_profile.php" class="default-color">My Profile</a></span>
                        <span class="clearfix"></span>
                    </h1>
                </div>
                <div class="col-xs-12 col-sm-8 text-right settings-block">
            		<form action="?<?= $_GET['edit_contact'] != 'true' ? 'edit_contact=true' : '' ?>" method="post" id="edit_contact">
            			<button name="subtab" value="<?= $subtab ?>" onclick="$('#edit_contact').submit();" class="btn brand-btn pull-right"><?= $_GET['edit_contact'] != 'true' ? 'Edit' : 'View' ?></button>
            		</form>
                    <a href="<?= WEBSITE_URL ?>/Daysheet/daysheet.php" class="btn brand-btn pull-right">Planner</a>
                </div>
                <div class="clearfix"></div>
            </div><!-- .tile-header -->

			<div class="tile-container">

				<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

	                <!-- Sidebar -->
	                <div class="collapsible tile-sidebar set-section-height">
	                	<?php include('tile_sidebar.php'); ?>
	                </div><!-- .tile-sidebar -->

					<!-- Main Screen -->
	                <div class="fill-to-gap tile-content set-section-height" style="padding: 0;">
						<div class="main-screen-details">
							<h4>Software Access</h4>
							<?php
							$value_config = ',Role,User Name,Password,';
							$edit_config = ',User Name,Password,'; ?>
							<?php
							include ('../Contacts/add_contacts_basic_info.php');
							?>
						<button type='submit' name='contactid' value='<?php echo $contactid; ?>' class="btn brand-btn pull-right">Submit</button>
						<a href='<?php echo WEBSITE_URL; ?>/home.php' class="btn brand-btn pull-right">Back</a>
						</div>
					</div>
					<div class="clearfix"></div>

				</form>
			</div>
		</div>
	</div>
</div>
<?php include_once ('../footer.php'); ?>