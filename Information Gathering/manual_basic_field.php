          <script type="text/javascript">
          $(document).on('change', 'select[name="heading_number"]', function() { selectSection(this); });
          $(document).on('change', 'select[name="sub_heading_number"]', function() { selectSubSection(this); });
          </script>
          
         <?php if (strpos($value_config, ','."Topic (Sub Tab)".',') !== FALSE) { ?>
          <?php if($action == 'view') { ?>
           <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Topic (Sub Tab):</label>
            <div class="col-sm-8">
                <?php echo $category; ?>
            </div>
          </div>
          <?php } else { ?>
           <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Topic (Sub Tab):</label>
            <div class="col-sm-8">
                <select id="category" name="category" class="chosen-select-deselect form-control" width="380">
                    <option value=''></option>
                    <?php
                    $query = mysqli_query($dbc,"SELECT distinct(category) FROM infogathering");
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
                    $query = mysqli_query($dbc,"SELECT distinct(heading) FROM infogathering");
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