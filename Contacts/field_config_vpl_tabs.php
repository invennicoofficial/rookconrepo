<?php
include_once('../include.php');
checkAuthorised('vpl');
if (isset($_POST['add_tab'])) {
    $inventory_tabs = filter_var($_POST['inventory_tabs'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='vpl_tabs'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$inventory_tabs' WHERE name='vpl_tabs'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('vpl_tabs', '$inventory_tabs')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    set_config($dbc, 'show_category_dropdown_vpl', filter_var($_POST['show_category_dropdown_vpl'],FILTER_SANITIZE_STRING));
    set_config($dbc, 'vpl_sort_accordions', filter_var($_POST['vpl_sort_accordions'],FILTER_SANITIZE_STRING));
}
?>
<div class="standard-body-title">
    <h3>Vendor Price List Settings - Tabs</h3>
</div>
<div class="standard-body-content full-height">
    <div class="dashboard-item dashboard-item2 full-height">
		<form id="form1" name="form1" method="post"	enctype="multipart/form-data" class="form-horizontal" role="form">
	        <div class="form-group">
	            <label for="fax_number"	class="col-sm-4	control-label"><span class="popover-examples list-inline"><a class="" style="margin:7px 5px 0 0;" data-toggle="tooltip" data-placement="top" title="These tabs sort your VPL items by Category, so please make sure the tab names match your VPL items's category names. Also, please make sure you do not place any spaces beside the commas."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span> Add Tabs Separated By a Comma:</label>
	            <div class="col-sm-8">
	              <input name="inventory_tabs" type="text" value="<?php echo get_config($dbc, 'vpl_tabs'); ?>" class="form-control"/>
	            </div>
	        </div>
			<div class="form-group">
	            <label for="fax_number"	class="col-sm-4	control-label"><span class="popover-examples list-inline"><a class="" style="margin:7px 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Instead of tabs, have a drop-down menu that will sort your VPL items by their respective categories."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span> Or Use a Drop-Down Menu:</label>
	            <div class="col-sm-8">
				<?php
				$checked = '';
				$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='show_category_dropdown_vpl'"));
				if($get_config['configid'] > 0) {
					$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT value FROM general_configuration WHERE name='show_category_dropdown_vpl'"));
					if($get_config['value'] == '1') {
						$checked = 'checked';
					}
				}
				?>
	              <input type='checkbox' style='width:20px; height:20px;' <?php echo $checked; ?>  name='show_category_dropdown_vpl' class='show_category_dropdown' value='1'>
	            </div>
	        </div>
	        <div class="form-group">
	        	<label class="col-sm-4 control-label">Sort Dashboard In Accordions:</label>
	        	<div class="col-sm-8">
	        		<?php $vpl_sort_accordions = get_config($dbc, 'vpl_sort_accordions'); ?>
					<input type='checkbox' style='width:20px; height:20px;' <?= $vpl_sort_accordions == 1 ? 'checked' : '' ?>  name='vpl_sort_accordions' value='1'>
	        	</div>
	        </div>

	        <div class="form-group pull-right">
	                <a href="?" class="btn brand-btn">Back</a>
	                <button	type="submit" name="add_tab" value="add_tab" class="btn brand-btn">Submit</button>
	            </div>
	        </div>

			<div class="clearfix"></div>
        </form>
    </div>
</div>