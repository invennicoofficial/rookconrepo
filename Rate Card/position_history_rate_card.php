<?php
$id = $_GET['id'];
if(is_numeric($id)): 
	$sql = "SELECT p.`name` position, r.`history` FROM `company_rate_card` r LEFT JOIN `positions` p ON r.`item_id` = p.`position_id` WHERE `companyrcid`=$id";
	$result = mysqli_fetch_array(mysqli_query($dbc, $sql));
	echo ($result['position'] == '' ? '' : "<h2>Position: {$result['position']}</h2>");
	echo "<p>".($result['history'] == '' ? 'No changes to display.' : $result['history'])."</p>";
endif;