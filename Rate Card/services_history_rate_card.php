<?php
$id = $_GET['id'];
if(is_numeric($id)): 
	$sql = "SELECT `history`,`item_id` FROM `company_rate_card` WHERE `rate_id`=$id";
	$result = mysqli_fetch_array(mysqli_query($dbc, $sql));
	echo ($result['contactid'] > 0 ? '' : "<h2>Service: ".get_field_value('heading','services','serviceid',$result['item_id'])."</h2>");
	echo "<p>".($result['history'] == '' ? 'No changes to display.' : $result['history'])."</p>";
endif;