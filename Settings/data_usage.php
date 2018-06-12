<?php include('../usage_update.php'); ?>
<script>
$(document).ready(function() {
	$('form input,form textarea').change(function() {
		$.ajax({
			url: '../usage_update.php',
			method: 'POST',
			data: {
				field: this.name,
				value: this.value
			}
		});
	});
});
</script>
<div id="no-more-tables">
	<table class="table table-bordered">
		<tr class="hidden-sm hidden-xs">
			<th style="max-width: 25%;"></th>
			<th class="text-center">Available Usage: <?= roundByteSize($total_paid) ?></th>
		</tr>
		<tr>
			<td data-title="Available: <?= roundByteSize($total_paid) ?>">Database Usage:</td>
			<td style="background-color: #AAA; padding: 0 0 0 0;">
				<div style="background-color: #FF807B; line-height: 2.5em; width:<?= $database / $total_paid * 100 ?>%;">&nbsp;</div>
				<div style="margin: -2em 1em 0;"><b><?= roundByteSize($database) ?></b></div>
			</td>
		</tr>
		<tr>
			<td data-title="Available: <?= roundByteSize($total_paid) ?>">File Usage:</td>
			<td style="background-color: #AAA; padding: 0 0 0 0;">
				<div style="background-color: #6DCFF6; line-height: 2.5em; width:<?= $filesystem / $total_paid * 100 ?>%;">&nbsp;</div>
				<div style="margin: -2em 1em 0;"><b><?= roundByteSize($filesystem) ?></b></div>
			</td>
		</tr>
		<tr>
			<td data-title="Available: <?= roundByteSize($total_paid) ?>">Total Usage:</td>
			<td style="background-color: #AAA; padding: 0 0 0 0;">
				<div style="background-color: #FF807B; display: inline-block; line-height: 2.5em; width:<?= $database / $total_paid * 100 ?>%;">&nbsp;</div><div style="background-color: #6DCFF6; display: inline-block; line-height: 2.5em; width:<?= $filesystem / $total_paid * 100 ?>%;">&nbsp;</div>
				<div style="margin: -2em 1em 0;"><b><?= roundByteSize($total_usage) ?></b></div>
			</td>
		</tr>
	</table>
</div>
You currently have available <?= roundByteSize($total_paid) ?>. This includes <?= $inc_count ?> additional increments of 500 MiB each.
<?php $query = mysqli_query($dbc, "SELECT * FROM `software_usage`");
if(mysqli_num_rows($query) > 0) { ?>
	<h3>All Software - Displayed Only on ffm.rookconnect.com</h3>
	<table class="table table-bordered">
		<tr>
			<th>Software URL</th>
			<th>Last Notification</th>
			<th>Database Size</th>
			<th>Filesystem Size</th>
			<th>Total Size</th>
			<th>Email Recipient</th>
		</tr>
		<?php while($row = mysqli_fetch_assoc($query)) { ?>
			<tr>
				<td><a href="<?= $row['software_id'] ?>"><?= $row['software_id'] ?></a></td>
				<td><?= roundByteSize($row['notification_mb'] * 1024 * 1024) ?></td>
				<td><?= roundByteSize($row['database_mb'] * 1024 * 1024) ?></td>
				<td><?= roundByteSize($row['filesystem_mb'] * 1024 * 1024) ?></td>
				<td><?= roundByteSize($row['total_mb'] * 1024 * 1024) ?></td>
				<td><?= $row['recipient_name'] ?> <<?= $row['recipient_address'] ?>></td>
			</tr>
		<?php } ?>
	</table>
<?php } ?>
<h3>Usage Limit Warning Email</h3>
<form class="form-horizontal"><?php
    $notes = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT note FROM notes_setting WHERE subtab='setting_data_usage'"));
    $note = $notes['note'];
        
    if ( !empty($note) ) { ?>
        <div class="notice double-gap-bottom popover-examples">
            <div class="col-sm-1 notice-icon"><img src="../img/info.png" class="wiggle-me" width="25"></div>
            <div class="col-sm-11">
                <span class="notice-name">NOTE:</span>
                <?= $note; ?>
            </div>
            <div class="clearfix"></div>
        </div><?php
    } ?>
    
	<div class="form-group">
		<label class="col-sm-4 control-label">Recipient Name:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="recipient_name" value="<?= $usage_config['recipient_name'] ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label">Recipient Address:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="recipient_address" value="<?= $usage_config['recipient_address'] ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label">Subject:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="email_subject" value="<?= $usage_config['email_subject'] ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label">Body:<br />
			<em>The body will be appended with one of the following two messages:<br />
			"You are within 100 MiB of the next data usage tier. You are currently using *." OR<br />
			"You have reached the next usage tier. You are currently using *."</em></label>
		<div class="col-sm-8">
			<textarea name="email_body"><?= $usage_config['email_body'] ?></textarea>
		</div>
	</div>
</form>
