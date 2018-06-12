<div class='main_frame' id='no-more-tables'>
	<?php
	$id = false;
	if(isset($_GET['id']))
		$id = is_numeric($_GET['id']) ? $_GET['id'] : false;
	
	$sql = "SELECT * FROM `company_rate_card` WHERE `companyrcid`='$id'";
	$row = mysqli_fetch_array(mysqli_query($dbc, $sql));
	if($id === false || mysqli_num_rows($result) > 0): ?>
		<div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_abi" >Rate Card Info<span class="glyphicon glyphicon-minus"></span></a>
                </h4>
            </div>

            <div id="collapse_abi" class="panel-collapse collapse in">
                <div class="panel-body">
					<div class='form-group clearfix completion_date'><label class='col-sm-4 control-label text-right'>Rate Card:</label>
					<div class='col-sm-8'><?php echo $row['rate_card_name']; ?></div></div>
					<div class='form-group clearfix completion_date'><label class='col-sm-4 control-label text-right'>Category:</label>
					<div class='col-sm-8'><?php echo $row['description']; ?></div></div>
					<?php $field_config = get_config($dbc, 'category_rate_fields');
					if(str_replace(',','',$field_config) == '') {
						$field_config = ",annual,monthly,hourly,";
					}
					if(strpos($field_config, ',start_end_dates,') !== false) { ?>
						<div class='form-group clearfix completion_date'><label class='col-sm-4 control-label text-right'>Start Date</label>
						<div class='col-sm-8'><?php echo $row['start_date']; ?></div></div>
						<div class='form-group clearfix completion_date'><label class='col-sm-4 control-label text-right'>End Date</label>
						<div class='col-sm-8'><?php echo $row['end_date']; ?></div></div>
					<?php }
					if(strpos($field_config, ',reminder_alerts,') !== false) { ?>
						<div class='form-group clearfix completion_date'><label class='col-sm-4 control-label text-right'>Alert Date</label>
						<div class='col-sm-8'><?php echo $row['alert_date']; ?></div></div>
						<div class='form-group clearfix completion_date'><label class='col-sm-4 control-label text-right'>Alert Staff</label>
						<div class='col-sm-8'><?php foreach(explode(',',$row['alert_staff']) as $staff_id) {
							if($staff_id > 0) {
								echo get_contact($dbc, $staff_id);
							}
						} ?>
						</div></div>
					<?php }
					if(strpos($field_config, ',daily,') !== false) { ?>
						<div class='form-group clearfix completion_date'><label class='col-sm-4 control-label text-right'>Daily Rate</label>
						<div class='col-sm-8'><?php echo $row['daily']; ?></div></div>
					<?php }
					if(strpos($field_config, ',hourly,') !== false) { ?>
						<div class='form-group clearfix completion_date'><label class='col-sm-4 control-label text-right'>Hourly Rate</label>
						<div class='col-sm-8'><?php echo $row['hourly']; ?></div></div>
					<?php }
					if(strpos($field_config, ',cost,') !== false) { ?>
						<div class='form-group clearfix completion_date'><label class='col-sm-4 control-label text-right'>Cost</label>
						<div class='col-sm-8'><?php echo $row['cost']; ?></div></div>
					<?php }
					if(strpos($field_config, ',unit_price,') !== false) { ?>
						<div class='form-group clearfix completion_date'><label class='col-sm-4 control-label text-right'>Price</label>
						<div class='col-sm-8'><?php echo $row['cust_price']; ?></div></div>
					<?php }
					if(strpos($field_config, ',uom,') !== false) { ?>
						<div class='form-group clearfix completion_date'><label class='col-sm-4 control-label text-right'>UoM</label>
						<div class='col-sm-8'><?php echo $row['uom']; ?></div></div>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>