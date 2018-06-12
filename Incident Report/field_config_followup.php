<?php if (isset($_POST['submit'])) {
	$inc_rep_followup_send = filter_var($_POST['inc_rep_followup_send'],FILTER_SANITIZE_STRING);
	set_config($dbc, 'inc_rep_followup_send', $inc_rep_followup_send);

	$inc_rep_followup_reminder_send = filter_var($_POST['inc_rep_followup_reminder_send'],FILTER_SANITIZE_STRING);
	set_config($dbc, 'inc_rep_followup_reminder_send', $inc_rep_followup_reminder_send);

	$inc_rep_followup_email = filter_var($_POST['inc_rep_followup_email'],FILTER_SANITIZE_STRING);
	set_config($dbc, 'inc_rep_followup_email', $inc_rep_followup_email);
	
	$inc_rep_followup_subject = filter_var($_POST['inc_rep_followup_subject'],FILTER_SANITIZE_STRING);
	set_config($dbc, 'inc_rep_followup_subject', $inc_rep_followup_subject);
	
	$inc_rep_followup_body = filter_var(htmlentities($_POST['inc_rep_followup_body']),FILTER_SANITIZE_STRING);
	set_config($dbc, 'inc_rep_followup_body', $inc_rep_followup_body);
	
    echo '<script type="text/javascript"> window.location.replace(""); </script>';
}
?>
<div class="gap-top">
	<div class="form-group">
		<?php $inc_rep_followup_send = get_config($dbc, 'inc_rep_followup_send'); ?>
		<label class="col-sm-4 control-label">Follow Up Email On Create:<br /><em>This will send an email to Staff set in the Assign Follow Up field when creating a new <?= INC_REP_NOUN ?>.</em></label>
		<div class="col-sm-8">
			<label class="form-checkbox"><input type="checkbox" name="inc_rep_followup_send" value="1" <?= $inc_rep_followup_send == 1 ? 'checked' : '' ?>> Enable</label>
		</div>
	</div>

	<div class="form-group">
		<?php $inc_rep_followup_reminder_send = get_config($dbc, 'inc_rep_followup_reminder_send'); ?>
		<label class="col-sm-4 control-label">Follow Up Reminder Email:<br /><em>This will send a Follow Up Reminder email on the day of the Follow Up Date.</em></label>
		<div class="col-sm-8">
			<label class="form-checkbox"><input type="checkbox" name="inc_rep_followup_reminder_send" value="1" <?= $inc_rep_followup_reminder_send == 1 ? 'checked' : '' ?>> Enable</label>
		</div>
	</div>

	<div class="form-group">
		<?php $inc_rep_followup_email = get_config($dbc, 'inc_rep_followup_email'); ?>
		<label class="col-sm-4 control-label">Follow Up Email Sends From:</label>
		<div class="col-sm-8">
			<input type="text" name="inc_rep_followup_email" class="form-control" value="<?= $inc_rep_followup_email ?>">
		</div>
	</div>

	<div class="form-group">
		<?php $inc_rep_followup_subject = get_config($dbc, 'inc_rep_followup_subject'); ?>
		<label class="col-sm-4 control-label">Follow Up Email Subject:</label>
		<div class="col-sm-8">
			<input type="text" name="inc_rep_followup_subject" class="form-control" value="<?= $inc_rep_followup_subject ?>">
		</div>
	</div>

	<div class="form-group">
		<?php $inc_rep_followup_body = html_entity_decode(get_config($dbc, 'inc_rep_followup_body')); ?>
		<label class="col-sm-4 control-label">Follow Up Email Body:<br /><em>Use [FOLLOWUPDATE] to indicate the Follow Up Date of the <?= INC_REP_NOUN ?>. Use [URL] to indicate the <?= INC_REP_NOUN ?> URL. Use [PDFURL] to indicate the PDF URL.</em></label>
		<div class="col-sm-8">
			<textarea name="inc_rep_followup_body" class="form-control"><?= $inc_rep_followup_body ?></textarea>
		</div>
	</div>
</div>