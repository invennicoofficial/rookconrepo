<?php include('../include.php');
error_reporting(0);
$dbc_support = mysqli_connect('mysql.rookconnect.com', 'ffm_rook_user', 'mIghtyLion!542', 'ffm_rook_db');
//$dbc_support = mysqli_connect('localhost', 'root', '', 'local_rookconnect_db');
$user = get_config($dbc, 'company_name');
$url = WEBSITE_URL;
$user_name = $user;
$user_category = '';
if($user == 'ROOK Connect' && $_SERVER['SERVER_NAME'] == 'ffm.rookconnect.com') {
	$user = $_SESSION['contactid'];
	$user_name = get_contact($dbc, $user);
	$user_category = get_contact($dbc, $user, 'category');
} else {
	$user = mysqli_fetch_array(mysqli_query($dbc_support, "SELECT `contactid` FROM `contacts` WHERE `name`='".encryptIt($user)."'"))['contactid'];
	$user_category = 'REMOTE_'.get_contact($dbc, $_SESSION['contactid'], 'category');
	if($user_category != 'REMOTE_Staff') {
		$user_category = 'USER_CUSTOMER';
		$user = $_SESSION['contactid'];
		$name = get_contact($dbc, $user);
		$user_name = ($name == '' ? get_client($dbc, $user) : $name);
		$dbc_support = $dbc;
	}
}
$current_tab = (empty($_GET['tab']) ? ($user_category == 'USER_CUSTOMER' ? 'customer' : 'requests') : $_GET['tab']); ?>
</head>
<body>
<?php include_once ('../navigation.php'); ?>

<div class="container">
    <div class="row">
		<h1>Customer Support: <?php switch($current_tab) {
			case 'services' : echo "Services"; break;
			case 'requests' : echo "Support Requests"; break;
			case 'meetings' : echo "Agendas & Meetings"; break;
			case 'documents' : echo "Customer Documents"; break;
		} ?> Dashboard</h1>
		<h3>User: <?= $user_name ?></h3>
		<?php if(!$dbc_support) { ?>
			<div class="notice double-gap-bottom">
				<img src="<?= WEBSITE_URL; ?>/img/error.png" class="wiggle-me" style="width:3em;">
				<div style="float:right; width:calc(100% - 4em);"><span class="notice-name">Error:</span>
				The software is unable to connect to the database. No support requests can be logged. We are working to resolve this error as quickly as possible. Your patience is appreciated.</div>
				<div class="clearfix"></div>
				<!--ERROR: #<?= mysqli_connect_errno() ?> - <?= mysqli_connect_error() ?>-->
			</div>
		<?php } else { ?>
			<div class="clearfix double-gap-bottom"></div>
			<div class="tab-container mobile-100-container">
				<?php if($user_category != 'USER_CUSTOMER') { ?>
					<a href="?tab=services"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'services' ? 'active_tab' : ''); ?>">Services</button></a>
					<a href="?tab=requests"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'requests' ? 'active_tab' : ''); ?>">Support Requests</button></a>
				<?php } ?>
				<a href="?tab=documents"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'documents' ? 'active_tab' : ''); ?>">Customer Documents</button></a>
				<a href="?tab=meetings"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'meetings' ? 'active_tab' : ''); ?>">Agendas & Meetings</button></a>
				<a href="?tab=customer"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'customer' ? 'active_tab' : ''); ?>">Information Requests</button></a>
			</div>
			<div id="no-more-tables">
				<?php include($current_tab.'.php'); ?>
			</div>
		<?php } ?>
	</div>
</div>
<?php include('../footer.php');