<div class="col-md-12">

        <div class="form-group">
            <label for="first_name" class="col-sm-4 control-label">Total Days:<br><em>(Ex: 5/9/40/120)</em></label>
            <div class="col-sm-8">
                <input name="total_days" data-table="workorder" data-id="<?= $workorderid ?>" data-id-field="workorderid" type="text" value="<?php echo $total_days; ?>" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="first_name" class="col-sm-4 control-label">Remaining Days:</label>
            <div class="col-sm-8">
                <?php
                $total_done = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(workordercommid) AS total_d FROM workorder_comment WHERE workorderid='$workorderid' AND type='day'"));
                $remain_days = ($total_days-$total_done['total_d']);
                echo $remain_days.' Days';
                ?>
            </div>
        </div>

   <?php
   if(!empty($_GET['workorderid'])) {
        $query_check_credentials = "SELECT * FROM workorder_comment WHERE workorderid='$workorderid' AND type='day' ORDER BY workordercommid DESC";
        $result = mysqli_query($dbc, $query_check_credentials);
        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {
            echo "<table class='table table-bordered'>
            <tr class='hidden-xs hidden-sm'>
            <th>Note</th>
            <th>Assign To</th>
            <th>Date</th>
            <th>Added By</th>
            </tr>";
            while($row = mysqli_fetch_array($result)) {
                echo '<tr>';
                $by = $row['created_by'];
                $to = $row['email_comment'];
                echo '<td data-title="Schedule">'.html_entity_decode($row['comment']).'</td>';
                echo '<td data-title="Schedule">'.get_staff($dbc, $to).'</td>';
                echo '<td data-title="Schedule">'.$row['created_date'].'</td>';
                echo '<td data-title="Schedule">'.get_staff($dbc, $by).'</td>';
                //echo '<td data-title="Schedule"><a href=\'delete_restore.php?action=delete&workordercommid='.$row['workordercommid'].'&workorderid='.$row['workorderid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a></td>';
                echo '</tr>';
            }
            echo '</table>';
        }
    }
    ?>

      <div class="form-group">
        <label for="site_name" class="col-sm-4 control-label">Day End Note:</label>
        <div class="col-sm-8">
          <textarea name="day_end_note" rows="4" cols="50" data-table="workorder_comment" data-id="" data-id-field="workordercommid" data-type="day" class="form-control" ></textarea>
        </div>
      </div>

        <div class="form-group">
		  <label for="site_name" class="col-sm-4 control-label">Send Email:</label>
		  <div class="col-sm-8">
			<input type="checkbox" value="Yes" name="send_email_on_comment" onclick="day_check_send_email(this);">
		  </div>
		</div>

        <div class="form-group">
		  <label for="site_name" class="col-sm-4 control-label">Assign/Email To:</label>
		  <div class="col-sm-8">
			<select data-placeholder="Select a Staff Member..." name="email_comment" data-table="workorder_comment" data-id="" data-id-field="workordercommid" data-type="day" class="chosen-select-deselect form-control" width="380">
			  <option value=""></option>
			  <?php
				$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Staff' AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
				foreach($query as $id) {
					$selected = '';
					//$selected = $id == $contactid ? 'selected = "selected"' : '';
					echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
				}
			  ?>
			</select>
		  </div>
		</div>
		<?php
		$sender = get_contact($dbc, $_SESSION['contactid'], 'email_address');
		$subject = 'Note Added on workorder for you to review.';
		$body = 'Note : [NOTE]<br><br>
			Please click below workorder link to view all information.<br>
			Work Order : <a target="_blank" href="'.$_SERVER['SERVER_NAME'].'/Work Order/add_workorder.php?workorderid=[WORKORDERID]">Click Here</a><br>';
		?>
		<script>
		function day_check_send_email(checked) {
			if(checked.checked) {
				$('#day_email_send_div').show();
			} else {
				$('#day_email_send_div').hide();
			}
		}
		</script>
		<div id="day_email_send_div email_div" style="display:none;">
			<div class="form-group">
				<label class="col-sm-4 control-label">Sending Email Address:</label>
				<div class="col-sm-8">
					<input type="text" name="day_email_sender" class="form-control email_sender" value="<?php echo $sender; ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Email Subject:</label>
				<div class="col-sm-8">
					<input type="text" name="day_email_subject" class="form-control email_subject" value="<?php echo $subject; ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Email Body:</label>
				<div class="col-sm-8">
					<textarea name="day_email_body" class="form-control email_body"><?php echo $body; ?></textarea>
				</div>
			</div>
			<button class="btn brand-btn pull-right" onclick="send_comment_email" data-table="workorder_comment" data-id-field="workordercommid" data-id="" data-field="comment" onclick="send_email(this); return false;">Send Email</button>
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
