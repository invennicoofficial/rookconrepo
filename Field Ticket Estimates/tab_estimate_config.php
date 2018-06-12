<?php if($_POST['submit_tab_add']): ?>
	<?php
		$tabConfig = implode(',', $_POST['project_path']);
		$query_update_tab = "update estimate_tab set estimate_tab_config = '" . $tabConfig . "' where estimate_tab_id = " . $_POST['submit_tab_add'];
		mysqli_query($dbc, $query_update_tab);
	?>
<?php endif; ?>
<h4>
<form action='' method='POST'>
<table border="0" width="100%" style="margin-left: 7px;">
	<tr><td colspan="4"><b>Available Accordions</b></td></tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td><input type="checkbox" value="Products" class='checkbox_checked' style="height: 15px; width: 20px;" name="project_path[]"> Products</td>
		<td><input type="checkbox" value="Inventory" class='checkbox_checked' style="height: 15px; width: 20px;" name="project_path[]"> Inventory</td>
		<td><input type="checkbox" value="Equipment" class='checkbox_checked' style="height: 15px; width: 20px;" name="project_path[]"> Equipment</td>
		<td><input type="checkbox" value="Labour" class='checkbox_checked' style="height: 15px; width: 20px;" name="project_path[]"> Labour</td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<!--<tr>
		<td><input type="checkbox" value="Bids" style="height: 15px; width: 20px;" name="project_path[]"> Estimates Dashboard Config</td>
		<td><input type="checkbox" value="Bids" style="height: 15px; width: 20px;" name="project_path[]"> Details</td>
		<td><input type="checkbox" value="Bids" style="height: 15px; width: 20px;" name="project_path[]"> Package</td>
		<td><input type="checkbox" value="Bids" style="height: 15px; width: 20px;" name="project_path[]"> Promotion</td>
		<td><input type="checkbox" value="Bids" style="height: 15px; width: 20px;" name="project_path[]"> Custom</td>
		<td><input type="checkbox" value="Bids" style="height: 15px; width: 20px;" name="project_path[]"> Material</td>
		<td><input type="checkbox" value="Bids" style="height: 15px; width: 20px;" name="project_path[]"> Services</td>

	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td><input type="checkbox" value="Bids" style="height: 15px; width: 20px;" name="project_path[]"> SR&ED </td>
		<td><input type="checkbox" value="Bids" style="height: 15px; width: 20px;" name="project_path[]"> Staff</td>
		<td><input type="checkbox" value="Bids" style="height: 15px; width: 20px;" name="project_path[]"> Contractor</td>
		<td><input type="checkbox" value="Bids" style="height: 15px; width: 20px;" name="project_path[]"> Clients</td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td><input type="checkbox" value="Bids" style="height: 15px; width: 20px;" name="project_path[]"> Vendor Pricelist</td>
		<td><input type="checkbox" value="Bids" style="height: 15px; width: 20px;" name="project_path[]"> Customer</td>

	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>

		<td><input type="checkbox" value="Bids" style="height: 15px; width: 20px;" name="project_path[]"> Expenses</td>
		<td><input type="checkbox" value="Bids" style="height: 15px; width: 20px;" name="project_path[]"> Other</td>
	</tr>-->
</table>
<?php if($_GET['tab_id']): ?>
<br>
<span style="margin-left: 7px;"><button type="submit" onclick='return checkTab();' name='submit_tab_add' value='<?php echo $_GET['tab_id']; ?>' class="btn brand-btn mobile-block <?php echo $active_tab; ?>" >Add To</button></span>
<?php endif; ?>
</form>
</h4>
<div style="margin-left: 7px;">
	<?php $get_tabs = mysqli_fetch_all(mysqli_query($dbc,"select * FROM bid_tab")); ?>
	<?php $active_tab = ''; ?>
	<?php foreach($get_tabs as $get_tab): ?>
		<?php if($_GET['tab_id'] == $get_tab[0]) {
			$active_tab = 'active_tab';
		}
		else {
			$active_tab = '';
		} ?>
		<a href='?tab=2&tab_id=<?php echo $get_tab[0]; ?>'><button type="button" value='<?php echo $get_tab[0]; ?>' class="btn brand-btn mobile-block <?php echo $active_tab; ?>" ><?php echo $get_implode_tabs[] = $get_tab[1]; ?></button></a>
	<?php endforeach; ?>
	<?php if($_GET['tab_id']): ?>
		<?php $get_current_tab = mysqli_fetch_assoc(mysqli_query($dbc,"select * FROM bid_tab where estimate_tab_id = " . $_GET['tab_id'])); ?>
		<br><br>
		<h4>
		Selected Accordion for <b><?php echo $get_current_tab['estimate_tab']; ?></b>
		<br><br>
		<?php $get_current_tab['estimate_tab_config'] = ',' . $get_current_tab['estimate_tab_config'] . ','; ?>
		<table border="0" width="100%">
			<tr>
				<?php if (strpos($get_current_tab['estimate_tab_config'], ','."Products".',') !== FALSE) {
					$product_checked = 'checked';
				}
				else {
					$product_checked = '';
				} ?>
				<?php if (strpos($get_current_tab['estimate_tab_config'], ','."Inventory".',') !== FALSE) {
					$inventory_checked = 'checked';
				}
				else {
					$inventory_checked = '';
				} ?>
				<?php if (strpos($get_current_tab['estimate_tab_config'], ','."Equipment".',') !== FALSE) {
					$equipment_checked = 'checked';
				}
				else {
					$equipment_checked = '';
				} ?>
				<?php if (strpos($get_current_tab['estimate_tab_config'], ','."Labour".',') !== FALSE) {
					$labour_checked = 'checked';
				}
				else {
					$labour_checked = '';
				} ?>
				<td><input type="checkbox" value="Products" <?php echo $product_checked; ?> disabled class='checkbox_checked' style="height: 15px; width: 20px;" name="project_path[]"> Products</td>
				<td><input type="checkbox" value="Inventory" <?php echo $inventory_checked; ?> disabled class='checkbox_checked' style="height: 15px; width: 20px;" name="project_path[]"> Inventory</td>
				<td><input type="checkbox" value="Equipment" <?php echo $equipment_checked; ?> disabled class='checkbox_checked' style="height: 15px; width: 20px;" name="project_path[]"> Equipment</td>
				<td><input type="checkbox" value="Labour" <?php echo $labour_checked; ?> disabled class='checkbox_checked' style="height: 15px; width: 20px;" name="project_path[]"> Labour</td>
			</tr>
		</table>
		</h4>
	<?php endif; ?>
</div>

<script type='text/javascript'>
	function checkTab()
	{
		if (jQuery('input.checkbox_checked').is(':checked')) {
			// Do Nothing
		}
		else {
			alert('Please Select Tab(s)');
		}
	}
</script>