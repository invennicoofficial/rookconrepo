<div class="col-md-12">
    <div class="form-group">
      <label for="site_name" class="col-sm-4 control-label">Business/Contact:</label>
      <div class="col-sm-8">
        <?php
		$client_list = explode(',',$clientid);
		$client_name = [];
		foreach($client_list as $client) {
			$client_name[] = get_contact($dbc, $client, "");
		}
		echo '<td data-title="Client">' . get_contact($dbc, $businessid, 'name').'<br>'.implode('<br />', $client_name) . '</td>'; ?>
      </div>
    </div>

    <div class="form-group">
      <label for="site_name" class="col-sm-4 control-label"><?php if (PROJECT_TILE=='Projects') { echo "Project"; } else { echo PROJECT_TILE; } ?> Name<span class="text-red">*</span>:</label>
      <div class="col-sm-8">
        <select data-placeholder="Choose a Project..." name="projectid" id="projectid"  class="chosen-select-deselect form-control" width="380">
          <option value=""></option>
          <?php $project_tabs = get_config($dbc, 'project_tabs');
			if($project_tabs == '') {
				$project_tabs = 'Client,SR&ED,Internal,R&D,Business Development,Process Development,Addendum,Addition,Marketing,Manufacturing,Assembly';
			}
			$project_tabs = explode(',',$project_tabs);
			$project_vars = [];
			foreach($project_tabs as $item) {
				$project_vars[] = preg_replace('/[^a-z_]/','',str_replace(' ','_',strtolower($item)));
			}
			$query = mysqli_query($dbc,"SELECT * FROM (SELECT projectid, projecttype, project_name FROM project WHERE businessid='$businessid' and deleted=0 UNION SELECT CONCAT('C',`projectid`), 'Client Project', `project_name` FROM `client_project` WHERE `clientid`='$businessid' AND `deleted`=0) PROJECTS order by project_name");
			while($row = mysqli_fetch_array($query)) {
				if(substr($row['projectid'],0,1) == 'C') {
					echo "<option ".($projectid == $row['projectid'] ? 'selected' : '')." value='".$row['projectid']."'>Client Project: ".$row['project_name'].'</option>';
				} else {
					foreach($project_vars as $key => $type_name) {
						if($type_name == $row['projecttype']) {
							echo "<option ".($projectid == $row['projectid'] ? 'selected' : '')." value='".$row['projectid']."'>".$project_tabs[$key].': '.$row['project_name'].'</option>';
						}
					}
				}
			}
          ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="site_name" class="col-sm-4 control-label">Piece Work:</label>
      <div class="col-sm-8">
        <?php echo $piece_work; ?>
      </div>
    </div>

    <div class="form-group">
        <div class="col-sm-4">
            <a href="#" class="btn brand-btn" onclick="history.go(-1);return false;">Back</a>
        </div>
        <div class="col-sm-8">
            <button type="submit" name="submit" value="submit" class="btn brand-btn pull-right">Submit</button>
        </div>
    </div>

 </div>
