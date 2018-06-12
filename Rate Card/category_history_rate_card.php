<?php
$id = $_GET['id'];
if(is_numeric($id)): 
	$sql = "SELECT `history`,`description` FROM `company_rate_card` WHERE `rate_id`=$id";
	$result = mysqli_fetch_array(mysqli_query($dbc, $sql));
	echo ($result['contactid'] > 0 ? '' : "<h2>Category: ".$result['description']."</h2>");
	echo "<p>".($result['history'] == '' ? 'No changes to display.' : $result['history'])."</p>";
endif;