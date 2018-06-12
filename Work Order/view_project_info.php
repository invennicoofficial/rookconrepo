<div class="col-md-12">
    <!--
    <div class="form-group">
      <label for="site_name" class="col-sm-4 control-label">Contact:</label>
      <div class="col-sm-8">
        <?php
        echo  get_contact($dbc, $businessid, 'name').'<br>'.get_contact($dbc, $clientid, 'first_name').' '.get_contact($dbc, $clientid, 'last_name'); ?>
      </div>
    </div>
    -->

    <!--
    <div class="form-group">
      <label for="site_name" class="col-sm-4 control-label">Service Type:</label>
      <div class="col-sm-8">
        <?php echo $service_type; ?>
      </div>
    </div>
    -->

    <div class="form-group">
      <label for="site_name" class="col-sm-4 control-label"><?php if (PROJECT_TILE=='Projects') { echo "Project"; } else { echo PROJECT_TILE; } ?>:</label>
      <div class="col-sm-8">
        <?php echo get_project($dbc, $projectid, 'project_name'); ?>
      </div>
    </div>

 </div>