<?php include_once('../include.php');
checkAuthorised('contracts');
$security = get_security($dbc, 'contracts');
$pin_levels = implode(",%' OR `pinned` LIKE '%,",array_filter(explode(',',ROLE)));
$pincount = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(*) `rows` FROM `contracts` WHERE `deleted`=0 AND (CONCAT(',',`pinned`,',') LIKE '%,ALL,%' OR CONCAT(',',`pinned`,',') LIKE '%,".$pin_levels.",%' OR CONCAT(',',`pinned`,',') LIKE '%,".$_SESSION['contactid'].",%')"))['rows'];
$contract_tabs = explode('#*#',mysqli_fetch_array(mysqli_query($dbc, "SELECT `contract_tabs` FROM `field_config_contracts`"))['contract_tabs']);
foreach($contract_tabs as $contract_i => $contract_tab) {
	if(!check_subtab_persmission($dbc, 'contracts', ROLE, config_safe_str($contract_tab))) {
		unset($contract_tabs[$contract_i]);
	}
}
array_unshift($contract_tabs, 'Favourites');
if($pincount > 0) {
	array_unshift($contract_tabs, 'Pinned');
}
if(empty($_GET['tab'])) {
	$_GET['tab'] = $contract_tabs[0];
}
?>
<div class="form-list">
	<?php 
	if($_GET['tab'] == 'Pinned') {
		$sql = "SELECT *, IF(CONCAT(',',`pinned`,',') LIKE '%,ALL,%' OR CONCAT(',',`pinned`,',') LIKE '%,".$pin_levels.",%' OR CONCAT(',',`pinned`,',') LIKE '%,".$_SESSION['contactid'].",%',1,0) `pin` FROM `contracts` WHERE `deleted` = 0 AND (CONCAT(',',`pinned`,',') LIKE '%,ALL,%' OR CONCAT(',',`pinned`,',') LIKE '%,".$pin_levels.",%' OR CONCAT(',',`pinned`,',') LIKE '%,".$_SESSION['contactid'].",%') ORDER BY `category`, LPAD(`heading_number`, 100, 0), LPAD(`sub_heading_number`, 100, 0), LPAD(`third_heading_number`, 100, 0)";
	} else if($_GET['tab'] == 'Favourites') {
		$sql = "SELECT *, IF(CONCAT(',',`pinned`,',') LIKE '%,ALL,%' OR CONCAT(',',`pinned`,',') LIKE '%,".$pin_levels.",%' OR CONCAT(',',`pinned`,',') LIKE '%,".$_SESSION['contactid'].",%',1,0) `pin` FROM `contracts` WHERE `deleted` = 0 AND CONCAT(',',`favourite`,',') LIKE '%,".$_SESSION['contactid'].",%' ORDER BY `category`, LPAD(`heading_number`, 100, 0), LPAD(`sub_heading_number`, 100, 0), LPAD(`third_heading_number`, 100, 0)";
	} else {
		$sql = "SELECT *, IF(CONCAT(',',`pinned`,',') LIKE '%,ALL,%' OR CONCAT(',',`pinned`,',') LIKE '%,".$pin_levels.",%' OR CONCAT(',',`pinned`,',') LIKE '%,".$_SESSION['contactid'].",%',1,0) `pin` FROM `contracts` WHERE `category` = '".$_GET['tab']."' AND `deleted` = 0 ORDER BY `category`, LPAD(`heading_number`, 100, 0), LPAD(`sub_heading_number`, 100, 0), LPAD(`third_heading_number`, 100, 0)";
	}
	$query = mysqli_query($dbc, $sql);
	$security_levels = get_security_levels($dbc);
	$heading = $sub_heading = '';
	if(mysqli_num_rows($query) > 0) {
		while($form = mysqli_fetch_array($query)) {
			if($heading != $form['heading_number'].' '.$form['heading'] && $form['sub_heading_number'] != '') {
				$heading = $form['heading_number'].' '.$form['heading'];
				echo "<div class='heading'>$heading</div>";
			}
			if($sub_heading != $form['sub_heading_number'].' '.$form['sub_heading'] && $form['third_heading_number'] != '') {
				$sub_heading = $form['sub_heading_number'].' '.$form['sub_heading'];
				echo "<div class='sub-heading'>$sub_heading</div>";
			}
			$form_name = ($form['third_heading_number'] != '' ? $form['third_heading_number'].' '.$form['third_heading'] : ($form['sub_heading_number'] != '' ? $form['sub_heading_number'].' '.$form['sub_heading'] : $form['heading_number'].' '.$form['heading']));
			echo '<div class="form">';
			echo '<a href="?tab='.$_GET['tab'].'&edit='.$form['contractid'].'">'.$form_name.'</a>';
			echo '<a href="?blank_pdf=true&contractid='.$form['contractid'].'" class="pad-horizontal pull-right">Download PDF</a>';
			if($security['edit'] > 0) {
				echo '<a href="" onclick="return archive(\''.$form['contractid'].'\');" class="pull-right">Archive</a>';
				echo '<a href="?add_contract='.$form['contractid'].'" class="pad-horizontal pull-right">Edit</a>';
				echo '<span class="pull-right pad-horizontal" style="max-width:100%;"><img class="inline-img small" onclick="markPinned(this);" src="'.($form['pin'] > 0 ? '../img/pinned-filled.png' : '../img/pinned.png').'">';
				echo '<span class="pinned" style="display:none; width:20em; max-width:100%;"><select multiple data-placeholder="Select Users and Levels" data-id="'.$form['contractid'].'" class="chosen-select-deselect"><option></option>';
				echo '<option '.(strpos(','.$form['pinned'].',', ',ALL,') !== FALSE ? 'selected' : '').' value="ALL">All Users</option>';
				foreach($security_levels as $level_label => $level_name) {
					echo '<option '.(strpos(','.$form['pinned'].',',','.$level_name.',') !== FALSE ? 'selected' : '').' value="'.$level_name.'">'.$level_label.'</option>';
				}
				foreach(sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `category`='Staff' AND `deleted`=0 AND `status`=1")) as $contact) {
					echo '<option '.(strpos(','.$form['pinned'].',', ','.$contact['contactid'].',') !== FALSE ? 'selected' : '').' value="'.$contact['contactid'].'">'.$contact['first_name'].' '.$contact['last_name'].'</option>';
				}
				echo '</select></span></span>';
			} else if($form['pin'] > 0) {
				echo '<span class="pull-right pad-horizontal"><img class="inline-img small" src="../img/pinned-filled.png"></span>';
			}
			echo '<span data-id="'.$form['contractid'].'" class="pull-right neg-25-margin-vertical pad-horizontal" onclick="markFavourite(this);"><img class="inline-img fave" src="../img/blank_favourite.png" style="'.(strpos(','.$form['favourite'].',',','.$_SESSION['contactid'].',') !== false ? 'display:none;' : '').'"><img class="inline-img fave" src="../img/full_favourite.png" style="'.(strpos(','.$form['favourite'].',',','.$_SESSION['contactid'].',') !== false ? '' : 'display:none;').'"></span>';
			echo "<div class='clearfix'></div></div>";
		}
	} else {
		echo '<h3>No Contracts Found.</h3>';
	} ?>
</div>