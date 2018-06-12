<?php include_once('../include.php');
checkAuthorised('sales_order');
/*
/* Sales Order Config - Fields
*/

if(isset($_POST['save_config'])) {
	if (!file_exists('download')) {
		mkdir('download', 0777, true);
	}
    $pos_logo = htmlspecialchars($_FILES["pos_logo"]["name"], ENT_QUOTES);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='sales_order_logo'"));
    if($get_config['configid'] > 0) {
		if($pos_logo == '') {
			$logo_update = $_POST['logo_file'];
		} else {
			$logo_update = $pos_logo;
		}
		move_uploaded_file($_FILES["pos_logo"]["tmp_name"],"download/" . $logo_update);
        $query_update_employee = "UPDATE `general_configuration` SET value = '$logo_update' WHERE name='sales_order_logo'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        move_uploaded_file($_FILES["pos_logo"]["tmp_name"], "download/" . $_FILES["pos_logo"]["name"]) ;
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('sales_order_logo', '$pos_logo')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
}
?>

<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
	<div class="gap-top">
		<?php
		$pos_logo = get_config($dbc, 'sales_order_logo');
		?>

		<div class="form-group">
			<label for="file[]" class="col-sm-4 control-label">Upload Logo
				<span class="popover-examples list-inline">&nbsp;
					<a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a>
				</span>
			:</label>
			<div class="col-sm-8">
				<?php if($pos_logo != '') {
					echo '<a href="download/'.$pos_logo.'" target="_blank">View</a>';
					?>
					<input type="hidden" name="logo_file" value="<?php echo $pos_logo; ?>" />
					<input name="pos_logo" type="file" data-filename-placement="inside" class="form-control" />
					<?php } else { ?>
					<input name="pos_logo" type="file" data-filename-placement="inside" class="form-control" />
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="pull-right gap-top gap-right gap-bottom">
	    <a href="index.php" class="btn brand-btn">Cancel</a>
	    <button type="submit" id="save_config" name="save_config" value="Submit" class="btn brand-btn">Submit</button>
	</div>
</form>