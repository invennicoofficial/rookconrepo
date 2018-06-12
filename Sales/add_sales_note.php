<div class="col-md-12">
   <?php
    if(!empty($_GET['salesid'])) {
        $query_check_credentials = "SELECT * FROM sales_notes WHERE salesid='$salesid' ORDER BY salesnoteid DESC";
        $result = mysqli_query($dbc, $query_check_credentials);
        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {
            echo "<table class='table table-bordered'>
            <tr class='hidden-xs hidden-sm'>
            <th>Heading</th>
            <th>Note</th>
            <th>Assign To</th>
            <th>Date</th>
            <th>Added By</th>
            </tr>";
            while($row = mysqli_fetch_array($result)) {
                echo '<tr>';
                $by = $row['created_by'];
                $to = $row['email_comment'];
                echo '<td data-title="Schedule">'.$row['note_heading'].'</td>';
                echo '<td data-title="Schedule">'.html_entity_decode($row['comment']).'</td>';
                echo '<td data-title="Schedule">'.get_staff($dbc, $to).'</td>';
                echo '<td data-title="Schedule">'.$row['created_date'].'</td>';
                echo '<td data-title="Schedule">'.get_staff($dbc, $by).'</td>';
                //echo '<td data-title="Schedule"><a href=\'delete_restore.php?action=delete&ticketcommid='.$row['ticketcommid'].'&ticketid='.$row['ticketid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a></td>';
                echo '</tr>';
            }
            echo '</table>';
        }
    }
    ?>
    <?php

    ?>
        <div class="form-group">
            <label for="first_name" class="col-sm-4 control-label">Note Heading:</label>
            <div class="col-sm-8">
                <select data-placeholder="Choose a Heading..." name="note_heading" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <option value="Issue">Issue</option>
                  <option value="Problem">Problem</option>
                  <option value="GAP">GAP</option>
                  <option value="Technical Uncertainty">Technical Uncertainty</option>
                  <option value="Base Knowledge">Base Knowledge</option>
                  <option value="Do">Do</option>
                  <option value="Already Known">Already Known</option>
                  <option value="Sources">Sources</option>
                  <option value="Current Designs">Current Designs</option>
                  <option value="Known Techniques">Known Techniques</option>
                  <option value="Review Needed">Review Needed</option>
                  <option value="Looking to Achieve">Looking to Achieve</option>
                  <option value="Plan">Plan</option>
                  <option value="Next Steps">Next Steps</option>
                  <option value="Learnt">Learned</option>
                  <option value="Discovered">Discovered</option>
                  <option value="Tech Advancements">Tech Advancements</option>
                  <option value="Work">Work</option>
                  <option value="Adjustments Needed">Adjustments Needed</option>
                  <option value="Future Designs">Future Designs</option>
                  <option value="Targets">Targets</option>
                  <option value="Audience">Audience</option>
                  <option value="Strategy">Strategy</option>
                  <option value="Desired Outcome">Desired Outcome</option>
                  <option value="Actual Outcome">Actual Outcome</option>
                  <option value="Check">Check</option>
                  <option value="Objective">Objective</option>
                  <option value="General">General</option>
                </select>

            </div>
        </div>

      <div class="form-group">
        <label for="site_name" class="col-sm-4 control-label">Note:</label>
        <div class="col-sm-8">
          <textarea name="comment" rows="4" cols="50" class="form-control" ></textarea>
        </div>
      </div>

        <div class="form-group">
		  <label for="site_name" class="col-sm-4 control-label">Send Email:</label>
		  <div class="col-sm-8">
			<input type="checkbox" value="Yes" name="send_email_on_comment">
		  </div>
		</div>

        <div class="form-group">
		  <label for="site_name" class="col-sm-4 control-label">Assign/Email To:</label>
		  <div class="col-sm-8">
			<select data-placeholder="Choose a Staff Member..." name="email_comment" class="chosen-select-deselect form-control" width="380">
			  <option value=""></option>
				<?php $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE deleted=0 AND category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND status > 0"),MYSQLI_ASSOC));
				foreach($query as $id) { ?>
					<option value='<?php echo  $id; ?>' ><?php echo get_contact($dbc, $id); ?></option>
				<?php } ?>
			</select>
		  </div>
		</div>

</div>
