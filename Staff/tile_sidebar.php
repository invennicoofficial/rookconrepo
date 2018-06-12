<!-- Tile Sidebar -->
<?php
$field_tabs = ','.get_config($dbc, 'staff_field_subtabs').',';
$field_tabs .= 'Software ID,';
$sidebar_fields = [];
if($contactid > 0) {
	$sidebar_fields['id_card'] = ['ID Card','ID Card','id_card'];
}
$sidebar_fields['staff_information'] = ['Staff Information','Staff Information','staff_info'];
$sidebar_fields['staff_address'] = ['Staff Address','Staff Address','staff_address'];
$sidebar_fields['position'] = ['Position','Position','position'];
$sidebar_fields['employee_information'] = ['Employee Information','Employee Information','employee'];
$sidebar_fields['driver_information'] = ['Driver Information','Driver Information','driver'];
$sidebar_fields['direct_deposit_information'] = ['Direct Deposit Information','Direct Deposit Information','direct_deposit'];
$sidebar_fields['software_id'] = ['Software ID','Software ID','software_access'];
$sidebar_fields['social_media'] = ['Social Media','Social Media','social'];
$sidebar_fields['emergency'] = ['Emergency','Emergency','emergency'];
$sidebar_fields['health'] = ['Health','Health & Safety','health'];
$sidebar_fields['schedule'] = ['Schedule','Staff Schedule','schedule'];
if(tile_enabled($dbc, 'project')['user_enabled'] > 0) {
	$sidebar_fields['project'] = ['Project',PROJECT_TILE,'projects'];
}
if(tile_enabled($dbc, 'ticket')['user_enabled'] > 0) {
	$sidebar_fields['ticket'] = ['Ticket',TICKET_TILE,'tickets'];
}
$sidebar_fields['hr'] = ['HR','HR Record','hr_record'];
$sidebar_fields['staff_docs'] = ['Staff Documents','Staff Documents','staff_docs'];
$sidebar_fields['incident_reports'] = ['Incident Reports',INC_REP_TILE,'incident_reports'];
$sidebar_fields['time_off'] = ['Time Off','Time Off Request Form','hr_record'];
$sidebar_fields['time_off_requests'] = ['Time Off','Time Off Requests','hr_record'];
$sidebar_fields['certificates'] = ['Certificates','Certificates & Certifications','certificates'];
$sidebar_fields['rate_card'] = ['Rate Card','Rate Card','rate_card'];
$sidebar_fields['history'] = ['History','History','history'];

$subtab = $_POST['subtab']; ?>
<script type="text/javascript">
function submitButton(subtab) {
	<?php if($subtab == 'id_card') { ?>
		window.location.replace('?contactid=<?= $_GET['contactid'] ?>&subtab='+subtab);
	<?php } else { ?>
		$('input[required]').prop('checked',true);
		$('[name="subtab"]').val(subtab);
		$('[name="contactid"]').click();
	<?php } ?>
}
</script>
<button type="submit" name="contactid" value="<?= $contactid ?>" style="display: none;"></button>
<ul class="sidebar" style="height: inherit;">
    <?php foreach($sidebar_fields as $key => $sidebar_field) {
        if (strpos($field_tabs,','.$sidebar_field[0].',') !== FALSE && (check_subtab_persmission($dbc, 'staff', ROLE, $sidebar_field[2]) === TRUE || empty($sidebar_field[2])) && !in_array($sidebar_field[0], $subtabs_hidden)) {
			if(empty($subtab)) {
				$subtab = $key;
			}
			?><a href="" onclick="submitButton('<?= $key ?>'); return false;"><li <?= ($subtab == $key ? 'class="active"' : '') ?>><?= $sidebar_field[1] ?></li></a><?php
		}
    } ?>
</ul>
<input type="hidden" name="subtab" value="<?= $subtab ?>">