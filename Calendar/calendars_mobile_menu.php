<?php 
$calendar_type_width = 100;
if(config_visible_function($dbc, 'calendar_rook')) {
	$calendar_type_width -= 15;
}
if(vuaed_visible_function($dbc, 'calendar_rook')) {
	$calendar_type_width -= 15;
}
$calendar_type_width .= '%'; ?>
<?php if($edit_access == 1) { ?><div class="calendar-mobile-block-item" style="width: 15%; padding: 0.25em;" onclick="toggleCalendarAdd();"><img src="../img/icons/ROOK-add-icon.png" style="max-height: 100%;"></div><?php } ?><div class="calendar-mobile-block-item" style="width: <?= $calendar_type_width ?>;" onclick="toggleCalendarType();"><span><?= $calendar_label ?><img src="<?= WEBSITE_URL ?>/img/icons/dropdown-arrow.png" style="height: 1em; margin: 0.25em 0.5em;" class="counterclockwise pull-right"></span></div><?php if(config_visible_function($dbc, 'calendar_rook')) { ?><div class="calendar-mobile-block-item" style="width: 15%; padding: 0.25em;"><a href="field_config_calendar.php"><img src="../img/icons/settings-4.png" style="max-height: 100%;"></a></div><?php } ?>
<div class="clearfix"></div>
<div class="calendar-mobile-block-item" style="width: 50%;" <?= count($mobile_calendar_views) > 1 ? 'onclick="toggleCalendarView();"' : '' ?>><span>View: <?= $mobile_calendar_view ?><?= count($mobile_calendar_views) > 1 ? '<img src="'.WEBSITE_URL.'/img/icons/dropdown-arrow.png" style="height: 1em; margin: 0.25em 0.5em;" class="counterclockwise pull-right">' : '' ?></span></div><div class="calendar-mobile-block-item" style="width:50%;" id="contact_label_div" <?= $_GET['type'] != 'my' ? 'onclick="toggleCalendarContact();"' : '' ?>><span><div id="contact_label" style="display: inline;"><?= $mobile_calendar_contact_label ?></div> <?php if($_GET['type'] != 'my') { ?><img src="<?= WEBSITE_URL ?>/img/icons/dropdown-arrow.png" style="height: 1em; margin: 0.25em 0.5em;" class="counterclockwise pull-right"><?php } ?></span></div>
<div class="clearfix"></div>
<div class="calendar-mobile-block-item" style="width: 100%;">
	<span class="menu-item inactive calendar_menu_list" onclick="toggleMobileView(this);">List</span>
	<span class="menu-item active calendar_menu_date" onclick="toggleMobileView(this);">Date</span>
</div>
<div class="clearfix"></div>