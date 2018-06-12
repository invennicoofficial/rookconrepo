<?php include_once('../include.php');
checkAuthorised('contracts');

$security = get_security($dbc, 'contracts');
$pin_levels = implode(",%' OR `pinned` LIKE '%,",array_filter(explode(',',ROLE)));
$pincount = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(*) `rows` FROM `contracts` WHERE `deleted`=0 AND (CONCAT(',',`pinned`,',') LIKE '%,ALL,%' OR CONCAT(',',`pinned`,',') LIKE '%,".$pin_levels.",%' OR CONCAT(',',`pinned`,',') LIKE '%,".$_SESSION['contactid'].",%')"))['rows'];
$contract_tabs = explode('#*#',mysqli_fetch_array(mysqli_query($dbc, "SELECT `contract_tabs` FROM `field_config_contracts`"))['contract_tabs']);
array_unshift($contract_tabs, 'Favourites');
if($pincount > 0) {
	array_unshift($contract_tabs, 'Pinned');
} ?>

<script type="text/javascript">
$(document).ready(function() {
	if($(window).width() <= 767) {
		$('[name="reporting_client"]').click(function() {
			var btn = this;
			var contactid = $('[name=contactid]').val();
			var businessid = $('[name=businessid]').val();
			var category = $('[name=category]').val();
			var heading = $('[name=heading]').val();
			var s_start_date = $('[name=s_start_date]').val();
			var s_end_date = $('[name=s_end_date]').val();
			$.ajax({
				url: '../Contract/reports.php',
				method: 'POST',
				data: { contactid: contactid, businessid: businessid, category: category, heading: heading, s_start_date: s_start_date, s_end_date: s_end_date },
				success: function(response) {
					$(btn).closest('.panel-body').html(response);
				}
			});
			return false;
		});
		$('[name="display_all_asset"]').click(function() {
			var btn = this;
			$.ajax({
				url: '../Contract/reports.php',
				method: 'POST',
				success: function(response) {
					$(btn).closest('.panel-body').html(response);
				}
			});
			return false;
		});
	}
});
</script>

