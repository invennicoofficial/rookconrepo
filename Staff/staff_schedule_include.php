<?php
include_once ('../Calendar/calendar_functions_inc.php');
?>
</head>
<script type="text/javascript">
$(document).ready(function() {
	$('.config_value').change(set_config);
	$('[name=staff_schedule_lock_date]').change(setLock);
});
function setLock() {
	var lock_date = $('[name=staff_schedule_lock_date]').val();
	$.ajax({
		url: '../Staff/staff_ajax.php?action=set_lock',
		method: 'POST',
		data: {
			date: lock_date
		}
	});
}
function set_config() {
	var name = this.name;
	var value = this.value;
	$.ajax({
		url: '../Staff/staff_ajax.php?action=set_config',
		method: 'POST',
		data: {
			name: name,
			value: value
		}
	});
}
// function overlayIFrameSlider(url) {
// 	$('.iframe_overlay .iframe').css('position', 'relative');
// 	$('.iframe_overlay .iframe').css('left', '100%');
// 	$('.iframe_overlay .iframe').css('float', 'right');
// 	$('.iframe_overlay .iframe').css('width', '50%');
// 	$('.iframe_overlay .iframe iframe').height(0);
// 	$('.iframe_overlay .iframe .iframe_loading').show();
// 	$('.iframe_overlay .iframe iframe').prop('src',url);
// 	$('.iframe_overlay .iframe').height($('.tile-header').height() + $('.tile-container').height());
// 	$('.iframe_overlay').show();
// 	$('.iframe_overlay .iframe iframe').load(function() {
// 		$(this).contents().find('html').css('padding-left', '1em');
// 		$('.iframe_overlay').click(function() {
// 			if(confirm('Closing out of this window will discard your changes. Are you sure you want to close the window?')) {
// 				$('.iframe_overlay').hide();
// 				$('.iframe_overlay .iframe iframe').off('load').attr('src', '');
// 				$('html').prop('onclick',null).off('click');
// 				window.location.reload();
// 			}
// 		});
// 		$('.iframe_overlay iframe').height($('.main-screen').height());
// 		$('.iframe_overlay').height($('.tile-header').height() + $('.tile-container').height());
// 		$('.iframe_overlay .iframe .iframe_loading').hide();
// 		$(this).off('load').load(function() {
// 			$('.iframe_overlay').hide();
// 			$(this).off('load').attr('src', '');
// 			window.location.reload();
// 		});
// 	});
// 	$('.iframe_overlay .iframe').animate({left: 0},500);
// }
function exportShifts(contactid) {
    $.ajax({
        type: 'GET',
        url: '../Calendar/calendar_ajax_all.php?fill=export_shifts&contactid='+contactid,
        dataType: 'html',
        success: function(response) {
            window.open(response, '_blank');
        }
    });
}
</script>
<script type="text/javascript" src="../Staff/staff.js"></script>
<body>
<?php if(!isset($_GET['mobile_view'])) { include_once ('../navigation.php'); }

if(FOLDER_NAME == 'staff') {
	$contact_id = $_GET['contactid'];
} else {
	$contact_id = $_SESSION['contactid'];
}
$subtab = 'schedule';
// if (isset($_POST['subtab'])) {
// 	$subtab = $_POST['subtab'];
// }

