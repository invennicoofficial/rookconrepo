<?php
/*
Budget Ajax All
*/
include ('../database_connection.php');

if($_GET['fill'] == 'budget_cat_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT category FROM budget_category WHERE budgetid = '$value' group by category");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['category']."'>".$row['category'].'</option>';
	}
}

if($_GET['fill'] == 'budget_heading_config') {
    $value = $_GET['value'];
	$budgetid = $_GET['budgetid'];
	$query = mysqli_query($dbc,"SELECT expense, budget_categoryid FROM budget_category WHERE category = '$value' and budgetid = $budgetid");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['budget_categoryid']."'>".$row['expense'].'</option>';
	}
}
