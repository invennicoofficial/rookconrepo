<?php
include_once('../include.php');
checkAuthorised('vpl');
if (isset($_POST['inv_impexp'])) {
	set_config($dbc, 'show_impexp_vpl', $_POST['show_impexp_vpl']);
}
?>
<div class="standard-body-title">
    <h3>Vendor Price List Settings - Import/Export</h3>
</div>
<div class="standard-body-content full-height">
    <div class="dashboard-item dashboard-item2 full-height">
		<form id="form1" name="form1" method="post"	enctype="multipart/form-data" class="form-horizontal" role="form">
			<div class="form-group">
	            <label for="fax_number"	class="col-sm-4	control-label"><span class="popover-examples list-inline"><a class="" style="margin:7px 5px 0 0;" data-toggle="tooltip" data-placement="top" title="The Import/Export functionality allows users to export a full spreadsheet of the tile's data, as well as add or edit multiple row items at once."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>Enable Import/Export:</label>
	            <div class="col-sm-8">
				<?php
				$checked = '';
				$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='show_impexp_vpl'"));
				if($get_config['configid'] > 0) {
					$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT value FROM general_configuration WHERE name='show_impexp_vpl'"));
					if($get_config['value'] == '1') {
						$checked = 'checked';
					}
				}
				?>
	              <input type='checkbox' style='width:20px; height:20px;' <?php echo $checked; ?>  name='show_impexp_vpl' class='show_impexp_vpl' value='1'>
	            </div>
	        </div>
	        <div class="form-group pull-right">
	                <a href="?" class="btn brand-btn">Back</a>
	                <button	type="submit" name="inv_impexp" value="inv_field" class="btn brand-btn">Submit</button>
	            </div>
	        </div>

			<div class="clearfix"></div>
        </form>
    </div>
</div>