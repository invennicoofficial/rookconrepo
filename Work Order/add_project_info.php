<div class="col-md-12">
		<div class="form-group">
		  <label for="site_name" class="col-sm-4 control-label"><?php if (PROJECT_TILE=='Projects') { echo "Project"; } else { echo PROJECT_TILE; } ?> Name<span class="text-red">*</span>:</label>
		  <div class="col-sm-8">
			<select data-placeholder="Select a Project..." name="projectid" data-table="workorder" data-id="<?= $workorderid ?>" data-id-field="workorderid" id="projectid"  class="chosen-select-deselect form-control" width="380">
			  <option value=""></option>
			  <?php
				$query = mysqli_query($dbc,"SELECT projectid, project_name FROM project order by project_name");
				while($row = mysqli_fetch_array($query)) {
					echo "<option ".($_GET['projectid'] == $row['projectid'] ? 'selected' : '')." value='". $row['projectid']."'>#".$row['projectid'].' : '.$row['project_name'].'</option>';
				}
			  ?>
			</select>
		  </div>
		</div>
</div>

<?php if ( strpos($value_config, ',PI Business,') !== false || ( strpos($value_config, ','."PI Business".',') === false && strpos($value_config, ','."PI Name".',') === false) ) { ?>
	<div class="form-group clearfix completion_date">
		<label for="first_name" class="col-sm-4 control-label text-right"><span class="text-red">*</span> Business:</label>
		<div class="col-sm-8">
			<select name="businessid" id="businessid" data-placeholder="Select a Business..." data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" class="chosen-select-deselect form-control" width="380" onchange="businessFilter();">
				<option value=''></option><?php
				$query = mysqli_query($dbc, "SELECT `contactid`, `name` FROM `contacts` WHERE `category`='Business' AND `deleted`=0 ORDER BY `category`");
				while($row = mysqli_fetch_array($query)) {
					if ($businessid== $row['contactid']) {
						$selected = 'selected="selected"';
					} else {
						$selected = '';
					}
					echo "<option ".$selected." value='". $row['contactid']."'>".decryptIt($row['name']).'</option>';
				} ?>
			</select>
		</div>
	</div>
<?php } ?>

<?php if ( strpos($value_config, ',PI Name,') !== false ) { ?>
	<div class="form-group clearfix completion_date">
		<label for="first_name" class="col-sm-4 control-label text-right"><span class="text-red">*</span> Name:</label>
		<div class="col-sm-8">
			<select name="clientid" id="clientid" data-placeholder="Select a Contact..." data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" class="chosen-select-deselect form-control" width="380" onchange="clientFilter();">
				<option value=''></option><?php
				$query = mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name`, `businessid` FROM `contacts` WHERE `deleted`=0");
				while ( $row=mysqli_fetch_array($query) ) {
					$selected = ( $clientid==$row['contactid'] ) ? 'selected="selected"' : ($businessid > 0 && $businessid != $row['businessid'] ? 'style="display:none;"' : '');
					echo '<option data-business="'.$row['businessid'].'" '. $selected .' value="'. $row['contactid'] .'">'. decryptIt($row['first_name']) . ' ' . decryptIt($row['last_name']) .'</option>';
				} ?>
			</select>
		</div>
	</div>
<?php } ?>