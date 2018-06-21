<?php
include_once ('../include.php');
ob_clean();

if($_GET['action'] == 'settings_tabs') {
	$contract_tabs = filter_var(implode('#*#', $_POST['contract_tabs']),FILTER_SANITIZE_STRING);
	mysqli_query($dbc, "INSERT INTO `field_config_contracts` (`fieldconfigid`) SELECT 1 FROM (SELECT COUNT(*) rows FROM `field_config_contracts`) num WHERE num.rows = 0");
	mysqli_query($dbc, "UPDATE `field_config_contracts` SET `contract_tabs` = '$contract_tabs'");
} else if($_GET['action'] == 'settings_fields') {
	$contract_fields = filter_var(implode(',', $_POST['contract_fields']),FILTER_SANITIZE_STRING);
	mysqli_query($dbc, "INSERT INTO `field_config_contracts` (`fieldconfigid`) SELECT 1 FROM (SELECT COUNT(*) rows FROM `field_config_contracts`) num WHERE num.rows = 0");
	mysqli_query($dbc, "UPDATE `field_config_contracts` SET `fields` = '$contract_fields'");
} else if($_GET['action'] == 'mark_favourite') {
	$id = $_GET['id'];
	$user = $_GET['user'];
	mysqli_query($dbc, "UPDATE `contracts` SET `favourite`=TRIM(BOTH ',' FROM REPLACE(IF(CONCAT(',',`favourite`,',') LIKE '%,$user,%',REPLACE(CONCAT(',',`favourite`,','),',$user,',','),CONCAT(`favourite`,',$user')),',,',',')) WHERE `contractid`='$id'");
} else if($_GET['action'] == 'mark_pinned') {
	$id = filter_var($_POST['id'],FILTER_SANITIZE_STRING);
	$users = filter_var(implode(',',$_POST['users']),FILTER_SANITIZE_STRING);
	mysqli_query($dbc, "UPDATE `contracts` SET `pinned`=',$users,' WHERE `contractid`='$id'");
} else if($_GET['action'] == 'archive') {
	$id = filter_var($_POST['id'],FILTER_SANITIZE_STRING);
	mysqli_query($dbc, "UPDATE `contracts` SET `deleted` = 1 WHERE `contractid`='$id'");
} else if($_GET['action'] == 'set_category') {
	$category = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
	echo '<option></option>';
	$heading_count = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `heading_number` FROM `contracts` WHERE LPAD(`heading_number`, 100, 0) IN (SELECT MAX(LPAD(`heading_number`, 100, 0)) FROM `contracts` WHERE `deleted`=0 AND `category`='".$category."') GROUP BY `heading_number`"))['heading_number'] + 5;
	for($i = 1; $i <= $heading_count; $i++) {
		$heading = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `heading` FROM `contracts` WHERE `heading_number`='$i' AND `category`='".$category."' AND `deleted`=0"))['heading']; ?>
		<option value="<?= $i ?>"><?= $i.' '.$heading ?></option>
	<?php }
} else if($_GET['action'] == 'set_form_section') {
	$section = filter_var($_POST['section'],FILTER_SANITIZE_STRING);
	$section = $section > 0 ? $section : 1;
	$category = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
	$heading = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `heading` FROM `contracts` WHERE `heading_number`='$section' AND `category`='$category' AND `deleted`=0"))['heading'];
	echo $heading.'#*#<option></option>';
	$heading_count = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `heading_number`, `sub_heading_number` FROM `contracts` WHERE LPAD(`sub_heading_number`, 100, 0) IN (SELECT MAX(LPAD(`sub_heading_number`, 100, 0)) FROM `contracts` WHERE `deleted`=0 AND `category`='".$category."' AND `heading_number`='$section') GROUP BY `sub_heading_number`"));
	$heading_count = substr($heading_count['sub_heading_number'],strlen($heading_count['heading_number']) + 1) + 5;
	for($i = 1; $i <= $heading_count; $i++) {
		$heading = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `sub_heading` FROM `contracts` WHERE `sub_heading_number`='".$section.'.'.$i."' AND `category`='".$category."' AND `deleted`=0"))['sub_heading']; ?>
		<option value="<?= $section.'.'.$i ?>"><?= $section.'.'.$i.' '.$heading ?></option>
	<?php }
} else if($_GET['action'] == 'set_form_subsection') {
	$subsection = filter_var($_POST['subsection'],FILTER_SANITIZE_STRING);
	$subsection = $subsection > 0 ? $subsection : 1;
	$category = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
	if($subsection == '') {
		echo "#*#<option></option>";
	} else {
		$sub_heading = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `sub_heading` FROM `contracts` WHERE `sub_heading_number`='$section' AND `category`='$category' AND `deleted`=0"))['sub_heading'];
		echo $sub_heading.'#*#<option></option>';
		$heading_count = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `third_heading_number`, `sub_heading_number` FROM `contracts` WHERE LPAD(`third_heading_number`, 100, 0) IN (SELECT MAX(LPAD(`third_heading_number`, 100, 0)) FROM `contracts` WHERE `deleted`=0 AND `category`='".$category."' AND `heading_number`='$subsection') GROUP BY `third_heading_number`"));
		$heading_count = substr($heading_count['third_heading_number'],strlen($heading_count['sub_heading_number']) + 1) + 5;
		for($i = 1; $i <= $heading_count; $i++) {
			$heading = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `third_heading_number` FROM `contracts` WHERE `third_heading_number`='".$subsection.'.'.$i."' AND `category`='".$category."' AND `deleted`=0"))['sub_heading']; ?>
			<option value="<?= $subsection.'.'.$i ?>"><?= $subsection.'.'.$i.' '.$heading ?></option>
		<?php }
	}
}