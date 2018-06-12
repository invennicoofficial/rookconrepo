<?php include_once('../include.php'); ?>
<form name="form_sites" method="post" action="inventory_checklist.php" class="form-inline double-gap-top" role="form">

	<div class="search-group">
		<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-sm-4">
					<label for="site_name" class="control-label">
						<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to see all inventory for which you have records."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						Select Inventory:</label>
				</div>
				<div class="col-sm-8">
					<select data-placeholder="Select Inventory" name="search_inv" class="chosen-select-deselect form-control">
						<option></option>
						<?php $search_inv = (!empty($_POST['search_inv']) ? $_POST['search_inv'] : '');
						$query = mysqli_query($dbc,"SELECT `inventoryid`, `category`, `part_no`, `name` FROM `inventory` ORDER BY `category`, `part_no`, `name`");
						$category = '';
						while($inventory = mysqli_fetch_array($query)) {
							if($inventory['category'] != $category) {
								$category = $inventory['category'];
								echo ($category == '' ? '' : '</optgroup>').'<optgroup label="'.$category.'">';
							} ?>
							<option <?= ($inventory['inventoryid'] == $search_inv ? " selected" : '') ?> value='<?=  $inventory['inventoryid'] ?>' ><?= $inventory['part_no'].': '.$inventory['name'] ?></option><?php
						}
						echo ($category == '' ? '' : '</optgroup>'); ?>
					</select>
				</div>
			</div>
		</div>
		<div class="form-group col-lg-3 col-md-4 col-sm-12 col-xs-12">
			<button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
		</div><!-- .form-group -->
		<div class="clearfix"></div>
	</div>

    <?php if(vuaed_visible_function($dbc, 'inventory') == 1) {
		echo '<a href="add_checklist.php" class="btn brand-btn mobile-block gap-bottom pull-right double-gap-top">Add Checklist</a>';
	}
	if($search_inv != '') {
		$checklists = mysqli_query($dbc, "SELECT * FROM `inventory` inv LEFT JOIN `item_checklist` list ON list.`checklist_item`='inventory' AND list.`deleted`=0 AND inv.`inventoryid`=list.`item_id` WHERE `inventoryid`='$search_inv'");
		while($checklist = mysqli_fetch_array($checklists)) {
			include('../Checklist/item_checklist_view.php');
		}
	} else {
		echo "<h3>Select inventory to view checklists.</h3>";
	} ?>
</form>