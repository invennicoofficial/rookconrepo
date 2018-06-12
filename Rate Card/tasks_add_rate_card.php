<?php // Edit Category Rate Card
if (isset($_POST['submit'])) {
	require_once('../include.php');
	$rate_card_name = filter_var($_POST['rate_card'],FILTER_SANITIZE_STRING);
	$taskid = filter_var($_POST['taskid'],FILTER_SANITIZE_STRING);
	$start_date = filter_var($_POST['start_date'],FILTER_SANITIZE_STRING);
	$end_date = filter_var($_POST['end_date'],FILTER_SANITIZE_STRING);
	$alert_date = filter_var($_POST['alert_date'],FILTER_SANITIZE_STRING);
	$alert_staff = filter_var(implode(',',$_POST['alert_staff']),FILTER_SANITIZE_STRING);
	$uom = filter_var($_POST['uom'],FILTER_SANITIZE_STRING);
	if($uom == 'NEW_UOM') {
		$uom = filter_var($_POST['uom_new'],FILTER_SANITIZE_STRING);
	}
	$cost = filter_var($_POST['cost'],FILTER_SANITIZE_STRING);
	$margin = filter_var($_POST['margin'],FILTER_SANITIZE_STRING);
	$profit = filter_var($_POST['profit'],FILTER_SANITIZE_STRING);
	$price = filter_var($_POST['price'],FILTER_SANITIZE_STRING);
    $ratecardid = $_POST['submit'];

	$history = 'Task rate card '.($ratecardid == '' ? 'Added' : 'Edited').' by '.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' on '.date('Y-m-d h:i:s');
	$sql = '';
	if($ratecardid == '') {
		$sql = "INSERT INTO `company_rate_card` (`rate_card_name`,`tile_name`,`item_id`,`start_date`,`end_date`,`uom`,`cost`,`margin`,`profit`,`cust_price`) VALUES
			('$rate_card_name','Tasks','$taskid','$start_date','$end_date','$uom','$cost','$margin','$profit','$price')";
	}
	else {
		$sql = "UPDATE `company_rate_card` SET `rate_card_name`='$rate_card_name',`item_id`='$taskid',`start_date`='$start_date',`end_date`='$end_date',`uom`='$uom',`cost`='$cost',`margin`='$margin',`profit`='$profit',`cust_price`='$price' WHERE `companyrcid`='$ratecardid'";
	}

	$result = mysqli_query($dbc, $sql);
	echo '<script type="text/javascript"> window.location.replace("?card=tasks&type=tasks&t='.$_GET['t'].'"); </script>';
} ?>
<script type="text/javascript">
$(document).ready(function() {
	$('[name="category"],[name="taskid"]').each(function() {
		if($(this).val() != undefined && $(this).val() != '') {
			filterTasks(this);
		}
	});
});
$(document).on('change', 'select[name="uom"]', function() { addNewUom(this); });
$(document).on('change', 'select[name="category"],select[name="taskid"]', function() { filterTasks(this); });
$(document).on('change', '.price_controls', function() { calculatePrices(this); });
function addNewUom(sel) {
	if($(sel).val() == 'NEW_UOM') {
		$('[name="uom_new"]').show();
	} else {
		$('[name="uom_new"]').hide();
	}
}
function filterTasks(sel) {
	var category = $('[name="category"]');
	var category_filter = '';
	if($(category).val() != undefined && $(category).val() != '') {
		category_filter = '[data-category="'+$(category).val()+'"]';
	}
	if(sel.name == 'category') {
		$('[name="taskid"] option').hide();
		$('[name="taskid"] option'+category_filter).show();
		$('[name="taskid"]').trigger('change.select2');
	} else if(sel.name == 'taskid') {
		$('[name="category"]').val($('[name="taskid"]').find('option:selected').data('category'));
		$('[name="category"]').trigger('change.select2');
	}
}
function calculatePrices(input) {
	var cost = $('[name="cost"]').val();
	var margin = $('[name="margin"]').val() > 0 ? $('[name="margin"]').val() : 0;
	var profit = $('[name="profit"]').val() > 0 ? $('[name="profit"]').val() : 0;
	var price = $('[name="price"]').val() > 0 ? $('[name="price"]').val() : 0;
	if(cost != undefined && cost > 0) {
		if(input.name == 'cost') {
			if(margin > 0 && !(profit > 0)) {
				profit = parseFloat(cost) * parseFloat(margin) / 100;
				$('[name="profit"]').val(parseFloat(profit).toFixed(2));
			}
			price = parseFloat(cost) + parseFloat(profit);
			$('[name="price"]').val(parseFloat(price).toFixed(2));
		} else if(input.name == 'margin') {
			profit = parseFloat(cost) * parseFloat(margin) / 100;
			$('[name="profit"]').val(parseFloat(profit).toFixed(2));

			price = parseFloat(cost) + parseFloat(profit);
			$('[name="price"]').val(parseFloat(price).toFixed(2));
		} else if(input.name == 'profit') {
			margin = parseFloat(profit) / parseFloat(cost) * 100;
			$('[name="margin"]').val(parseFloat(margin).toFixed(2));

			price = parseFloat(cost) + parseFloat(profit);
			$('[name="price"]').val(parseFloat(price).toFixed(2));
		} else if(input.name == 'price') {
			if(margin > 0 && !(profit > 0)) {
				profit = parseFloat(price) * parseFloat(margin) / 100;
			}
			cost = parseFloat(price) - parseFloat(profit);
			$('[name="cost"]').val(parseFloat(cost).toFixed(2));
		}
	}
}
</script>

