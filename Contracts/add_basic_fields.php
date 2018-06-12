           <script type="text/javascript">
            $(document).on('change', 'select[name="category"]', function() { window.location.replace('?tab='+this.value); });
            $(document).on('change', 'select[name="heading_number"]', function() { selectSection(this); });
            $(document).on('change', 'select[name="sub_heading_number"]', function() { selectSubSection(this); });
           </script>
           
           <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Tab:</label>
            <div class="col-sm-8">
                <select id="category" <?= (empty($contractid) ? '' : 'readonly') ?> name="category" class="chosen-select-deselect form-control" width="380">
                    <option value=''></option>
                    <?php $contract_tabs = explode('#*#', $config['contract_tabs']);
					foreach(array_filter($contract_tabs, function($val) { return ($val != 'Follow Up' && $val != 'Reporting'); }) as $tab_name) {
                        if ($category == $tab_name) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $tab_name."'>".$tab_name.'</option>';
                    } ?>
                </select>
            </div>
          </div>

          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Section #:</label>
            <div class="col-sm-8">
                <select id="heading_number" name="heading_number" class="chosen-select-deselect form-control" width="380">
                    <option value=''></option>
                    <?php
                    for($i=1;$i<=$max_section;$i++) {
                        echo "<option ".($heading_number == $i ? 'selected' : '')." value='". $i."'>".$i.'</option>';
                    }
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

          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Section Heading:</label>
            <div class="col-sm-8">
                <select id="heading" name="heading" class="chosen-select-deselect form-control" width="380">
                    <option value=''></option>
                    <?php
                    $query = mysqli_query($dbc,"SELECT distinct(heading) FROM contracts WHERE category='$category' order by heading");
                    while($row = mysqli_fetch_array($query)) {
                        echo "<option ".($heading == $row['heading'] ? 'selected' : '')." value='". $row['heading']."'>".$row['heading'].'</option>';

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
						$sub_heading_result = mysqli_query($dbc, "SELECT DISTINCT `sub_heading_number`, `sub_heading`, IFNULL(`third_heading_number`,'') third_heading_number FROM `contracts` WHERE `category`='$category' AND `heading_number`='$heading_number' AND `deleted`=0");
						while($sub_heading_vals = mysqli_fetch_array($sub_heading_result)) {
							$sub_heading_numbers[] = $sub_heading_vals['sub_heading_number'];
							$sub_headings[] = $sub_heading_vals['sub_heading'];
							if($sub_heading_vals['third_heading_number'] == '') {
								$disabled[] = $sub_heading_vals['sub_heading_number'];
							}
						}
						echo "<option></option>";
						for($j=1;$j<=$max_subsection;$j++) {
							echo "<option ".($sub_heading_number === $heading_number.'.'.$j ? 'selected' : (in_array("$heading_number.$j",$disabled) ? 'disabled' : ''));
							echo " value='". $heading_number.'.'.$j."'>".$heading_number.'.'.$j.(in_array("$heading_number.$j",$sub_heading_numbers) ? ' : '.$sub_headings[array_search("$heading_number.$j",$sub_heading_numbers)] : '').'</option>';
						}
					}
                    ?>
                </select>
            </div>
          </div>

          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Sub Section Heading:</label>
            <div class="col-sm-8">
              <input name="sub_heading" value="<?php echo $sub_heading; ?>" type="text" id="sub_heading" class="form-control">
            </div>
          </div>

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
						$third_tier_result = mysqli_query($dbc, "SELECT DISTINCT `third_heading_number`, `third_heading` FROM `contracts` WHERE `category`='$category' AND `heading_number`='$heading_number' AND `deleted`=0");
						while($third_tier_vals = mysqli_fetch_array($third_tier_result)) {
							$third_tier_numbers[] = $third_tier_vals['third_heading_number'];
							$third_tiers[] = $third_tier_vals['third_heading'];
						}
						echo "<option></option>";
						for($j=1;$j<=$max_subsection;$j++) {
							echo "<option ".($third_heading_number === $sub_heading_number.'.'.$j ? 'selected' : (in_array("$sub_heading_number.$j",$third_tier_numbers) ? 'disabled' : ''));
							echo " value='". $sub_heading_number.'.'.$j."'>".$sub_heading_number.'.'.$j.(in_array("$sub_heading_number.$j",$third_tier_numbers) ? ' : '.$third_tiers[array_search("$sub_heading_number.$j",$third_tier_numbers)] : '').'</option>';
						}
					}
                    ?>
                </select>
            </div>
          </div>

          <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Third Tier Heading:</label>
            <div class="col-sm-8">
              <input name="third_heading" value="<?php echo $third_heading; ?>" type="text" id="name" class="form-control">
            </div>
          </div>
