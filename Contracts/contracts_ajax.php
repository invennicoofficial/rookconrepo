<?php
include ('../include.php');
ob_clean();
error_reporting(0);

if($_GET['fill'] == 'section') {
    $heading_number = $_GET['heading_number'];
    $category = $_GET['category'];
    $type = $_GET['type'];

	$query = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT heading FROM contracts WHERE heading_number = '$heading_number' AND category = '$category'"));
    echo $query['heading'];
}

if($_GET['fill'] == 'sub_heading_number') {
    $heading_number = $_POST['heading_number'];
    $category = $_POST['category'];
    $type = $_POST['type'];
    $sub_heading = $_POST['sub_heading'];
	$max_subsection = $_POST['max_section'];

	$sub_heading_numbers = [];
	$sub_headings = [];
	$disabled = [];
	$sub_heading_result = mysqli_query($dbc, "SELECT DISTINCT `sub_heading_number`, `sub_heading`, IFNULL(`third_heading_number`,'') third_heading_number FROM `contracts` WHERE `category`='$category' AND `heading_number`='$heading_number' AND `deleted`=0");
	while($sub_heading_used = mysqli_fetch_array($sub_heading_result)) {
		$sub_heading_numbers[] = $sub_heading_used['sub_heading_number'];
		$sub_headings[] = $sub_heading_used['sub_heading'];
		if($sub_heading_used['third_heading_number'] == '') {
			$disabled[] = $sub_heading_used['sub_heading_number'];
		}
	}
	echo "<option></option>";
	for($j=1;$j<=$max_subsection;$j++) {
		echo "<option ".($sub_heading === "$heading_number.$j" ? 'selected' : (in_array("$heading_number.$j",$disabled) ? 'disabled' : ''));
		echo " value='". $heading_number.'.'.$j."'>".$heading_number.'.'.$j.(in_array("$heading_number.$j",$sub_heading_numbers) ? ' : '.$sub_headings[array_search("$heading_number.$j",$sub_heading_numbers)] : '').'</option>';
	}
}

if($_GET['fill'] == 'third_heading_number') {
    $heading_number = $_POST['sub_heading_number'];
    $category = $_POST['category'];
    $type = $_POST['type'];
    $sub_heading = $_POST['third_heading'];
	$max_subsection = $_POST['max_section'];

	$sub_heading_numbers = [];
	$sub_headings = [];
	$sub_heading_result = mysqli_query($dbc, "SELECT DISTINCT `third_heading_number`, `third_heading` FROM `contracts` WHERE `category`='$category' AND `sub_heading_number`='$heading_number' AND `deleted`=0");
	while($sub_heading_used = mysqli_fetch_array($sub_heading_result)) {
		$sub_heading_numbers[] = $sub_heading_used['third_heading_number'];
		$sub_headings[] = $sub_heading_used['third_heading'];
	}
	echo "<option></option>";
	for($j=1;$j<=$max_subsection;$j++) {
		echo "<option ".($sub_heading === "$heading_number.$j" ? 'selected' : (in_array("$heading_number.$j",$sub_heading_numbers) ? 'disabled' : ''));
		echo " value='". $heading_number.'.'.$j."'>".$heading_number.'.'.$j.(in_array("$heading_number.$j",$sub_heading_numbers) ? ' : '.$sub_headings[array_search("$heading_number.$j",$sub_heading_numbers)] : '').'</option>';
	}
}

if($_GET['fill'] == 'subsection') {
    $sub_heading_number = $_GET['sub_heading_number'];
    $category = $_GET['category'];

	$query = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT sub_heading FROM contracts WHERE sub_heading_number = '$sub_heading_number' AND category = '$category'"));
    echo $query['sub_heading'];
}

if($_GET['fill'] == 'business_contacts') {
	$businessid = $_GET['businessid'];
	
	echo "<option></option>";
	$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `category`, `name`, `last_name`, `first_name`, `status` FROM `contacts` WHERE `deleted`=0 AND `businessid`='$businessid'"),MYSQLI_ASSOC));
	$category = '';
	foreach($query as $id) {
		$contact = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid`='$id'"));
		if($category != $contact['category']) {
			$category = $contact['category'];
			echo "<optgroup label='$category'>\n";
		}
		if(empty($contact['name'])) {
			$name = decryptIt($contact['first_name']).' '.decryptIt($contact['last_name']);
		} else {
			$name = decryptIt($contact['name']);
		}
		echo "<option value='$id'>$name</option>";
	}
}
?>