<?php include_once('../include.php');

$email_communicationid = $_GET['email_communicationid'];

$query = "SELECT * FROM `email_communication` WHERE `email_communicationid` = '$email_communicationid'";
$row = mysqli_fetch_assoc(mysqli_query($dbc, $query));
?>

<div class="popup-inner" style="max-height: 80%;">
	<div class="popup-inner-resizable" style="overflow: auto;">
		<p><b>Email By : </b><?php echo get_staff($dbc, $row['created_by']); ?></p>
		<?php if($row['to_contact'] != ''): ?>
			<p><b>Email To Contact : </b><?php echo $row['to_contact']; ?></p>
		<?php endif; ?>
		<?php if($row['new_emailid'] != ''): ?>
			<p><b>Additional Email : </b><?php echo $row['new_emailid']; ?></p>
		<?php endif; ?>
		<?php if($row['to_staff'] != ''): ?>
			<p><b>Email To Staff : </b><?php echo $row['to_staff'] ?></p>
		<?php endif; ?>
		<?php if($row['cc_contact'] != ''): ?>
			<p><b>Email Cc Contact : </b><?php echo $row['cc_contact']; ?></p>
		<?php endif; ?>
		<?php if($row['cc_staff'] != ''): ?>
			<p><b>Email Cc Staff : </b><?php echo $row['cc_staff']; ?></p>
		<?php endif; ?>
		<?php if($row['follow_up_by'] != ''): ?>
			<p><b>Follow Up By : </b><?php echo get_contact($dbc, $row['follow_up_by']); ?></p>
		<?php endif; ?>
		<?php if($row['subject'] != ''): ?>
			<p><b>Subject : </b><?php echo $row['subject']; ?></p>
		<?php endif; ?>
		<?php if($row['email_body'] != ''): ?>
			<p><b>Email Body : </b><?php echo html_entity_decode($row['email_body']); ?></p>
		<?php endif; ?>
		<a class="popup-close" onclick="closeEmailPopup(); return false;" href="#">x</a>
	</div>
</div>