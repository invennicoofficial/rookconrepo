<?php include_once('../include.php');
checkAuthorised('sales_order');
/*
/* Sales Order Config - General
*/

if(isset($_POST['save_config'])) {
	$sales_order_tile_name = filter_var($_POST['sales_order_tile'],FILTER_SANITIZE_STRING).'#*#'.filter_var($_POST['sales_order_noun'],FILTER_SANITIZE_STRING);
	set_config($dbc, 'sales_order_tile_name', $sales_order_tile_name);

    $field_config = filter_var($_POST['auto_archive_days'],FILTER_SANITIZE_STRING);
    mysqli_query($dbc, "INSERT INTO `field_config_so` (`auto_archive_days`) SELECT '$field_config' FROM (SELECT COUNT(*) rows FROM `field_config_so`) num WHERE num.rows = 0");
    mysqli_query($dbc, "UPDATE `field_config_so` SET `auto_archive_days` = '$field_config'");

    session_start();
    $_SESSION['CONSTANT_UPDATED'] = 0;
    echo '<script type="text/javascript"> window.location.href = "?tab=general"; </script>';
}

$field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `field_config_so`"));
$auto_archive_days = !empty($field_config['auto_archive_days']) ? $field_config['auto_archive_days'] : 30;
?>

<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
	<div class="gap-top">
		<div class="form-group">
			<label class="control-label col-sm-4">Tile Name:<br><em>Enter the name you would like the Sales Order tile to be labelled as.</em></label>
			<div class="col-sm-8">
				<input type="text" name="sales_order_tile" class="form-control" value="<?= SALES_ORDER_TILE ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-4">Tile Noun:<br><em>Enter the name you would like individual Sales Orders to be labelled as.</em></label>
			<div class="col-sm-8">
				<input type="text" name="sales_order_noun" class="form-control" value="<?= SALES_ORDER_NOUN ?>">
			</div>
		</div>
	    <div class="form-group">
	        <label class="control-label col-sm-4">Auto Archive Completed <?= SALES_ORDER_TILE ?> After # of Days</label>
	        <div class="col-sm-8">
	            <input type="number" min="0" name="auto_archive_days" value="<?= $auto_archive_days ?>" class="form-control">
	        </div>
	    </div>
	</div>
	<div class="pull-right gap-top gap-right gap-bottom">
	    <a href="index.php" class="btn brand-btn">Cancel</a>
	    <button type="submit" id="save_config" name="save_config" value="Submit" class="btn brand-btn">Submit</button>
	</div>
</form>