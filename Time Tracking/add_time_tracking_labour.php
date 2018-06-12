<script type="text/javascript">
$(document).ready(function() {
    $(".hide_show_crew").hide();

    $('#add_row_crew').on( 'click', function () {
            $(".hide_show_crew").show();
            var clone = $('.additional_crew').clone();
            clone.find('.form-control').val('');
            clone.removeClass("additional_crew");
            $('#add_here_new_crew').append(clone);
            return false;
    });

});
</script>

<div class="col-md-12">

		<div class="form-group">
			<label for="additional_note" class="col-sm-4 control-label"><h3>Crew</h3></label>
			<div class="col-sm-8">
				<div class="form-group clearfix">
					<label class="col-sm-3 text-center">Name</label>
					<label class="col-sm-2 text-center">Position</label>
					<label class="col-sm-1 text-center">REG Hours</label>
					<label class="col-sm-1 text-center">REG Rate</label>
                    <label class="col-sm-1 text-center">OT Hours</label>
                    <label class="col-sm-1 text-center">OT Rate</label>
				</div>

				<?php
				if(empty($_GET['timetrackingid'])) {
					?>

					<div class="additional_crew clearfix">
						<div class="clearfix"></div>

					<?php for($total_line=0; $total_line<=3; $total_line++) {
					?>
						<div class="form-group clearfix">
							<div class="col-sm-3">
								<select data-placeholder="Choose a crew..." name="staffid[]" class="chosen-select-deselect1 form-control office_zip" width="380">
								  <option value=""></option>
								  <?php
									$query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 order by first_name");
									while($row = mysqli_fetch_array($query)) { ?>
										<option value='<?php echo  $row['contactid']; ?>' ><?php echo decryptIt($row['first_name']).' '.decryptIt($row['last_name']); ?></option>
										<?php  }
										?>
								</select>
							</div>
							<div class="col-sm-2">
								<select data-placeholder="Choose a Position..." name="position[]" class="chosen-select-deselect1 form-control office_zip" width="380">
								  <option value=""></option>
								  <?php
									$query = mysqli_query($dbc,"SELECT distinct(position) FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 order by position");
									while($row = mysqli_fetch_array($query)) {
										?>
										<option value='<?php echo  $row['position']; ?>' ><?php echo $row['position']; ?></option>
										<?php  }
										?>
								</select>
							</div>
							<div class="col-sm-1">
								<input name="reg_hours[]" type="text" class="form-control office_zip" />
							</div>
							<div class="col-sm-1">
								<input name="reg_rate[]" type="text" class="form-control office_zip" />
							</div>
							<div class="col-sm-1">
								<input name="ot_hours[]" type="text" class="form-control office_zip" />
							</div>
							<div class="col-sm-1">
								<input name="ot_rate[]" type="text" class="form-control office_zip" />
							</div>
						</div>
					<?php } ?>

					</div>

					<div id="add_here_new_crew"></div>

					<div class="form-group triple-gapped clearfix">
						<div class="col-sm-offset-4 col-sm-8">
							<button id="add_row_crew" class="btn brand-btn pull-left">Add More</button>
						</div>
					</div>

					<?php
						$id++;
				} else {
                        $query_check_credentials = "SELECT * FROM time_tracking_labour WHERE timetrackingid='$timetrackingid'";
                        $result = mysqli_query($dbc, $query_check_credentials);
                        while($row = mysqli_fetch_array( $result )) {
						    ?>
		                    <input type="hidden" name="timetrackinglabourid[]" value="<?php echo $row['timetrackinglabourid']; ?>" />

							<div class="form-group clearfix">
							  <div class="col-sm-3">

								<select data-placeholder="Choose a crew..." name="staffid[]" class="chosen-select-deselect1 form-control office_zip" width="380">
								  <option value=""></option>
								  <?php
									$query1 = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 order by first_name");
									while($row1 = mysqli_fetch_array($query1)) { ?>
										<option <?php if ($row['staffid'] == $row1['contactid']) {
										echo " selected='selected'"; } ?> value='<?php echo  $row1['contactid']; ?>' ><?php echo decryptIt($row1['first_name']).' '.decryptIt($row1['last_name']); ?></option>
										<?php  }
										?>
								</select>
							  </div>

							  <div class="col-sm-2">
								<select data-placeholder="Choose a Position..." name="position[]" class="chosen-select-deselect1 form-control office_zip" width="380">
								  <option value=""></option>
								  <?php
									$query2 = mysqli_query($dbc,"SELECT distinct(position) FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 order by position");
									while($row2 = mysqli_fetch_array($query2)) {
										?>
										<option <?php if ($row['position'] == $row2['position']) {
										echo " selected='selected'"; } ?> value='<?php echo  $row2['position']; ?>' ><?php echo $row2['position']; ?></option>
										<?php  }
										?>
								</select>
							  </div>
							  <div class="col-sm-1">
								<input name="reg_hours[]" type="text" value="<?php echo $row['reg_hours'];	?>" class="form-control office_zip" />
							  </div>
							  <div class="col-sm-1">
								<input name="reg_rate[]" type="text" value="<?php echo $row['reg_rate']; ?>" class="form-control office_zip" />
							  </div>
							  <div class="col-sm-1">
								<input name="ot_hours[]" type="text" value="<?php echo $row['ot_hours']; ?>" class="form-control office_zip" />
							  </div>
							  <div class="col-sm-1">
								<input name="ot_rate[]" type="text" value="<?php echo $row['ot_rate']; ?>" class="form-control office_zip" />
							  </div>

						</div>
                        <?php
                        }
                       ?>

					 <div class="enter_cost additional_crew hide_show_crew">
						<div class="clearfix"></div>

						<div class="form-group clearfix">
							<div class="col-sm-3">
								<select data-placeholder="Choose a crew..." name="staffid[]" class="chosen-select-deselect1 form-control office_zip" width="380">
								  <option value=""></option>
								  <?php
									$query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 order by first_name");
									while($row = mysqli_fetch_array($query)) { ?>
										<option value='<?php echo  $row['contactid']; ?>' ><?php echo decryptIt($row['first_name']).' '.decryptIt($row['last_name']); ?></option>
										<?php  }
										?>
								</select>
							</div>
							<div class="col-sm-2">
								<select data-placeholder="Choose a Position..." name="position[]" class="chosen-select-deselect1 form-control office_zip" width="380">
								  <option value=""></option>
								  <?php
									$query = mysqli_query($dbc,"SELECT distinct(position) FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 order by position");
									while($row = mysqli_fetch_array($query)) {
										?>
										<option value='<?php echo  $row['position']; ?>' ><?php echo $row['position']; ?></option>
										<?php  }
										?>
									<option value=""></option>
									  <?php
										$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT distinct(position) FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
										foreach($query as $id) {
											$selected = '';
											$selected = strpos(','.$assign_staff.',', ','.$row['contactid'].',') ? 'selected = "selected"' : '';
											echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
										}
									  ?>
								</select>
							</div>
							<div class="col-sm-1">
								<input name="reg_hours[]" type="text" class="form-control office_zip" />
							</div>
							<div class="col-sm-1">
								<input name="reg_rate[]" type="text" class="form-control office_zip" />
							</div>
							<div class="col-sm-1">
								<input name="ot_hours[]" type="text" class="form-control office_zip" />
							</div>
							<div class="col-sm-1">
								<input name="ot_rate[]" type="text" class="form-control office_zip" />
							</div>
						</div>

					</div>

					<div id="add_here_new_crew"></div>

					<div class="form-group triple-gapped clearfix">
						<div class="col-sm-offset-4 col-sm-8">
							<button id="add_row_crew" class="btn brand-btn pull-left">Add More</button>
						</div>
					</div>
					<?php }

					?>
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
