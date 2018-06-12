      <div class="form-group">
        <label for="site_name" class="col-sm-4 control-label">Task:</label>
        <div class="col-sm-8">
			<select data-placeholder="Choose a Type..." name="timer_task" id="timer_task" class="chosen-select-deselect form-control" width="380">
                <option value=""></option>
              <?php
                $tabs = get_config($dbc, 'workorder_task');
                $each_tab = explode(',', $tabs);
                foreach ($each_tab as $cat_tab) {
                    if ($timer_task == $cat_tab) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    echo "<option ".$selected." value='".$cat_tab."'>".$cat_tab.'</option>';
                }
              ?>

			</select>
        </div>
      </div>

    <?php
    include ('add_workorder_timer.php');
    ?>