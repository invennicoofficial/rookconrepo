<?php include_once('../include.php');
checkAuthorised('form_builder');
include_once('../Form Builder/field_values.php');

$templateid = $_GET['templateid'];
$template_fields = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `user_form_fields` WHERE `form_id` = '$templateid' AND `type` != 'OPTION' AND `deleted` = 0 ORDER BY `sort_order`"),MYSQLI_ASSOC);
?>
<h3>Fields</h3>
<table class="table table-bordered">
	<tr>
		<th style="width: 90%;">Field</th>
		<th style="width: 10%;">Include</th>
	</tr>
	<?php foreach($template_fields as $template_field) { ?>
		<tr>
			<td data-title="Field"><?= ($template_field['type'] == 'SELECT_CUS' ? $field_types['SELECT'] : $field_types[$template_field['type']]) ?>: <?= $template_field['label'] ?></td>
			<td align="center" data-title="Include"><input type="checkbox" name="form_fields[<?= $template_field['field_id'] ?>]" style="width: 20px; height: 20px;" checked></td>
		</tr>
	<?php } ?>
</table>