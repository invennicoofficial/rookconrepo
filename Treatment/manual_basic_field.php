<?php if($action != 'view' || strpos($form,'AB-') === FALSE): ?>
          <?php if($action != 'view') { ?>
		  <script>
		  $(document).ready(function() {
			  setTopic('<?php echo $tab_name; ?>');
		  });
      $(document).on('change', 'select[name="tab"]', function() { setTopic(this.value); });
      $(document).on('change', 'select[name="heading_number"]', function() { selectSection(this); });
      $(document).on('change', 'select[name="sub_heading_number"]', function() { selectSubSection(this); });
		  function setTopic(tab) {
			  if(tab == '') {
				  var topic_list = "<?php
                    $query = mysqli_query($dbc,"SELECT distinct(category) FROM patientform WHERE `category` NOT IN ('forms', 'assess', 'treatment', 'discharge')");
                    while($row = mysqli_fetch_array($query)) {
                        echo "<option ".($category == $row['category'] ? 'selected' : '')." value='". $row['category']."'>".$row['category'].'</option>';
                    }
                    echo "<option value = 'Other'>New Topic (Sub Tab)</option>";
                    ?>";
			  } else {
				  var topic_list = "<option <?php echo ('forms' == $category ? 'selected' : ''); ?> value='forms'>Patient Forms</option>"+
                    "<option <?php echo ('assess' == $category ? 'selected' : ''); ?> value='assess'>Assessment</option>"+
                    "<option <?php echo ('treatment' == $category ? 'selected' : ''); ?> value='treatment'>Treatment</option>"+
                    "<option <?php echo ('discharge' == $category ? 'selected' : ''); ?> value='discharge'>Discharge</option>";
			  }
              topic_list = "<option value=''></option>" + topic_list;
			  $('#category').empty().append(topic_list).trigger('change.select2');
		  }
		  </script>
           <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Main Tab:</label>
            <div class="col-sm-8">
                <select id="tab" name="tab" class="chosen-select-deselect form-control" width="380">
                    <option value=''></option>
                    <option <?php echo ('front_desk' == $tab_name ? 'selected' : ''); ?> value='front_desk'>Front Desk</option>
                    <option <?php echo ('physiotherapy' == $tab_name ? 'selected' : ''); ?> value='physiotherapy'>Physiotherapy</option>
                    <option <?php echo ('massage' == $tab_name ? 'selected' : ''); ?> value='massage'>Massage Therapy</option>
                    <option <?php echo ('mvc' == $tab_name ? 'selected' : ''); ?> value='mvc'>MVC/MVA</option>
                    <option <?php echo ('wcb' == $tab_name ? 'selected' : ''); ?> value='wcb'>WCB</option>
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
		  
         <?php if (strpos($value_config, ','."Topic (Sub Tab)".',') !== FALSE) { ?>
          <?php if($action == 'view') { ?>
           <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Topic (Sub Tab):</label>
            <div class="col-sm-8">
                <?php echo ucwords($category); ?>
            </div>
          </div>
          <?php } else { ?>
           <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Topic (Sub Tab):</label>
            <div class="col-sm-8">
                <select id="category" name="category" class="chosen-select-deselect form-control" width="380">
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

          <?php if($action == 'view') { ?>
           <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Section #:</label>
            <div class="col-sm-8">
                <?php echo $heading_number; ?>
            </div>
          </div>
          <?php } else { ?>

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

          <?php if($action == 'view') { ?>
           <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Section Heading:</label>
            <div class="col-sm-8">
                <?php echo $heading; ?>
            </div>
          </div>
          <?php } else { ?>

          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Section Heading:</label>
            <div class="col-sm-8">
                <select id="heading" name="heading" class="chosen-select-deselect form-control" width="380">
                    <option value=''></option>
                    <?php
                    $query = mysqli_query($dbc,"SELECT distinct(heading) FROM patientform");
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
          <?php if($action == 'view') { ?>
           <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Sub Section #:</label>
            <div class="col-sm-8">
                <?php echo $sub_heading_number; ?>
            </div>
          </div>
          <?php } else {
          ?>

          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Sub Section #:</label>
            <div class="col-sm-8">
                <select id="sub_heading_number" name="sub_heading_number" class="chosen-select-deselect form-control" width="380">
                    <option value=''></option>
                    <?php
                    for($i=1;$i<=$max_section;$i++) {
                        for($j=1;$j<=$max_subsection;$j++) {
                            if ($sub_heading_number === $i.'.'.$j) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $i.'.'.$j."'>".$i.'.'.$j.'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
          </div>

        <?php } ?>
      <?php } ?>

       <?php if (strpos($value_config, ','."Sub Section Heading".',') !== FALSE) { ?>
          <?php if($action == 'view') { ?>
           <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Sub Section Heading:</label>
            <div class="col-sm-8">
                <?php echo $sub_heading; ?>
            </div>
          </div>
          <?php } else { ?>

          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Sub Section Heading:</label>
            <div class="col-sm-8">
              <input name="sub_heading" value="<?php echo $sub_heading; ?>" type="text" id="sub_heading" class="form-control">
            </div>
          </div>
        <?php } ?>
      <?php } ?>

       <?php if (strpos($value_config, ','."Third Tier Section #".',') !== FALSE) { ?>
          <?php if($action == 'view') { ?>
           <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Third Tier Section #:</label>
            <div class="col-sm-8">
                <?php echo $third_heading_number; ?>
            </div>
          </div>
          <?php } else { ?>

          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Third Tier Section #:</label>
            <div class="col-sm-8">
                <select id="third_heading_number" name="third_heading_number" class="chosen-select-deselect form-control" width="380">
                    <option value=''></option>
                    <?php
                    for($i=1;$i<=$max_section;$i++) {
                        for($j=1;$j<=$max_subsection;$j++) {
                            for($k=1;$k<=$max_thirdsection;$k++) {
                                if ($third_heading_number == $i.'.'.$j.'.'.$k) {
                                    $selected = 'selected="selected"';
                                } else {
                                    $selected = '';
                                }
                                echo "<option ".$selected." value='". $i.'.'.$j.'.'.$k."'>".$i.'.'.$j.'.'.$k.'</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>
          </div>

        <?php } ?>
      <?php } ?>

       <?php if (strpos($value_config, ','."Third Tier Heading".',') !== FALSE) { ?>
          <?php if($action == 'view') { ?>
           <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Third Tier Heading:</label>
            <div class="col-sm-8">
                <?php echo $third_heading; ?>
            </div>
          </div>
          <?php } else { ?>

          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Third Tier Heading:</label>
            <div class="col-sm-8">
              <input name="third_heading" value="<?php echo $third_heading; ?>" type="text" id="name" class="form-control">
            </div>
          </div>
        <?php } ?>
      <?php } ?>

      <?php if (strpos($value_config, ','."Filled By Staff".',') !== FALSE) { ?>
        <?php if($action == 'view') { ?>
          <div class="form-group">
          <label for="filled_staff" class="col-sm-4 control-label">Staff:</label>
          <div class="col-sm-8">
            <select data-placeholder="Select Staff" name="filled_by_staff" class="chosen-select-deselect form-control">
              <option></option>
              <?php
                $staffids = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted` = 0 AND `status` > 0"),MYSQLI_ASSOC));
                foreach ($staffids as $id) {
                  echo '<option value="'.$id.'">'.get_contact($dbc, $id).'</option>';
                }
              ?>
            </select>
          </div>
          </div>
        <?php } ?>
      <?php } ?>

      <?php if (!empty($attach_contact_type)) { ?>
        <?php if($action == 'view') { ?>
          <div class="form-group">
          <label for="attach_contact" class="col-sm-4 control-label"><?= $attach_contact_type ?>:</label>
          <div class="col-sm-8">
            <select data-placeholder="Select <?= $attach_contact_type ?>" name="attach_contact" class="chosen-select-deselect form-control">
              <option></option>
              <?php
                $contactids = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `category` = '".$attach_contact_type."' AND `deleted` = 0 AND `status` > 0"),MYSQLI_ASSOC));
                foreach ($contactids as $id) {
                  echo '<option value="'.$id.'">'.get_contact($dbc, $id).'</option>';
                }
              ?>
            </select>
          </div>
          </div>
        <?php } ?>
      <?php } ?>

      <?php if (strpos($value_config, ','."Ticket".',') !== FALSE || !empty($ticketid)) { ?>
        <?php if($action == 'view') { ?>
          <div class="form-group">
          <label for="filled_staff" class="col-sm-4 control-label"><?= TICKET_NOUN ?>:</label>
          <div class="col-sm-8">
            <select data-placeholder="Select <?= TICKET_NOUN ?>" name="ticketid" class="chosen-select-deselect form-control">
              <option></option>
              <?php
                $ticketids = mysqli_fetch_all(mysqli_query($dbc,"SELECT * FROM `tickets` WHERE `deleted` = 0 AND (`status` NOT IN ('Done','Archive') OR `ticketid` = '".$ticketid."') ORDER BY `heading`"),MYSQLI_ASSOC);
                foreach ($ticketids as $id) {
                  echo '<option '.($ticketid == $id['ticketid'] ? 'selected' : '' ).' value="'.$id['ticketid'].'">'.get_ticket_label($dbc, $id).'</option>';
                }
              ?>
            </select>
          </div>
          </div>
        <?php } ?>
      <?php } ?>

      <?php if (strpos($value_config, ','."Date".',') !== FALSE) { ?>
        <?php if($action == 'view') { ?>
          <div class="form-group">
          <label for="filled_date" class="col-sm-4 control-label">Date:</label>
          <div class="col-sm-8">
            <input type="text" name="filled_date" class="datepicker form-control">
          </div>
          </div>
        <?php } ?>
      <?php } ?>

<?php endif; ?>