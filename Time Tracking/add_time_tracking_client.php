<script type="text/javascript">
$(document).ready(function() {
    $("#businessid").change(function() {
		window.location = 'add_time_tracking.php?bid='+this.value;
	});
});
</script>

<div class="col-md-12">

    <div class="form-group clearfix completion_date">
        <label for="first_name" class="col-sm-4 control-label text-right"><?= BUSINESS_CAT ?><span class="brand-color">*</span>:</label>
        <div class="col-sm-8">
            <select name="businessid" id="businessid" data-placeholder="Choose an Option..." class="chosen-select-deselect form-control" width="380">
                <option value=''></option>
                <?php
                $query = mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE category='".BUSINESS_CAT."' AND deleted=0 ORDER BY category");
                while($row = mysqli_fetch_array($query)) {
                    if ($businessid== $row['contactid']) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    echo "<option ".$selected." value='". $row['contactid']."'>".decryptIt($row['name']).'</option>';
                }
                ?>
            </select>
        </div>
    </div>

		<div class="form-group">
		  <label for="site_name" class="col-sm-4 control-label">Contact<span class="text-red">*</span>:</label>
		  <div class="col-sm-8">
			<select data-placeholder="Choose a Contact..." id="clientid" name="contactid" class="chosen-select-deselect form-control" width="380">
			  <option value=""></option>
			  <?php
				$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE businessid='$businessid' AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
				foreach($query as $id) {
					$selected = '';
					$selected = $id == $contactid ? 'selected = "selected"' : '';
					echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
				}
			  ?>
			</select>
		  </div>
		</div>

        <div class="form-group">
            <div class="col-sm-4">
				<a href="time_tracking.php" class="btn brand-btn">Back</a>
                <!--<a href="<?php //echo $back_url; ?>" class="btn brand-btn">Back</a>
				<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;">Back</a>-->
            </div>
            <div class="col-sm-8">
                <button type="submit" name="submit" value="submit" class="btn brand-btn pull-right">Submit</button>
            </div>
        </div>

</div>
