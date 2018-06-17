

      <?php if(!empty($_GET['formid'])) {
        $is_user_form = get_safety($dbc, $_GET['safetyid'], 'user_form_id');
        if($is_user_form > 0) {
          $form_details = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `user_form_pdf` WHERE `pdf_id` = '".$_GET['formid']."'"));
        } else {
          $safety_db = array_search($form,$safety_table_list);
          $form_details = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `$safety_db` WHERE `".($form == 'Motor Vehicle Accident Form' ? 'incidentreportid' : 'fieldlevelriskid')."` = '".$_GET['formid']."'"));
        }
      } else {
        $form_details = '';
        $form_details['safety_siteid'] = $_GET['siteid'];
      } ?>

          <script type="text/javascript">
          $(document).on('change', 'select[name="heading_number"]', function() { selectSection(this); });
          $(document).on('change', 'select[name="sub_heading_number"]', function() { selectSubSection(this); });
          </script>
          
         <?php if (strpos($value_config, ','."Topic (Sub Tab)".',') !== FALSE) { ?>
          <?php if($action != 'view') { ?>
           <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Topic (Sub Tab):</label>
            <div class="col-sm-8">
                <select id="category" name="category" class="chosen-select-deselect form-control" width="380">
                    <option value=''></option>
                    <?php
                    //$query = mysqli_query($dbc,"SELECT distinct(category) FROM safety WHERE tab='$tab'");
                    $query = mysqli_query($dbc,"SELECT distinct(category) FROM safety order by category");
                    while($row = mysqli_fetch_array($query)) {
                        if ($category == $row['category']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $row['category']."'>".$row['category'].'</option>';
                    }
                    echo "<option value = 'Other'>New Topic (Sub Tab)</option>";
                    ?>
                </select>
            </div>
          </div>

           <div class="form-group" id="new_category" style="display: none;">
            <label for="travel_task" class="col-sm-4 control-label">New Topic (Sub Tab) Name:
            </label>
            <div class="col-sm-8">
                <input name="new_category" type="text" class="form-control" />
            </div>
          </div>
          <?php } ?>
      <?php } ?>


        <?php if (strpos($value_config, ','."Section #".',') !== FALSE) { ?>
          <?php if($action != 'view') { ?>
          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Section #:</label>
            <div class="col-sm-8">
                <select id="heading_number" name="heading_number" class="chosen-select-deselect form-control" width="380">
                    <option value=''></option>
                    <?php
                    for($i=1;$i<=$max_section;$i++) {
                        if ($heading_number == $i) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $i."'>".$i.'</option>';
                    }
                    /*
                    $query = mysqli_query($dbc,"SELECT distinct(heading_number) FROM manuals WHERE manual_type='$type'");
                    while($row = mysqli_fetch_array($query)) {
                        if ($heading_number == $row['heading_number']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                    }
                    echo "<option value = 'Other'>New Heading Number</option>";
                    */
                    ?>
                </select>
            </div>
          </div>

           <div class="form-group" id="new_heading_number" style="display: none;">
            <label for="travel_task" class="col-sm-4 control-label">New Section #:
            </label>
            <div class="col-sm-8">
                <input name="new_heading_number" type="text" class="form-control" />
            </div>
          </div>

        <?php } ?>
      <?php } ?>

        <?php if (strpos($value_config, ','."Section Heading".',') !== FALSE) { ?>

          <?php if($action != 'view') { ?>
          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Section Heading:</label>
            <div class="col-sm-8">
                <select id="heading" name="heading" class="chosen-select-deselect form-control" width="380">
                    <option value=''></option>
                    <?php
                    //$query = mysqli_query($dbc,"SELECT distinct(heading) FROM safety WHERE tab='$tab'");
                    $query = mysqli_query($dbc,"SELECT distinct(heading) FROM safety order by heading");
                    while($row = mysqli_fetch_array($query)) {
                        if ($heading == $row['heading']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $row['heading']."'>".$row['heading'].'</option>';

                    }
                    echo "<option value = 'Other'>New Heading</option>";
                    ?>
                </select>
            </div>
          </div>

           <div class="form-group" id="new_heading" style="display: none;">
            <label for="travel_task" class="col-sm-4 control-label">New Section Heading:
            </label>
            <div class="col-sm-8">
                <input name="new_heading" type="text" class="form-control" />
            </div>
          </div>

        <?php } ?>
      <?php } ?>

       <?php if (strpos($value_config, ','."Sub Section #".',') !== FALSE) { ?>
          <?php if($action != 'view') { ?>
          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Sub Section #:</label>
            <div class="col-sm-8">
                <select id="sub_heading_number" name="sub_heading_number" class="chosen-select-deselect form-control" width="380">
                    <?php
					if($heading_number == '') {
						echo "<option>Please select a Section #</option>";
					} else {
						$sub_heading_numbers = [];
						$sub_headings = [];
						$disabled = [];
						$sub_heading_result = mysqli_query($dbc, "SELECT DISTINCT `sub_heading_number`, `sub_heading`, IFNULL(`third_heading_number`,'') third_heading_number FROM `safety` WHERE `tab`='$tab' AND `heading_number`='$heading_number' AND `deleted`=0");
						while($sub_heading_vals = mysqli_fetch_array($sub_heading_result)) {
							$sub_heading_numbers[] = $sub_heading_vals['sub_heading_number'];
							$sub_headings[] = $sub_heading_vals['sub_heading'];
							if($sub_heading_vals['third_heading_number'] == '') {
								$disabled[] = $sub_heading_vals['sub_heading_number'];
							}
						}
						echo "<option></option>";
						for($j=1;$j<=$max_subsection;$j++) {
						echo "$sub_heading_number - $heading_number.$j";
							echo "<option ".($sub_heading_number === "$heading_number.$j" ? 'selected' : (in_array("$heading_number.$j",$disabled,true) ? 'disabled' : ''));
							echo " value='". $heading_number.'.'.$j."'>".$heading_number.'.'.$j.(in_array("$heading_number.$j",$sub_heading_numbers,true) ? ' : '.$sub_headings[array_search("$heading_number.$j",$sub_heading_numbers)] : '').'</option>';
						}
					}
                    ?>
                </select>
            </div>
          </div>

        <?php } ?>
      <?php } ?>

       <?php if (strpos($value_config, ','."Sub Section Heading".',') !== FALSE) { ?>
          <?php if($action != 'view') { ?>
          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Sub Section Heading:</label>
            <div class="col-sm-8">
              <input name="sub_heading" value="<?php echo $sub_heading; ?>" type="text" id="sub_heading" class="form-control">
            </div>
          </div>
        <?php } ?>
      <?php } ?>

       <?php if (strpos($value_config, ','."Third Tier Section #".',') !== FALSE) { ?>
          <?php if($action != 'view') { ?>
          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Third Tier Section #:</label>
            <div class="col-sm-8">
                <select id="third_heading_number" name="third_heading_number" class="chosen-select-deselect form-control" width="380">
                    <?php
					if($sub_heading_number == '') {
						echo "<option>Please select a Section # and Sub Section #</option>";
					} else {
						$third_tier_numbers = [];
						$third_tiers = [];
						$third_tier_result = mysqli_query($dbc, "SELECT DISTINCT `third_heading_number`, `third_heading` FROM `safety` WHERE `manual_type`='$type' AND `category`='$category' AND `heading_number`='$heading_number' AND `deleted`=0");
						while($third_tier_vals = mysqli_fetch_array($third_tier_result)) {
							$third_tier_numbers[] = $third_tier_vals['third_heading_number'];
							$third_tiers[] = $third_tier_vals['third_heading'];
						}
						echo "<option></option>";
						for($j=1;$j<=$max_subsection;$j++) {
							echo "<option ".($third_heading_number === $sub_heading_number.'.'.$j ? 'selected' : (in_array("$sub_heading_number.$j",$third_tier_numbers,true) ? 'disabled' : ''));
							echo " value='". $sub_heading_number.'.'.$j."'>".$sub_heading_number.'.'.$j.(in_array("$sub_heading_number.$j",$third_tier_numbers,true) ? ' : '.$third_tiers[array_search("$sub_heading_number.$j",$third_tier_numbers)] : '').'</option>';
						}
					}
                    ?>
                </select>
            </div>
          </div>

        <?php } ?>
      <?php } ?>

       <?php if (strpos($value_config, ','."Third Tier Heading".',') !== FALSE) { ?>
          <?php if($action != 'view') { ?>
          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Third Tier Heading:</label>
            <div class="col-sm-8">
              <input name="third_heading" value="<?php echo $third_heading; ?>" type="text" id="name" class="form-control">
            </div>
          </div>
        <?php } ?>
      <?php } ?>

      <?php if(strpos($value_config, ','."Project".',') !== FALSE && $action == 'view') { ?>
          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label"><?= PROJECT_NOUN ?>:</label>
            <div class="col-sm-8">
              <select name="safety_projectid" class="chosen-select-deselect form-control">
                <option></option>
                <?php $query = mysqli_query($dbc,"SELECT projectid, projecttype, project_name, businessid, clientid, status FROM project WHERE deleted=0 AND (status NOT IN ('Archive') OR `projectid`='$projectid') order by `projectid` DESC");
                while($row = mysqli_fetch_array($query)) {
                  echo "<option value='".$row['projectid']."' ".($row['projectid'] == $form_details['safety_projectid'] ? 'selected' : '').">".get_project_label($dbc,$row).'</option>';
                }
                ?>
              </select>
            </div>
          </div>
      <?php } ?>

      <?php if(strpos($value_config, ','."Site".',') !== FALSE && $action == 'view') { ?>
          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Site:</label>
            <div class="col-sm-8">
              <select name="safety_siteid" class="chosen-select-deselect form-control">
                <option></option>
                <?php $query = mysqli_query($dbc,"SELECT * FROM `contacts` WHERE `category`='Sites' AND deleted=0 ORDER BY IFNULL(NULLIF(`display_name`,''),`site_name`)");
                while($row = mysqli_fetch_array($query)) {
                  echo "<option value='".$row['contactid']."' ".($row['contactid'] == $form_details['safety_siteid'] ? 'selected' : '' ).">".($row['display_name'] == '' ? $row['site_name'] : $row['display_name'])."</option>";
                } ?>
              </select>
            </div>
          </div>
      <?php } ?>

      <?php if(strpos($value_config, ','."Ticket".',') !== FALSE && $action == 'view') { ?>
          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label"><?= TICKET_NOUN ?>:</label>
            <div class="col-sm-8">
              <select name="safety_ticketid" class="chosen-select-deselect form-control">
                <option></option>
                <?php $ticket_list = mysqli_fetch_all(mysqli_query($dbc,"SELECT * FROM `tickets` WHERE `deleted` = 0 AND `status` NOT IN ('Done','Archive') ORDER BY `heading`"),MYSQLI_ASSOC);
                foreach($ticket_list as $ticket) {
                  echo "<option value='".$ticket['ticketid']."' ".($ticket['ticketid'] == $form_details['safety_ticketid'] ? 'selected' : '').">".get_ticket_label($dbc, $ticket).'</option>';
                } ?>
              </select>
            </div>
          </div>
      <?php } ?>

      <?php if(strpos($value_config, ','."Client".',') !== FALSE && $action == 'view') { ?>
          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Client:</label>
            <div class="col-sm-8">
              <select name="safety_clientid" class="chosen-select-deselect form-control">
                <option></option>
                <?php foreach(sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name`, `businessid`, `region`, `con_locations`, `classification`, `name` FROM `contacts` WHERE `deleted`=0 AND `status`>0 AND (CONCAT(`first_name`,`last_name`) != '' OR `name` != '')")) as $row) {
                  echo '<option value="'.$row['contactid'].'" '.($row['contactid'] == $form_details['safety_clientid'] ? 'selected' : '').'>'. $row['full_name'] .'</option>';
                } ?>
              </select>
            </div>
          </div>
      <?php } ?>