<div class="col-md-12">
	<?php if(!empty($_GET['workorderid'])) {
        $query_check_credentials = "SELECT * FROM workorder_comment WHERE workorderid='$workorderid' AND type='$comment_type' ORDER BY workordercommid DESC";
        $result = mysqli_query($dbc, $query_check_credentials);
        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {
            echo "<table class='table table-bordered'>
            <tr class='hidden-xs hidden-sm'>
            <th>Note</th>
            <th>".(in_array($comment_type, ['member_note','client_log']) ? "References" : "Assign To")."</th>
            <th>Date</th>
            <th>Added By</th>
            </tr>";
            while($row = mysqli_fetch_array($result)) {
                echo '<tr>';
                $by = $row['created_by'];
                $to = $row['email_comment'];
                //echo '<td data-title="Schedule">'.$row['note_heading'].'</td>';
                echo '<td data-title="Note">'.html_entity_decode($row['comment']).'</td>';
                echo '<td data-title="'.($row['reference_contact'] > 0 ? 'References' : 'Assign To').'">'.get_contact($dbc, ($row['reference_contact'] > 0 ? $row['reference_contact'] : $row['email_comment'])).'</td>';
                echo '<td data-title="Date">'.$row['created_date'].'</td>';
                echo '<td data-title="Added By">'.get_contact($dbc, $by).'</td>';
                //echo '<td data-title="Schedule"><a href=\'delete_restore.php?action=delete&ticketcommid='.$row['ticketcommid'].'&ticketid='.$row['ticketid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a></td>';
                echo '</tr>';
            }
            echo '</table>';
        }
    } ?>
</div>
<div class="col-md-12 multi-block">
  <div class="form-group">
	<label for="site_name" class="col-sm-4 control-label">Note:</label>
	<div class="col-sm-8">
		<input type="hidden" name="workorder_comment_type[]" value="<?= $comment_type ?>">
	  <textarea name="comment" data-table="workorder_comment" data-id="" data-id-field="workordercommid" data-type="<?= $comment_type ?>" rows="4" cols="50" class="form-control" ></textarea>
	</div>
  </div>

	<div class="form-group" style="<?= in_array($comment_type, ['member_note','client_log']) ? "display:none;" : "" ?>">
	  <label for="site_name" class="col-sm-4 control-label">Send Email:</label>
	  <div class="col-sm-8">
		<input type="hidden" name="send_email_on_comment[]" value="">
		<input type="checkbox" value="Yes" name="check_send_email" onclick="workorder_comment_check_send_email(this);">
	  </div>
	</div>

	<div class="form-group">
	  <label for="site_name" class="col-sm-4 control-label"><?= in_array($comment_type, ['member_note','client_log']) ? "References" : "Assign/Email To" ?>:</label>
	  <?php $comment_category = ($comment_type == 'member_note' ? "Members" : $comment_type == 'client_log' ? "Clients" : "Staff"); ?>
	  <div class="col-sm-8">
		<select data-placeholder="Select <?= $comment_category ?>..." name="email_comment" data-table="workorder_comment" data-id="" data-id-field="workordercommid" data-type="<?= $comment_type ?>" class="chosen-select-deselect form-control" width="380">
		  <option value=""></option>
		  <?php
			$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='$comment_category' AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
			foreach($query as $id) {
				$selected = '';
				//$selected = strpos($deliverable_contactid, ','.$id.',') !== FALSE ? 'selected = "selected"' : '';
				echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
			}
		  ?>
		</select>
	  </div>
	</div><?php
	$sender = get_contact($dbc, $_SESSION['contactid'], 'email_address');
	$subject = 'Note added on Work Order for you to Review';
	$body = 'The following note has been added on a work order for you:<br>[REFERENCE]<br><br>
			Client: [CLIENT]<br>
			Work Order Heading: [HEADING]<br>
			Status: [STATUS]<br>
			Please click the Work Order link below to view all information.<br>
			<a target="_blank" href="'.WEBSITE_URL.'/Work Order/add_workorder.php?workorderid=[WORKORDERID]">Work Order #[WORKORDERID]</a><br>';
	?>
	<script>
	function workorder_comment_check_send_email(checked) {
		if(checked.checked) {
			$(checked).closest('.col-sm-8').find('[name="send_email_on_comment[]"]').val('Yes');
			$(checked).closest('.note-block').find('.workorder_comment_email_send_div').show();
		} else {
			$(checked).closest('.col-sm-8').find('[name="send_email_on_comment[]"]').val('');
			$(checked).closest('.note-block').find('.workorder_comment_email_send_div').hide();
		}
	}
	</script>
	<div class="workorder_comment_email_send_div email_div" style="display:none;">
		<div class="form-group">
			<label class="col-sm-4 control-label">Sending Email Address:</label>
			<div class="col-sm-8">
				<input type="text" name="workorder_comment_email_sender[]" class="form-control email_sender" value="<?php echo $sender; ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Email Subject:</label>
			<div class="col-sm-8">
				<input type="text" name="workorder_comment_email_subject[]" class="form-control email_subject" value="<?php echo $subject; ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Email Body:</label>
			<div class="col-sm-8">
				<textarea name="workorder_comment_email_body[]" class="form-control email_body"><?php echo $body; ?></textarea>
			</div>
		</div>
		<button class="btn brand-btn pull-right" onclick="send_comment_email" data-table="workorder_comment" data-id-field="workordercommid" data-id="" data-field="comment" onclick="send_email(this); return false;">Send Email</button>
	</div>
	<button class="btn brand-btn pull-right" onclick="addMulti(this); return false;">Add Note</button>
	<div class="clearfix"></div>
</div>