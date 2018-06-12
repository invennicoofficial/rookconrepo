<?php include_once('../include.php');
checkAuthorised('sales_order');
/*
/* Sales Order Config - Fields
*/

if(isset($_POST['save_config'])) {
	$field_config = filter_var(implode(',',$_POST['so_fields']),FILTER_SANITIZE_STRING);

	mysqli_query($dbc, "INSERT INTO `field_config_so` (`dashboard_fields`) SELECT '$field_config' FROM (SELECT COUNT(*) rows FROM `field_config_so`) num WHERE num.rows = 0");
	mysqli_query($dbc, "UPDATE `field_config_so` SET `dashboard_fields` = '$field_config'");
}

$field_list = [
	'Business Contact',
	'Classification',
	'Next Action',
	'Next Action Follow Up Date'
];

$field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `field_config_so`"));
$value_config = ','.$field_config['dashboard_fields'].',';
?>

<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
    <div class="gap-top">
    	<h4>Fields</h4>
        <table cellpadding='10' class='table table-bordered'>
        	<?php $i = 0;
        	foreach ($field_list as $field) {
        		if ($i == 0) {
        			echo '<tr>';
        		}
        		echo '<td><input type="checkbox" '.(strpos($value_config, ','.$field.',') !== FALSE ? 'checked' : '').' value="'.$field.'" style="height: 20px; width: 20px;" name="so_fields[]">&nbsp;&nbsp;'.$field.'</td>';
        		$i++;
        		if($i == 5) {
        			echo '</tr>';
        			$i = 0;
        		}
        	}
        	if($i != 0) {
        		echo '</tr>';
        	} ?>
        </table>
    </div>
    <div class="pull-right gap-top gap-right gap-bottom">
        <a href="index.php" class="btn brand-btn">Cancel</a>
        <button type="submit" id="save_config" name="save_config" value="Submit" class="btn brand-btn">Submit</button>
    </div>
</form>