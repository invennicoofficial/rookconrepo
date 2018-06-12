<script type="text/javascript">
$(document).ready(function() {
    $("#location").change(function() {
        if($("#location option:selected").text() == 'New Location') {
                $( "#new_location" ).show();
        } else {
            $( "#new_location" ).hide();
        }
    });
} );
</script>

<div class="col-md-12">

      <?php if (strpos($value_config, ','."Location".',') !== FALSE) { ?>
       <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Location:</label>
        <div class="col-sm-8">
            <select id="location" name="location" class="chosen-select-deselect form-control" width="380">
                <option value=''></option>
                <?php
                $query = mysqli_query($dbc,"SELECT distinct(location) FROM time_tracking order by location");
                while($row = mysqli_fetch_array($query)) {
                    if ($location == $row['location']) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    echo "<option ".$selected." value='". $row['location']."'>".$row['location'].'</option>';
                }
                echo "<option value = 'Other'>New Location</option>";
                ?>
            </select>
        </div>
      </div>

       <div class="form-group" id="new_location" style="display: none;">
        <label for="travel_task" class="col-sm-4 control-label">New Location:
        </label>
        <div class="col-sm-8">
            <input name="new_location" type="text" class="form-control" />
        </div>
      </div>
      <?php } ?>

        <div class="form-group">
            <label for="first_name" class="col-sm-4 control-label text-right">Job #:</label>
            <div class="col-sm-8">
                <input name="job_number" value="<?php echo $job_number; ?>" type="text" class="form-control"></p>
            </div>
        </div>

        <div class="form-group">
            <label for="first_name" class="col-sm-4 control-label text-right">AFE #:</label>
            <div class="col-sm-8">
                <input name="afe_number" value="<?php echo $afe_number; ?>" type="text" class="form-control"></p>
            </div>
        </div>

        <div class="form-group">
            <label for="first_name" class="col-sm-4 control-label text-right">Work Performed:</label>
            <div class="col-sm-8">
                <input name="work_preformed" value="<?php echo $work_preformed; ?>" type="text" class="datepicker"></p>
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
