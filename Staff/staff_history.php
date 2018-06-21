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
checkAuthorised('staff');
error_reporting(0);
if(isset($_POST['contactid'])) {
    if(!empty($_POST['subtab'])) {
        $action_page = 'staff_edit.php?contactid='.$_GET['contactid'];
        if($_POST['subtab'] == 'certificates') {
            $action_page = 'certificate.php?contactid='.$_GET['contactid'];
        } else if($_POST['subtab'] == 'history') {
            $action_page = 'staff_history.php?contactid='.$_GET['contactid'];
        } else if($_POST['subtab'] == 'reminders') {
            $action_page = 'staff_reminder.php?contactid='.$_GET['contactid'];
        } else if($_POST['subtab'] == 'schedule') {
            $action_page = 'staff_schedule.php?contactid='.$_GET['contactid'];
        }?>
        <form action="<?php echo $action_page; ?>" method="post" id="change_page">
            <input type="hidden" name="subtab" value="<?php echo $_POST['subtab']; ?>">
        </form>
        <script type="text/javascript"> document.getElementById('change_page').submit(); </script>
    <?php }
}
?>
</head>
<script type="text/javascript" src="staff.js"></script>
<body>
<?php if(!isset($_GET['mobile_view'])) { include_once ('../navigation.php'); }
$subtab = 'history';
$id = isset($_GET['contactid']) ? $_GET['contactid'] : (isset($_GET['id']) ? $_GET['id'] : $_SESSION['contactid']);
$field_tabs = ','.get_config($dbc, 'staff_field_subtabs').',';
$subtab = 'history';
// if (!empty($_POST['subtab'])) {
//     $subtab = $_POST['subtab'];
// }
$from_url = 'staff.php?tab=active';
if (!empty($_GET['from'])) {
    $from_url = $_GET['from'];
}
if(!empty($_GET['from_url'])) {
    $from_url = $_GET['from_url'];
}
if(!empty($_POST['from_url'])) {
    $from_url = $_POST['from_url'];
}
?>
<div id="staff_div" class="container">
    <?php if(!isset($_GET['mobile_view'])) { include('mobile_view.php'); } ?>
    <div class="row hide-titles-mob">
        <!-- <div id="no-more-tables" class="main-screen contacts-list"> -->
        <div class="main-screen contacts-list">
            <!-- Tile Header -->
            <div class="tile-header standard-header">
                <div class="col-sm-12">
                    <h1>
                        <span class="pull-left" style="margin-top: -5px;"><a href="<?= $from_url ?>" class="default-color">Staff</a>: <?= $contactid > 0 ? get_contact($dbc, $contactid) : 'Add New' ?></span>
                        <?php if ( config_visible_function ( $dbc, 'staff' ) == 1 ) { ?>
                            <div class="pull-right gap-left top-settings">
                                <a href="staff.php?settings=dashboard" class="mobile-block pull-right "><img title="Tile Settings" src="<?= WEBSITE_URL; ?>/img/icons/settings-4.png" class="settings-classic wiggle-me" width="30"></a>
                                <span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                            </div><?php
                        } ?>
                        <?php if(vuaed_visible_function($dbc, 'staff') > 0) { ?>
                            <a href="staff_edit.php" class="btn brand-btn pull-right">New Staff</a>
                        <?php } ?>
                        <span class="clearfix"></span>
                        <div class="alert alert-danger text-sm text-center" style="display:none;"></div>
                        <div class="alert alert-success text-sm text-center" style="display:none;"></div>
                    </h1>
                </div>
                <div class="clearfix"></div>
            </div><!-- .tile-header -->

            <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

                <!-- Sidebar -->
                <div class="standard-collapsible tile-sidebar set-section-height">
                    <?php include('tile_sidebar.php'); ?>
                </div><!-- .tile-sidebar -->

                <div class="has-main-screen scale-to-fill tile-content set-section-height" style="padding: 0; overflow-y: auto;">
                    <div class="main-screen-details main-screen override-main-screen <?= $subtab != 'id_card' ? 'standard-body' : '' ?>" style="height: inherit;">
                        <div class='standard-body-title'>
                            <h3>History</h3>
                        </div>
                        <div class='standard-body-dashboard-content pad-top pad-left pad-right'>
                        	<h4>History</h4>
							<table class="table table-bordered">
								<tr class="hidden-xs hidden-sm">
									<th>Edit Date</th>
									<th>Edited By</th>
									<th>Fields Set</th>
								</tr>
								<?php $query = "SELECT * FROM `contacts_history` WHERE `contactid`='$id'";
								$results = mysqli_query($dbc, $query);
								while($row = mysqli_fetch_array($results)):
									$name = $row['updated_by'];
									$staff = mysqli_query($dbc, "SELECT `contactid` FROM `contacts` WHERE CONCAT(`first_name`, ' ', `last_name`)='$name'");
									if($staff_member = mysqli_fetch_array($staff)) {
										$name = get_contact($dbc, $staff_member['contactid']);
									}
									?>
									<tr>
										<td data-title="Edit Date"><?php echo $row['updated_at']; ?></td>
										<td data-title="Edited By"><?php echo $name; ?></td>
										<td data-title="Fields Set"><?php echo str_replace("\n","<br />\n",$row['description']); ?></td>
									</tr>
								<?php endwhile; ?>
							</table>
						</div>
					</div>
                </div>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
</div>
<?php include_once ('../footer.php'); ?>