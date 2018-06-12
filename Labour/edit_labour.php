<?php include_once('../labour.php');
if (isset($_POST['add_labour'])) {

	if($_POST['new_labour'] != '') {
		$labour_type = filter_var($_POST['new_labour'],FILTER_SANITIZE_STRING);
	} else {
		$labour_type = filter_var($_POST['labour_type'],FILTER_SANITIZE_STRING);
	}

	if($_POST['new_category'] != '') {
		$category = filter_var($_POST['new_category'],FILTER_SANITIZE_STRING);
	} else {
		$category = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
	}
    $invoice_description = filter_var(htmlentities($_POST['invoice_description']),FILTER_SANITIZE_STRING);
    $ticket_description = filter_var(htmlentities($_POST['ticket_description']),FILTER_SANITIZE_STRING);
    $name = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
    $hourly_rate = filter_var($_POST['hourly_rate'],FILTER_SANITIZE_STRING);

    $labour_code = filter_var($_POST['labour_code'],FILTER_SANITIZE_STRING);
    //$category = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
    $heading = filter_var($_POST['heading'],FILTER_SANITIZE_STRING);
    $cost = filter_var($_POST['cost'],FILTER_SANITIZE_STRING);
    //$description = filter_var($_POST['description'],FILTER_SANITIZE_STRING);

    $description = filter_var(htmlentities($_POST['description']),FILTER_SANITIZE_STRING);

    if($_POST['same_desc'] == 1) {
        $quote_description = $description;
    } else {
        $quote_description = filter_var(htmlentities($_POST['quote_description']),FILTER_SANITIZE_STRING);
    }
    $daily_rate = filter_var($_POST['daily_rate'],FILTER_SANITIZE_STRING);
    $wcb = filter_var($_POST['wcb'],FILTER_SANITIZE_STRING);
    $benefits = filter_var($_POST['benefits'],FILTER_SANITIZE_STRING);
    $salary = filter_var($_POST['salary'],FILTER_SANITIZE_STRING);
    $bonus = filter_var($_POST['bonus'],FILTER_SANITIZE_STRING);
    $minimum_billable = filter_var($_POST['minimum_billable'],FILTER_SANITIZE_STRING);
    $estimated_hours = filter_var($_POST['estimated_hours'],FILTER_SANITIZE_STRING);
    $actual_hours = filter_var($_POST['actual_hours'],FILTER_SANITIZE_STRING);
    $msrp = filter_var($_POST['msrp'],FILTER_SANITIZE_STRING);

    if(empty($_POST['labourid'])) {
        $query_insert_vendor = "INSERT INTO `labour` (`labour_type`, `category`, `labour_code`, `heading`, `cost`, `description`, `quote_description`, `invoice_description`, `ticket_description`, `daily_rate`, `wcb`, `benefits`, `salary`, `bonus`, `minimum_billable`, `estimated_hours`, `actual_hours`, `msrp`, `name`, `hourly_rate`) VALUES ('$labour_type', '$category', '$labour_code', '$heading', '$cost', '$description', '$quote_description', '$invoice_description', '$ticket_description', '$daily_rate', '$wcb', '$benefits', '$salary', '$bonus', '$minimum_billable', '$estimated_hours', '$actual_hours', '$msrp', '$name', '$hourly_rate')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $labourid = mysqli_insert_id($dbc);
        $url = 'Added';
    } else {
        $labourid = $_POST['labourid'];
        $query_update_vendor = "UPDATE `labour` SET `labour_type` = '$labour_type', `category` = '$category',`labour_code` = '$labour_code', `heading` = '$heading', `cost` = '$cost', `description` = '$description', `quote_description` = '$quote_description', `invoice_description` = '$invoice_description', `ticket_description` = '$ticket_description', `daily_rate` = '$daily_rate', `wcb` = '$wcb', `benefits` = '$benefits', `salary` = '$salary', `bonus` = '$bonus', `minimum_billable` = '$minimum_billable', `estimated_hours` = '$estimated_hours', `actual_hours` = '$actual_hours', `msrp` = '$msrp', `name` = '$name', `hourly_rate` = '$hourly_rate' WHERE `labourid` = '$labourid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    include('../Labour/save_rate_card.php');

    echo '<script type="text/javascript"> window.location.replace("?category='.$labour_type.'"); </script>';

 //   mysqli_close($dbc);//Close the DB Connection
}

?>
<script type="text/javascript">
$(document).ready(function() {
    $("#form1").submit(function( event ) {
        var labour_type = $("#labour_type").val();
        var category = $("input[name=category]").val();
        var heading = $("input[name=heading]").val();
        if (labour_type == '' || category == '' || heading == '' ) {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
    });

    $("#labour_type").change(function() {
        if($("#labour_type option:selected").text() == 'New Labour') {
                $( "#new_labour" ).show();
        } else {
            $( "#new_labour" ).hide();
        }
    });

    $("#category").change(function() {
        if($("#category option:selected").text() == 'New Category') {
                $( "#new_category" ).show();
        } else {
            $( "#new_category" ).hide();
        }
    });

} );
</script>

<div class="sidebar standard-collapsible tile-sidebar hide-titles-mob">
	<ul>
		<a href='?'><li>Back to Dashboard</li></a>
		<a href='' onclick="return false;"><li class="active">Labour Information</li></a>
	</ul>
</div>

<div class="scale-to-fill has-main-screen">
	<div class="main-screen standard-body form-horizontal" style="height: auto;">
		<div class="standard-body-title">
			<h3><?= !empty($_GET['edit']) ? 'Edit' : 'Add' ?> Labour</h3>
		</div>

		<div class="standard-body-content" style="padding: 1em;">
            <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
				<?php
				$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT labour FROM field_config"));
				$value_config = ',Labour Type,Heading,'.$get_field_config['labour'].',';

				$labour_type = '';
				$category = '';
				$labour_code = '';
				$heading = '';
				$cost = '';
				$description = '';
				$quote_description = '';
				$invoice_description = '';
				$ticket_description = '';

				$daily_rate = '';
				$name = '';
				$hourly_rate = '';
				$wcb = '';
				$benefits = '';
				$salary = '';
				$bonus = '';
				$minimum_billable = '';
				$estimated_hours = '';
				$actual_hours = '';
				$msrp = '';

				if(!empty($_GET['edit'])) {

                  	$labourid = $_GET['edit'];
                  	$get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM labour WHERE labourid='$labourid'"));

                  	$labour_type = $get_contact['labour_type'];
                  	$category = $get_contact['category'];
                  	$labour_code = $get_contact['labour_code'];
                  	$heading = $get_contact['heading'];
                  	$cost = $get_contact['cost'];
                  	$description = $get_contact['description'];
                  	$quote_description = $get_contact['quote_description'];
                  	$invoice_description = $get_contact['invoice_description'];
                  	$ticket_description = $get_contact['ticket_description'];
                  	$name = $get_contact['name'];
                  	$hourly_rate = $get_contact['hourly_rate'];

                  	$daily_rate = $get_contact['daily_rate'];
                  	$wcb = $get_contact['wcb'];
                  	$benefits = $get_contact['benefits'];
                  	$salary = $get_contact['salary'];
                  	$bonus = $get_contact['bonus'];
                  	$minimum_billable = $get_contact['minimum_billable'];
                  	$estimated_hours = $get_contact['estimated_hours'];
                  	$actual_hours = $get_contact['actual_hours'];
                  	$msrp = $get_contact['msrp'];

                  	?>
                  	<input type="hidden" id="labourid" name="labourid" value="<?php echo $labourid ?>" />
              	<?php } ?>

              	<?php if (strpos($value_config, ','."Labour Type".',') !== FALSE) { ?>
              	<div class="form-group">
              		<label for="company_name" class="col-sm-4 control-label">Labour Type<span class="hp-red">*</span>:</label>
              		<div class="col-sm-8">
              			<select id="labour_type" name="labour_type" class="chosen-select-deselect form-control" width="380">
              				<option value=''></option>
              				<?php
              				$query = mysqli_query($dbc,"SELECT distinct(labour_type) FROM labour order by labour_type");
              				while($row = mysqli_fetch_array($query)) {
              					if ($labour_type == $row['labour_type']) {
              						$selected = 'selected="selected"';
              					} else {
              						$selected = '';
              					}
              					echo "<option ".$selected." value='". $row['labour_type']."'>".$row['labour_type'].'</option>';

              				}
              				echo "<option value = 'Other'>New Labour</option>";
              				?>
              			</select>
              		</div>
              	</div>

              	<div class="form-group" id="new_labour" style="display: none;">
              		<label for="travel_task" class="col-sm-4 control-label">New Labour Type:
              		</label>
              		<div class="col-sm-8">
              			<input name="new_labour" type="text" class="form-control" />
              		</div>
              	</div>
              	<?php } ?>

              	<?php if (strpos($value_config, ','."Category".',') !== FALSE) { ?>

				<!-- <div class="form-group">
				<label for="company_name" class="col-sm-4 control-label">Category<span class="hp-red">*</span>:</label>
				<div class="col-sm-8">
				<input name="category" value="<?php echo $category; ?>" type="text" id="name" class="form-control">
				</div>
				</div>
				-->

				<div class="form-group">
					<label for="company_name" class="col-sm-4 control-label">Category<span class="hp-red">*</span>:</label>
					<div class="col-sm-8">
						<select id="category" name="category" class="chosen-select-deselect form-control" width="380">
							<option value=''></option>
							<?php
							$query = mysqli_query($dbc,"SELECT distinct(category) FROM labour order by category");
							while($row = mysqli_fetch_array($query)) {
								if ($category == $row['category']) {
									$selected = 'selected="selected"';
								} else {
									$selected = '';
								}
								echo "<option ".$selected." value='". $row['category']."'>".$row['category'].'</option>';

							}
							echo "<option value = 'Other'>New Category</option>";
							?>
						</select>
					</div>
				</div>

				<div class="form-group" id="new_category" style="display: none;">
					<label for="travel_task" class="col-sm-4 control-label">New Category Name:
					</label>
					<div class="col-sm-8">
						<input name="new_category" type="text" class="form-control" />
					</div>
				</div>

				<?php } ?>

				<?php if (strpos($value_config, ','."Heading".',') !== FALSE) { ?>
				<div class="form-group">
					<label for="company_name" class="col-sm-4 control-label">Heading<span class="hp-red">*</span>:</label>
					<div class="col-sm-8">
						<input name="heading" value="<?php echo $heading; ?>" type="text" id="name" class="form-control">
					</div>
				</div>
				<?php } ?>

				<?php if (strpos($value_config, ','."Name".',') !== FALSE) { ?>
				<div class="form-group">
					<label for="company_name" class="col-sm-4 control-label">Name<span class="hp-red">*</span>:</label>
					<div class="col-sm-8">
						<input name="name" value="<?php echo $name; ?>" type="text" id="name" class="form-control">
					</div>
				</div>
				<?php } ?>

				<?php if (strpos($value_config, ','."Labour Code".',') !== FALSE) { ?>
				<div class="form-group">
					<label for="company_name" class="col-sm-4 control-label">Labour Code:</label>
					<div class="col-sm-8">
						<input name="labour_code" value="<?php echo $labour_code; ?>" type="text" id="name" class="form-control">
					</div>
				</div>
				<?php } ?>

				<?php if (strpos($value_config, ','."Description".',') !== FALSE) { ?>
				<div class="form-group">
					<label for="first_name[]" class="col-sm-4 control-label">Description:</label>
					<div class="col-sm-8">
						<textarea name="description" rows="5" cols="50" class="form-control"><?php echo $description; ?></textarea>
					</div>
				</div>
				<?php } ?>

				<?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { ?>
				<div class="form-group">
					<label for="first_name[]" class="col-sm-4 control-label"></label>
					<div class="col-sm-8">
						<input type="checkbox" value="1" name="same_desc">Check this if Quote Description is same as Description.
					</div>
				</div>

				<div class="form-group">
					<label for="first_name[]" class="col-sm-4 control-label">Quote Description:</label>
					<div class="col-sm-8">
						<textarea name="quote_description" rows="5" cols="50" class="form-control"><?php echo $quote_description; ?></textarea>
					</div>
				</div>
				<?php } ?>

				<?php if (strpos($value_config, ','."Invoice Description".',') !== FALSE) { ?>
				<div class="form-group">
					<label for="company_name" class="col-sm-4 control-label">Invoice Description:</label>
					<div class="col-sm-8">
						<textarea name="invoice_description" rows="5" cols="50" class="form-control"><?php echo $invoice_description; ?></textarea>
					</div>
				</div>
				<?php } ?>

				<?php if (strpos($value_config, ','."Ticket Description".',') !== FALSE) { ?>
				<div class="form-group">
					<label for="company_name" class="col-sm-4 control-label"><?= TICKET_NOUN ?> Description:</label>
					<div class="col-sm-8">
						<textarea name="ticket_description" rows="5" cols="50" class="form-control"><?php echo $ticket_description; ?></textarea>
					</div>
				</div>
				<?php } ?>

				<!-- <?php if (strpos($value_config, ','."Hourly Rate".',') !== FALSE) { ?>
				<div class="form-group">
					<label for="company_name" class="col-sm-4 control-label">Hourly Rate<span class="hp-red">*</span>:</label>
					<div class="col-sm-8">
						<input name="hourly_rate" value="<?php echo $hourly_rate; ?>" type="text" id="name" class="form-control">
					</div>
				</div>
				<?php } ?> -->

				<!-- <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { ?>
				<div class="form-group">
					<label for="company_name" class="col-sm-4 control-label">Cost:</label>
					<div class="col-sm-8">
						<input name="cost" value="<?php echo $cost; ?>" type="text" class="form-control">
					</div>
				</div>
				<?php } ?> -->

				<!-- <?php if (strpos($value_config, ','."Daily Rate".',') !== FALSE) { ?>
				<div class="form-group">
					<label for="company_name" class="col-sm-4 control-label">Daily Rate:</label>
					<div class="col-sm-8">
						<input name="daily_rate" value="<?php echo $daily_rate; ?>" type="text" class="form-control">
					</div>
				</div>
				<?php } ?> -->

				<?php if (strpos($value_config, ','."WCB".',') !== FALSE) { ?>
				<div class="form-group">
					<label for="company_name" class="col-sm-4 control-label">WCB:</label>
					<div class="col-sm-8">
						<input name="wcb" value="<?php echo $wcb; ?>" type="text" class="form-control">
					</div>
				</div>
				<?php } ?>

				<?php if (strpos($value_config, ','."Benefits".',') !== FALSE) { ?>
				<div class="form-group">
					<label for="company_name" class="col-sm-4 control-label">Benefits:</label>
					<div class="col-sm-8">
						<input name="benefits" value="<?php echo $benefits; ?>" type="text" class="form-control">
					</div>
				</div>
				<?php } ?>

				<?php if (strpos($value_config, ','."Salary".',') !== FALSE) { ?>
				<div class="form-group">
					<label for="company_name" class="col-sm-4 control-label">Salary:</label>
					<div class="col-sm-8">
						<input name="salary" value="<?php echo $salary; ?>" type="text" class="form-control">
					</div>
				</div>
				<?php } ?>

				<?php if (strpos($value_config, ','."Bonus".',') !== FALSE) { ?>
				<div class="form-group">
					<label for="company_name" class="col-sm-4 control-label">Bonus:</label>
					<div class="col-sm-8">
						<input name="bonus" value="<?php echo $bonus; ?>" type="text" class="form-control">
					</div>
				</div>
				<?php } ?>

				<?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { ?>
				<div class="form-group">
					<label for="company_name" class="col-sm-4 control-label">Minimum Billable:</label>
					<div class="col-sm-8">
						<input name="minimum_billable" value="<?php echo $minimum_billable; ?>" type="text" class="form-control">
					</div>
				</div>
				<?php } ?>

				<?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { ?>
				<div class="form-group">
					<label for="company_name" class="col-sm-4 control-label">Estimated Hours:</label>
					<div class="col-sm-8">
						<input name="estimated_hours" value="<?php echo $estimated_hours; ?>" type="text" class="form-control">
					</div>
				</div>
				<?php } ?>

				<?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { ?>
				<div class="form-group">
					<label for="company_name" class="col-sm-4 control-label">Actual Hours:</label>
					<div class="col-sm-8">
						<input name="actual_hours" value="<?php echo $actual_hours; ?>" type="text" class="form-control">
					</div>
				</div>
				<?php } ?>

				<?php if (strpos($value_config, ','."MSRP".',') !== FALSE) { ?>
				<div class="form-group">
					<label for="company_name" class="col-sm-4 control-label">MSRP:</label>
					<div class="col-sm-8">
						<input name="msrp" value="<?php echo $msrp; ?>" type="text" class="form-control">
					</div>
				</div>
				<?php } ?>

				<?php if (strpos($value_config, ','."Rate Card".',') !== FALSE) {
					include('../Labour/edit_rate_card.php');
				} ?>

				<div class="form-group">
					<p><span class="hp-red"><em>Required Fields *</em></span></p>

	                <div class="pull-right">
	                    <a href="?" class="btn brand-btn">Cancel</a>
	                    <button type="submit" name="add_labour" value="Submit" class="btn brand-btn">Submit</button>
	                </div>
				</div>
			</form>
		</div>
	</div>
</div>