<div class='scale-to-fill has-main-screen'>
	<div class='main-screen standard-body form-horizontal'>
		<div class="standard-body-title hide-titles-mob">
			<h3>Reporting</h3>
		</div>
		<div class="standard-body-content pad-top" style="padding: 5px;">
			<form name="form_sites" method="post" action="" class="form-horizontal" role="form">
				<?php $contactid = '';
				$businessid = '';
		        $category = '';
		        $heading = '';
		        $s_start_date = '';
		        $s_end_date = '';
		        if(!empty($_POST['contactid'])) {
		            $contactid = $_POST['contactid'];
		        }
		        if(!empty($_POST['businessid'])) {
		            $businessid = $_POST['businessid'];
		        }
		        if(!empty($_POST['category'])) {
		            $category = $_POST['category'];
		        }
		        if(!empty($_POST['heading'])) {
		            $heading = $_POST['heading'];
		        }
		        if(!empty($_POST['s_start_date'])) {
		            $s_start_date = $_POST['s_start_date'];
		        }
		        if(!empty($_POST['s_end_date'])) {
		            $s_end_date = $_POST['s_end_date'];
		        }
		        if (isset($_POST['display_all_asset'])) {
		            $contactid = '';
					$businessid = '';
		            $category = '';
		            $heading = '';
		            $status = '';
		            $s_start_date = '';
		            $s_end_date = '';
		        } ?>

				<div class="form-group col-sm-6 col-xs-12">
					<label class="col-sm-4 col-xs-4 control-label">Staff:</label>
					<div class="col-sm-8 col-xs-8">
						<select data-placeholder="Select a Staff Member..." name="contactid" class="chosen-select-deselect form-control" width="380">
						  <option value=""></option>
						  <?php $query = sort_contacts_query(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND deleted=0 AND status > 0"));
							foreach($query as $staff) {
								echo "<option ".($contactid == $staff['contactid'] ? 'selected' : '')." value='". $staff['contactid']."'>".$staff['first_name'].' '.$staff['last_name'].'</option>';
							} ?>
						</select>
					</div>
				</div>

				<div class="form-group col-sm-6 col-xs-12">
					<label class="col-sm-4 col-xs-4 control-label">Business:</label>
					<div class="col-sm-8 col-xs-8">
						<select data-placeholder="Select a Business..." name="businessid" class="chosen-select-deselect form-control" width="380">
						  <option value=""></option>
						  <?php $query = sort_contacts_query(mysqli_query($dbc,"SELECT contactid, first_name, last_name, name FROM contacts WHERE category = 'Business' AND deleted=0 AND status > 0"), 'name');
							foreach($query as $business) {
								echo "<option ".($contactid == $business['contactid'] ? 'selected' : '')." value='". $business['contactid']."'>".$business['name'].'</option>';
							} ?>
						</select>
					</div>
				</div>

				<div class="form-group col-sm-6 col-xs-12">
					<label class="col-sm-4 col-xs-4 control-label">Category:</label>
					  <div class="col-sm-8 col-xs-8">
							<select data-placeholder="Select a Category" name="category" class="chosen-select-deselect form-control" width="380">
							  <option value=""></option>
							  <?php foreach($contract_tabs as $contract_tab) { ?>
								  <option <?= $contract_tab == $category ? 'selected' : '' ?> value='<?= $contract_tab ?>' ><?= $contract_tab ?></option>
							  <?php } ?>
							</select>
					  </div>
				</div>

				<div class="form-group col-sm-6 col-xs-12">
					<label class="col-sm-4 col-xs-4 control-label">Heading:</label>
					  <div class="col-sm-8 col-xs-8">
							<select data-placeholder="Select a Heading..." name="heading" class="chosen-select-deselect form-control" width="380">
							  <option value=""></option>
							  <?php
								$query = mysqli_query($dbc,"SELECT DISTINCT(`heading`) FROM `contracts` WHERE deleted=0 ORDER BY `heading`");
								while($row = mysqli_fetch_array($query)) { ?>
									<option <?= $row['heading'] == $heading ? 'selected' : '' ?> value='<?php echo $row['heading']; ?>' ><?php echo $row['heading']; ?></option>
								<?php }
							  ?>
							</select>
					  </div>
				</div>

				<div class="form-group col-sm-6 col-xs-12">
					<label class="col-sm-4 col-xs-4 control-label">Start Date:</label>
					<div class="col-sm-8 col-xs-8">
						<input name="s_start_date" type="text" class="datepicker form-control" value="<?php echo $s_start_date; ?>" style="width:100%;">
					</div>
				</div>

				<div class="form-group col-sm-6 col-xs-12">
					<label class="col-sm-4 col-xs-4 control-label">End Date:</label>
					<div class="col-sm-8 col-xs-8">
						<input name="s_end_date" type="text" class="datepicker form-control" value="<?php echo $s_end_date; ?>" style="width:100%;">
					</div>
				</div>

		        <div class="form-group pull-right">
					<button type="submit" name="reporting_client" value="Submit" class="btn brand-btn mobile-block">Submit</button>
					<button type="submit" name="display_all_asset" value="Display All" class="btn brand-btn mobile-block">Display All</button>
		        </div>
				<div class="clearfix"></div>

				<?php $sql = "SELECT `contracts_completed`.*, `contracts`.`category`, `contracts`.`heading`, `contracts`.`sub_heading` FROM `contracts_completed` LEFT JOIN `contracts` ON `contracts_completed`.`contractid` = `contracts`.`contractid` WHERE `contracts_completed`.`deleted` = 0 AND `contracts`.`deleted` = 0";
				if($contactid > 0) {
					$sql .= " AND `staffid` = '$contactid'";
				}
				if($category != '') {
					$sql .= " AND `category`='$category'";
				}
				if($heading != '') {
					$sql .= " AND `heading` LIKE '%$heading'";
				}
				if($businessid > 0) {
					$sql .= " AND `businessid` = '$businessid'";
				}
				if($s_start_date != '') {
					$sql .= " AND `today_date` >= '$s_start_date'";
				}
				if($s_end_date != '') {
					$sql .= " AND `today_date` <= '$s_end_date'";
				}
				$sql .= ' ORDER BY `today_date` DESC, `completedcontractid` DESC';
				$query = mysqli_query($dbc, $sql);

				if(mysqli_num_rows($query) > 0) { ?>
					<div id='no-more-tables'>
						<table class='table table-bordered'>
							<tr class="hidden-xs hidden-sm">
								<th>Staff</th>
								<th>Business</th>
								<th>Category</th>
								<th>Heading</th>
								<th>Sub Section</th>
								<th>Date</th>
								<th>PDF</th>
							</tr>
							<?php while($row = mysqli_fetch_assoc($query)) { ?>
								<tr>
									<td data-title="Staff"><?= get_contact($dbc, $row['staffid']) ?></td>
									<td data-title="Business"><?= get_client($dbc, $row['businessid']) ?></td>
									<td data-title="Category"><?= $row['category'] ?></td>
									<td data-title="Heading"><?= $row['heading'] ?></td>
									<td data-title="Sub Section"><?= $row['sub_heading'] ?></td>
									<td data-title="Date"><?= $row['today_date'] ?></td>
									<td data-title="PDF"><a href="<?= $row['contract_file'] ?>"><img class="inline-img" src="../img/pdf.png"></a></td>
								</tr>
							<?php } ?>
						</table>
					</div>
				<?php } else {
					echo "<h3>No Results Found</h3>";
				} ?>
			</form>
		</div>
	</div>
</div>