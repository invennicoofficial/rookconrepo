<?php $_GET['tab'] = 'Payroll';
$_GET['tile'] = 'Shop Work Orders';
$tile = $_GET['tile'];
$tab_url = $tab = $_GET['tab']; ?>
<form name="form_sites" method="post" action="" class="form-inline" role="form">
	<input type='hidden' class='' value='<?php echo $_GET['tile']; ?>' name='tile_get'>
	<input type='hidden' class='' value='<?php echo $_GET['tab']; ?>' name='tab_get'>

	<?php

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT project_manage_dashboard, dashboard_view, tile_data, tile_employee FROM field_config_project_manage WHERE tile='$tile' AND tab='$tab_url' AND project_manage_dashboard IS NOT NULL"));
	$value_config = ','.$get_field_config['project_manage_dashboard'].',';
	$dashboard_view = $get_field_config['dashboard_view'];
	$tile_data = $get_field_config['tile_data'];
	$tile_employee = $get_field_config['tile_employee'];

	$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT project_path FROM project_workflow WHERE tile_name='$tile'"));

	$project_path = $get_config['project_path'];

	$to = explode(',', $project_path);
?>
	<?php
		if(!empty($_GET['tab'])){
	?>
<div class="pad-top pad-bottom">
		<?php
			$dropdownstaff='';
			$dropdownworkorder='';
			$dropdownworkdate='';
			$dropdownworkenddate = '';

			if(isset($_POST['search_shopworkorder_submit'])) {
				if (isset($_POST['search_staff'])) {
					$dropdownstaff = $_POST['search_staff'];
				}
				if (isset($_POST['search_workorder'])) {
					$dropdownworkorder = $_POST['search_workorder'];
				}
				if (isset($_POST['search_date'])) {
					$dropdownworkdate = $_POST['search_date'];
				}
				if (isset($_POST['end_search_date'])) {
					$dropdownworkenddate = $_POST['end_search_date'];
				}
			}
			if (isset($_POST['display_all_shopworkorder'])) {
				$dropdownstaff='';
				$dropdownworkorder='';
				$dropdownworkdate='';
				$dropdownworkenddate = '';
			}
		?>
		<?php
			if (strpos($value_config, ','."Search By Staff".',') !== FALSE) {
		?>
		<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
			<label for="search_staff" class="control-label">Search By Staff:</label>
		</div>
		<div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
			<select data-placeholder="Pick a Client" name="search_staff" class="form-control">
			  <option value="">Select</option>
			<?php
				$query_search_staff = mysqli_query($dbc,"SELECT DISTINCT c.first_name, c.last_name, c.contactid FROM contacts c, project_manage_assign_to_timer p WHERE c.contactid = p.created_by");
				while($row_search_staff = mysqli_fetch_array($query_search_staff)) {
				?><option <?php if($row_search_staff['contactid']==$dropdownstaff){ echo "selected"; } ?> value='<?php echo $row_search_staff['contactid']; ?>' ><?php echo $row_search_staff['first_name'].' '.$row_search_staff['last_name']; ?></option>
			<?php } ?>
			</select>
		</div>
		<?php }
		if (strpos($value_config, ','."Search By Work Order".',') !== FALSE) {
		?>
		<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
			<label for="search_workorder" class="control-label">Search By Work Orders:</label>
		</div>
		<div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
			<select data-placeholder="Pick a Client" name="search_workorder" class="form-control">
			  <option value="">Select</option>
			<?php
			  $query_search_workorder = mysqli_query($dbc,"SELECT DISTINCT timer_task FROM project_manage_assign_to_timer WHERE timer_task!='' AND timer_task!='0'");
				while($row_search_workorder = mysqli_fetch_array($query_search_workorder)) {
				?><option <?php if($row_search_workorder['timer_task']==$dropdownworkorder){ echo "selected"; } ?> value='<?php echo $row_search_workorder['timer_task']; ?>' ><?php echo $row_search_workorder['timer_task']; ?></option>
			<?php } ?>
			</select>
		</div>
		<?php }
		if (strpos($value_config, ','."Search By Date".',') !== FALSE) {
		?>
			<br /><br />
			<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
				<label for="search_date" class="control-label">Search From Date:</label>
			</div>
			<div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
				<?php if(isset($_POST['search_shopworkorder_submit'])) { ?>
					<input type="text" name="search_date" class="datepicker" value="<?php echo $_POST['search_date']?>" style="width:100%;">
				<?php } else { ?>
					<input type="text" name="search_date" class="datepicker" style="width:100%;">
				<?php } ?>
			</div>
			<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
				<label for="search_date" class="control-label">Search Until Date:</label>
			</div>
			<div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
				<?php if(isset($_POST['search_shopworkorder_submit'])) { ?>
					<input type="text" name="end_search_date" class="datepicker" value="<?php echo $_POST['end_search_date']?>" style="width:100%;">
				<?php } else { ?>
					<input type="text" name="end_search_date" class="datepicker" style="width:100%;">
				<?php } ?>
			</div>
		<?php }
		if ((strpos($value_config, ','."Search By Staff".',') !== FALSE) || (strpos($value_config, ','."Search By Work Order".',') !== FALSE) || (strpos($value_config, ','."Search By Date".',') !== FALSE)) {
		?>
		<div class='mobile-100-container'>
			<div class="form-group gap-right">
				<button type="submit" name="search_shopworkorder_submit" value="Search" class="btn brand-btn mobile-block mobile-100">Search</button>
			</div>
			<div class="form-group gap-right">
				<button type="submit" name="display_all_shopworkorder" value="Display All" class="btn brand-btn mobile-block mobile-100">Display All</button>
			</div>
		</div>
		<?php
			}
		?>
	</div>
	<?php } ?>
<div id="no-more-tables"> <?php
$current_url = WEBSITE_URL.'/Payroll/payroll.php?tab=shop_work_order';
include_once("../Shop Time Sheet/shop_time_sheets.php");
?>

</form>

</div>

</div>