<div class='main_frame' id='no-more-tables'><form id="task_rate" name="task_rate" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
	<h3>Task Rate Card</h3>
    <?php
    $id = $_GET['id'];
	if($id > 0) {
		$ratecard = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `company_rate_card` WHERE `companyrcid`='$id'"));
	} else {
		$ratecard = ['item_id'=>filter_var($_GET['task'],FILTER_SANITIZE_STRING)];
	}
    $task = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `task_types` WHERE `id`='{$ratecard['item_id']}'"));

    $field_config = ',category,heading,start_date,end_date,cost,profit,margin,price,';
	$rates_sql = "SELECT `rate_card_name` FROM `company_rate_card` WHERE `deleted`=0 GROUP BY `rate_card_name` ORDER BY `rate_card_name`";
	$rate_results = mysqli_query($dbc, $rates_sql); ?>
	<div class='form-group clearfix completion_date'><label class='col-sm-4 control-label text-right'>Rate Card:</label>
		<div class='col-sm-8'>
			<select name='rate_card' data-placeholder='Select Rate Card' class='chosen-select-deselect form-control'><option></option>
			<?php while($rate_name = mysqli_fetch_array($rate_results)) {
				echo "<option".($rate_name['rate_card_name'] == $ratecard['rate_card_name'] ? ' selected' : '')." value='{$rate_name['rate_card_name']}' title='{$rate_name['rate_card_name']}'>{$rate_name['rate_card_name']}</option>";
			} ?>
			</select>
		</div>
	</div>
	<?php if(strpos($field_config, ',category,') !== FALSE) { ?>
		<div class="form-group">
			<label class="col-sm-4 control-label">Category:</label>
			<div class="col-sm-8">
				<select name="category" data-placeholder="Select a Category..." class="chosen-select-deselect form-control">
					<option></option>
					<?php $query = mysqli_query($dbc, "SELECT DISTINCT(`category`) FROM `task_types` WHERE `deleted` = 0 ORDER BY `category`");
					while($row = mysqli_fetch_array($query)) { ?>
						<option value="<?= $row['category'] ?>" <?= $task['category'] == $row['category'] ? 'selected' : '' ?>><?= $row['category'] ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	<?php } ?>

	<?php if(strpos($field_config, ',heading,') !== FALSE) { ?>
		<div class="form-group">
			<label class="col-sm-4 control-label">Heading:</label>
			<div class="col-sm-8">
				<select name="taskid" data-placeholder="Select a Heading..." class="chosen-select-deselect form-control">
					<option></option>
					<?php $query = mysqli_query($dbc, "SELECT * FROM `task_types` WHERE `deleted` = 0 ORDER BY `description`");
					while($row = mysqli_fetch_array($query)) { ?>
						<option data-category="<?= $row['category'] ?>" value="<?= $row['id'] ?>" <?= $task['id'] == $row['id'] ? 'selected' : '' ?>><?= $row['description'] ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	<?php } ?>

	<?php if(strpos($field_config, ',start_date,') !== FALSE) { ?>
		<div class="form-group">
			<label class="col-sm-4 control-label">Start Date:</label>
			<div class="col-sm-8">
				<input type="text" name="start_date" class="form-control datepicker" value="<?= $ratecard['start_date'] ?>">
			</div>
		</div>
	<?php } ?>

	<?php if(strpos($field_config, ',end_date,') !== FALSE) { ?>
		<div class="form-group">
			<label class="col-sm-4 control-label">End Date:</label>
			<div class="col-sm-8">
				<input type="text" name="end_date" class="form-control datepicker" value="<?= $ratecard['end_date'] ?>">
			</div>
		</div>
	<?php } ?>
	<?php if(strpos($field_config, ',uom,') !== FALSE) { ?>
		<div class="form-group">
			<label class="col-sm-4 control-label">UOM:</label>
			<div class="col-sm-8">
				<select name="uom" data-placeholder="Select a UOM..." class="chosen-select-deselect form-control">
					<option></option>
					<option value="NEW_UOM">Add New UOM</option>
					<?php $query = mysqli_query($dbc, "SELECT DISTINCT(`uom`) FROM `company_rate_card` WHERE `deleted` = 0 AND IFNULL(`uom`,'') != '' ORDER BY `uom`");
					while($row = mysqli_fetch_array($query)) { ?>
						<option value="<?= $row['uom'] ?>" <?= $ratecard['uom'] == $row['uom'] ? 'selected' : '' ?>><?= $row['uom'] ?></option>
					<?php } ?>
				</select>
				<input type="text" name="uom_new" class="form-control" style="display: none;">
			</div>
		</div>
	<?php } ?>

	<?php if(strpos($field_config, ',cost,') !== FALSE) { ?>
		<div class="form-group">
			<label class="col-sm-4 control-label">Cost:</label>
			<div class="col-sm-8">
				<input type="number" name="cost" class="form-control price_controls" value="<?= $ratecard['cost'] ?>" min="0.00" step="0.01">
			</div>
		</div>
	<?php } ?>

	<?php if(strpos($field_config, ',margin,') !== FALSE) { ?>
		<div class="form-group">
			<label class="col-sm-4 control-label">Profit %:</label>
			<div class="col-sm-8">
				<input type="number" name="margin" class="form-control price_controls" value="<?= $ratecard['margin'] ?>" min="0.00" step="0.01">
			</div>
		</div>
	<?php } ?>

	<?php if(strpos($field_config, ',profit,') !== FALSE) { ?>
		<div class="form-group">
			<label class="col-sm-4 control-label">Profit $:</label>
			<div class="col-sm-8">
				<input type="number" name="profit" class="form-control price_controls" value="<?= $ratecard['profit'] ?>" min="0.00" step="0.01">
			</div>
		</div>
	<?php } ?>

	<?php if(strpos($field_config, ',price,') !== FALSE) { ?>
		<div class="form-group">
			<label class="col-sm-4 control-label">Price:</label>
			<div class="col-sm-8">
				<input type="number" name="price" class="form-control price_controls" value="<?= $ratecard['cust_price'] ?>" min="0.00" step="0.01">
			</div>
		</div>
	<?php } ?>
	<button type='submit' name='submit' value='<?php echo $id; ?>' class="btn brand-btn btn-lg pull-right">Submit</button>
</div>