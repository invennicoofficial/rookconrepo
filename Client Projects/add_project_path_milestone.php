<div class="form-group clearfix completion_date">
    <label for="first_name" class="col-sm-4 control-label text-right">Project Template:</label>
    <div class="col-sm-8">
        <select name="project_path" id="project_path" data-placeholder="Select a Template..." class="chosen-select-deselect form-control" width="380">
            <option value=''></option>
            <?php
            $query = mysqli_query($dbc,"SELECT project_path_milestone, project_path FROM client_project_path_milestone ORDER BY `project_path`");
            while($row = mysqli_fetch_array($query)) {
                if ($project_path== $row['project_path_milestone']) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }
                echo "<option ".$selected." value='". $row['project_path_milestone']."'>".$row['project_path'].'</option>';
            }
            ?>
        </select>
    </div>
</div>

<div class="form-group">
  <label for="site_name" class="col-sm-4 control-label">Milestone & Timeline:</label>
  <div class="col-sm-8 template-target"></div>
</div>