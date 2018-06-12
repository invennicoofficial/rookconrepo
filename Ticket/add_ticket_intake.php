<?php include_once('../include.php'); ?>
<?= (!empty($renamed_accordion) ? '<h3>'.$renamed_accordion.'</h3>' : '<h3>Intake</h3>') ?>

<?php
$intake_forms = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `intake` WHERE `ticketid` = '$ticketid' AND '$ticketid' > 0 AND `deleted` = 0"),MYSQLI_ASSOC);

if(!empty($intake_forms)) {
	echo '<ul>';
	foreach($intake_forms as $form) {
		$intake_form = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `intake_forms` WHERE `intakeformid` = '".$form['intakeformid']."'")); ?>
		<li><a href="../Intake/<?= $form['intake_file'] ?>" target="_blank">Intake #<?= $form['intakeid'] ?>: <?= !empty($intake_form['form_name']) ? $intake_form['form_name'].':' : '' ?> <?= !empty($form['contactid']) ? get_contact($dbc, $form['contactid']) : (!empty($form['name']) ? $form['name'] : 'No Contact') ?>: <?= $form['received_date'] ?></a></li>
	<?php }
} else {
	echo 'No Intake Forms Found.';
}