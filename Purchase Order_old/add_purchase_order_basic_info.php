<?php if (strpos($value_config, ','."Business".',') !== FALSE) { ?>
<div class="form-group clearfix">
    <label for="first_name" class="col-sm-4 control-label text-right">Business:</label>
    <div class="col-sm-8">
        <select data-placeholder="Choose a Business..." name="businessid" id="businessid" class="chosen-select-deselect form-control" width="380">
          <option value=""></option>
          <?php
            $query = mysqli_query($dbc,"SELECT name, contactid FROM contacts WHERE category='Business' AND deleted=0 ORDER BY name");
            while($row = mysqli_fetch_array($query)) {
                if ($businessid == $row['contactid']) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }
                echo "<option ".$selected." value='". $row['contactid']."'>".$row['name'].'</option>';
            }
          ?>
        </select>
    </div>
</div>
<?php } ?>
<?php if (strpos($value_config, ','."Project".',') !== FALSE) { ?>
<div class="form-group">
  <label for="site_name" class="col-sm-4 control-label">Project:</label>
  <div class="col-sm-8">
    <select data-placeholder="Choose a Project..." name="projectid" id="projectid"  class="chosen-select-deselect form-control" width="380">
      <option value=""></option>
      <?php
        $query = mysqli_query($dbc,"SELECT projectid, project_name FROM project");
        while($row = mysqli_fetch_array($query)) {
            if ($projectid == $row['projectid']) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }
            echo "<option ".$selected." value='". $row['projectid']."'>".$row['project_name'].'</option>';
        }
      ?>
    </select>
  </div>
</div>
<?php } ?>
<?php if (strpos($value_config, ','."Ticket".',') !== FALSE) { ?>
<div class="form-group">
  <label for="site_name" class="col-sm-4 control-label">Ticket:</label>
  <div class="col-sm-8">
    <select data-placeholder="Choose a Ticket..." name="ticketid" id="ticketid"  class="chosen-select-deselect form-control" width="380">
      <option value=""></option>
      <?php
        $query = mysqli_query($dbc,"SELECT ticketid, service_type, heading FROM tickets WHERE status!='Archived'");
        while($row = mysqli_fetch_array($query)) {
            if ($ticketid == $row['ticketid']) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }
            echo "<option ".$selected." value='". $row['ticketid']."'>".$row['service_type'].' : '.$row['heading'].'</option>';
        }
      ?>
    </select>
  </div>
</div>
<?php } ?>
<?php if (strpos($value_config, ','."Work Order".',') !== FALSE) { ?>
<div class="form-group">
  <label for="site_name" class="col-sm-4 control-label">Work Order:</label>
  <div class="col-sm-8">
    <select data-placeholder="Choose a Work Order..." name="workorderid" id="workorderid"  class="chosen-select-deselect form-control" width="380">
      <option value=""></option>
      <?php
        $query = mysqli_query($dbc,"SELECT workorderid, service_type, heading FROM workorder WHERE status!='Archived'");
        while($row = mysqli_fetch_array($query)) {
            if ($workorderid == $row['workorderid']) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }
            echo "<option ".$selected." value='". $row['workorderid']."'>".$row['service_type'].' : '.$row['heading'].'</option>';
        }
      ?>
    </select>
  </div>
</div>
<?php } ?>
<?php if (strpos($value_config, ','."Vendor".',') !== FALSE) { ?>
<div class="form-group vendor">
    <label for="fax_number"	class="col-sm-4	control-label">Vendor:</label>
    <div class="col-sm-8">
        <select <?php if ($type !== '') { echo "readonly";} ?>  data-placeholder="Choose a Vendor..." id="vendorid" name="vendorid" class="chosen-select-deselect form-control" width="380">
          <option value=""></option>
		  <?php
			$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Vendor' AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
			foreach($query as $id) {
				$selected = '';
				$selected = $id == $vendorid ? 'selected = "selected"' : '';
				echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id, 'name').' '.get_contact($dbc, $id).'</option>';
			}
		  ?>
        </select>
    </div>
  </div>
<?php } ?>
<?php if (strpos($value_config, ','."Issue Date".',') !== FALSE) { ?>
<div class="form-group vendor">
    <label for="first_name" class="col-sm-4 control-label text-right">Issue Date:</label>
    <div class="col-sm-8">
        <input name="issue_date" type="text" value="<?php echo $issue_date;?>" class="datepicker"></p>
    </div>
</div>
<?php } ?>