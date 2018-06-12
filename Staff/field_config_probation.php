<script>
$(document).ready(function() {
	$('input[type=checkbox]').change(function() {
		if(this.name == 'probation_certificates' || this.name == 'probation_forms' || this.name == 'probation_email') {
			var value = [];
			$('[name="'+this.name+'"]:checked').each(function() {
				value.push(this.value);
			});
			$.ajax({
				url: 'staff_ajax.php?action=settings',
				method: 'POST',
				data: {
					name: this.name,
					value: value.join('#*#')
				},
				success: function(response) {
					console.log(response);
				}
			});
		}
	});
	$('.add_cert').off('click',add_cert).click(add_cert);
});
function add_cert() {
	var row = $('.cert_row').last().clone();
	row.find('input').val('');
	$('.cert_row').last().after(row);
	$('.add_cert').off('click',add_cert).click(add_cert);
}
</script>
<h3>Notification Email</h3>
<div class="form-group cert_row">
	<label class="col-sm-4">Email that receives notification of completion of forms and certificates:</label>
	<div class="col-sm-8">
		<input name="probation_email" type="text" class="form-control" value="<?= get_config($dbc, 'probation_email') ?>">
	</div>
</div>
<h3>Required Certificates</h3>
<?php $query = mysqli_query($dbc,"SELECT distinct(certificate_type) FROM certificate");
$required_certificates = $probation_certificates = array_filter(explode('#*#',get_config($dbc, 'probation_certificates')));
while($row = mysqli_fetch_array($query)) {
	$i = array_search($row['certificate_type'],$required_certificates);
	if($i !== FALSE) {
		unset($required_certificates[$i]);
	}
	echo "<label class='form-checkbox'><input name='probation_certificates' type='checkbox' ".(in_array($row['certificate_type'],$probation_certificates) ? 'checked' : '')." value='". $row['certificate_type']."'>".$row['certificate_type'].'</label>';
}
foreach($required_certificates as $required_type) {
	echo "<label class='form-checkbox'><input type='checkbox' checked value='". $required_type."'>".$required_type.'</label>';
} ?>
<div class="form-group cert_row">
	<label class="col-sm-4">Additional Certificate Type:</label>
	<div class="col-sm-7">
		<input name="probation_certificates" type="text" class="form-control">
	</div>
	<div class="col-sm-1">
		<img class="inline-img add_cert" src="../img/icons/ROOK-add-icon.png">
	</div>
</div>
<h3>Required HR Forms</h3>
<?php $forms = mysqli_query($dbc, "SELECT `hrid`, `category`, `heading_number`, `heading`, `sub_heading_number`, `sub_heading`, `third_heading_number`, `third_heading` FROM `hr` WHERE `heading_number` != '' AND `deleted`=0 ORDER BY `category`, LPAD(`heading_number`, 100, 0), LPAD(`sub_heading_number`, 100, 0), LPAD(`third_heading_number`, 100, 0)");
$probation_forms = explode('#*#',get_config($dbc, 'probation_forms'));
while($form = $forms->fetch_assoc()) { ?>
	<label class='form-checkbox'><input name='probation_forms' type='checkbox' <?= in_array($form['hrid'],$probation_forms) ? 'checked' : '' ?> value='<?= $form['hrid'] ?>'><?= $form['category'].' '.($form['third_heading_number'] != '' ? $form['third_heading_number'].' '.$form['third_heading'] : ($form['sub_heading_number'] != '' ? $form['sub_heading_number'].' '.$form['sub_heading'] : $form['heading_number'].' '.$form['heading'])) ?></label>
<?php } ?>