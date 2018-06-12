<?= (!empty($renamed_accordion) ? '<h3>'.$renamed_accordion.'</h3>' : '<h3>Complete '.TICKET_NOUN.'</h3>') ?>
<?php if($access_complete === TRUE) { ?>
	<div class="form-group">
		<label class="control-label col-sm-4">Staff:</label>
		<div class="col-sm-8">
			<select name="sign_off_id" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" class="chosen-select-deselect"><option></option>
				<?php foreach($staff_list as $staff) { ?>
					<option <?= ($staff['contactid'] == $get_ticket['sign_off_id']) || (strpos($value_config,','."Complete Default Session User".',') !== FALSE && empty($get_ticket['sign_off_id']) && $staff['contactid'] == $_SESSION['contactid']) ? 'selected' : '' ?> value="<?= $staff['contactid'] ?>"><?= $staff['first_name'].' '.$staff['last_name'] ?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Signature:</label>
		<div class="col-sm-8">
			<?php if(file_exists('download/sign_off_'.$ticketid.'_'.$get_ticket['sign_off_id'].'.png')) { ?>
				<img src="download/sign_off_<?= $ticketid ?>_<?= $get_ticket['sign_off_id'] ?>.png" height="150">
			<?php } else {
				$output_name = 'sign_off_signature';
				$sign_output_options = 'data-table="tickets" data-id="'.$ticketid.'" data-id-field="ticketid"';
				include('../phpsign/sign_multiple.php'); ?>
				<?php if(strpos($value_config, ','."Complete Hide Sign & Complete".',') === FALSE) { ?>
					<button class="<?= strpos($value_config, ',Complete Do Not Require Notes,') !== FALSE ? 'force_sign_off_click' : 'sign_off_click' ?> btn brand-btn pull-right">Sign &amp; Complete</button>
				<?php } ?>
				<?php if(config_visible_function($dbc, 'ticket') > 0 && strpos($value_config, ','."Complete Sign & Force Complete".',') !== FALSE) { ?>
					<br /><div class="clearfix"></div>
					<button class="force_sign_off_click btn brand-btn pull-right">Sign &amp; Force Complete</button>
					<span class="popover-examples list-inline pull-right" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="This will complete the <?= TICKET_NOUN ?> even if there are notes that have not been entered."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<?php } ?>
				<input type="hidden" name="complete_force" value="0">
			<?php } ?>
		</div>
	</div>
<?php } else if(file_exists('download/sign_off_'.$ticketid.'_'.$get_ticket['sign_off_id'].'.png')) { ?>
	<div class="form-group">
		<label class="control-label col-sm-4">Signature:</label>
		<div class="col-sm-8">
			<?= get_contact($dbc, $get_ticket['sign_off_id']) ?><br />
			<img src="download/sign_off_<?= $ticketid ?>_<?= $get_ticket['sign_off_id'] ?>.png" height="150">
		</div>
	</div>
	<?php $pdf_contents[] = ['Signature', get_contact($dbc, $get_ticket['sign_off_id']).'<br /><img src="download/sign_off_'.$ticketid.'_'.$get_ticket['sign_off_id'].'.png" height="150">', 'img']; ?>
<?php $notes = mysqli_query($dbc, "SELECT `ticket_comment`.*, `tickets`.`ticket_type` FROM ticket_comment LEFT JOIN `tickets` ON `ticket_comment`.`ticketid`=`tickets`.`ticketid` WHERE `ticket_comment`.ticketid='$ticketid' AND `ticket_comment`.type='completion_notes' AND `ticket_comment`.`deleted`=0 ORDER BY ticketcommid DESC");
	if(mysqli_num_rows($notes) > 0) {
		if($generate_pdf) {
			ob_clean();
		}
		while($row = mysqli_fetch_array($notes)) {
			echo '<div class="note_block">';
				echo profile_id($dbc, $row['created_by']);
				echo '<div class="pull-right" style="width: calc(100% - 3.5em);">'.html_entity_decode($row['comment'].$row['note']);
				echo "<em>Check In All used by ".get_contact($dbc, $row['created_by'])." at ".$row['note_date'].$row['created_date']."</em>";
			echo '</div><div class="clearfix"></div><hr></div>';
		}
		if($generate_pdf) {
			$pdf_contents[] = ['', ob_get_contents()];
		}
	}
} else {
	echo "<h3>".TICKET_NOUN." is not complete.</h3>";
	$pdf_contents[] = ['', TICKET_NOUN.' is not complete.'];
} ?>