if(isset($_POST['calendar_today'])) {
	$calendar_date = date('Y-m-d');
} else {
	if(isset($_POST['calendar_date'])) {
		$calendar_date = date('Y-m-d', strtotime($_POST['calendar_date']));
		$_GET['calendar_date'] = $calendar_date;
	} else if(isset($_GET['calendar_date'])) {
		$calendar_date = date('Y-m-d', strtotime($_GET['calendar_date']));
	} else {
		$calendar_date = date('Y-m-d');
	}
}
$month = date("n", strtotime($calendar_date));
$year = date("Y", strtotime($calendar_date));
$month_string = date("F", strtotime($calendar_date));
$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
$lock_date = get_config($dbc, 'staff_schedule_lock_date');
$from_url = 'staff.php?tab=active';
if (!empty($_GET['from'])) {
	$from_url = $_GET['from'];
}
if(!empty($_GET['from_url'])) {
	$from_url = $_GET['from_url'];
}
if(!empty($_POST['from_url'])) {
	$from_url = $_POST['from_url'];
} ?>
<div id="staff_div" class="container">
	<div class="iframe_overlay" style="display:none; margin-top: -20px;margin-left:-15px;">
		<div class="iframe">
			<div class="iframe_loading">Loading...</div>
			<iframe src=""></iframe>
		</div>
	</div>
	<div class="row hide_on_iframe">
		<!-- <div id="no-more-tables" class="main-screen contacts-list"> -->
		<div class="main-screenlist main-screen">
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

			<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
                <!-- Sidebar -->
                <div class="standard-collapsible tile-sidebar set-section-height">
                	<?php include('tile_sidebar.php'); ?>
                </div><!-- .tile-sidebar -->

                <!-- Scalable Sidebar -->
                <?php if($_GET['shiftid']) : ?>
	                <div class="scalable pull-right unbooked_view" style="height: 30em; overflow: auto;">
	                	<?php include('../Calendar/shifts.php'); ?>
	                </div>
	            <?php endif; ?>

				<!-- Main Screen -->
                <div class="has-main-screen scale-to-fill tile-content set-section-height" style="padding: 0; overflow-y: auto;">
                    <div class="main-screen-details main-screen override-main-screen <?= $subtab != 'id_card' ? 'standard-body' : '' ?>" style="height: inherit;">
                        <div class='standard-body-title'>
                            <h3>Staff Schedule</h3>
                        </div>
                        <div class='standard-body-content pad-top pad-left pad-right pad-bottom'>
							<div class="col-sm-12">
								<h4 class="col-sm-4">Staff Schedule <?php if(approval_visible_function($dbc, 'staff') > 0) { ?><img class="inline-img" title="Lock all schedules before a selected date." src="../img/icons/locked-1.png" onclick="$('[name=staff_schedule_lock_date]').focus();"><input type="text" style="border:0; width:0;" class="no-pad datepicker config_value" name="staff_schedule_lock_date" value="<?= $lock_date ?>"><?php } ?></h4>
								<div class="col-sm-4">
									<label class="control-label">Date:</label>
									<input type="text" name="calendar_date" value="<?= $calendar_date ?>" class="form-control inline datepicker">
									<button type="submit" name="submit_date" class="btn brand-btn mobile-block">Submit</button>
									<button type="submit" name="calendar_today" class="btn brand-btn mobile-block">Today</button>
								</div>
								<div class="col-sm-4">
								    <?php $enabled_fields = ','.mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_contacts_shifts`"))['enabled_fields'].',';
								    if (strpos($enabled_fields, ',import_button,') !== FALSE) { ?>
										<a href="" onclick="overlayIFrameSlider('<?= WEBSITE_URL ?>/Calendar/shifts.php?shiftid=IMPORT&hideaddbutton=true&shift_contactid=<?= $contact_id ?>'); return false;" class="btn brand-btn pull-right">Import</a>
								    <?php }
								    if (strpos($enabled_fields, ',export_button') !== FALSE) { ?>
								        <a href="" onclick="exportShifts(<?= $contact_id ?>); return false;" class="btn brand-btn pull-right">Export</a>
								    <?php } ?>
									<a href="" onclick="overlayIFrameSlider('<?= WEBSITE_URL ?>/Calendar/shifts.php?shiftid=NEW&shift_staffid=<?= $contact_id ?>&hideaddbutton=true'); return false;" class="btn brand-btn pull-right">Add Shift</a>
								</div>
							</div>
							<div class="clearfix"></div>
							<?php for($cur_day = 1; $cur_day <= $days_in_month; $cur_day++) {
								$today_date = $cur_day.'-'.$month.'-'.$year;
								$new_today_date = date_format(date_create_from_format('j-n-Y', $today_date), 'Y-m-d');
								$day_of_week = date('l', strtotime($new_today_date));
								$shifts = checkShiftIntervals($dbc, $contact_id, $day_of_week, $new_today_date, 'all'); ?>
								<div class="shift_day double-gap-left">
									<h4 style="font-weight: normal;"><?= date('F d, Y', strtotime($new_today_date)) ?></h4>
									<ul>
									<?php if(!empty($shifts)) {
										$total_booked_time = 0;
										foreach($shifts as $shift) {
											echo '<li>';
											echo ($shift['startdate'] < $lock_date ? '' : '<a href="" onclick="overlayIFrameSlider(\''.WEBSITE_URL.'/Calendar/shifts.php?shiftid='.$shift['shiftid'].'&hideaddbutton=true\'); return false;" >');
											if(!empty($shift['dayoff_type'])) {
												echo 'Day Off: '.date('h:i a', strtotime($shift['starttime'])).' - '.date('h:i a', strtotime($shift['endtime'])).'<br>';
												echo 'Day Off Type: '.$shift['dayoff_type'];
											} else {
												$total_booked_time += (strtotime($shift['endtime']) - strtotime($shift['starttime']));
												echo 'Shift: '.date('h:i a', strtotime($shift['starttime'])).' - '.date('h:i a', strtotime($shift['endtime']));
												if(!empty($shift['break_starttime']) && !empty($shift['break_endtime'])) {
													echo '<br>';
													echo 'Break: '.date('h:i a', strtotime($shift['break_starttime'])).' - '.date('h:i a', strtotime($shift['break_endtime']));
												}
												if(!empty($shift['clientid'])) {
													echo '<br>';
													echo get_contact($dbc, $shift['clientid'], 'category').': ';
													echo get_contact($dbc, $shift['clientid']);
												}
											}
											echo ($shift['startdate'] < $lock_date ? '' : '</a>');
											echo '</li>';
										}
										echo '<br>Total Booked Time: '.(sprintf('%02d', floor($total_booked_time / 3600)).':'.sprintf('%02d', floor($total_booked_time % 3600 / 60))).'';
									} else {
										echo 'No Shifts Found.';
									} ?>
									</ul>
								</div>
								<hr style="height: 1px; border: 0; border-top: 1px solid #ccc;">
							<?php } ?>
						</div>
					</div>
				</div>
            </form>
		</div>
	</div>
</div>
<?php include_once ('../footer.php'); ?>