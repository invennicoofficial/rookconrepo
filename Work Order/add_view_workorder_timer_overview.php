<div class="col-md-12">
      <div class="form-group">
        <label for="site_name" class="col-sm-4 control-label">Estimated Time to Complete Work Order:</label>
        <div class="col-sm-8">
            <select style="width: 100px;" data-placeholder="Choose a Type..." name="max_time_hour" class="chosen-select-deselect1 form-control" >
            <?php
            for($i=0;$i<40;$i++) {
                if($i<10) {
                    $i = '0'.$i;
                }
                if($max_time[0] == $i) {
                    $selected = ' selected';
                } else {
                    $selected = '';
                }
                echo "<option ".$selected." value='". $i."'>".$i.'</option>';
            }
            ?>
            </select>Hour
            <select style="width: 100px;" data-placeholder="Choose a Type..." name="max_time_minute" class="chosen-select-deselect1 form-control" >
            <?php
            for($i=00;$i<60;$i++) {
                if($i<10) {
                    $i = '0'.$i;
                }
                if($max_time[1] == $i) {
                    $selected = ' selected';
                } else {
                    $selected = '';
                }
                echo "<option ".$selected." value='". $i."'>".$i.'</option>';
            }
            ?>
            </select>Minute
        </div>
      </div>

    <?php

    if(!empty($_GET['workorderid'])) {
        echo '<h4>Current Time Towards Work Order</h4>';
        $query_check_credentials = "SELECT * FROM workorder_timer WHERE workorderid='$workorderid' ORDER BY workordertimerid DESC";
        $result = mysqli_query($dbc, $query_check_credentials);
        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {
            echo "<table class='table table-bordered'>
            <tr class='hidden-xs hidden-sm'>
            <th>Type</th>
            <th>Time</th>
            <th>Task</th>
            <th>Date</th>
            <th>Added By</th>
            </tr>";
            $times = array();
            while($row = mysqli_fetch_array($result)) {
                echo '<tr>';
                $by = $row['created_by'];
                echo '<td data-title="Schedule">'.$row['timer_type'].'</td>';
                echo '<td data-title="Schedule">'.$row['start_time'].' - '.$row['end_time'].'</td>';
                echo '<td data-title="Schedule">'.$row['timer_task'].'</td>';
                echo '<td data-title="Schedule">'.$row['created_date'].'</td>';
                echo '<td data-title="Schedule">'.get_staff($dbc, $by).'</td>';
                echo '</tr>';
                //$total_time += strtotime($row['timer']);
                $times[] = $row['timer'];
            }
            echo '</table>';
        }
    }

    //echo $time = date("h:i:s",$total_time);

    ?>

      <div class="form-group">
        <label for="site_name" class="col-sm-4 control-label">Total Spent Time For Work Order:</label>
        <div class="col-sm-8">
            <?php
                echo AddPlayTime($times);
            ?>
        </div>
      </div>

    <div class="form-group">
        <div class="col-sm-4">
            <!--<a href="<?php //echo $back_url; ?>" class="btn brand-btn">Back</a>-->
			<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;" title="The entire form will close without submit if this back button is pressed.">Back</a>
        </div>
        <div class="col-sm-8">
            <button type="submit" name="submit" value="submit" class="btn brand-btn pull-right" title="The entire form will submit and close if this submit button is pressed.">Submit</button>
        </div>
    </div>
</div>
<?php

function AddPlayTime($times) {
    // loop throught all the times
    foreach ($times as $time) {
        list($hour, $minute) = explode(':', $time);
        $minutes += $hour * 60;
        $minutes += $minute;
    }

    $hours = floor($minutes / 60);
    $minutes -= $hours * 60;

    // returns the time already formatted
    return sprintf('%02d Hour %02d Minute', $hours, $minutes);
}
